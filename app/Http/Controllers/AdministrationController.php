<?php

namespace App\Http\Controllers;

use App\Enums\AccessLevelEnum;
use App\Models\VatsimAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class AdministrationController extends Controller
{
    public function index()
    {
        return redirect()->route('administration.accounts');
    }

    public function accounts()
    {
        $privilegedAccounts = VatsimAccount::where('access_level', AccessLevelEnum::Administrator)->orWhere('access_level', AccessLevelEnum::Root)->get();
        return view('administration.accounts.index', compact('privilegedAccounts'));
    }

    public function controllers()
    {
        $controllerAccounts = VatsimAccount::whereAccessLevel(AccessLevelEnum::Controller)->get();
        return view('administration.accounts.controllers', compact('controllerAccounts'));
    }

    public function addAccess(Request $request)
    {
        $vatsimAccount = VatsimAccount::whereId($request->get('id'))->first();

        if (!$vatsimAccount) {
            toastr()->error('Account not found. They may need to login to natTrak first.');
            return redirect()->route('administration.accounts');
        }

        $vatsimAccount->access_level = AccessLevelEnum::Administrator;
        $vatsimAccount->save();

        toastr()->success('Account added!');
        return redirect()->route('administration.accounts');
    }

    public function addControllerAccess(Request $request)
    {
        $vatsimAccount = VatsimAccount::whereId($request->get('id'))->first();

        if (!$vatsimAccount) {
            toastr()->error('Account not found. They may need to login to natTrak first.');
            return redirect()->route('administration.controllers');
        } else if ($vatsimAccount->can('administrate')) {
            toastr()->error('Account already an administrator.');
            return redirect()->route('administration.controllers');
        }

        $vatsimAccount->access_level = AccessLevelEnum::Controller;
        $vatsimAccount->save();

        toastr()->success('Account added!');
        return redirect()->route('administration.controllers');
    }

    public function removeAccess(Request $request)
    {
        $vatsimAccount = VatsimAccount::whereId($request->get('vatsimAccountId'))->first();

        if (Auth::id() == $vatsimAccount->id) {
            toastr()->error('You can\'t remove yourself!');
            return redirect()->route('administration.accounts');
        } elseif ($vatsimAccount->access_level == AccessLevelEnum::Root) {
            toastr()->error('You can\'t remove a root user.');
            return redirect()->route('administration.accounts');
        }

        if ($vatsimAccount->rating_int < 5) {
            $vatsimAccount->access_level = AccessLevelEnum::Pilot;
        } else {
            $vatsimAccount->access_level = AccessLevelEnum::Controller;
        }

        $vatsimAccount->save();

        toastr()->success("$vatsimAccount->id's access has been removed");
        return redirect()->route('administration.accounts');
    }

    public function removeControllerAccess(Request $request)
    {
        $vatsimAccount = VatsimAccount::whereId($request->get('vatsimAccountId'))->first();

        if (Auth::id() == $vatsimAccount->id) {
            toastr()->error('You can\'t remove yourself!');
            return redirect()->route('administration.controllers');
        }

        $vatsimAccount->access_level = AccessLevelEnum::Pilot;
        $vatsimAccount->save();

        toastr()->success("$vatsimAccount->id's access has been removed");
        return redirect()->route('administration.controllers');
    }

    public function activityLog()
    {
        return view('administration.activity-log', [
            'log' => Activity::all()
        ]);
    }


}
