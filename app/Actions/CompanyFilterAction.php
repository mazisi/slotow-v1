<?php
namespace App\Actions;

use App\Models\Company;

class CompanyFilterAction{

  public function filterCompanies(){
    
  return Company::
    when(request('term') 
      && !request('active_status') 
      && !request('company_type'), 
      function ($query) {
          $query->where('name','LIKE','%'.request('term').'%')
          ->orWhere('reg_number','LIKE','%'.request('term').'%');      
      
  })

  //Search and company type
  ->when(request('term') 
      && !request('active_status') 
      && request('company_type'), 
      function ($query) {
      return $query
      ->where(function($query){
          $query->where('name','LIKE','%'.request('term').'%');                   
        })
        ->where('company_type','LIKE','%'.request('company_type').'%');
      
  })
//Search and company type and Active
  ->when(request('term') 
      && request('active_status') == 'Active'
      && request('company_type'), 
      function ($query) {
          $query->where('name','LIKE','%'.request('term').'%')
                ->where('company_type', 'LIKE','%'.request('company_type').'%')
                ->where('active','1');   
                   
  })

//Search and company type and All
  ->when(request('term') 
      && request('active_status') == 'All'
      && request('company_type'), 
      function ($query) {
          $query->where('name','LIKE','%'.request('term').'%')
                ->where('company_type','LIKE','%'.request('company_type').'%');   
       
      
  })

//Search and Inactive
  ->when(request('term') 
      && request('active_status') == 'Inactive'
      && !request('company_type'), 
      function ($query) {
          $query->where('name','LIKE','%'.request('term').'%')
                ->where('active','0');   
       
      
  })

//Search and company type and Active
  ->when(request('term') 
      && request('active_status') == 'Active'
      && !request('company_type'), 
      function ($query) {
          $query->where('name','LIKE','%'.request('term').'%')
                ->where('active','1');   
        
      
  })




  ->when(!request('term')  && !request('company_type') && request('active_status') == 'Inactive', 
      function ($query){
          return $query->where('active','0');            
      })

      ->when(!request('term')  && !request('company_type') && request('active_status') == 'Active', 
      function ($query){
          return $query->where('active','1');            
      })
          
          ->when(!request('term') && request('company_type') && request('active_status') == 'Inactive', 
          function ($query){
              $query->where('active',true)
                    ->where('company_type','LIKE','%'.request('company_type').'%');            
          })

          ->when(!request('term') && request('company_type') && request('active_status') == 'Active', 
          function ($query){
              $query->where('active',true)
                    ->where('company_type','LIKE','%'.request('company_type').'%');            
          })

      ->when(!request('term')  && !request('active_status') && request('company_type'), 
          function ($query){
              $query->where('company_type','LIKE','%'.request('company_type').'%');                
          })
          ->latest()->paginate(20)->withQueryString();

  }
}