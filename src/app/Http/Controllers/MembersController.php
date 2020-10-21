<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Members\Registration;
use App\Models\Members\Status;

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
        $status = Status::all();

        return view('members.index', [
            'status' => $status
        ]);
    }

    public function view(Registration $registration)
    {
        return view('members.view', ['registration' => $registration]);
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
}
