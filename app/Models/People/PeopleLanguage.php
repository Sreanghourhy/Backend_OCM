<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeopleLanguage extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function people(){
        return $this->belongsTo( \App\Models\People\People::class , 'people_id' , 'id' );
    }
}
