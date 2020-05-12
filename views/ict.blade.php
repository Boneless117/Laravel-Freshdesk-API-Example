@extends('layouts/portal/default')

@section('content')
    <div class="panel-header mb-0 ">
        <div class="header text-center">
            <h1 class="display-4 text-center text-white mb-0">ICT : Service Request</h1>
        </div>
    </div>

    <div class="container">

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{url('/service')}}">Service Request</a></li>
            <li class="breadcrumb-item active">ICT</li>
        </ol>
    </nav>

        <div class="card">
            <div class="card-body p-t-0">
                <div class="" id="submit" role="tabpanel" aria-labelledby="submit-tab">

                    <form id="ticket-form" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="form-group row" style="display: none">
                            <label for="email-input" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input name="email" type="text" class="form-control col-6 ignoreField" id="email-input"
                                           value="{{$email}}" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="date-required" class="col-sm-2 col-form-label">Date Required</label>
                            <div class="col-sm-3">

                                <div class="input-group date" id="">
                                    <div class="input-group">
                                        <input name="date" type="text" class="form-control " id="datetimepicker" required>
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    Enter the date you need assistance
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="location-select" class="col-sm-2 col-form-label">Location</label>
                            <div class="col-sm-10">
                                <select name="location" class="form-control" id="location-select" data-width="100%"
                                        autocomplete="off" required>
                                    <option></option>
                                    <option>A Block</option>
                                    <option>B Block</option>
                                    <option>C Block</option>
                                    <option>D Block</option>
                                    <option>E Block</option>
                                    <option>Learning Hub</option>
                                    <option>Trade Trading Center</option>
                                    <option>Music Transportable</option>
                                    <option>WAVE</option>
                                    <option>Shed</option>
                                    <option>New Gym</option>
                                    <option>Grounds</option>
                                    <option>STEM</option>
                                    <option>Digital Realm</option>
                                </select>
                                <small class="form-text text-muted">
                                    Where do you need assistance?
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="room-input" class="col-sm-2 col-form-label">Room / Location</label>
                            <div class="col-sm-10">
                                <input name="room" type="text" class="form-control" id="room-input"
                                       aria-describedby="locationHelp"
                                       placeholder="Example: UB08" autocomplete="off" required>
                                <small class="form-text text-muted">
                                    Enter the room or location where you need assistance.
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="subject-input" class="col-sm-2 col-form-label">Subject</label>
                            <div class="col-sm-10">
                                <input name="subject" type="text" class="form-control" id="subject-input"
                                       aria-describedby="subjectHelp"
                                       placeholder="Example: Printer not working"
                                       autocomplete="off"
                                       pattern=".{4,30}"
                                       title="Must be between 4 and 30 Characters"
                                       required>
                                <small class="form-text text-muted">
                                    Enter a meaningful title. Needs to be more than 10 characters.
                                </small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description-input" class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10">
                                <textarea name="description" class="form-control" id="summernote" required></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col">
                                <input type="submit" name="submit" id="submitButton" class="btn btn-primary btn-block"
                                       value="Send Request">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<!--[if IE]>
<script> alert("Unsupported Browser! Please use Google Chrome");
window.location = "staff.wss.sa.edu.au";</script>
<![endif]-->
    <script src="{{ URL::asset('js/plugins/summernote/summernote.js') }}"></script>
    <link href="{{ URL::asset('js/plugins/summernote/summernote.css') }}" rel="stylesheet">

    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#summernote').summernote({
            height: 100,
            placeholder: 'Enter the details here...',
            toolbar: [
                // [groupName, [list of button]]
                ['style', ['bold', 'italic', 'underline', 'clear']],
                ['para', ['ul', 'ol', 'paragraph']]
            ]
        });

        // $('select').selectpicker();

        $('#datetimepicker').datetimepicker({
            format: "DD/MM/YYYY",
            daysOfWeekDisabled: [0, 6]
        });

        $("#ticket-form").on('submit', function (e) {
            e.preventDefault();

            var form = $("#ticket-form");
            if (form[0].checkValidity() === false) {
                e.preventDefault()
                e.stopPropagation()
            }
            form.addClass('was-validated');

            var sendEmail = true;

            if ($("#email-input").val() == "") {
                warning_msg("Missing Email");
                sendEmail = false;
            }
            if ($("#date-required").val() == "") {
                warning_msg("Missing Date");
                sendEmail = false;
            }
            if ($("#location-select").val() == "") {
                warning_msg("Missing Location");
                sendEmail = false;
            }
            if ($("#room-input").val() == "") {
                warning_msg("Missing Room");
                sendEmail = false;
            }
            if ($("#subject-input").val() == "") {
                warning_msg("Missing Subject");
                sendEmail = false;
            }
            if ($("#subject-input").val() == "") {
                warning_msg("Missing Subject");
                sendEmail = false;
            }
            if ($("#subject-input").val().length < 10) {
                warning_msg("Subject needs to be more than 10 characters");
                sendEmail = false;
            }
            if ($("#summernote").val() == "") {
                warning_msg("Missing Description");
                sendEmail = false;
            }

            if (sendEmail === true) {
                let formData = $(this).serialize();
                $.ajax({
                    type: 'post',
                    url: "{{url('')}}/api/v1_0/portal/service/create/ict",
                    data: formData,
                    beforeSend: function () {
                        $('#submitButton').attr("disabled", "disabled");
                        $('#ticket-form').css("opacity", ".5");
                    },
                    success: function (response) {
                        console.log(response);
                        success_msg_again("Ticket has been submitted");
                        $('#ticket-form').css("opacity", "");
                        $("#submitButton").removeAttr("disabled");
                    },
                    error: function (response){
                        console.log(response);
                        error_msg(response['msg']);
                    }
                });
            }
        });

    </script>
@endpush
