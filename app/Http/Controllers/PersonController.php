<?php

namespace App\Http\Controllers;

use App\Actions\PeopleFilterAction;
use App\Models\Task;
use Inertia\Inertia;
use App\Models\People;
use Illuminate\Http\Request;
use App\Http\Requests\ValidatePeople;
use App\Models\Company;
use App\Models\Licence;
use App\Models\LicenceTransfer;
use App\Models\PeopleDocument;

class PersonController extends Controller
{
    public function index(){
        $people = (new PeopleFilterAction)->filterPeople();

        return Inertia::render('People/Person',['people' => $people]);
    }

    public function create(){
        return Inertia::render('People/CreatePerson');
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:200',
            'id_or_passport' => 'required|unique:people,id_or_passport'
        ]);
       try {        
        $person = People::create([
            'full_name'=> $request->name.' '.$request->surname,
            'date_of_birth' => $request->date_of_birth,
            'id_or_passport' => $request->id_or_passport,
            'email_address_1' => $request->email_address_1,
            'email_address_2' => $request->email_address_2,
            'cell_number' => $request->cell_number,
            'telephone' => $request->telephone,
            'passport_valid_until' => $request->passport_valid_until,
             'slug' => sha1(time()),
            ]);
            
            if($person){
               return to_route('view_person',['slug' => $person->slug])->with('success','Person created succesfully.');
            }
            return back()->with('error','Error creating person.');
       } catch (\Throwable $th) {
         return back()->with('error','Error creating person.');
       }
    }

    public function show($slug){
        $person = People::with('nominations','people_documents','company')->whereSlug($slug)->first();
        $id_document = PeopleDocument::where('people_id',$person->id)->where('doc_type','ID Document')->first();
        $police_clearance = PeopleDocument::where('people_id',$person->id)->where('doc_type','Police Clearance')->first();
        $passport_doc = PeopleDocument::where('people_id',$person->id)->where('doc_type','Passport')->first();
        $work_permit_doc = PeopleDocument::where('people_id',$person->id)->where('doc_type','Work Permit')->first();
        $fingerprints = PeopleDocument::where('people_id',$person->id)->where('doc_type','Fingerprints')->first();
        $tasks = Task::where('model_type','Person')->where('model_id',$person->id)->latest()->paginate(4)->withQueryString();
        $linked_licences = Licence::with('company')->where('people_id',$person->id)->paginate(10);

        return Inertia::render('People/ViewPerson',[
            'person' => $person,
            'tasks' => $tasks,
            'id_document' => $id_document,
            'linked_licences' => $linked_licences,
            'police_clearance' => $police_clearance,
            'passport_doc' => $passport_doc,
            'work_permit_doc' => $work_permit_doc,
            'fingerprints' => $fingerprints
           ]);
     }

    public function update(ValidatePeople $request){
        
       try {
           $person = People::whereSlug($request->slug)->update([
            'full_name'=> $request->name,
            'date_of_birth' => $request->date_of_birth,
            'id_or_passport' => $request->id_or_passport,
            'email_address_1' => $request->email_address_1,
            'email_address_2' => $request->email_address_2,
            'cell_number' => $request->cell_number,
            'telephone' => $request->telephone,
            'passport_valid_until' => $request->passport_valid_until,
            ]);
            if($person){
               return back()->with('success','Person updated succesfully.');
            }
            return back()->with('error','Error updating person.');
       } catch (\Throwable $th) {
         return back()->with('error','Error updating person.');
       }
    }

    public function destroy($slug){
        $delete_person = People::whereSlug($slug)->firstOrFail();
        if($delete_person->delete()){
            $del_licences = Licence::where('people_id',$delete_person->id)->get(['id']);
            foreach ($del_licences as $delete_lic) {
                $delete_lic->delete();
            }

            //delete transfers belonging to person
            $deleteTransfers = LicenceTransfer::where(function ($query) use($delete_person){ 
                $query->where('people_id',$delete_person->id)
                      ->orWhere('old_people_id',$delete_person->id);         
             })->get(['id']);
           
            foreach ($deleteTransfers as $delete_transfer) {
                $delete_transfer->delete();
            }
            return to_route('people')->with('success','Person deleted succesfully.');
        }
        return to_route('people')->with('error','Error updated person.');
    }


  


    public function updateActiveStatus(Request $request,$slug){
        try {
            $person =People::whereSlug($slug)->first();
        if($request->unChecked){
          $person->update(['active' => 0]);
        }else{
            $person->update(['active' => $request->status]);
        }
        return back()->with('success','Saved.');
        } catch (\Throwable $th) {
            return back()->with('error','An error occurred.');
        }
    }
    
}
