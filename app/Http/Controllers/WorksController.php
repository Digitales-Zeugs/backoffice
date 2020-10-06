<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\WorkRegistration;
use App\Models\SADAICRoles;

class WorksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $requests = WorkRegistration::whereIn('id', function($query) {
            $query->select('registration_id')
            ->from('works_distribution')
            ->groupBy('registration_id')
            ->havingRaw('SUM(`response`) = COUNT(*)');
        })->with('distribution')->get()->toArray();

        $roles = SADAICRoles::all()->toArray();

        return view('works.index', [
            'requests' => $requests,
            'roles'    => $roles
        ]);
    }
}
