<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Lead;
use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\LeadRequest;
use App\Services\CreateRecordService;
use GuzzleHttp\RequestOptions;
use GuzzleHttp\Psr7\Request as Psr7Request;
use GuzzleHttp\Psr7\Request as GuzzleRequest;
use Illuminate\Validation\ValidationException;

class LeadController extends Controller
{
    private $gi;
    private $token;
    private $baseUri;
    private $createLeadPath;
    private $getLeadStatusPath;

    public function __construct(
        protected CreateRecordService $createRecordService,
    ) {
        $this->gi = env('GI');
        $this->token = env('TOKEN');
        $this->baseUri = env('BASE_URI');
        $this->createLeadPath = env('CREATE_LEAD_PATH');
        $this->getLeadStatusPath = env('GET_LEAD_STATUS_PATH');
    }

    public function createLeadUpd(LeadRequest $request) {
        $existingUser = User::where('email', $request['email'])
                ->orWhere('phone', $request['phone'])
                ->first();

        if ( ! $existingUser) {
            $newLead = $this->createRecordService->lead($request);
            $newUser = $this->createRecordService->user($request);
            $newUser->lead()->associate($newLead)->save();

            return new JsonResponse([
                'success' => true,
                'message' => 'New record created.',
                'lead' => $newLead,
                'user' => $newUser,
            ]);
        }

        return new JsonResponse([
            'success' => false,
            'message' => 'Record already exists.',
            'lead' => $existingUser->lead,
            'user' => $existingUser,
        ]);
    }



    public function createLeadFirst(LeadRequest $request) {
        
        $existingUser = User::where('email', $request['email'])
                ->orWhere('phone', $request['phone'])
                ->first();

        if ($request->header('api-key') == $this->token) {
            if ( ! $existingUser) {
                $newUser = $this->createRecordService->user($request);
                $newLead = $this->createRecordService->lead($request);
                $newLead->associate($newUser);
                $newLead->save();

                return new JsonResponse([
                    'success' => true,
                    'message' => 'New user record created.',
                    'lead' => $newLead,
                    'user' => $newUser,
                ]);
            } else {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'User record already exists.',
                    'existing_user' => $existingUser,
                ]);
            }
        } else {
            return new JsonResponse([
                'success' => false,
                'message' => 'Invalid token.',
            ]);
        }
    }

    public function createLeadSecond(LeadRequest $request)
    {
        try {
            $existingUser = User::where('email', $request['email'])
                ->orWhere('phone', $request['phone'])
                ->first();

            $client = new Client([
                'base_uri' => $this->baseUri,
                'headers' => [
                    'Content-Type' => 'application/json',
                    'api-key' => $this->token,
                ],
            ]);
            
            // Caught exception: Server error: `POST http://api.hell-leads.com/v2/create_lead/` resulted in a `500 Internal Server Error` response
            try {
                $response = $client->post($this->createLeadPath, [
                    'json' => $request,
                ]);
            } catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }

            if ($response->getStatusCode() == 200) {
                if ($existingUser) {
                    return new JsonResponse([
                        'sucess' => true,
                        'message' => 'Request sent. The user record already exists in the database.',
                    ]);
                }
                $newUser = $this->createRecordService->user($request);
                $newLead = $this->createRecordService->lead($request);
                $newUser->lead()->associate($newLead);
                return new JsonResponse([
                    'success' => true,
                    'message' => 'Lead created successfully.',
                    'lead' => $newLead,
                    'user' => $newLead->user,
                ]);
            } else {
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Failed to send lead to API.',
                    'status' => $response->getStatusCode(),
                    'error' => $response->getBody()->getContents(),
                ]);
            }
        } catch (ValidationException $e) {
        }
        // finally {}   // do I need this?
    }

    public function getLeadStatus(LeadRequest $request)
    {
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'api-key' => $this->token,
        ];
        $body = [
            "rangeFrom" => "2022-12-16",
            "rangeTo" => "2022-12-16",
            "filters" => ["transactions" => "Your transaction_id"],
        ];

        $response = $client->post('https://api.hell-leads.com/get_lead_status/', [
            'headers' => $headers,
            'body' => $body
        ]);
        $data = $response->getBody()->getContents();
        return $data;
    }
}