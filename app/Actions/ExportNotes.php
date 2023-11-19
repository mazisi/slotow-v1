<?php

namespace App\Actions;

use App\Models\Task;

class ExportNotes{


  static function getNoteExports($model_id, $model)
  {
    $notes = Task::where('model_id',$model_id)->where('model_type',$model)->get(['body','created_at']);
    $notesCollection = '';
    if($notes){
        foreach ($notes as $note) {
        $notesCollection .=  $note->created_at.' '.$note->body. ' ';
        }
    }
    return $notesCollection;
  }

}