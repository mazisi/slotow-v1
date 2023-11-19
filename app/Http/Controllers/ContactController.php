<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller{

    public function index(){
            $contacts = Contact::when(request('q'), function ($query) {
                return $query->where('first_name', 'LIKE', '%'.request('q').'%')
                             ->orWhere('last_name', 'LIKE', '%'.request('q').'%')
                             ->orWhere('email', 'LIKE', '%'.request('q').'%')
                             ->orWhere('business_phone', 'LIKE', '%'.request('q').'%')
                             ->orWhere('mobile_phone', 'LIKE', '%'.request('q').'%');
            })->latest()->paginate(20)->withQueryString();
        
        return Inertia::render('Contacts/Contact',['contacts' => $contacts]);
    }

    public function create(){
        return Inertia::render('Contacts/CreateContact');
    }

    public function createContact(){
        return Inertia::render('Contacts/CreateIndividualContact');
    }

    public function viewContact($id){
        $contact = Contact::whereId($id)->firstOrFail();
        return Inertia::render('Contacts/ViewContact',['contact' => $contact]);
    }

    public function updateIndividualContact(Request $request,$id) {
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);
        $model = Contact::whereId($id)->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'business_phone' => $request->business_number,
            'mobile_phone' => $request->mobile_number,
        ]);
        if($model){
            return back()->with('success','Contact updated succesfully.');
        }
        return back()->with('error','Error updating contact.');
    }

    public function storeIndividualContact(Request $request){
        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);

        $contact = Contact::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'business_phone' => $request->business_number,
            'mobile_phone' => $request->mobile_number,
        ]);
        if($contact){
            return to_route('view_contact',['id' => $contact->id])->with('success','Contact created succesfully.');
        }
        return back()->with('error','Error creating contact.');
    }

    public function store(Request $request){
        $request->validate([
         "csv_file" => "required|file"
        ]);
       $csv_file = $request->csv_file->store('contacts','public');
       $valid_extension = array("csv");
       $extension = $request->csv_file->getClientOriginalExtension();

       if(in_array(strtolower($extension),$valid_extension)){
            if (($handle = fopen ( storage_path () . '/app/public/'.$csv_file, 'r' )) !== FALSE) {
                $flag = true;
            
                while ( ($data = fgetcsv ( $handle, 1000, ',' )) !== FALSE ) {
                  if($request->header == '1'){
                    if($flag) { 
                        $flag = false; continue;
                     }
                    }
                    $csv_data = new Contact ();
                    $csv_data->first_name = $data [0];
                    $csv_data->middle_name = $data [1];
                    $csv_data->last_name = $data [2];
                    $csv_data->business_phone = $data [3];
                    $csv_data->mobile_phone = $data [4];
                    $csv_data->email = $data [5];
                    $csv_data->save ();
                }
            
                fclose ( $handle );
                return to_route('contacts')->with('success','Contacts uploaded successfully.');
            }else{
                return to_route('contacts')->with('error','Error uploading contacts.');
            }
        }else{
            return to_route('contacts')->with('error', 'Uploaded file is not a valid csv file.');
          }
    
    }

    public function destroy($id){
       $contact = Contact::find($id);
       if($contact->delete()){
        return redirect(route('contacts'))->with('success','Contact deleted successfully.');
       }
       return redirect(route('contacts'))->with('error','Error deleting contact.');
    }



}
