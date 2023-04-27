<?php

namespace Database\Seeders;

use Faker\Factory as Faker;
use Illuminate\Http\Request;
use Illuminate\Database\Seeder;

use App\Services\CreateRecordService;
use App\Http\Controllers\LeadController;
use App\Http\Requests\LeadRequest;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        $data = [
            'ig' => env('GI'),
            'email' => $faker->unique()->safeEmail(),
            'firstname' => $faker->firstName(),
            'lastname' => $faker->lastname(),
            'country' => $faker->countryISOAlpha3(),
            'phone' => $faker->regexify('^\+123(5|6|7|9)(3|[5-9])\d{7}$'),
            'ip' => $faker->ipv4(),
            "sub_id1" => "Unique user id",
            "sub_id2" => "SID2",
            "sub_id3" => "SID3",
            "sub_id4" => "SID4",
            "sub_id5" => "SID5",
            "aff_param1" => "name offer",
            "aff_param2" => "Free params",
            "aff_param3" => "Free params",
            "aff_param4" => "Free params",
            "aff_param5" => "Free params",
        ];

        $request = LeadRequest::create(env('CREATE_LEAD_LINK'), 'POST', $data);
        $createRecordService = new CreateRecordService();
        $controller = new LeadController($request, $createRecordService);
        $controller->createLead($request);
    }
}
