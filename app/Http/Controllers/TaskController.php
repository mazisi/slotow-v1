<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Events\LogUserActivity;

class TaskController extends Controller
{
    public function index()
    {
        return Inertia::render('Tasks/Task');
    }
    public function store(Request $request)
    {
    $request->validate([
        'body'=> 'required',
        'model_id' => 'required',
         'model_type' => 'required',
         ]);
        $add_task = Task::create([
            'user_id' => auth()->id(),
            'model_type'=> $request->model_type,
            'model_id' => $request->model_id,
            'body' => $request->body
        ]);
        if($add_task){
            return back()->with('success','Note added successfully');
        }
        return back()->with('error','Error adding note!!!');
    }

    public function destroy($id)
    {
       $task = Task::find($id);
       $activity = 'Deleted Task: ' . $task->body;
       event(new LogUserActivity(auth()->user(), $activity));
       if($task->delete()){
        return back()->with('success','Note deleted successfully.');
        }
        return back()->with('error','Error deleting note!!!');
    }
}
