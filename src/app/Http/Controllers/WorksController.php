<?php

namespace App\Http\Controllers;

use App\Mail\NotifyDistribution;
use App\Mail\NotifyRejection;
use App\Models\Work\Registration;
use App\Models\Work\Status;
use Illuminate\Http\Request;
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
                return $this->beginAction($registration);
            break;
            case 'rejectAction':
                return $this->rejectAction($registration);
            break;
            case 'sendToInternal':
                return $this->sendToInternal($request, $registration);
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
        $errors = [];
        // 
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
                'status' => 'failed',
                'errors' => $errors
            ];
        }

        foreach($registration->distribution as $distribution) {
            if ($distribution->type == 'member') {
                Mail::to($distribution->member->email)->send(new NotifyDistribution($distribution));
            } else {
                Mail::to($distribution->meta->email)->send(new NotifyDistribution($distribution));
            }
        }

        $registration->status_id = 2; // En proceso
        $registration->save();

        return [
            'status' => 'success'
        ];
    }

    private function rejectAction(Registration $registration)
    {
        // Cambio estado en la BBDD
        $registration->status_id = 8; // Rechazado
        $registration->save();

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

    private function sendToIntenal()
    {
        // Cambiar estado en la BBDD
    }

    private function approveRequest()
    {
        // Cambiar estado en la BBDD
        // Enviar mail al iniciador
    }

    private function rejectRequest()
    {
        // Cambiar estado en la BBDD
        // Enviar mail al iniciador
    }
}
