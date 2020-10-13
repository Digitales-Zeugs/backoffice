<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WorkRegistration;
use App\Models\SADAICRoles;

class WorksController extends Controller
{
    public $datatablesModel = WorkRegistration::class;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('datatables')->only('index');
    }

    public function index(Request $request)
    {
        if (!$request->ajax()) {
            return view('works.index');
        }

        $query = $request->datatablesQuery;
        $query->with('distribution', 'distribution.role');
        $requests = $query->get();

        $response = response(null);
        $response->datatablesOutput = $requests;

        return $response;
    }

    public function view(WorkRegistration $registration)
    {
        $registration->load('distribution', 'distribution.role');

        return view('works.view', [
            'registration' => $registration
        ]);
    }
}
