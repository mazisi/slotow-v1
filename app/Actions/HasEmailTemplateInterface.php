<?php

namespace App\Actions;

use Illuminate\Database\Eloquent\Model;

interface HasEmailTemplateInterface{

    public function getMailTemplate(Model $model);
    
}