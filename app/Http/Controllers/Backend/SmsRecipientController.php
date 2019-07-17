<?php

namespace App\Http\Controllers\Backend;

use App\Department;
use App\Outlet;
use App\SmsRecipient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class SmsRecipientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        $query = SmsRecipient::all();
        if(request()->ajax()) {
            return DataTables::of($query)
                ->addColumn('edit', function (SmsRecipient $smsRecipient) {
                    $route = route('smsRecipient.edit', $smsRecipient->id);
                    return "<a href='$route' class='mb-2 mr-2 btn-icon btn btn-primary'><i class='pe-7s-tools btn-icon-wrapper'></i> Edit</a>";
                })
                ->addColumn('delete', function (SmsRecipient $smsRecipient) {
                    $route = route('smsRecipient.destroy', $smsRecipient->id);
                    $csrf = csrf_field();
                    $method = method_field('DELETE');
                    return "<form action='$route' method='post'>$csrf$method<button class='mb-2 mr-2 btn btn-icon btn-danger'><i class='pe-7s-delete-user'></i> Delete</button></form>";
                })
                ->editColumn('created_at', function (SmsRecipient $smsRecipient) {
                    return Carbon::parse($smsRecipient->created_at)->diffForHumans();
                })
                ->editColumn('type_name', function (SmsRecipient $smsRecipient) {
                    return $smsRecipient->sms_recipientable->name;
                })
                ->rawColumns(['edit', 'delete'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'id', 'title' => 'ID'],
            ['data' => 'name', 'title' => 'Name'],
            ['data' => 'number', 'title' => 'Number'],
            ['data' => 'type_name', 'title' => 'Outlet / Dept.'],
            ['data' => 'created_at', 'title' => 'Created_at'],
            ['data' => 'edit', 'title' => ''],
            ['data' => 'delete', 'title' => '']
        ]);

        return view('architect.smsRecipient.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $outlets = Outlet::pluck('name', 'id')->toArray();
        $departments = Department::pluck('name', 'id')->toArray();
        $groups = [
            "outlet" => $outlets,
            "department" => $departments
        ];
        //return dd($groups);
        return view('architect.smsRecipient.create', compact('groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'number' => ['required', 'unique:sms_recipients,number'],
            'desc' => 'nullable',
            'type' => ['required', 'in:outlet,department'],
            'type_id' => ['required', 'numeric']
        ]);

        $type = $request->type;
        $recipient = new SmsRecipient();
        $recipient->name = $request->name;
        $recipient->number = $request->number;
        $recipient->desc = $request->desc;

        switch ($type)
        {
            case "department":
                $depart = Department::findOrFail($request->type_id);
                $depart->sms_recipients()->save($recipient);
                break;
            case "outlet":
                $outlet = Outlet::findOrFail($request->type_id);
                $outlet->sms_recipients()->save($recipient);
                break;
            default:
                return redirect()->route('smsRecipient.index')->with('failure', "The $type you're trying to assign doesn't exists.");
                break;
        }

        $recipient->save();
        return redirect()->route('smsRecipient.index')->with('status', 'SMS Recipient has been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\SmsRecipient  $smsRecipient
     * @return \Illuminate\Http\Response
     */
    public function show(SmsRecipient $smsRecipient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\SmsRecipient  $smsRecipient
     * @return \Illuminate\Http\Response
     */
    public function edit(SmsRecipient $smsRecipient)
    {
        $outlets = Outlet::pluck('name', 'id')->toArray();
        $departments = Department::pluck('name', 'id')->toArray();
        $groups = [
            "outlet" => $outlets,
            "department" => $departments
        ];
        return view('architect.smsRecipient.edit', compact('smsRecipient', 'groups'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\SmsRecipient  $smsRecipient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SmsRecipient $smsRecipient)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'number' => ['required', Rule::unique('sms_recipients', 'number')->ignore($smsRecipient->id)],
            'desc' => 'nullable',
            'type' => ['required', 'in:outlet,department'],
            'type_id' => ['required', 'numeric']
        ]);

        $type = $request->type;
        $recipient = $smsRecipient->update($request->all());

        if($type !== $smsRecipient->type || $request->type_id !== $smsRecipient->sms_recipientable->id) {
            switch ($type)
            {
                case "department":
                    $depart = Department::findOrFail($request->type_id);
                    $depart->sms_recipients()->save($smsRecipient);
                    break;
                case "outlet":
                    $outlet = Outlet::findOrFail($request->type_id);
                    $outlet->sms_recipients()->save($smsRecipient);
                    break;
                default:
                    return redirect()->route('smsRecipient.index')->with('failure', "The $type you're trying to assign doesn't exists.");
                    break;
            }
        }

        return redirect()->route('smsRecipient.index')->with('status', 'SMS recipient has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\SmsRecipient  $smsRecipient
     * @return \Illuminate\Http\Response
     */
    public function destroy(SmsRecipient $smsRecipient)
    {
        try {
            $smsRecipient->delete();
        } catch (\Exception $e) {
            return redirect()->back()->with('failure', "Deletion of $smsRecipient->name failed. Reason: " . $e->getMessage());
        }
        return redirect()->route('smsRecipient.index')->with('status', "SMS recipient $smsRecipient->name has been deleted.");
    }
}
