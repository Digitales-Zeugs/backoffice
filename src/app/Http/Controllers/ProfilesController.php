<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfileUpdates;
use App\Models\ProfileUpdatesStatus;

class ProfilesController extends Controller
{
    public $datatablesModel = ProfileUpdates::class;

    public function __construct()
    {
        $this->middleware('datatables')->only('datatables');
        $this->middleware('auth');
    }

    public function index()
    {
        $status = ProfileUpdatesStatus::all();

        return view('profiles.index', ['status' => $status]);
    }

    public function view(ProfileUpdates $profile)
    {
        return view('profiles.view', ['profile' => $profile]);
    }

    public function changeStatus(Request $request, ProfileUpdates $profile)
    {
        $request->validate([
            'status' => 'required|exists:profile_updates_status,id',
        ]);

        $profile->status_id = $request->status;
        $profile->updated_at = now();
        $profile->save();

        if ($request->ajax()) {
            return response($profile);
        } else {
            return redirect()->action(
                [ProfilesController::class, 'view'],
                ['profile' => $profile->id]
            );
        }
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
