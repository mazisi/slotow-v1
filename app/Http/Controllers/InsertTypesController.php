<?php

namespace App\Http\Controllers;

use App\Models\LicenceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InsertTypesController extends Controller
{
  public function insert(){
  $inserts = collect([ 
  ["type" => "Club liquor licence"],
  ["type" => "Dance Hall liquor licence"],
  ["type" => "Discretionary off-consumption liquor licence"],
  ["type" => "Discretionary on-consumption liquor licence"],
  ["type" => "Gaming premises liquor licence"],
  ["type" => "Grocer’s Wine liquor licence"],
  ["type" => "Hotel liquor licence"],
  ["type" => "Liquor Store liquor licence"],
  ["type" => "Micro-manufacturing liquor licence"],
  ["type" => "National Distribution liquor licence"],
  ["type" => "National Manufacturing and Distribution liquor licence"],
  ["type" => "National Manufacturing liquor licence"],
  ["type" => "Night Club liquor licence"],
  ["type" => "Occasional liquor licence"],
  ["type" => "Pool Club liquor licence"],
  ["type" => "Pub liquor licence"],
  ["type" => "Restaurant liquor licence"],
  ["type" => "Sorghum Beer off-consumption liquor licence"],
  ["type" => "Sorghum Beer on-consumption liquor licence"],
  ["type" => "Sports Ground liquor licence"],
  ["type" => "Tavern liquor licence"],
  ["type" => "Theatre liquor licence"]
 ]);
        foreach($inserts as $insert){
            LicenceType::insert([
                "licence_type" => $insert["type"]
            ]);
        }
        return "done!!!";
        
    }

      public function insert_years()
      {
        $years = collect([ 
            ["year" => 2023],
            ["year" => 2022],
            ["year" => 2021],
            ["year" => 2020],
            ["year" => 2019],
            ["year" => 2018],
            ["year" => 2017],
            ["year" => 2016],
            ["year" => 2015],
            ["year" => 2014],
            ["year" => 2013],
            ["year" => 2012],
            ["year" => 2011],
            ["year" => 2010],
            ["year" => 2009],
            ["year" => 2008],
            ["year" => 2007],
            ["year" => 2006],
            ["year" => 2005],
            ["year" => 2004],
            ["year" => 2003],
            ["year" => 2002],
            ["year" => 2001],
            ["year" => 2000]
           ]);
                  foreach($years as $insert){
                      DB::table('years')->insert([
                          "year" => $insert["year"]
                      ]);
                  }
                  return "done!!!";
      }
}
