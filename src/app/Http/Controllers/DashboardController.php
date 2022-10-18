<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $workStatsPerDay = DB::table('works_registration')
            ->selectRaw('DATE(created_at) created, COUNT(*) count')
            ->orderBy('created', 'DESC')
            ->groupByRaw('created')
            ->limit(7)
            ->get()
            ->toArray();

        $workStatsStatus = DB::table('works_status')
            ->select(['works_status.id', 'works_status.name', DB::raw('COUNT(works_registration.id) count')])
            ->leftJoin('works_registration', 'works_registration.status_id', '=', 'works_status.id')
            ->groupBy('works_status.id', 'works_status.name')
            ->get();

        $worksTotal = 0;
        $worksFinished = 0;
        $workStatsStatus->each(function($item) use (&$worksTotal, &$worksFinished) {
            if ($item->id == 9) {
                $worksFinished += $item->count;
            }

            $worksTotal += $item->count;
        });

        $workStatsStatus = $workStatsStatus->toArray();

        $jingleStatsStatus = DB::table('jingles_registration_status')
            ->select(['jingles_registration_status.id', 'jingles_registration_status.name', DB::raw('COUNT(jingles_registration.id) count')])
            ->leftJoin('jingles_registration', 'jingles_registration.status_id', '=', 'jingles_registration_status.id')
            ->groupBy('jingles_registration_status.id', 'jingles_registration_status.name')
            ->get()
            ->toArray();

        return view('dashboard.index', [
            'worksTotal'    => $worksTotal,
            'worksFinished' => $worksFinished,
            'worksDays'     => $workStatsPerDay,
            'worksStatus'   => $workStatsStatus,
            'jinglesStatus' => $jingleStatsStatus
        ]);
    }
}
