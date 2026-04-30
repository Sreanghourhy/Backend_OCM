<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Passport extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];
    public function people(){
        return $this->belongsTo( \App\Models\People\People::class , 'passport_id' , 'id' );
    }
}
