<?php

use Illuminate\Database\Seeder;
use App\Client;
use App\Profile;
use App\Business;
use App\ContactInfo;

class ClientTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
//        $client = factory(App\Client::class, 5)->create();
//
//        $client->each(function ($client) {
//            factory('App\Business', 10)->create(['client_id' => $client->id]);
//        });

        $faker = Faker\Factory::create();

        $total = 20;

        if($total > 0){
            for($a = 0; $a < $total; $a++){
            $count = Client::count();
            $data = new Client();
            $data->count = str_pad($count + 1, 5, 0, STR_PAD_LEFT);
            $data->email = $faker->email;
            $data->author = 1;
            if($data->save()){
                $profile = new Profile();
                $profile->client_id = $data->id;
                $profile->firstname = $faker->firstName;
                $profile->middlename = $faker->lastName;
                $profile->lastname = $faker->lastName;
                $profile->save();

                $random = rand(0,1);

                $business = new Business();
                $business->client_id = $data->id;
                if($random == 1){
                    $business->billing = 0;
                }
                $business->main = 1;
                $business->name = $faker->company;
                $business->oic = $profile->firstname.' '.$profile->lastname;
                if($business->save()){
                    $infos = array(
                        array('permanent_address', $faker->address),
                        array('telephone', $faker->tollFreePhoneNumber),
                        array('mobile', $faker->phoneNumber),
                    );
                    foreach ($infos as $info){
                        $contact = new ContactInfo();
                        $contact->business_id = $business->id;
                        $contact->type = $info[0];
                        $contact->description = $info[1];
                        $contact->save();
                    }
                }

                if($random == 1){
                    $business = new Business();
                    $business->client_id = $data->id;
                    $business->name = $faker->company;
                    $business->oic = $faker->firstName.' '.$faker->lastName;
                    if($business->save()){
                        $infos = array(
                            array('permanent_address', $faker->address),
                            array('telephone', $faker->tollFreePhoneNumber)
                        );
                        foreach ($infos as $info){
                            $contact = new ContactInfo();
                            $contact->business_id = $business->id;
                            $contact->type = $info[0];
                            $contact->description = $info[1];
                            $contact->save();
                        }
                    }
                }

            }

        }
        }


    }
}
