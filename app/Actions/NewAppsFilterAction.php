<?php

namespace App\Actions;

use App\Models\Licence;

class NewAppsFilterAction {
    
  public function filterLicence(){
    return Licence::with(["company","people","licence_type"])
//Search Only
            
        ->when(request('term') 
            && !request('licence_date') 
            && !request('licence_type') 
            && !request('status') 
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
            && !request('status') 
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
                    && !request('status')
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
                && !request('status') 
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

// search and licence date and licence_type and activeStatus
            ->when(request('term') 
                && request('licence_date') 
                && request('licence_type') 
                && request('status')
                && !request('province'),
                function ($query){
                    $query->where('status',request('status'))
                          ->where('licence_type_id',request('licence_type'))
                          ->whereMonth('licence_date',request('licence_date'))
                          ->where(function ($query) {
                            $query->where('trading_name','LIKE','%'.request('term').'%')
                            ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                            ->orWhere('licence_number','LIKE','%'.request('term').'%');
                        });
                })


// search and province and licence date and licence_type and activeStatus
                ->when(request('term') 
                    && request('licence_date') 
                    && request('licence_type') 
                    && request('status') 
                    && request('province'),
                    function ($query){ 
                        $query->where('province',request('province'))
                              ->where('status',request('status'))
                              ->where('licence_type_id',request('licence_type'))
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
                    && request('status')
                    && request('province'),
                    function ($query){ 
                        $query->where('province',request('province'))
                              ->where('status',request('status'))
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
                         && !request('status')
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

// search and licence date and Status == Inactive plus province
                     ->when(request('term') 
                     && request('licence_date') 
                     && !request('licence_type') 
                     && request('status')
                     && !request('province'),
                 function ($query){
                     $query->where('status',request('status'))
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
                                && request('status')
                                && !request('province'),
                     function ($query){
                         $query->where('licence_type_id',request('licence_type'))
                             ->where('status',request('status'))
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
                    && !request('status')
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
                    && !request('status')
                    && !request('licence_date'),
                    function ($query){ 
                        $query->where('licence_type_id',request('licence_type'))
                                ->where(function ($query) {
                                    $query->where('trading_name','LIKE','%'.request('term').'%')
                                    ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                                    ->orWhere('licence_number','LIKE','%'.request('term').'%');
                                });            
                })
// search and activeStatus 
                 ->when(request('term') 
                    && !request('licence_date') 
                    && !request('licence_type') 
                    && request('status')
                    && !request('province'),
                    function ($query){
                        $query->where('status',request('status'))
                                ->where(function ($query) {
                                    $query->where('trading_name','LIKE','%'.request('term').'%')
                                    ->orWhere('old_licence_number','LIKE','%'.request('term').'%')
                                    ->orWhere('licence_number','LIKE','%'.request('term').'%');
                                });       
                })


          
          
//Search and province and status
                ->when(request('term') 
                    && request('province') 
                    && !request('licence_type') 
                    && request('status')
                    && !request('licence_date'),
                    function ($query){
                        $query->where('province', request('province'))
                              ->where('status', request('status'))
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
                        && request('status')
                        && !request('province'),
                    function ($query){
                        $query->where('status',request('status'))
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
                    && request('status'),
                    function ($query){
                        $query->where('status',request('status'))
                              ->where('licence_type_id',request('licence_type'));         
                })


// licence date and licence_type and activeStatus == Active
            ->when(!request('term') 
            && request('licence_date') 
            && request('licence_type') 
            && request('status')
            && !request('province'),
            function ($query){
                $query->where('status',request('status'))
                      ->where('licence_type_id',request('licence_type'))
                      ->whereMonth('licence_date',request('licence_date'));
            })


//Inactive and licence_date
                ->when(!request('term') && !request('licence_type') && !request('province')
                        && request('licence_date') 
                        && request('status'),
                    function ($query){
                        $query->whereMonth('licence_date',request('licence_date'))
                              ->where('status',request('status'));
                              
                })


//Status and province
                ->when(!request('term') && !request('licence_date') && !request('licence_type') && request('status')
                       && request('province'),
                    function ($query){
                        $query->where('province', 'LIKE','%'.request('province').'%')
                              ->where('status',request('status'));                              
                })

                

//Licence date and licence_type
                ->when(!request('term') && !request('province') && !request('status') && request('licence_date') 
                    && request('licence_type') ,
                    function ($query){
                        $query->where('licence_type_id',request('licence_type'))
                              ->whereMonth('licence_date',request('licence_date'));         
                })

//Licence date and province
            ->when(!request('term') && request('province') && !request('status') && request('licence_date') 
            && !request('licence_type') ,
            function ($query){
                $query->where('province',request('province'))
                    ->whereMonth('licence_date',request('licence_date'));         
            })

//Licence type and province
            ->when(!request('term') && request('province') && !request('status') && !request('licence_date') 
            && request('licence_type') ,
            function ($query){
                $query->where('province',request('province'))
                    ->where('licence_type_id',request('licence_type'));         
            })

//Licence date and licence_type and inactive and province
                ->when(!request('term') 
                      && request('licence_date') 
                      && request('status')
                      && request('licence_type') 
                      && request('province'),
                    function ($query){
                        $query->whereMonth('licence_date',request('licence_date'))
                              ->where('licence_type_id',request('licence_type'))
                              ->where('province',request('province'))
                              ->where('status',request('status'));         
                })

//Licence date and licence_type and province
                ->when(!request('term') 
                      && request('licence_date') 
                      && !request('status')
                      && request('licence_type') 
                      && request('province'),
                    function ($query){
                        $query->whereMonth('licence_date',request('licence_date'))
                              ->where('licence_type_id',request('licence_type'))
                              ->where('province',request('province'));         
                })


 //Licence date and Active and province
        ->when(!request('term') 
        && request('licence_date') 
        && request('status')
        && !request('licence_type') 
        && request('province'),
        function ($query){
        $query->whereMonth('licence_date',request('licence_date'))
                ->where('province',request('province'))
                ->where('status',request('status'));         
        })

                ->when(
                        !request('term') 
                        && !request('licence_date')
                        && !request('province')
                        && request('status') 
                        && request('licence_type'), 
                        function ($query){ 
                            return $query->where('status',request('status'))
                            ->where('licence_type_id',request('licence_type'));               
                })

                ->when(!request('term') 
                        && !request('licence_date')
                        && !request('province')
                        && !request('status')
                        && request('licence_type'), 
                    function ($query){ 
                        return $query->where('licence_type_id',request('licence_type'));                
                    })
                    
                    ->when(!request('term') 
                          && !request('licence_date')
                          && !request('province')
                          && request('status') 
                          && request('licence_type'), 
                    function ($query){
                        return $query->where('status',request('status'))
                        ->where('licence_type_id',request('licence_type'));               
                })


            ->when(!request('term') && request('licence_date') 
            && request('status') && request('licence_type'), 
                function ($query){ 
                    return $query->whereMonth('licence_date',request('licence_date'))
                    ->where('licence_type_id',request('licence_type'))
                    ->where('status',request('status'));                
                })

            ->when(request('status')
                        && !request('licence_date')
                        && !request('licence_type')
                        && !request('province')
                        && !request('term'), 
                function ($query){
                    $query->where('status',request('status'));                
                })
            
            ->when(request('province')
                        && !request('term')
                        && !request('status')
                        && !request('licence_date')
                        && !request('licence_type'), 
                function ($query){ 
                    return $query->where('province', 'LIKE','%'.request('province').'%');                
                })
            
            

            ->when(request('licence_date')
                        && !request('term')
                        && !request('status')
                        && !request('province')
                        && !request('licence_type'), 
                function ($query){
                    return $query->whereMonth('licence_date',request('licence_date'));                
                })

               
                    
                    ->where(function ($query){ 
                        $query->where('status','1')
                            ->orWhere('status','2')
                            ->orWhere('status','4')
                            ->orWhere('status','7')
                            ->orWhere('status','8')
                            ->orWhere('status','10')
                            ->orWhere('status','12')
                            ->orWhere('status','14')
                            ->orWhere('status','15');               
                })
                ->whereNull('deleted_at')
                ->where('is_new_app',1)
                ->orderBy('trading_name')->paginate(20)->withQueryString();
  }
}