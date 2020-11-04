<?php

namespace App\Http\Controllers;

use App\Mail\NotifyDistribution;
use App\Mail\NotifyRejection;
use App\Models\Work\Log as InternalLog;
use App\Models\Work\Registration;
use App\Models\Work\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class WorksController extends Controller
{
    public $datatablesModel = Registration::class;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('datatables')->only('datatables');
    }

    public function index()
    {
        if (!Auth::user()->can('nb_obras', 'lee')) {
            abort(403);
        }

        $requests = Registration::where('status_id', 1)->get();
        $status = Status::all();

        return view('works.index', [
            'requests' => $requests,
            'status'   => $status
        ]);
    }

    public function showView(Registration $registration)
    {
        if (!Auth::user()->can('nb_obras', 'lee')) {
            abort(403);
        }

        return view('works.view', ['registration' => $registration]);
    }

    public function datatables(Request $request)
    {
        if (!Auth::user()->can('nb_obras', 'lee')) {
            abort(403);
        }

        $query = $request->datatablesQuery;
        $query->with('status');
        $requests = $query->get();

        $response = response(null);
        $response->datatablesOutput = $requests;
        return $response;
    }

    public function changeStatus(Request $request, Registration $registration)
    {
        if (!Auth::user()->can('nb_obras', 'homologa')) {
            abort(403);
        }

        switch($request->input('status')) {
            case 'beginAction':
                if ($request->input('force', false) === true) {
                    return $this->beginActionForce($registration);
                }

                return $this->beginAction($registration);
            break;
            case 'rejectAction':
                return $this->rejectAction($registration);
            break;
            case 'sendToInternal':
                return $this->sendToInternal($registration);
            break;
            case 'approveRequest':
                return $this->approveRequest($registration);
            break;
            case 'rejectRequest':
                return $this->rejectRequest($registration);
            break;
            case 'finishRequest':
                return $this->finishRequest($registration);
            break;
            default:
                abort(403);
        }
    }

    public function downloadFile(Request $request)
    {
        if (!Auth::user()->can('nb_obras', 'lee')) {
            abort(403);
        }

        try {
            $path = explode('/', $request->input('file'));
            if ($path[0] != 'files') abort(403);
            if ($path[1] != 'users') abort(403);

            if (!Storage::exists($request->input('file'))) {
                abort(404);
            }

            return Storage::download($request->input('file'));
        } catch (Throwable $t) {
            Log::error("Error descargando archivo de registro de obra",
                [
                    "error" => $t,
                    "data"  => json_encode($request->all(), JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_IGNORE )
                ]
            );

            abort(500);
        }
    }

    private function beginAction(Registration $registration)
    {
        try {
            if (!Auth::user()->can('nb_obras', 'homologa')) {
                abort(403);
            }

            $errors = [];

            foreach($registration->distribution as $distribution) {
                if ($distribution->type == 'member') {
                    if (trim($distribution->member->email) != "" && filter_var($distribution->member->email, FILTER_VALIDATE_EMAIL)) {
                        // Si tiene dirección válida, notificamos
                        Mail::to($distribution->member->email)->queue(new NotifyDistribution($distribution));
                    } else {
                        // Si no, logeamos
                        InternalLog::create([
                            'registration_id' => $registration->id,
                            'distribution_id' => $distribution->id,
                            'action_id'       => 11, // REGISTRATION_NOT_NOTIFIED
                            'time'            => now(),
                            'action_data'     => ['member' => $distribution->member_id]
                        ]);

                        // Mail seteado
                        if (trim($distribution->member->email) == "") {
                            $errors[] = $distribution->member->nombre . " no tiene una dirección de correo electrónica configurada";
                        } else {
                            // Mail válido
                            if (!filter_var($distribution->member->email, FILTER_VALIDATE_EMAIL)) {
                                $errors[] = $distribution->member->nombre . " tiene una dirección de correo electrónica errónea: " . $distribution->member->email;
                            }
                        }
                    }
                }
            }

            $registration->status_id = 2; // En proceso
            $registration->save();

            InternalLog::create([
                'registration_id' => $registration->id,
                'action_id'       => 3, // REGISTRATION_ACEPTED
                'time'            => now()
            ]);

            return [
                'status' => 'success',
                'errors' => $errors
            ];
        } catch (Throwable $t) {
            Log::error("Error iniciando trámite de registro de obra",
                [
                    "error" => $t,
                    "data"  => json_encode($request->all(), JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_IGNORE )
                ]
            );

            return [
                'status'   => 'failed',
                'continue' => false
            ];
        }
    }

    private function rejectAction(Registration $registration)
    {
        if (!Auth::user()->can('nb_obras', 'homologa')) {
            abort(403);
        }

        // Cambio estado en la BBDD
        $registration->status_id = 8; // Rechazado
        $registration->save();

        InternalLog::create([
            'registration_id' => $registration->id,
            'action_id'       => 4, // REGISTRATION_REJECTED
            'time'            => now()
        ]);

        // Notificación
        if (trim($registration->initiator->email) == "") {
            return [
                'status'  => 'success',
                'warning' => 'No se pudo notificar al iniciador del trámite porque no tiene configurada dirección de correo electrónico'
            ];
        } else {
            if (!filter_var($registration->initiator->email, FILTER_VALIDATE_EMAIL)) {
                return [
                    'status'  => 'success',
                    'warning' => 'No se pudo notificar al iniciador del trámite porque tiene una dirección de correo electrónica errónea: ' . $distribution->initiator->email
                ];
            } else {
                Mail::to($registration->initiator->email)->queue(new NotifyRejection($registration));
                return [
                    'status'  => 'success'
                ];
            }
        }
    }

    private function sendToInternal(Registration $registration)
    {
        try {
            if (!Auth::user()->can('nb_obras', 'homologa')) {
                abort(403);
            }

            $registration->status_id = 6;
            $registration->save();

            InternalLog::create([
                'registration_id' => $registration->id,
                'action_id'       => 8, // SEND_TO_INTERNAL
                'action_data'     => ['operator_id' => Auth::user()->usuarioid],
                'time'            => now()
            ]);

            return [
                'status'   => 'success'
            ];
        } catch (Throwable $t) {
            Log::error("Error enviando trámite de registro de obra al sistema interno",
                [
                    "error" => $t,
                    "data"  => json_encode($request->all(), JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_IGNORE )
                ]
            );

            return [
                'status'   => 'failed'
            ];
        }
    }

    private function approveRequest(Registration $registration)
    {
        try {
            if (!Auth::user()->can('nb_obras', 'homologa')) {
                abort(403);
            }

            $registration->status_id = 7;
            $registration->save();

            InternalLog::create([
                'registration_id' => $registration->id,
                'action_id'       => 9, // REQUEST_ACCEPTED
                'action_data'     => ['operator_id' => Auth::user()->usuarioid],
                'time'            => now()
            ]);

            return [
                'status'   => 'success'
            ];
        } catch (Throwable $t) {
            Log::error("Error guardando aprobación del trámite",
                [
                    "error" => $t,
                    "data"  => json_encode($request->all(), JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_IGNORE )
                ]
            );

            return [
                'status'   => 'failed'
            ];
        }
    }

    private function rejectRequest(Registration $registration)
    {
        try {
            if (!Auth::user()->can('nb_obras', 'homologa')) {
                abort(403);
            }

            $registration->status_id = 8;
            $registration->save();

            InternalLog::create([
                'registration_id' => $registration->id,
                'action_id'       => 10, // REQUEST_REJECTED
                'action_data'     => ['operator_id' => Auth::user()->usuarioid],
                'time'            => now()
            ]);

            return [
                'status'   => 'success'
            ];
        } catch (Throwable $t) {
            Log::error("Error guardando rechazo del trámite",
                [
                    "error" => $t,
                    "data"  => json_encode($request->all(), JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_IGNORE )
                ]
            );

            return [
                'status'   => 'failed'
            ];
        }
    }

    private function finishRequest(Registration $registration)
    {
        try {
            if (!Auth::user()->can('nb_obras', 'homologa')) {
                abort(403);
            }

            $registration->status_id = 9;
            $registration->save();

            InternalLog::create([
                'registration_id' => $registration->id,
                'action_id'       => 12, // REQUEST_FINISHED
                'action_data'     => ['operator_id' => Auth::user()->usuarioid],
                'time'            => now()
            ]);

            return [
                'status'   => 'success'
            ];
        } catch (Throwable $t) {
            Log::error("Error guardando finalización del trámite",
                [
                    "error" => $t,
                    "data"  => json_encode($request->all(), JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_IGNORE )
                ]
            );

            return [
                'status'   => 'failed'
            ];
        }
    }

    public function response(Request $request, Registration $registration)
    {
        try {
            if (!Auth::user()->can('nb_obras', 'carga')) {
                abort(403);
            }

            if (!$request->has('response') || !$request->has('distribution_id')) {
                abort(403);
            }

            if ($request->input('response') != 'accept' && $request->input('response') != 'reject') {
                abort(403);
            }

            $distribution_id = $request->input('distribution_id');

            $distribution = $registration->distribution->where('id', $distribution_id)->first();

            // Si el socio no es parte de la distribución del registro
            if (!$distribution) {
                abort(403);
            }

            // Si ya respondió que si, no se puede cambiar
            if ($distribution->response == true) {
                return [
                    'status' => 'failed',
                    'errors' => [
                        'No se puede cambiar la respuesta a una solicitud de registro ya aceptada'
                    ]
                ];
            }

            $distribution->response = $request->input('response') == 'accept';
            $distribution->liable_id = Auth::user()->usuarioid;
            $distribution->save();

            // action_id = 6 -> DISTRIBUTION_CONFIRMED
            // action_id = 7 -> DISTRIBUTION_REJECTED
            InternalLog::create([
                'registration_id' => $registration->id,
                'distribution_id' => $distribution->id,
                'action_id'       => $request->input('response') == 'accept' ? 6 : 7,
                'action_data'     => ['operator_id' => Auth::user()->usuarioid],
                'time'            => now()
            ]);

            // Chequeamos si todas las partes aprobaron el trámite
            $finished = $registration->distribution->every(function ($current, $key) {
                return !!$current->response;
            });

            // Si el trámite está terminado...
            if ($finished) {
                $registration->status_id = 5; // Aprobado Propietarios
            // Si la respuesta fue negativa
            } elseif (!$distribution->response) {
                $registration->status_id = 3; // Disputa Propietarios
            }

            $registration->save();

            return [
                'status' => 'success'
            ];
        } catch (Throwable $t) {
            Log::error("Error registrando respuesta de socio a un solicitud de registro de obra",
                [
                    "error" => $t,
                    "data"  => json_encode($request->all(), JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_IGNORE )
                ]
            );

            return [
                'status' => 'failed',
                'errors' => [
                    'Se produjo un error desconocido al momento de registrar su respuesta'
                ]
            ];
        }
    }

    public function saveObservations(Request $request, Registration $registration)
    {
        try {
            if (!Auth::user()->can('nb_obras', 'carga')) {
                abort(403);
            }

            $registration->observations = $request->input('content', null) ?? '';
            $registration->save();

            return [
                'status'   => 'success'
            ];
        } catch (Throwable $t) {
            Log::error("Error guardando finalización del trámite",
                [
                    "error" => $t,
                    "data"  => json_encode($request->all(), JSON_PARTIAL_OUTPUT_ON_ERROR | JSON_INVALID_UTF8_IGNORE )
                ]
            );

            return [
                'status'   => 'failed'
            ];
        }
    }
}
