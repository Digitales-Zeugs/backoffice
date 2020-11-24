<?php

namespace App\Http\Controllers;

use App\Models\Work\Distribution;
use App\Models\Work\Registration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IntegrationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('integration.index');
    }

    public function exportWorks() {
        $works = Registration::where('status_id', 6)->get();

        $submissionId = 1;

        $works_data = $works->map(function(Registration $work) use (&$submissionId) {
            $interestedParties = $work->distribution->map(function(Distribution $dist) {
                return [
                    'nameNumber' => $dist->type == 'member' ? $dist->member->ipname : -1,
                    'name'       => $dist->type == 'member' ? ucwords(strtolower($dist->member->nombre)) : $dist->meta->name,
                    'role'       => $dist->fn,
                    'porcentPer' => $this->formatPercentage($dist->public),
                    'porcentMec' => $this->formatPercentage($dist->mechanic),
                    'porcentSyn' => $this->formatPercentage($dist->sync)
                ];
            });

            $data = [
                'submissionId'      => $submissionId,
                'agency'            => '128',
                'originalTitle'     => $work->title,
                'interestedParties' => $interestedParties
            ];

            $submissionId++;

            return $data;
        });

        $date = new \DateTime('now');

        $fileContents = [
            'fileHeader' => [
                '$schema'              => './work_schema.json',
                'submittingAgency'     => '128',
                'fileCreationDateTime' => $date->format('Y-m-d\TH:i:s.uT'),
                'receivingAgency'      => '061'
            ],
            'addWorks' => $works_data
        ];

        $fileName = 'work-';
        $fileName .= $date->format('Y-m-d\TH:i:s');
        $fileName .= '-128-061-registros.json';

        return response()->streamDownload(function() use ($fileContents) {
            echo json_encode($fileContents, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        }, $fileName, [
            'Content-Encoding' => 'utf-8',
            'Content-Type'     => 'application/json'
        ]);
    }

    private function formatPercentage($percentage) {
        return $percentage * 100;
    }
}