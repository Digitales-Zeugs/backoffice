<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Work\Registration;

class WorksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $requests = Registration::whereIn('id', function($query) {
            $query->select('registration_id')
            ->from('works_distribution')
            ->groupBy('registration_id')
            ->havingRaw('SUM(`response`) = COUNT(*)');
        })->get();

        return view('works.index', ['requests' => $requests]);
    }

    public function showView(Registration $registration)
    {
        return view('works.view', ['registration' => $registration]);
    }
}
