<?php
namespace App\Actions;

use App\Models\People;

class PeopleFilterAction{


  public function filterPeople(){
    
    return People::when(request('term') && request('active_status') === 'Active', 
    function ($query){
        $query->where('active','1')
        ->where(function ($query) {
            $query->where('full_name','LIKE','%'.request('term').'%')
            ->orWhere('email_address_1','LIKE','%'.request('term').'%')
            ->orWhere('email_address_2','LIKE','%'.request('term').'%');
        });
    
    })

    ->when(request('term') && request('active_status') === 'Inactive', 
        function ($query){
            
            $query->where('active','0')
                  ->where(function ($query) {
                    $query->where('full_name','LIKE','%'.request('term').'%')
                    ->orWhere('email_address_1','LIKE','%'.request('term').'%')
                    ->orWhere('email_address_2','LIKE','%'.request('term').'%');
                });
        
        })

        ->when(request('term') && !request('active_status'), 
            function ($query){ 
                    $query->where('full_name','LIKE','%'.request('term').'%')
                          ->orWhere('email_address_1','LIKE','%'.request('term').'%')
                          ->orWhere('email_address_2','LIKE','%'.request('term').'%');
                
            
            })
        

    ->when(!request('term') && request('active_status') ==='Inactive', 
        function ($query){
            return $query->where('active','0');                
        })

        ->when(!request('term') && request('active_status') ==='Active', 
            function ($query){
                return $query->where('active','1');                
            })
    ->latest()->paginate(20)->withQueryString();
  }
}