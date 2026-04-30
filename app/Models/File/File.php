<?php

namespace App\Models\File;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class File extends Model
{
    use HasFactory, SoftDeletes;

    public function folders(){
        return $this->belongsToMany(\App\Models\Folder\Folder::class,'file_folders','file_id','folder_id');
    }
}
