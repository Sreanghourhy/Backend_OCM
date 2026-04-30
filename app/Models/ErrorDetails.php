<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ErrorDetails extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function user(){
        return $this->belongsTo(\App\Models\User::class,'user_id','id');
    }
}
