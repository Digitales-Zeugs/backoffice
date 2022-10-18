<?php

namespace App\Http\Controllers;

use App\Models\ProfileUpdates;
use App\Models\ProfileUpdatesStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        if (!Auth::user()->can('nb_socios', 'lee')) {
            abort(403);
        }

        $status = ProfileUpdatesStatus::all();

        return view('profiles.index', ['status' => $status]);
    }

    public function view(ProfileUpdates $profile)
    {
        if (!Auth::user()->can('nb_socios', 'lee')) {
            abort(403);
        }

        return view('profiles.view', ['profile' => $profile]);
    }

    public function changeStatus(Request $request, ProfileUpdates $profile)
    {
        if (!Auth::user()->can('nb_socios', 'homologa')) {
            abort(403);
        }

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
