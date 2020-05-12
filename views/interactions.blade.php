@extends('layouts/portal/default')

@section('content')
    <div class="panel-header mb-0 ">
        <div class="header text-center">
            <h1 class="display-4 text-center text-white mb-0">ICT : Log Interaction</h1>
        </div>
    </div>

    <div class="container">

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
                            <label for="location-select" class="col-sm-2 col-form-label">Type</label>
                            <div class="col-sm-10">
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="walk-radio" name="customRadio" class="custom-control-input">
                                    <label class="custom-control-label" for="walk-radio">Walk-Up</label>
                                </div>
                                <div class="custom-control custom-radio">
                                    <input type="radio" id="phone-radio" name="customRadio" class="custom-control-input">
                                    <label class="custom-control-label" for="phone-radio">Phone</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row" >
                            <label for="email-input" class="col-sm-2 col-form-label">User</label>
                            <div class="col-sm-10">
                                <div class="input-group">
                                    <select name="user" class="form-control form-control-chosen" id="user-select" data-width="100%"
                                            autocomplete="off" required>
                                        <option></option>
                                        <optgroup label="Staff">
                                        @foreach($staffList as $staff)
                                            <option value="{{$staff->id}}">{{$staff->name}}</option>
                                        @endforeach
                                        </optgroup>
                                        <optgroup label="Student">
                                        @foreach($studentList as $student)
                                            <option value="student">{{$student->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="location-select" class="col-sm-2 col-form-label">Category</label>
                            <div class="col-sm-10">
                                <select name="category" class="form-control form-control-chosen" id="category-select" data-width="100%"
                                        autocomplete="off" required>
                                    <option></option>
                                    <option>AV</option>
                                    <option>Printer</option>
                                    <option>Password</option>
                                    <option>Email</option>
                                    <option>Google</option>
                                    <option>Phones</option>
                                    <option>Daymap</option>
                                    <option>PC Issues</option>
                                    <option>Internet / Network</option>
                                    <option>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="description-input" class="col-sm-2 col-form-label">Description</label>
                            <div class="col-sm-10">
                                <textarea name="content" class="form-control" id="summernote" required></textarea>
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
        $('.form-control-chosen').chosen();
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

        $("#ticket-form").on('submit', function (e) {
            e.preventDefault();
            let type = false
            let student = $("#user-select option:selected").text();
            var sendEmail = true;
            let staff = {{$id}};
            if($("#walk-radio").is(':checked')){
                type = "Walk Up";
            }
            if($("#phone-radio").is(':checked')) {
                type = "Phone";
            }
            let user = $("#user-select").val();
            let category = $("#category-select").val();
            let description = $("#summernote").val();

            // Validation
            if (type == false){
                error_msg("Missing Type")
                return false;
            }
            if (user == ""){
                error_msg("Missing User")
                return false;
            }
            if (category == ""){
                error_msg("Missing Category")
                return false;
            }
            if (sendEmail === true) {
                $.ajax({
                    type: 'POST',
                    url: "{{url('/api/v1_0/portal/service/interactions/new')}}",
                    data: {
                        "id"   : staff,
                        "type" : type,
                        "user" : user,
                        "student" : student,
                        "category" : category,
                        "description" : description
                    },
                    beforeSend: function () {
                        $('#submitButton').attr("disabled", "disabled");
                        $('#ticket-form').css("opacity", ".5");
                    },
                    success: function (response) {
                        console.log(response);
                        alert("Ticket has been submitted");
                        // success_msg("Ticket has been submitted");
                        $('#ticket-form').css("opacity", "");
                        $("#submitButton").removeAttr("disabled");
                        $("#ticket-form").trigger("reset");
                        window.location.reload();
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
