<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i=0; $i<20000; $i++) {
            Contact::create([
                'first_name'=> Str::random(10),
                'middle_name'=> Str::random(10),
                'last_name'=> Str::random(10),
                'email' => Str::random(10).'@gmail.com',
                'business_phone' => '0027'.rand(5000,10000000),
                'mobile_phone' => '0027'.rand(5000,10000000)
                ]);
        }
        
            
    }
}
