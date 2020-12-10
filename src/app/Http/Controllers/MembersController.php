<?php

namespace App\Http\Controllers;

use App\Models\Members\Registration;
use App\Models\Members\Status;
use App\Mail\NotifyMemberRejection;
use App\Mail\NotifyMemberSendToInternal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class MembersController extends Controller
{
    public $datatablesModel = Registration::class;

    public function __construct()
    {
        $this->middleware('datatables')->only('datatables');
        $this->middleware('auth');
    }

    public function index()
    {
        if (!Auth::user()->can('nb_socios', 'lee')) {
            abort(403);
        }

        $status = Status::all();

        return view('members.index', [
            'status' => $status
        ]);
    }

    public function view(Registration $registration)
    {
        if (!Auth::user()->can('nb_socios', 'lee')) {
            abort(403);
        }

        return view('members.view', ['registration' => $registration]);
    }

    public function datatables(Request $request)
    {
        if (!Auth::user()->can('nb_socios', 'lee')) {
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
        if (!Auth::user()->can('nb_socios', 'carga')) {
            abort(403);
        }

        switch($request->input('status')) {
            case 'beginAction':
                return $this->beginAction($registration);
            break;
            case 'rejectAction':
                return $this->rejectAction($registration);
            break;
            default:
                abort(403);
        }
    }

    private function beginAction(Registration $registration)
    {
        if (!Auth::user()->can('nb_socios', 'carga')) {
            abort(403);
        }

        // Cambio estado en la BBDD
        $registration->status_id = 3; // Para Procesar
        $registration->save();

        if ($registration->email) {
            Mail::to($registration->email)->queue(new NotifyMemberSendToInternal($registration->name ?? 'Usuario', $registration->id));
        }

        return [
            'status' => 'success'
        ];
    }

    private function rejectAction(Registration $registration)
    {
        if (!Auth::user()->can('nb_socios', 'carga')) {
            abort(403);
        }

        // Cambio estado en la BBDD
        $registration->status_id = 6; // Rechazado
        $registration->save();

        if ($registration->email) {
            Mail::to($registration->email)->queue(new NotifyMemberRejection($registration->name ?? 'Usuario', $registration->id));
        }

        return [
            'status' => 'success'
        ];
    }
}
