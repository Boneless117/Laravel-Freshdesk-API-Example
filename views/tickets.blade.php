@extends('layouts/portal/default')

@section('content')

    <div class="panel-header mb-0 ">
        <div class="header text-center">
            <h1 class="display-4 text-center text-white mb-0">My Service Requests</h1>
        </div>
    </div>

    <div class="container">

{{--        <nav aria-label="breadcrumb">--}}
{{--            <ol class="breadcrumb">--}}
{{--                <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>--}}
{{--                <li class="breadcrumb-item active">Service Requests</li>--}}
{{--            </ol>--}}
{{--        </nav>--}}

        <div class="card bg-transparent">
            <div class="card-body p-t-0">
                <a class="" id="submit" role="tabpanel" aria-labelledby="submit-tab">
                    @if($requests != false)
                    @foreach($requests as $request)
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between mouse-click" onclick="show_info({{$request->id}}, this)">
                                <p class="m-0">
                                    <strong>{{$request->subject}}</strong>
                                </p>
                                <div class="float-right">
                                    {{$request->created}} :
                                    @if($request->status == 2)
                                        <span class='badge badge-warning'>Open</span>
                                    @elseif($request->status == 3)
                                        <span class='badge badge-info'>Pending</span>
                                    @elseif($request->status == 4)
                                        <span class='badge badge-primary'>Resolved</span>
                                    @elseif($request->status == 5)
                                        <span class='badge badge-success'>Closed</span>
                                    @endif
                                </div>
                            </div>

                            <div id="ticket-{{$request->id}}-info" style="display:none;">
                                <form>
                                    <div class="form-group row mb-1 mt-1">
                                        <label class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10">
                                            <div class="btn-group float-right" role="group" aria-label="First group">
                                                <button type="button" id="ticket-notes-get-{{$request->id}}" class="btn btn-info btn-sm" onclick="show_notes('{{$request->id}}')">Get Notes</button>
                                                <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#add-note-model" data-ticket="{{$request->id}}">Add Note</button>
                                                <button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#close-request-model" data-ticket="{{$request->id}}">Close Request</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                <hr class="m-2">
                                <div class="alert alert-primary" role="alert">
                                    <small>Ticket ID: {{$request->id}}</small>
                                    <br>
                                    <small>Request Received: {{$request->created}}</small>
                                    <hr>
                                    <div class="mb-1">
                                        {!! $request->content !!}
                                    </div>
                                </div>
                                <div id="notes-toggle-{{$request->id}}"></div>
                                <div id="get-notes-{{$request->id}}">
                                    <div class="alert text-dark text-center m-0 p-0" role="alert">
                                        Click on the 'Get Notes' button to see your notes.
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                        <div class="card">
                            <div class="card-body text-center">
                                Ahhh... Looks like you have not submitted any support tickets. That or something went wrong.
                            </div>
                        </div>
                @endif
                </div>
                <div class="tab-pane fade show" id="submit" role="tabpanel" aria-labelledby="submit-tab">
                </div>
            </div>
        </div>

    </div>

    <!-- Add Note Model -->
    <div class="modal fade" id="add-note-model" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Note</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="add-note-form">
                    <div class="modal-body">
                            <input id="add-note-ticket" style="display:none;" name="ticket" />
                            <p>Adding a note will update the request for you.<br>This will open the request if it has been closed</p>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Message:</label>
                                <textarea class="form-control summernote" id="add-note-note" name="note"></textarea>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Note</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Close Request Model -->
    <div class="modal fade" id="close-request-model" tabindex="-1" role="dialog" aria-labelledby="close-request-model" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Close Request</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="close-request-form">
                    <div class="modal-body">
                        <input id="close-request-ticket" style="display:none;" name="ticket" />
                        <p>This will close your request. You can add a message on why you are closing the request.</p>
                        <div class="form-group">
                            <label for="message-text" class="col-form-label">Message:</label>
                            <textarea class="form-control summernote" id="close-request-note" name="note"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Note & Close Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <script src="{{ URL::asset('js/plugins/summernote/summernote.js') }}"></script>
    <link href="{{ URL::asset('js/plugins/summernote/summernote.css') }}" rel="stylesheet">

{{--    <script src="http://127.0.0.1:8000/js/plugins/summernote/summernote.js"></script>--}}
{{--    <link href="http://127.0.0.1:8000/js/plugins/summernote/summernote.css" rel="stylesheet">--}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#add-note-model').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var ticket = button.data('ticket') // Extract info from data-* attributes
            var modal = $(this);
            modal.find('#add-note-ticket').val(ticket);
        });

        $('#close-request-model').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget) // Button that triggered the modal
            var ticket = button.data('ticket') // Extract info from data-* attributes
            var modal = $(this);
            modal.find('#close-request-ticket').val(ticket);
        });

        $('.summernote').summernote({
            height: 100,
            placeholder: 'Enter the details here...',
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']]
            ]
        });

        $("#add-note-form").on('submit', function (e) {
            e.preventDefault();
            let formData = $(this).serialize();
            // Send AJAX to add the note
            $.ajax({
                type: "POST",
                url: "{{url('')}}/api/v1_0/portal/service/notes/add",
                data: formData,
                beforeSend: function () {
                    $('#add-note-form').css("opacity", ".5");
                },
                success: function(response){
                    if(response[0]['original']['success'] == true){
                        success_msg('Added note. Refreshing page');
                        alert('Note has been added');
                        window.location.reload();
                    }
                    else{
                        error_msg(response[0]['original']['msg']);
                    }
                },
                error: function(response){
                    error_msg(response[0]['original']['msg']);
                }
            })
        });

        $("#close-request-form").on('submit', function (e) {
            e.preventDefault();
            let formData = $(this).serialize();
            // Send AJAX to add the note
            $.ajax({
                type: "POST",
                url: '{{url("")}}/api/v1_0/portal/service/close',
                data: formData,
                beforeSend: function () {
                    $('#close-request-form').css("opacity", ".5");
                },
                success: function(response){
                    if(response[0]['original']['success'] == true){
                        success_msg('Request has been cancelled. Refreshing page');
                        alert('Request has been cancelled');
                        window.location.reload();
                    }
                    else{
                        error_msg(response[0]['original']['msg']);
                    }
                },
                error: function(response){
                    error_msg(response[0]['original']['msg']);
                }
            })
        });

        function show_info(ticket, obj) {
            $('#ticket-' + ticket + "-info").slideToggle();
        }

        function show_notes(id) {
            $.ajax({
                url: '{{url("")}}/api/v1_0/portal/service/notes/get',
                data: { "ticket": id },
                type: "POST",
                beforeSend: function () {
                    var body = "<div class='alert text-dark text-center m-0 p-0' role='alert'>Looking up your notes now...</div>";
                    $("#get-notes-" + id).empty().append(body);
                },
                success: function (response) {
                    let count = response['notes'].length;
                    let body = "";
                    if (count == 0) {
                        body += "<div class='alert text-dark text-center m-0 p-0' role='alert'>There are no notes on this request</div>";
                        $("#ticket-notes-get-" + id).prop("disabled", true);
                        $("#get-notes-" + id).empty().append(body);
                    } else {
                        for (let i = 0; i < count; i++) {
                            body += response['notes'][i]['html'];
                        }
                        $("#get-notes-" + id).empty().append(body).hide().slideToggle();
                    }
                }
            })
        };

    </script>
@endpush
