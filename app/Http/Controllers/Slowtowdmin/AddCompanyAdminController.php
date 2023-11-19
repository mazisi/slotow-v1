<?php

namespace App\Http\Controllers\Slowtowdmin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\SendMailCredentials;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\People;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AddCompanyAdminController extends Controller
{
    public function store(Request $request){
       $request->validate([
           'company_id'=> 'required|exists:companies,id',
           'person_id'=> 'required|exists:people,id'
       ]);
       
$exist = DB::table('company_user')->where('company_id',$request->company_id)->where('user_id',$request->person_id)->first();

       if(is_null($exist)){
            $get_person_data = People::find($request->person_id);
            $password = Str::random(6);
          
            if(!is_null( $get_person_data->email_address_1)){
              $email = $get_person_data->email_address_1;
            }elseif (!is_null( $get_person_data->email_address_2) && is_null($get_person_data->email_address_1)) {
              $email = $get_person_data->email_address_2;
            }elseif (!is_null( $get_person_data->email_address_3) && is_null($get_person_data->email_address_2)) {
              $email = $get_person_data->email_address_3;
            }else{
              return back()->with('error','This selected person does not have email address.');
            }
            
          $user = User::create([
            'name' => $get_person_data->full_name,
            'email' => $email,
            'people_id' => $get_person_data->id,
            'password' => Hash::make($password)
          ]);

          if($user){            
            $user->assignRole('company-admin');        
            $user->company()->attach($request->company_id);      
            
          
          $mailables = [
              'full_name' => $user->name,
              'email' => $user->email,
              'password' => $password
          ];
          Mail::to($email)->send(new SendMailCredentials($mailables));
            return back()->with('success','Company admin added successfully.');
          }
            return back()->with('error','Ooops!!! An error occured while creating company admin.');
          

       }
    return back()->with('error','Ooops!!! This person is already selected as company admin.');
}
}
