<?php

namespace App\Http\Controllers\Backend;

use App\TicketStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Html\Builder;

class TicketStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Builder $builder)
    {
        $query = TicketStatus::query()->orderBy('created_at', 'desc');
        if(request()->ajax()) {
            return DataTables::of($query)
                ->addColumn('edit', function (TicketStatus $ticketStatus) {
                    return view('architect.datatables.form-edit', ['route' => 'ticketStatus', 'model' => $ticketStatus]);
                })
                ->addColumn('delete', function (TicketStatus $ticketStatus) {
                    return view('architect.datatables.form-delete', ['route' => 'ticketStatus', 'model' => $ticketStatus]);
                })
                ->editColumn('active', function (TicketStatus $ticketStatus) {
                    return view('architect.datatables.form-active', ['active' => $ticketStatus->active]);
                })
                ->editColumn('created_at', function (TicketStatus $ticketStatus) {
                    return Carbon::parse($ticketStatus->created_at)->diffForHumans();
                })
                ->rawColumns(['edit', 'active', 'delete'])
                ->toJson();
        }

        $html = $builder->columns([
            ['data' => 'id', 'title' => 'ID'],
            ['data' => 'name', 'title' => 'Name'],
            ['data' => 'active', 'title' => 'Status'],
            ['data' => 'created_at', 'title' => 'Created At'],
            ['data' => 'edit', 'title' => ''],
            ['data' => 'delete', 'title' => '']
        ]);

        return view('architect.ticketstatus.index', compact('html'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('architect.ticketstatus.create');
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
            'name' => 'required', 'string', 'unique:ticket_statuses,name',
            'active' => 'in:on'
        ]);

        $outlet = TicketStatus::create([
            'name' => $request->name,
            'active' => $request->has('active') && $request->active == "on" ? true : false,
            'desc' => $request->desc ?: null
        ]);
        return redirect()->route('ticketStatus.index')->with('status', 'Ticket Status has been created.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\TicketStatus  $ticketStatus
     * @return \Illuminate\Http\Response
     */
    public function show(TicketStatus $ticketStatus)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\TicketStatus  $ticketStatus
     * @return \Illuminate\Http\Response
     */
    public function edit(TicketStatus $ticketStatus)
    {
        return view('architect.ticketstatus.edit', compact('ticketStatus'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\TicketStatus  $ticketStatus
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TicketStatus $ticketStatus)
    {
        $request->validate([
            'name' => 'required', 'string', 'unique:ticket_statuses,name',
            'active' => 'in:on'
        ]);

        $ticketStatus->update([
            'name' => $request->name,
            'active' => $request->has('active') && $request->active == "on" ? true : false,
            'desc' => $request->desc ?: null
        ]);
        return redirect()->route('ticketStatus.index')->with('status', 'Ticket Status has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\TicketStatus  $ticketStatus
     * @return \Illuminate\Http\Response
     */
    public function destroy(TicketStatus $ticketStatus)
    {
        if($ticketStatus->complains()->count() > 1) {
            return redirect()->route('ticketStatus.index')->with('failure', "You can't delete this status because there are tickets assigned with this status. First assign them with some other status and try again.");
        }
        try {
            $ticketStatus->delete();
        } catch (\Exception $e) {
            return redirect()->route('ticketStatus.index')->with('failure', "Failed: " . $e->getMessage());
        }

        return redirect()->route('ticketStatus.index')->with('status', 'Ticket Status has been deleted');
    }
}
