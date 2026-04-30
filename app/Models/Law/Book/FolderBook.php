<?php

namespace App\Models\Law\Book;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FolderBook extends Model
{
    use SoftDeletes;
    protected $guarded = ['id'] ;
    protected $fillable = ['folder_id','book_id'];

    public function folder(){
        return $this->belongsTo(\App\Models\Law\Book\Folder::class,'bid','id');
    }
    public function book(){
        return $this->belongsTo(\App\Models\Law\Book\BOok::class,'bid','id');
    }
}
