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

    public function exportWorks()
    {
        $works = Registration::where('status_id', 6)->get();

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
                'submissionId'      => $work->id,
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

    public function importWorks(Request $request)
    {
        if (!$request->hasFile('file')) {
            abort(400);
        }

        $contents = $request->file('file')->get();
        $contents = json_decode($contents);

        if ($contents->fileHeader->receivingAgency != '128') {
            abort(400);
        }

        $events = [];
        $stats = [
            'success' => 0,
            'failure' => 0
        ];

        foreach($contents->acknowledgements as $ack) {
            // Si no es alta, omitimos el registro
            if ($ack->originalTransactionType != 'AddWork') {
                $events[] = "Respuesta $ack->submissionId omitida porque no es un alta";
                $stats['failure']++;
                continue;
            }

            $work = Registration::find($ack->originalSubmissionId);

            // Si no encontramos la solicitud en la BBDD, omitimos el registro
            if (!$work) {
                $events[] = "Respuesta $ack->submissionId omitida porque no se encontro solicitud(id $ack->originalSubmissionId) en la BBDD";
                $stats['failure']++;
                continue;
            }

            // Si la solicitud no está a la espera de respuesta, omitimos el registro
            if ($work->status_id != 6) {
                $events[] = "Respuesta $ack->submissionId omitida porque la solicitud(id $ack->originalSubmissionId) no está a la espera de respuesta";
                $stats['failure']++;
                continue;
            }

            if ($ack->transactionStatus == 'FullyAccepted') {
                $work->status_id = 7;
                $work->approved = true;
                $work->codwork = $ack->codworkSq;
                $stats['success']++;
            } else if ($ack->transactionStatus == 'Rejected') {
                $work->status_id = 8;
                $work->approved = true;
                $stats['success']++;
            } else {
                $events[] = "Respuesta $ack->submissionId omitida porque no está soportado el tipo";
                $stats['failure']++;
                continue;
            }

            $work->save();
        }

        return [
            'status' => 'success',
            'events' => $events,
            'stats'  => $stats
        ];
    }

    private function formatPercentage($percentage) {
        return $percentage * 100;
    }
}