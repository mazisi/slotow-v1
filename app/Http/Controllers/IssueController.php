<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\Request;
use Inertia\Inertia;

class IssueController extends Controller
{
    public function index() {   
         if(auth()->user()->hasRole('company-admin')){
            $issues = Issue::with('user.company')
            ->when(request('status'), 
                function ($query){ 
                    return $query->where('status',request('status'));
                
                })->when(request('priority'), 
                    function ($query){ 
                        return $query->where('severity',request('priority'));                 
                    })
    
                    ->when(request('priority') && request('status'), 
                    function ($query){ 
                        return $query->where('severity',request('priority'))
                        ->where('status',request('status'));                
                    })
                    ->whereUserId(auth()->id())
                   ->latest()->paginate(50);
                   return Inertia::render('CompanyAdminDash/Issues/Issues',['issues' => $issues]);
         } else {
        $issues = Issue::with('user.company')
        ->when(request('status'), 
            function ($query){ 
                return $query->where('status',request('status'));
            
            })->when(request('priority'), 
                function ($query){ 
                    return $query->where('severity',request('priority'));                 
                })

                ->when(request('priority') && request('status'), 
                function ($query){ 
                    return $query->where('severity',request('priority'))
                    ->where('status',request('status'));                
                })

               ->latest()->paginate(50);
    }   
       
        return Inertia::render('Issues/Issue',['issues' => $issues]);
    }

    public function create()
    {
        return Inertia::render('Issues/CreateIssue');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                "severity" => "required|in:High,Moderate,Low",
                'body' => "required"
            ]);
    
            
            Issue::create([
                "user_id" => auth()->id(),
                "body"    => $request->body,
                "severity" => $request->severity,
                "url" => $request->url,
                "slug" => sha1(time())
            ]);
            return to_route('issues')->with('success','Issue submitted successfully.');
        } catch (\Throwable $th) {
            return back()->with('error','Error creating issue.');
        }
    }

    public function show($slug){
        $issue = Issue::with('user.company')->whereSlug($slug)->first();
        return Inertia::render('Issues/ViewIssue',['issue' => $issue]);
    }

    public function changeStatus($slug,$status)
    {
        if($status === 'Deleted'){
            Issue::whereSlug($slug)->delete();
          return to_route('issues')->with('success','Status updated successfully.');;
        }

        Issue::whereSlug($slug)->update([
            'status' => $status
        ]);
        
        return back()->with('success','Status updated successfully.');
    }

    public function update(Request $request, $slug){

      try {
        $request->validate([
            "severity" => "required|in:High,Moderate,Low",
            'body' => "required"
        ]);

        Issue::whereSlug($slug)->update([
            "body"    => $request->body,
            "severity" => $request->severity,
            "url" => $request->url
        ]);
        
        return back()->with('success','Issue updated successfully.');
      } catch (\Throwable $th) {
        return back()->with('error','Error Updating Issue.');
      }
    }
}
