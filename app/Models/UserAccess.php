<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAccess extends SpatieRole
{
  use SoftDeletes;
  private $fields = [];

  private function createAccessFile(){
    // Check whether the folder does exist
    if( file_exists( storage_path() . 'logs' ) ){
      
    }
    // Create logs folder

    // Create file log for today
  }

}