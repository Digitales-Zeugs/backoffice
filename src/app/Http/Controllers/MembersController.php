<?php

namespace App\Http\Controllers;

use App\Models\Members\Registration;
use App\Models\Members\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
