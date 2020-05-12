<?php

namespace App;

use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Freshdesk extends Model
{
    /**
     * Returns a users ID from FreshDesk
     */
    public function getUserID($email)
    {
        $url = env('FD_URL') . "api/v2/contacts?email=$email";
        $response = $this->sendCommand($url);
        if (!$response) {
            // The user is not a client, so must be an agent.
            $url = env('FD_URL') . "api/v2/agents?email=$email";
            $response = $this->sendCommand($url);
        }
        return (!$response ? false : $response[0]->id);
    }

    /**
     * Return a users tickets
     */
    public function getTickets($email)
    {
        $url = getenv('FD_URL') . "api/v2/tickets?include=description&email=$email&order_type=desc&per_page=30";
        $response = $this->sendCommand($url);
        $count = 0;
        $result = array();

        // We want to check if there was a success value returned.
        // If so, we got an error or invalid response.
        if (!isset($response["success"])) {
            foreach ($response as $ticket) {
                $created_date = date('d/m/Y g:i a', strtotime($ticket->created_at));
                $updated_date = date('d/m/Y g:i a', strtotime($ticket->updated_at));
                $ticket_status = $this->get_ticket_status($ticket->status);
                $result[$count] = (object)[
                    'id' => $ticket->id,
                    'subject' => $ticket->subject,
                    'content' => $ticket->description,
                    'status' => $ticket->status,
                    'created' => $created_date,
                    'updated' => $updated_date
                ];
                $count++;
            }
            return $result;
        }
        else{
            return false;
        }


    }

    /**
     * Create a users ticket
     */
    public function createTicket($payload)
    {
        $url = env('FD_URL') . 'api/v2/tickets';
        $response = $this->sendCommand($url, $payload);
        return (isset($response->success) ? response()->json(array($response), 400) : response()->json(array('msg' => 'Created the request', 'success' => true), 200));
    }

    /**
     * Set a ticket as closed
     */
    public function closeTicket($ticket, $note)
    {
        $url = env('FD_URL') . "api/v2/tickets/$ticket";
        $staff = new Staff();
        $user = Auth::user()->getCommonName();
        $id = $staff->getId($user);
        $email = $staff->getEmail($id);
        $payload = json_encode(array(
            "priority" => 2,
            "status" => 5,
        ));
        if ($note != "") {
            $this->updateNotes($ticket, $note);
        }
        $response = $this->sendCommand($url, $payload, true);
        if (isset($response->success) == false) {
            return response()->json(array('msg' => 'Request has been cancelled', 'success' => true), 200);
        } else {
            return response()->json(array($response), 400);
        }
    }

    /**
     * Return an array of tickets
     * or error message
     */
    public function getNotes($ticket)
    {
        $url = env('FD_URL') . 'api/v2/tickets/' . $ticket['ticket'] . '/conversations';
        $response = $this->sendCommand($url);
        $count = 0;
        $result = array();
        if (isset($response->success) == false) {
            foreach ($response as $ticket) {
                $created_date = date('d/m/Y g:i a', strtotime($ticket->created_at));
                $updated_date = date('d/m/Y g:i a', strtotime($ticket->updated_at));
                if ($ticket->private == false) {
                    $result[$count] = (object)[
                        'body' => $ticket->body,
                        'user_id' => $ticket->user_id,
                        'agent' => $ticket->from_email,
                        'created' => $created_date,
                        'updated' => $created_date,
                        'updated' => $updated_date,
                        'html' => <<<EOF
<div class="alert alert-secondary text-dark" role="alert">
    <small>Note Added: $created_date</small>
    <hr>
    <p class="mb-1">$ticket->body</p>
</div>
EOF
                    ];
                    $count++;
                }
            }
            return $result;
        } else {
            return response()->json(array('msg' => 'No Notes for this request', 'success' => false), 200);
        }
    }

    /**
     * Update the ticket with a new comment
     */
    public function updateNotes($ticket, $note)
    {
        $user = Auth::user()->name;
        $body = "<p><strong>Noted added by: </strong>" . $user . "</p><hr>" . $note;
        $url = env('FD_URL') . 'api/v2/tickets/' . $ticket . '/notes';
        $payload = json_encode(array(
            "body" => $body,
            "private" => false,
            "incoming" => true
        ));
        $response = $this->sendCommand($url, $payload);
        if (isset($response->success) == false) {
            return response()->json(array('msg' => 'Note added', 'success' => true), 200);
        } else {
            return response()->json(array('msg' => 'Could not update the note', 'success' => false), 400);
        }
    }

    /**
     * The CURL config setup
     * $payload will be false unless specified
     * Returns Array
     */
    private function sendCommand($url, $payload = false, $update = false)
    {
        $ch = curl_init($url);
        $header[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_HEADER, $header);
        curl_setopt($ch, CURLOPT_USERPWD, env('FD_API') . ":" . env('FD_PASS'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($update) {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        }
        if ($payload) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        } else {
            curl_setopt($ch, CURLOPT_HTTPGET, 1);
        }
        curl_setopt($ch, CURLOPT_VERBOSE, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
        $server_output = curl_exec($ch);
        $info = curl_getinfo($ch);
        // Header info
        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $headers = substr($server_output, 0, $header_size);
        $response = substr($server_output, $header_size);
        curl_close($ch);

        if ($info['http_code'] == 200) {
            $data = json_decode($response);
            return $data;
        } else if ($info['http_code'] == 201) {
            $data = json_decode($response);
            return $data;
        } else {
            Log::Error($server_output);
            Log::Error('Failed to send service request | HTTP Code: ' . $info['http_code'] . ' | Header: ' . $headers . ' | Response: ' . $response);
            return array('msg' => 'Failed to send command', 'success' => false, 'http_code' => $info['http_code'], 'header' => $headers, 'response' => $response);
        }
    }

    private function get_ticket_status($num)
    {
        $status["<span class='badge badge-warning'>Open</span>"] = 2;
        $status["<span class='badge badge-info'>Pending</span>"] = 3;
        $status["<span class='badge badge-primary'>Resolved</span>"] = 4;
        $status["<span class='badge badge-success'>Closed</span>"] = 5;
        return array_search($num, $status);
    }
}
