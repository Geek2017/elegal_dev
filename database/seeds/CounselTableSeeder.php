<?php

use Illuminate\Database\Seeder;
use App\Counsel;
use App\User;
use App\Profile;
use App\ContactInfo;

class CounselTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new Counsel();
        $data->email = 'peter.ralla@gmail.com';
        $data->lawyer_type = 'Lawyer Type';
        $data->lawyer_code = 'Lawyer Code';
        $data->author = 1;
        if($data->save()){
            $profile = new Profile();
            $profile->counsel_id = $data->id;
            $profile->firstname = 'Peter Leo';
            $profile->middlename = 'M.';
            $profile->lastname = 'Ralla';
            $profile->save();

            $address = new ContactInfo();
            $address->counsel_id = $data->id;
            $address->type = 'present_address';
            $address->description = 'Brgy. Banadero, Legazpi City';
            $address->save();

            $user = new User();
            $user->name = $profile->firstname.' '.$profile->middlename.' '.$profile->lastname;
            $user->email = $data->email;
            $user->password = bcrypt('pacific');
            if($user->save()){
                $user->assignRole('counsel');
                $data->user_id = $user->id;
                $data->save();
            }
        }

        $total = 0;
        if($total > 0){
            $faker = Faker\Factory::create();
            for($a = 0; $a < $total; $a++){
                $data = new Counsel();
                $data->email = $faker->email;
                $data->lawyer_type = $faker->jobTitle;
                $data->lawyer_code = $faker->phoneNumber;
                if($data->save()){
                    $profile = new Profile();
                    $profile->counsel_id = $data->id;
                    $profile->firstname = $faker->firstName;
                    $profile->middlename = $faker->lastName;
                    $profile->lastname = $faker->lastName;
                    $profile->save();

                    $address = new ContactInfo();
                    $address->counsel_id = $data->id;
                    $address->type = 'present_address';
                    $address->description = $faker->address;
                    $address->save();

                    $user = new User();
                    $user->name = $profile->firstname.' '.$profile->middlename.' '.$profile->lastname;
                    $user->email = $data->email;
                    $user->password = bcrypt('pacific');
                    if($user->save()){
                        $user->assignRole('counsel');
                        $data->user_id = $user->id;
                        $data->save();
                    }

                }

            }
        }


    }
}
