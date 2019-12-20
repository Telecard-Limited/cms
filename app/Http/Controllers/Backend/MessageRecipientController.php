<?php

namespace App\Http\Controllers\Backend;

use App\MessageRecipient;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Spatie\ValidationRules\Rules\Delimited;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class MessageRecipientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Builder $builder
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index(Builder $builder)
    {
        $query = MessageRecipient::query();
        if(request()->ajax()) {
            return DataTables::of($query)
                ->addColumn('edit', function (MessageRecipient $messageRecipient) {
                    return view('architect.datatables.form-edit', ['model' => $messageRecipient, 'route' => 'messageRecipient']);
                })
                ->addColumn('delete', function (MessageRecipient $messageRecipient) {
                    return view('architect.datatables.form-delete', ['model' => $messageRecipient, 'route' => 'messageRecipient']);
                })
                ->rawColumns(['edit', 'delete'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'id', 'title' => 'ID'],
            ['data' => 'name', 'title' => 'Name'],
            ['data' => 'numbers', 'title' => 'Numbers'],
            ['data' => 'edit', 'title' => 'Edit'],
            ['data' => 'delete', 'title' => 'Delete'],
        ]);

        return view('architect.messageRecipient.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('architect.messageRecipient.create');
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
            'name' => ['required', 'string', 'unique:message_recipients,name'],
            'numbers' => ['required', new Delimited('numeric')]
        ]);

        $message = MessageRecipient::create([
            'name' => $request->name,
            'numbers' => $request->numbers
        ]);
        return redirect()->route('messageRecipient.index')->with('status', 'SMS Recipient has been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MessageRecipient  $messageRecipient
     * @return \Illuminate\Http\Response
     */
    public function show(MessageRecipient $messageRecipient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MessageRecipient  $messageRecipient
     * @return \Illuminate\Http\Response
     */
    public function edit(MessageRecipient $messageRecipient)
    {
        return view('architect.messageRecipient.edit', compact('messageRecipient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MessageRecipient  $messageRecipient
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function update(Request $request, MessageRecipient $messageRecipient)
    {
        $request->validate([
            'name' => ['required', 'string', Rule::unique('message_recipients', 'name')->ignore($messageRecipient->id)],
            'numbers' => ['required', new Delimited('numeric')]
        ]);

        $message = $messageRecipient->update([
            'name' => $request->name,
            'numbers' => $request->numbers
        ]);
        return redirect()->route('messageRecipient.index')->with('status', 'SMS Recipient has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MessageRecipient  $messageRecipient
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    public function destroy(MessageRecipient $messageRecipient)
    {
        if($messageRecipient->complains()->count()) {
            return redirect()->route('messageRecipient.index')->with('failure', "This message recipient list has multple complains assigned to it. First unassign them before procedding to deletion.");
        }
        try {
            $messageRecipient->delete();
            return redirect()->route('messageRecipient.index')->with('status', 'SMS Recipient has been deleted.');
        } catch (\Exception $exception) {
            return redirect()->route('messageRecipient.index')->with('error', "SMS recipient deletion failed. Reason: " . $exception->getMessage());
        }
    }
}
