<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProfileUpdates;

class ProfilesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $updates = ProfileUpdates::where('status_id', 1)->get();

        return view('profiles.index', ['updates' => $updates]);
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
}
