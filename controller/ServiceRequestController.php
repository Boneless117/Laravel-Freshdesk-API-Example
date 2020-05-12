<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Controllers\Portal;

use App\Permission;
use App\Student;
use Auth;
use App\Staff;
use App\Freshdesk;
use App\Models\Portal\ServiceRequest;

use Illuminate\Http\Request;

class ServiceRequestController extends Controller
{

    /**
     * Show new Interaction page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function interactions(){

        // Do we have permission
        if ( Permission::check('requests_ict') == false){
            return view('/errors.nopermission');
        }
        $staff = new Staff();
        $student = new Student();
        $staffList = $staff::all('id', 'name');
        $studentList = $student::all('id', 'name');
        $staffId = Auth::user()->id;
        $staffemail = Auth::user()->email;

        // Does the user have a freshdesk id?
        $staff->getFreshdeskId();

        return view('/portal/service/interactions', array(
            'email' => $staffemail,
            'id' => $staffId,
            'staffList' => $staffList,
            'studentList' => $studentList
        ));
    }

    /**
     * Create a ticket for the ICT department
     * @return \Illuminate\Http\JsonResponse
     */
    public function newInteraction(){
        $fd = new Freshdesk();
        $staff = new Staff();
        $attributes = request()->all();
        $user = Auth::user()->name;

        if ($attributes['user'] === "student"){
            $userName = $staff->getName($attributes['student']);
            $staffEmail = "ICT.Shared820@schools.sa.edu.au";
        }
        else{
            $userName = $staff->getName($attributes['user']);
            $staffEmail = $staff->getEmail($attributes['user']);
        }

        $subject = "[ICT] " . $attributes['category'];
        $body = "<b>Reported by: </b>" . $user. "<br><b>For: </b> " . $userName . "<br><b>Date: </b> " . now() . "<br><br><b>Notes: </b><br>" . $attributes['description'] . "";
        $ticket_payload = json_encode(array(
            "email" => $staffEmail,
            "subject" => $subject,
            "description" => $body,
            "priority" => 1,
            "status" => 2,
            "tags" => array($attributes['type'], $attributes['category']),
            "responder_id" => Auth::user()->freshdesk_id,
        ));
        $response = $fd->createTicket($ticket_payload);
        return response()->json(array($response), 200);
    }

    /**
     * Show ICT Request Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function ict(){
        $staffemail = Auth::user()->email;
        return view('/portal/service/ict', array(
            'email' => $staffemail
        ));
    }

    /**
     * Show Facilities Request Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function facilities(){
        $staffemail = Auth::user()->email;
        return view('/portal/service/facilities', array(
            'email' => $staffemail
        ));
    }

    /**
     * Show Facilities Request Page
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bookroom(){
        $staffemail = Auth::user()->email;
        return view('/portal/service/bookroom', array(
            'email' => $staffemail
        ));
    }

    /**
     * Return the notes for the ticket
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNotes(){
        $fd = new Freshdesk();
        $ticket = request()->all('ticket');
        $notes = $fd->getNotes($ticket);
        return response()->json(array(
            'notes' => $notes
        ), 200);
    }

    /**
     * Add a note to the ticket
     * @return \Illuminate\Http\JsonResponse
     */
    public function addNote(){
        $fd = new Freshdesk();
        $attributes = request()->all('ticket', 'note');
        $ticket = $attributes['ticket'];
        $note = $attributes['note'];
        $response = $fd->updateNotes($ticket, $note);
        return response()->json(array($response), 200);
    }

    /**
     * Create a ticket for the ICT department
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitTicketICT(){
        $fd = new Freshdesk();
        $attributes = request()->all();
        $staffemail = Auth::user()->email;
        $subject = "[ICT] " . $attributes['subject'];
        $body = "<b>Location: </b>" . $attributes['location'] . "<br><b>Room: </b> " . $attributes['room'] . "<br><b>Date: </b> " . $attributes['date'] . "<br><br><b>Request Info: </b><br>" . $attributes['description'] . "";
        $ticket_payload = json_encode(array(
            "email" => $staffemail,
            "subject" => $subject,
            "description" => $body,
            "priority" => 1,
            "status" => 2,
            "tags" => array("Web Portal", $attributes['location'])
        ));
        $response = $fd->createTicket($ticket_payload);
        return response()->json(array($response), 200);
    }

    /**
     * Create a ticket for the ICT department
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitTicketBookroom(){
        $fd = new Freshdesk();
        $attributes = request()->all();
        $staffemail = Auth::user()->email;
        $subject = "[BRM] " . $attributes['date'] . " | ". $attributes['type'];
        $body = "<b>Subject/Class/Event: </b>" . $attributes['subject'] . "<br><b>Space: </b>" . $attributes['spaceLocation'] . "<br><b>Layout: </b> " . $attributes['spaceLayout'] . "<br><b>Date: </b> " . $attributes['date'] . "<br><b>Block: </b> " . $attributes['block'] . "<br><br><b>Info: </b><br>" . $attributes['supportInfo'] . "<br><br><b>Additional Notes: </b><br>" . $attributes['addNotes'] ."";
        $ticket_payload = json_encode(array(
            "email" => $staffemail,
            "subject" => $subject,
            "description" => $body,
            "priority" => 1,
            "status" => 2,
            "tags" => array("Web Portal")
        ));
        $response = $fd->createTicket($ticket_payload);
        return response()->json(array($response), 200);
    }

    /**
     * Create a ticket for the Facilities department
     * @return
     */
    public function submitTicketFacilities(){
        $fd = new Freshdesk();
        $attributes = request()->all();
        $staffemail = Auth::user()->email;
        $subject = "[FAC] " . $attributes['date'] . " | " . $attributes['subject'];
        $body = "<b>Location: </b>" . $attributes['location'] . "<br><b>Room: </b> " . $attributes['room'] . "<br><b>Date: </b> " . $attributes['date'] . "<br><br><b>Request Info: </b><br>" . $attributes['description'] . "";
        $ticket_payload = json_encode(array(
            "email" => $staffemail,
            "subject" => $subject,
            "description" => $body,
            "priority" => 1,
            "status" => 2,
            "tags" => array("Web Portal", $attributes['location'])
        ));
        $response = $fd->createTicket($ticket_payload);
        return response()->json(array($response), 200);
    }

    /**
     * Close the ticket
     * @return JSON
     */
    public function closeTicket(){
        $fd = new Freshdesk();
        $attributes = request()->all('ticket', 'note');
        $ticket = $attributes['ticket'];
        $note = $attributes['note'];
        $response = $fd->closeTicket($ticket, $note);
        return response()->json(array($response), 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service = new ServiceRequest();
        $staffemail = Auth::user()->email;
        $requests = $service->getUserTickets();
        return view('/portal/service/tickets', array(
            'email' => $staffemail,
            'requests' => $requests
        ));
    }

}
