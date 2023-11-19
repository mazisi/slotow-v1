<?php

namespace App\Actions;

use App\Models\Licence;

class LicenceFilterAction{
    
  public function filterLicence(){
    return Licence::with(["company","people","licence_type"])
//Search Only
            
        ->when(request('term') 
            && !request('licence_date') 
            && !request('licence_type') 
            && !request('active_status') 
            && !request('province'), 
            function ($query) {
              $query->where(function($query){
                    $query->where('trading_name','LIKE','%'.request('term').'%')
                       ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                       ->orWhere('licence_number','LIKE','%'.request('term').'%');
                    });

                // $query->orWhereHas('company', function($query){
                //     $query->where('name', 'like', '%'.request('term').'%');                
                // });   
            })
          
// search plus licence date    
        ->when(request('term') 
            && request('licence_date') 
            && !request('licence_type') 
            && !request('active_status') 
            && !request('province'), 
            function ($query){
                $query->whereMonth('licence_date',request('licence_date'))
                ->where(function ($query) {
                    $query->where('trading_name','LIKE','%'.request('term').'%')
                    ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                    ->orWhere('licence_number','LIKE','%'.request('term').'%');
                });
            
            })
            
            //Search and province and licence date
                ->when(request('term') 
                    && request('province') 
                    && !request('active_status')
                    && !request('licence_type') 
                    && request('licence_date'),
                    function ($query){
                        $query->where('province',request('province'))
                        ->whereMonth('licence_date',request('licence_date'))
                        ->where(function ($query) {
                            $query->where('trading_name','LIKE','%'.request('term').'%')
                            ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                            ->orWhere('licence_number','LIKE','%'.request('term').'%');
                        });
                        
                })

       // search and licence date and licence_type
            ->when(request('term') 
                && request('licence_date') 
                && request('licence_type') 
                && !request('active_status') 
                && !request('province'),
                function ($query){
                    $query->where('licence_type_id',request('licence_type'))
                    ->whereMonth('licence_date',request('licence_date'))
                    ->where(function ($query) {
                        $query->where('trading_name','LIKE','%'.request('term').'%')
                        ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                        ->orWhere('licence_number','LIKE','%'.request('term').'%');
                    });
                    // ->orWhereHas('company', function($query){
                    //     $query->where('name', 'like', '%'.request('term').'%');                
                    //  });               
                })

// search and licence date and licence_type and activeStatus == Active
            ->when(request('term') 
                && request('licence_date') 
                && request('licence_type') 
                && request('active_status') == 'Active'
                && !request('province'),
                function ($query){
                    $query->where('is_licence_active',true)
                          ->where('licence_type_id',request('licence_type'))
                          ->whereMonth('licence_date',request('licence_date'))
                          ->where(function ($query) {
                            $query->where('trading_name','LIKE','%'.request('term').'%')
                            ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                            ->orWhere('licence_number','LIKE','%'.request('term').'%');
                        });
                })



//search and licence date and licence_type and activeStatus == Inactive
                ->when(request('term') 
                    && request('licence_date') 
                    && request('licence_type') 
                    && request('active_status') == 'Inactive'
                    && !request('province'),
                    function ($query){
                        $query->where('licence_type_id',request('licence_type'))
                        ->whereMonth('licence_date',request('licence_date'))
                        ->where('is_licence_active',0)
                        ->where(function ($query) {
                            $query->where('trading_name','LIKE','%'.request('term').'%')
                            ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                            ->orWhere('licence_number','LIKE','%'.request('term').'%');
                        });
                                              
                })

// search and province and licence date and licence_type and activeStatus == Active
                ->when(request('term') 
                    && request('licence_date') 
                    && request('licence_type') 
                    && request('active_status') == 'Active'
                    && request('province'),
                    function ($query){ 
                        $query->where('province',request('province'))
                              ->where('is_licence_active',1)
                              ->where('licence_type_id',request('licence_type'))
                              ->whereMonth('licence_date',request('licence_date'))
                              ->where(function ($query) {
                                $query->where('trading_name','LIKE','%'.request('term').'%')
                                ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                                ->orWhere('licence_number','LIKE','%'.request('term').'%');
                            });
                })

// search and licence date and licence_type and activeStatus == Inactive plus province
                ->when(request('term') 
                    && request('licence_date') 
                    && request('licence_type') 
                    && request('active_status') == 'Inactive'
                    && request('province'),
                    function ($query){ 
                        $query->where('province', 'LIKE','%'.request('province').'%')
                              ->where('is_licence_active',0)
                              ->where('licence_type_id',request('licence_type'))
                              ->whereMonth('licence_date',request('licence_date'))
                              ->where(function ($query) {
                                $query->where('trading_name','LIKE','%'.request('term').'%')
                                ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                                ->orWhere('licence_number','LIKE','%'.request('term').'%');
                            });
                })

 // search and licence date and activeStatus == Inactive plus province-----------------------------------------------------------------
                        ->when(request('term') 
                            && request('licence_date') 
                            && !request('licence_type') 
                            && request('active_status') == 'Inactive'
                            && request('province'),
                        function ($query){
                            $query->where('province',request('province'))
                                ->where('is_licence_active',0)
                                ->whereMonth('licence_date',request('licence_date'))
                                ->where(function ($query) {
                                    $query->where('trading_name','LIKE','%'.request('term').'%')
                                    ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                                    ->orWhere('licence_number','LIKE','%'.request('term').'%');
                                });
                        })
// search and province and licence_type and activeStatus == Active
                ->when(request('term') 
                    && !request('licence_date') 
                    && request('licence_type') 
                    && request('active_status') == 'Active'
                    && request('province'),
                    function ($query){ 
                        $query->where('province',request('province'))
                              ->where('is_licence_active',1)
                              ->where('licence_type_id',request('licence_type'))
                              ->where(function ($query) {
                                $query->where('trading_name','LIKE','%'.request('term').'%')
                                ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                                ->orWhere('licence_number','LIKE','%'.request('term').'%');
                            });
                })


// search and licence date, licence type and province
                     ->when(request('term') 
                         && request('licence_date') 
                         && request('licence_type') 
                         && !request('active_status')
                         && request('province'),
                     function ($query){
                         $query->whereMonth('licence_date',request('licence_date'))
                              ->where('licence_type_id',request('licence_type'))
                              ->where('province',request('province'))
                              ->where(function ($query) {
                                $query->where('trading_name','LIKE','%'.request('term').'%')
                                ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                                ->orWhere('licence_number','LIKE','%'.request('term').'%');
                            });
                     })

// search and licence date and activeStatus == Inactive plus province
                     ->when(request('term') 
                     && request('licence_date') 
                     && !request('licence_type') 
                     && request('active_status') == 'Inactive'
                     && !request('province'),
                 function ($query){
                     $query->where('is_licence_active',0)
                         ->whereMonth('licence_date',request('licence_date'))
                         ->where(function ($query) {
                            $query->where('trading_name','LIKE','%'.request('term').'%')
                            ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                            ->orWhere('licence_number','LIKE','%'.request('term').'%');
                        });
                 })


// search and licencetype and activeStatus == Inactive 
                         ->when(request('term') 
                                && !request('licence_date') 
                                && request('licence_type') 
                                && request('active_status') == 'Inactive'
                                && !request('province'),
                     function ($query){
                         $query->where('licence_type_id',request('licence_type'))
                             ->where('is_licence_active',0)
                             ->where(function ($query) {
                                $query->where('trading_name','LIKE','%'.request('term').'%')
                                ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                                ->orWhere('licence_number','LIKE','%'.request('term').'%');
                            });
                     })
//Search and province only
                ->when(request('term') 
                    && request('province') 
                    && !request('licence_type') 
                    && !request('active_status')
                    && !request('licence_date'),
                    function ($query){
                        $query->where('province', 'LIKE','%'.request('province').'%')
                                ->where(function ($query) {
                                    $query->where('trading_name','LIKE','%'.request('term').'%')
                                    ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                                    ->orWhere('licence_number','LIKE','%'.request('term').'%');
                                });
                              
                })

//Search and licence_type only
                ->when(request('term') 
                    && request('licence_type') 
                    && !request('province') 
                    && !request('active_status')
                    && !request('licence_date'),
                    function ($query){ 
                        $query->where('licence_type_id',request('licence_type'))
                                ->where(function ($query) {
                                    $query->where('trading_name','LIKE','%'.request('term').'%')
                                    ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                                    ->orWhere('licence_number','LIKE','%'.request('term').'%');
                                });            
                })
// search and activeStatus == Active 
                 ->when(request('term') 
                    && !request('licence_date') 
                    && !request('licence_type') 
                    && request('active_status') == 'Active'
                    && !request('province'),
                    function ($query){
                        $query->where('is_licence_active',true)
                                ->where(function ($query) {
                                    $query->where('trading_name','LIKE','%'.request('term').'%')
                                    ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                                    ->orWhere('licence_number','LIKE','%'.request('term').'%');
                                });       
                })

 // search and activeStatus == Inactive 
                ->when(request('term') 
                    && !request('licence_date') 
                    && !request('licence_type') 
                    && request('active_status') == 'Inactive'
                    && !request('province'),
                    function ($query){ 
                        $query->where('is_licence_active',0)
                        ->where(function ($query) {
                            $query->where('trading_name','LIKE','%'.request('term').'%')
                            ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                            ->orWhere('licence_number','LIKE','%'.request('term').'%');
                        });         
                })


          
          
//Search and province and Active
                ->when(request('term') 
                    && request('province') 
                    && !request('licence_type') 
                    && request('active_status') == 'Active'
                    && !request('licence_date'),
                    function ($query){
                        $query->where('province', request('province'))
                              ->where('is_licence_active', true)
                              ->where(function ($query) {
                                $query->where('trading_name','LIKE','%'.request('term').'%')
                                ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                                ->orWhere('licence_number','LIKE','%'.request('term').'%');
                            });         
                })

//Search and province and Inactive
                ->when(request('term') 
                    && request('province') 
                    && request('active_status') == 'Inactive'
                    && !request('licence_type') 
                    && !request('licence_date'),
                    function ($query){
                        $query->where('province',request('province'))
                               ->where('is_licence_active', 0)
                               ->where(function ($query) {
                                $query->where('trading_name','LIKE','%'.request('term').'%')
                                ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                                ->orWhere('licence_number','LIKE','%'.request('term').'%');
                            });
                })

  // search and licencetype and activeStatus == Active 
                ->when(request('term') 
                        && !request('licence_date') 
                        && request('licence_type') 
                        && request('active_status') === 'Active'
                        && !request('province'),
                    function ($query){
                        $query->where('is_licence_active',1)
                              ->where('licence_type_id',request('licence_type'))
                              ->where(function ($query) {
                                    $query->where('trading_name','LIKE','%'.request('term').'%')
                                    ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                                    ->orWhere('licence_number','LIKE','%'.request('term').'%');
                            });
                     })

  
//Active and licence_type
                ->when(!request('term') 
                    && !request('province')
                    && !request('licence_date') 
                    && request('licence_type') 
                    && request('active_status') == 'Active',
                    function ($query){ 
                        $query->where('is_licence_active',true)
                              ->where('licence_type_id',request('licence_type'));         
                })
//Inactive and licence_type
                ->when(!request('term') 
                    && !request('province')
                    && !request('licence_date') 
                    && request('licence_type') 
                    && request('active_status') == 'Inactive',
                    function ($query){ 
                        $query->where('is_licence_active',0)
                              ->where('licence_type_id',request('licence_type'));         
                })


// licence date and licence_type and activeStatus == Active
            ->when(!request('term') 
            && request('licence_date') 
            && request('licence_type') 
            && request('active_status') == 'Active'
            && !request('province'),
            function ($query){
                $query->where('is_licence_active',1)
                      ->where('licence_type_id',request('licence_type'))
                      ->whereMonth('licence_date',request('licence_date'));
            })

// licence date and licence_type and activeStatus == Inactive
        ->when(!request('term') 
        && request('licence_date') 
        && request('licence_type') 
        && request('active_status') == 'Active'
        && !request('province'),
        function ($query){
            $query->where('is_licence_active',0)
                ->where('licence_type_id',request('licence_type'))
                ->whereMonth('licence_date',request('licence_date'));
        })
//Inactive and licence_date
                ->when(!request('term') && !request('licence_type') && !request('province')
                        && request('licence_date') 
                        && request('active_status') == 'Inactive',
                    function ($query){ 
                        $query->whereMonth('licence_date',request('licence_date'))
                              ->where('is_licence_active',0);
                              
                })
//Active and licence_date
                ->when(!request('term') && !request('licence_type') && !request('province')
                    && request('licence_date') 
                    && request('active_status') == 'Active',
                    function ($query){ 
                        $query->whereMonth('licence_date',request('licence_date'))
                              ->where('is_licence_active',true);                              
                })

//Active and province
                ->when(!request('term') && !request('licence_date') && !request('licence_type') && request('active_status') == 'Active'
                       && request('province'),
                    function ($query){ 
                        $query->where('province', 'LIKE','%'.request('province').'%')
                              ->where('is_licence_active',true);                              
                })

//Inactive and province
                ->when(!request('term') && !request('licence_date') && !request('licence_type') 
                       && request('active_status') == 'Inactive'
                       && request('province'),
                    function ($query){
                        $query->where('province', 'LIKE','%'.request('province').'%')
                              ->where('is_licence_active',0);
                              
                })
                

//Licence date and licence_type
                ->when(!request('term') && !request('province') && !request('active_status') && request('licence_date') 
                    && request('licence_type') ,
                    function ($query){
                        $query->where('licence_type_id',request('licence_type'))
                              ->whereMonth('licence_date',request('licence_date'));         
                })

//Licence date and province
            ->when(!request('term') && request('province') && !request('active_status') && request('licence_date') 
            && !request('licence_type') ,
            function ($query){
                $query->where('province',request('province'))
                    ->whereMonth('licence_date',request('licence_date'));         
            })

//Licence type and province
            ->when(!request('term') && request('province') && !request('active_status') && !request('licence_date') 
            && request('licence_type') ,
            function ($query){
                $query->where('province',request('province'))
                    ->where('licence_type_id',request('licence_type'));         
            })

//Licence date and licence_type and inactive and province
                ->when(!request('term') 
                      && request('licence_date') 
                      && request('active_status') == 'Inactive'
                      && request('licence_type') 
                      && request('province'),
                    function ($query){
                        $query->whereMonth('licence_date',request('licence_date'))
                              ->where('licence_type_id',request('licence_type'))
                              ->where('province',request('province'))
                              ->where('is_licence_active',0);         
                })

//Licence date and licence_type and province
                ->when(!request('term') 
                      && request('licence_date') 
                      && !request('active_status')
                      && request('licence_type') 
                      && request('province'),
                    function ($query){
                        $query->whereMonth('licence_date',request('licence_date'))
                              ->where('licence_type_id',request('licence_type'))
                              ->where('province',request('province'));         
                })
//Licence date and licence_type and Active and province
                ->when(!request('term') 
                && request('licence_date') 
                && request('active_status') == 'Active'
                && request('licence_type') 
                && request('province'),
                function ($query){
                $query->whereMonth('licence_date',request('licence_date'))
                        ->where('licence_type_id',request('licence_type'))
                        ->where('province',request('province'))
                        ->where('is_licence_active',1);         
                })



 //Licence date and Active and province
        ->when(!request('term') 
        && request('licence_date') 
        && request('active_status') == 'Active'
        && !request('licence_type') 
        && request('province'),
        function ($query){
        $query->whereMonth('licence_date',request('licence_date'))
                ->where('province',request('province'))
                ->where('is_licence_active',1);         
        })

                ->when(
                        !request('term') 
                        && !request('licence_date')
                        && !request('province')
                        && request('active_status') == 'Active' 
                        && request('licence_type'), 
                        function ($query){ 
                            return $query->where('is_licence_active',true)
                            ->where('licence_type_id',request('licence_type'));               
                })

                ->when(!request('term') 
                        && !request('licence_date')
                        && !request('province')
                        && !request('active_status')
                        && request('licence_type'), 
                    function ($query){ 
                        return $query->where('licence_type_id',request('licence_type'));                
                    })
                    
                    ->when(!request('term') 
                          && !request('licence_date')
                          && !request('province')
                          && request('active_status') == 'Active' 
                          && request('licence_type'), 
                    function ($query){ 
                        return $query->where('is_licence_active',true)
                        ->where('licence_type_id',request('licence_type'));               
                })


            ->when(!request('term') && request('licence_date') 
            && request('active_status') =='Inactive' && request('licence_type'), 
                function ($query){ 
                    return $query->whereMonth('licence_date',request('licence_date'))
                    ->where('licence_type_id',request('licence_type'))
                    ->where('is_licence_active',0);                
                })

            ->when(request('active_status') =='Active'
                        && !request('licence_date')
                        && !request('licence_type')
                        && !request('province')
                        && !request('term'), 
                function ($query){ 
                    return $query->where('is_licence_active',true);                
                })
            
            ->when(request('province')
                        && !request('term')
                        && !request('active_status')
                        && !request('licence_date')
                        && !request('licence_type'), 
                function ($query){ 
                    return $query->where('province', 'LIKE','%'.request('province').'%');                
                })
            
            

            ->when(request('licence_date')
                        && !request('term')
                        && !request('active_status')
                        && !request('province')
                        && !request('licence_type'), 
                function ($query){
                    return $query->whereMonth('licence_date',request('licence_date'));                
                })

                ->when(!request('term') 
                      && !request('licence_date')
                      && !request('province')
                      && !request('licence_type')
                      && request('active_status') =='Inactive', 
                function ($query){
                    return $query->where('is_licence_active',0);                
                })
            ->latest()->paginate(20)->withQueryString();
  }
}