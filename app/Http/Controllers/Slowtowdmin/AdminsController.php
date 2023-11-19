<?php

namespace App\Http\Controllers\Slowtowdmin;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Mail\SendMailCredentials;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AdminsController extends Controller
{
    public function index(){
      $users = User::with('roles')->latest()->paginate(10,['id','name','email','created_at','picture','is_active','last_activity_at']);
      return Inertia::render('AccountAdmin/Admins',['users' => $users]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'full_name' => 'required',
                'password' => 'required',
                'role' => 'required'
            ]);
    
           $user  = User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
           ]);
    
           $mailables = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => $request->password
          ];
    
           if($user){
            $does_role_exist = Role::whereName($request->role)->first();
            if(is_null($does_role_exist)){
             Role::create(['name' => $request->role]);
            }
             $user->assignRole($request->role);
             Mail::to($request->email)->send(new SendMailCredentials($mailables));
             return back()->with('success','Mail sent successfully');
           }
        } catch (\Throwable $th) {
          //throw $th;
            return back()->with('error','ERROR');
        }
    }

    public function update(Request $request){
        try {
            $request->validate([
                'email' => 'required|email',
                'full_name' => 'required',
            ]);    
           User::find($request->id)->update([
            'name' => $request->full_name,
            'email' => $request->email,
           ]);
           if(request('password')){
            User::find($request->id)->update(['password' => Hash::make($request->password)]);
           }

           if($request->hasFile('profilePic')){
            $request->validate(['profilePic' => 'required|image|mimes:jpeg,png,jpg|max:2048']);

            $removeSpace = str_replace(' ', '_',$request->profilePic->getClientOriginalName());
            $fileName = str_replace('-', '_',$removeSpace);
            
             $request->file('profilePic')->storeAs('/', $fileName, env('FILESYSTEM_DISK'));
             User::find($request->id)->update(['picture' => $fileName]);
           }

           return back()->with('success','User updated successfully.');
        } catch (\Throwable $th) {
         // throw $th;
          return back()->with('error','ERROR UPDATING USER');
        }
    }

    public function deactivate($id, $status){
      try {
        if($status == 0){
          User::find($id)->update(['is_active' => true]);
        }else{
          User::find($id)->update(['is_active' => false]);
        }       
        return back()->with('success','Updated!!!');
      } catch (\Throwable $th) {
        // throw $th;
        return back()->with('error','Error activating/deactivating user.');
      }
    }

    public function destroy($id){
      try {
          User::find($id)->delete();            
        return back()->with('success','User deleted successfully.');;
      } catch (\Throwable $th) {
        //throw $th;
        return back()->with('error','Error deleting user.');
      }
    }
}
