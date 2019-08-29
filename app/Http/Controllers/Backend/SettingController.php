<?php

namespace App\Http\Controllers\Backend;

use App\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingController extends Controller
{
    public function index()
    {
        $setting = Setting::firstOrFail();
        return view("architect.settings.index", compact('setting'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_method', '_token') as $index => $value)
        {
            Setting::where("key", "=", $index)->update([
                "value" => $value
            ]);
        }

        return redirect()->route('settings.index');
    }
}
