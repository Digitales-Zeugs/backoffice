<?php

namespace App\Http\Controllers;

use App\Mail\NotifyDistribution;
use App\Models\Work\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class WorksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $requests = Registration::where('status_id', 1)->get();

        return view('works.index', ['requests' => $requests]);
    }

    public function showView(Registration $registration)
    {
        return view('works.view', ['registration' => $registration]);
    }

    public function changeStatus(Request $request, Registration $registration)
    {
        // TODO: Validar permisos

        switch($request->input('status')) {
            case 'beginAction':
                return $this->beginAction($registration);
            break;
            case 'rejectAction':
                return $this->rejectAction($request, $registration);
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

    private function beginAction(Registration $registration)
    {
        foreach($registration->distribution as $distribution) {
            if ($distribution->type == 'member') {
                Mail::to($distribution->member->email)->send(new NotifyDistribution($distribution));
            } else {
                Mail::to($distribution->meta->email)->send(new NotifyDistribution($distribution));
            }
        }

        $registration->status_id = 2; // En proceso

        return [
            'status' => 'success'
        ];
    }

    private function rejectAction()
    {
        // Cambiar estado en la BBDD
        // Enviar mail al iniciador
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
