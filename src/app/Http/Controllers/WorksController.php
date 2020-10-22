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
        $requests = Registration::where('status_id', 1)->get();
        $status = Status::all();

        return view('works.index', [
            'requests' => $requests,
            'status'   => $status
        ]);
    }

    public function showView(Registration $registration)
    {
        return view('works.view', ['registration' => $registration]);
    }

    public function datatables(Request $request)
    {
        $query = $request->datatablesQuery;
        $query->with('status');
        $requests = $query->get();

        $response = response(null);
        $response->datatablesOutput = $requests;
        return $response;
    }

    public function changeStatus(Request $request, Registration $registration)
    {
        // TODO: Validar permisos

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
                return $this->approveRequest($request, $registration);
            break;
            case 'rejectRequest':
                return $this->rejectRequest($request, $registration);
            break;
            default:
                abort(403);
        }
    }

    public function downloadFile(Request $request)
    {
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
            $errors = [];

            // Verificación de los mails
            foreach($registration->distribution as $distribution) {
                if ($distribution->type == 'member') {
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

            if (count($errors) > 0) {
                return [
                    'status'   => 'failed',
                    'errors'   => $errors,
                    'continue' => true
                ];
            }

            foreach($registration->distribution as $distribution) {
                if ($distribution->type == 'member') {
                    Mail::to($distribution->member->email)->send(new NotifyDistribution($distribution));
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
                'status' => 'success'
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

    private function beginActionForce(Registration $registration)
    {
        try {
            $registration->status_id = 2; // En proceso
            $registration->save();

            InternalLog::create([
                'registration_id' => $registration->id,
                'action_id'       => 3, // REGISTRATION_ACEPTED
                'time'            => now(),
                'action_data'     => ['forced' => true]
            ]);

            foreach($registration->distribution as $distribution) {
                if ($distribution->type == 'member') {
                    if (trim($distribution->member->email) != "" && filter_var($distribution->member->email, FILTER_VALIDATE_EMAIL)) {
                        // Si tiene dirección válida, notificamos
                        Mail::to($distribution->member->email)->send(new NotifyDistribution($distribution));
                    } else {
                        // Si no, logeamos
                        InternalLog::create([
                            'registration_id' => $registration->id,
                            'distribution_id' => $distribution->id,
                            'action_id'       => 11, // REGISTRATION_NOT_NOTIFIED
                            'time'            => now(),
                            'action_data'     => ['member' => $distribution->member_id]
                        ]);
                    }
                }
            }

            return [
                'status' => 'success'
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
                Mail::to($registration->initiator->email)->send(new NotifyRejection($registration));
                return [
                    'status'  => 'success'
                ];
            }
        }
    }

    private function sendToInternal(Registration $registration)
    {
        try {
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

    private function approveRequest()
    {
        // Cambiar estado en la BBDD
        // Enviar mail al iniciador

        InternalLog::create([
            'registration_id' => $registration->id,
            'action_id'       => 9, // REQUEST_ACCEPTED
            'time'            => now()
        ]);
    }

    private function rejectRequest()
    {
        // Cambiar estado en la BBDD
        // Enviar mail al iniciador


        InternalLog::create([
            'registration_id' => $registration->id,
            'action_id'       => 10, // REQUEST_REJECTED
            'time'            => now()
        ]);
    }

    public function response(Request $request, Registration $registration)
    {
        try {
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
}
