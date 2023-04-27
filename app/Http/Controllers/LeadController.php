<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use App\Http\Requests\LeadRequest;
use App\Services\CreateRecordService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use GuzzleHttp\Client;


use Illuminate\Http\Request;

class LeadController extends Controller
{
    private $gi;
    private $token;
    private $baseUri;
    private $createLeadPath;
    private $getLeadStatusPath;    

    public function __construct(
        Request $request,
        protected CreateRecordService $createRecordService,
        ) {
        $this->gi = env('GI');
        $this->token =env('TOKEN');
        $this->baseUri = env('BASE_URI');
        $this->createLeadPath = env('CREATE_LEAD_PATH');
        $this->getLeadStatusPath = env('GET_LEAD_STATUS_PATH');
    }

    public function createLead(LeadRequest $request) {
        $request;
        try {
            $request;
            // $validData = $request->validated();
            // $existingUser = User::where('email', $validData['email'])
            //                     ->orWhere('phone', $validData['phone'])
            //                     ->first();
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
            // $response = $client->post($this->createLeadPath, [
            //     'json' => $validData,
            // ]);
            $response = $client->post($this->createLeadPath, [
                'json' => $request,
            ]);

            if ($response->getStatusCode() == 200) {
                if ($existingUser) {
                    return new JsonResponse([
                        'message' => 'Request sent. The user record already exists in the database.',
                    ]);
                } 

                // $newUser = $this->createRecordService->user($validData);
                // $newLead = $this->createRecordService->lead($validData);
                $newUser = $this->createRecordService->user($request);
                $newLead = $this->createRecordService->lead($request);

                $newLead->associate($newUser);
                $newLead->save();
                return new JsonResponse([
                    'message' => 'Lead created successfully.',
                    'lead' => $newLead,
                    'user' => $newLead->user,
                ]);
            } else {
                return new JsonResponse([
                    'message' => 'Failed to send lead to API.',
                    'status' => $response->getStatusCode(),
                    'error' => $response->getBody()->getContents(),
                ]);
            }
        } catch (ValidationException $e) {
            
        }
        // finally {}   // do I need this?
    }

    public function getLeadStatus(LeadRequest $request) {

    }
}

// $lead = Lead::find(1);
// $user = $lead->user;

// $user = User::find(1);
// $lead = $user->lead;



// class LeadController extends Controller
// {
//     public function create(Request $request)
//     {
//         $validatedData = $request->validate([
//             'first_name' => 'required|string',
//             'last_name' => 'required|string',
//             'email' => 'required|email|unique:leads,email',
//             'phone' => 'required|string|unique:leads,phone|regex:/^[+]?[1-9][0-9]{1,14}$/'
//         ]);

//         $leadData = [
//             'gi' => 10,
//             'email' => $validatedData['email'],
//             'firstname' => $validatedData['first_name'],
//             'lastname' => $validatedData['last_name'],
//             'country' => 'UK',
//             'phone' => $validatedData['phone'],
//             'ip' => $request->ip(),
//             'sub_id1' => '',
//             'sub_id2' => '',
//             'sub_id3' => '',
//             'sub_id4' => '',
//             'sub_id5' => '',
//             'aff_param1' => '',
//             'aff_param2' => '',
//             'aff_param3' => '',
//             'aff_param4' => '',
//             'aff_param5' => ''
//         ];

//         // Send the lead to the API
        // $response = Http::withHeaders([
        //     'Content-Type' => 'application/json',
        //     'api-key' => 'YOUR_API_TOKEN_HERE'
        // ])->post('https://api.hell-leads.com/v2/create_lead/', $leadData);

        // // Check if the lead was successfully sent to the API
        // if ($response->ok()) {
        //     // Save the lead in the database
        //     $lead = Lead::create($validatedData);
        //     return response()->json(['message' => 'Lead created successfully.', 'lead' => $lead]);
        // } else {
        //     // Handle the API error
        //     return response()->json(['message' => 'Failed to send lead to API.', 'error' => $response->json()], 400);
        // }
//     }
// }
