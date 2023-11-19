<?php

namespace App\Actions;

interface ReportShouldHaveStatusInterface{

  function getStatus($status) : string;

}