<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\User;

class CreateRecordService 
{
    public function __construct() {

    }

    public function user($data) {
        return User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'phone' => $data['phone'],
            'ip' => $data['ip'],
            'sub_id1' => $data['sub_id1'],
            'sub_id2' => $data['sub_id2'],
            'sub_id3' => $data['sub_id3'],
            'sub_id4' => $data['sub_id4'],
            'sub_id5' => $data['sub_id5'],
        ]);
    }

    public function lead($data) {
        return Lead::create([
            'gi' => $data['gi'],
            'token' => $data['token'],
            'aff_param1' => $data['aff_param1'],
            'aff_param2' => $data['aff_param2'],
            'aff_param3' => $data['aff_param3'],
            'aff_param4' => $data['aff_param4'],
            'aff_param5' => $data['aff_param5'],
        ]);
    }


}