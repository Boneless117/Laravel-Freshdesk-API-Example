@extends('layouts/portal/default')

@section('content')
    <div class="panel-header mb-5 ">
        <div class="header text-center">
            <h1 class="display-4 text-center text-white mb-0">Bookroom : Service Request</h1>
        </div>
    </div>

    <div class="container">

        <div class="card">
            <div class="card-body p-t-0">
                <div class="" id="submit" role="tabpanel" aria-labelledby="submit-tab">

                    <p class="lead text-center mb-4">
                        Please read the <u><a class="text-primary font-weight-bold" href="https://docs.google.com/presentation/d/11im2uw6gfmxXcKRP9DWmEUWVmGeO3S4eJfAbTvFf4V4/edit?pli=1#slide=id.g7b4e62bfd5_0_0">Learning Hub Timetable, Staffing and Protocols</a></u> for further information.
                    </p>

                    <form id="ticket-form" enctype="multipart/form-data">

                        <div class="form-group row" style="display: none">
                            <label for="email-input" class="col-sm-2 col-form-label">Email</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <input name="email" type="text" class="form-control col-6 ignoreField"
                                           id="email"
                                           value="{{$email}}" disabled>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="date-required" class="col-sm-2 col-form-label">Date Required</label>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <input name="date" type="text" class="form-control" id="datetimepicker" required>
                                </div>
                                <small class="form-text text-muted">
                                    Enter the date for your request
                                </small>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="block" class="col-sm-2 col-form-label">Block Needed</label>
                            <div class="col">
                                <select name="block" type="text" class="form-control" id="block" required>
                                    <option></option>
                                    <option>Before School</option>
                                    <option>Block 1</option>
                                    <option>Recess</option>
                                    <option>Block 2</option>
                                    <option>Lunch</option>
                                    <option>Block 3</option>
                                    <option>After School</option>
                                </select>
                                <small class="form-text text-muted">
                                    Choose the block needed
                                </small>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="subject-input" class="col-sm-2 col-form-label">Subject/Class/Event</label>
                            <div class="col-sm-10">
                                <input name="subject" type="text" class="form-control" id="subject"
                                       aria-describedby="subjectHelp"
                                       placeholder="Example: PBL Year 08 Blue"
                                       autocomplete="off"
                                       required>
                                <small class="form-text text-muted">
                                    Enter the name of your class
                                </small>
                            </div>
                        </div>

                        <div class="form-group row mb-3">
                            <label for="type" class="col-sm-2 col-form-label">Request Type</label>

                            <div class="col-sm-10">
                                <select name="location" class="form-control" id="type" data-width="100%"
                                        autocomplete="off" required>
                                    <option></option>
                                    <option value="type-space">Book a Learning Space</option>
                                    <option value="type-support">Learning Hub SSO Support</option>
                                    <option value="type-lead">Lead Teacher Request</option>
                                </select>
                                <small class="form-text text-muted">
                                    What type of request are you after?
                                </small>
                            </div>
                        </div>

                        <!-- Space Booking -->
                        <div id="type-space" style="display: none">
                            <hr class="mb-3">
                            <div class="form-group row mb-3">
                                <label for="space-location" class="col-sm-2 col-form-label">Location</label>
                                <div class="col-sm-10">
                                    <select name="space-location" class="form-control" id="space-location" data-width="100%"
                                            autocomplete="off" required>
                                        <option></option>
                                        <optgroup label="Learning Hub Locations">
                                            <option>Flexible Learning Space / Projector</option>
                                            <option>Flexible Learning Space / (Near Bookroom)</option>
                                            <option>Ideation Space + Whiteboard Wall</option>
                                            <option>Jelly Beans</option>
                                            <option>Board Room</option>
                                            <option>Media Room</option>
                                            <option>Recording Room</option>
                                        </optgroup>
                                        <optgroup label="Custom / Other">
                                            <option>Other (Enter in Notes)</option>
                                        </optgroup>
                                    </select>
                                    <small class="form-text text-muted">
                                        What space did you want to book out?
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="space-layout" class="col-sm-2 col-form-label">Layout</label>
                                <div class="col-sm-10">
                                    <select name="space-layout" class="form-control" id="space-layout" data-width="100%"
                                            autocomplete="off" required>
                                        <option></option>
                                        <optgroup label="Default Layouts">
                                            <option>Check In / Out</option>
                                            <option>Four-Square</option>
                                            <option>Hot Seat</option>
                                            <option>Learning Feud</option>
                                            <option>Socratic Seminar</option>
                                            <option>Speed Dating</option>
                                            <option>The Booth</option>
                                            <option>The Campfire</option>
                                            <option>The Differentiator</option>
                                            <option>The Duo-Pod</option>
                                            <option>The Fishbowl</option>
                                            <option>The Forum</option>
                                            <option>The Jigsaw</option>
                                            <option>The Lounge</option>
                                            <option>The Runway</option>
                                            <option>The Sanctuary</option>
                                            <option>The Think Tank</option>
                                            <option>The Tri-Pod</option>
                                            <option>Watering Hole</option>
                                        </optgroup>
                                        <optgroup label="Custom / Other">
                                            <option>Other (Enter in Notes)</option>
                                        </optgroup>
                                    </select>
                                    <small class="form-text text-muted">
                                        Check out the <u><a class="font-weight-bold text-primary"
                                                         href="https://sites.google.com/wirreandasecondary.sa.edu.au/amplifylearning/learning-spaces"
                                                            target="_blank">Amplify Learning</a></u> websites for ideas
                                    </small>
                                </div>
                            </div>
                        </div>
                        <!-- Learning Hub Support -->
                        <div id="type-support" style="display: none">
                            <hr class="mb-3">
                            <div class="form-group row mb-3">
                                <label for="support-location" class="col-sm-2 col-form-label">Location</label>
                                <div class="col-sm-10">
                                    <select name="support-location" class="form-control" id="type-support-location" data-width="100%"
                                            autocomplete="off" required>
                                        <option></option>
                                        <optgroup label="Learning Hub Locations">
                                            <option>Flexible Learning Space / Projector</option>
                                            <option>Flexible Learning Space / (Near Bookroom)</option>
                                            <option>Ideation Space + Whiteboard Wall</option>
                                            <option>Jelly Beans</option>
                                            <option>Board Room</option>
                                            <option>Media Room</option>
                                            <option>Recording Room</option>
                                        </optgroup>
                                        <optgroup label="Custom / Other">
                                            <option>Other (Enter in Notes)</option>
                                        </optgroup>
                                    </select>
                                    <small class="form-text text-muted">
                                        What space did you want to book out?
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="info" class="col-sm-2 col-form-label">What is required...</label>
                                <div class="col-sm-10">
                                    <textarea name="info" class="summernote form-control" id="support-info" rows="3" required></textarea>
                                    <small class="form-text text-muted">
                                        Briefly outline what you need the Learning Hub SSO to do
                                    </small>
                                </div>

                            </div>
                        </div>
                        <!-- Fianl Step -->
                        <div id="final-step" style="display: none">
                            <hr class="mb-3">
                            <div class="form-group row mb-3">
                                <label for="description-input" class="col-sm-2 col-form-label">Additional Notes</label>
                                <div class="col-sm-10">
                                    <textarea  name="description" class="form-control summernote" id="notes" rows="6" required></textarea>
                                    <small class="form-text text-muted">
                                        Enter any additional notes
                                    </small>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col">
                                    <input type="submit" name="submit" id="submitButton" class="btn btn-primary btn-block"
                                           value="Send Request">
                                </div>
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

        $(document).ready(function() {

            $('.summernote').summernote({
                height: 200,
                placeholder: 'Enter your notes here',
                toolbar: [
                    // [groupName, [list of button]]
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol']]
                ]
            });
        });

        // $('select').selectpicker();

        $('#datetimepicker').datetimepicker({
            format: "DD/MM/YYYY",
            daysOfWeekDisabled: [0, 6]
        });

        // Show information dependant on option section
        $("#type").change(function(){

            let type = $(this).val();
            if(type == "type-space"){
                $("#type-support").hide();
                $("#" + type).show();
                $("#final-step").show();
            }
            if(type == "type-support"){
                $("#type-space").hide();
                $("#" + type).show();
                $("#final-step").show();
            }
            if(type == "type-lead"){
                $("#type-space").hide();
                $("#type-support").hide();
                $("#final-step").show();
            }
            if(!type){
                $("#type-space").hide();
                $("#type-support").hide();
                $("#final-step").hide();
            }
        });



        $("#submitButton").click(function (event){
            event.preventDefault();

            var sendEmail = true;
            var payload = {};

            if(!$("#datetimepicker").val()){
                error_msg("Need to enter a date");
                return false;
            }

            if(!$("#block").val()){
                error_msg("Need to enter a block");
                return false;
            }

            if(!$("#subject").val()){
                error_msg("Need to enter a subject");
                return false;
            }

            if(!$("#type").val()){
                error_msg("Need to enter a support type");
                return false;
            }

            payload.email = $("#email").val();
            payload.date = $("#datetimepicker").val();
            payload.block = $("#block").val();
            payload.subject = $("#subject").val();
            payload.type = $("#type option:selected").text();

            payload.spaceLocation = $("#space-location").val();
            payload.spaceLayout = $("#space-layout").val();

            payload.supportLocation = $("#support-location").val();
            payload.supportInfo = $("#support-info").val()

            payload.addNotes = $("#notes").val();

            // console.log(payload);

            $.ajax({
                type: "POST",
                data: payload,
                url: "{{url('/api/v1_0/portal/service/create/bookroom')}}",
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

            {{--$.post('{{url('/api/v1_0/portal/service/create/bookroom')}}', payload)--}}
            {{--    .beforeSend(function (){--}}
            {{--        $('#submitButton').attr("disabled", "disabled");--}}
            {{--        $('#ticket-form').css("opacity", ".5");--}}
            {{--    })--}}
            {{--    .success(function (data){--}}
            {{--        console.log(response);--}}
            {{--        success_msg_again("Ticket has been submitted");--}}
            {{--        $('#ticket-form').css("opacity", "");--}}
            {{--        $("#submitButton").removeAttr("disabled");--}}
            {{--    })--}}
            {{--    .error(function(data){--}}
            {{--        console.log(response);--}}
            {{--        error_msg(response['msg']);--}}
            {{--    });--}}

        });

        //$("#ticket-form").on('submit', function (e) {
          //  e.preventDefault();
            /*


            let spaceLocation   = $("#space-location").val();
            let spaceLayout     = $("#space-layout").val();

            // LEARNING HUB SUPPORT SSO

            let supportLocation = $("#support-location").val();
            let supportInfo     = $("#support-info").val();

            // FINALLY

            let addNotes    = $("#notes").val();


/*

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
                    error: function (response) {
                        console.log(response);
                        error_msg(response['msg']);
                    }
                });
            }
            */
        //});

    </script>
    @endpush
