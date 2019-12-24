

@extends('layouts.architect')

@section('title', 'Complains')
@section('desc', 'Showing Complain# ' . $complain->getComplainNumber())
@section('icon', 'pe-7s-comment')

@section('styles')
    <style>
        .main-card {
            max-width: 1000px;
        }
    </style>
@endsection

@section('content')

    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="main-card mb-3 card">
                <div class="card-header">
                    <div class="btn-actions-pane-left">
                        <a href="{{ route('complain.edit', $complain->id) }}" class="btn btn-primary text-white btn-lg"><i class="fas fa-user-edit"></i> Edit</a>
                    </div>
                </div>
                <div class="card-body">

                    <ul class="list-group">
                        <li class="list-group-item">
                            <span>Complain# </span>
                            <span class="font-weight-bold">{{ $complain->getComplainNumber() }}</span>
                        </li>
                        <li class="list-group-item">
                            <span>Customer Name </span>
                            <span class="font-weight-bold">{{ $complain->customer->name ?? "DELETED" }}</span>
                        </li>
                        <li class="list-group-item">
                            <span>Customer Number </span>
                            <span class="font-weight-bold">{{ $complain->customer->number ?? "DELETED" }}</span>
                        </li>
                        <li class="list-group-item">
                            <span>Outlet </span>
                            <span class="font-weight-bold">{{ $complain->outlet->name }}</span>
                        </li>
                        <li class="list-group-item">
                            <span>Title </span>
                            <span class="font-weight-bold">{{ $complain->title }}</span>
                        </li>
                        <li class="list-group-item">
                            <span>Created At </span>
                            <span class="font-weight-bold">{{ $complain->created_at }}</span>
                        </li>
                        <li class="list-group-item">
                            <span>Created By </span>
                            <span class="font-weight-bold">{{ $complain->created_by->name }}</span>
                        </li>
                        <li class="list-group-item">
                            <span>Informed To </span>
                            <span class="font-weight-bold">{{ $complain->informed_to }}</span>
                        </li>
                        <li class="list-group-item">
                            <span>Complaint Type </span>
                            @foreach($complain->issues as $issue)
                                <div class="badge badge-{{ \Illuminate\Support\Arr::random(['success', 'primary', 'secondary', 'info', 'warning']) }}">{{ $issue->name }}</div>
                            @endforeach
                        </li>
                        <li class="list-group-item">
                            <span>Status </span>
                            @include('architect.datatables.status', ['status' => $complain->ticket_status->name])
                        </li>
                        <li class="list-group-item">
                            <span>Description </span>
                            <span class="font-weight-bold">{{ $complain->desc }}</span>
                        </li>
                        <li class="list-group-item">
                            <span>Remarks </span>
                            <span class="font-weight-bold">{{ $complain->remarks }}</span>
                        </li>
                        <li class="list-group-item">
                            <span>SMS Recipient(s) </span><br/>
                            @foreach($complain->message_recipients as $messageRecipient)
                                <span>{{ $messageRecipient->name }}: </span><span class="font-weight-bold">{{ $messageRecipient->numbers }}</span>
                                <br/>
                            @endforeach
                        </li>
                        <li class="list-group-item">
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered">
                                    <thead>
                                    <tr>
                                        <th class="text-center" colspan="9">
                                            Sent Messages
                                        </th>
                                    </tr>
                                    <tr>
                                        <th>Response</th>
                                        <th>Code</th>
                                        <th>Status</th>
                                        <th>Errorno</th>
                                        <th>MsgStatus</th>
                                        <th>MsgData</th>
                                        <th>Sender</th>
                                        <th>Receiver(s)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($complain->message_responses as $message)
                                        <tr>
                                            <td>{{ $message->response }}</td>
                                            <td>{{ $message->code }}</td>
                                            <td>{{ $message->status }}</td>
                                            <td>{{ simplexml_load_string($message->message)->errorno }}</td>
                                            <td>{{ simplexml_load_string($message->message)->status }}</td>
                                            <td>{{ simplexml_load_string($message->message)->msgdata }}</td>
                                            <td>{{ simplexml_load_string($message->message)->sender }}</td>
                                            <td>
                                                @foreach(simplexml_load_string($message->message)->receivers->receiver as $receiver)
                                                    {{ $receiver }}
                                                @endforeach
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </li>
                    </ul>

                </div>
            </div>
        </div>
    </div>

@endsection
