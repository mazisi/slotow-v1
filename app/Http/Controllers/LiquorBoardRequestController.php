<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LiquorBoardRequest;

class LiquorBoardRequestController extends Controller
{
    public function store(Request $request){
        try {
            $request->validate([
                'body' => 'required|string'
            ]);
            LiquorBoardRequest::create([
                'model_type'=> $request->model_type,
                'model_id' => $request->model_id,
                'body' => $request->body
            ]);
            return back()->with('success','Board request created successfully.');
        } catch (\Throwable $th) {
            return back()->with('error','Unable to create board request.');
        }

    }

    public function update(Request $request){
        try {
            $request->validate(['body' => 'required|string']);
            LiquorBoardRequest::whereId($request->id)->update([
                'body' => $request->body
            ]);
            return back()->with('success','Board request updated successfully.');
        } catch (\Throwable $th) {
            return back()->with('error','Unable to update board request.');
        }
    }
}
