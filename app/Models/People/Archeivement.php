<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Archeivement extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    
    public function officer(){
        return $this->belongsTo( \App\Models\Officer\Officer::class , 'officer_id' , 'id' );
    }
}
