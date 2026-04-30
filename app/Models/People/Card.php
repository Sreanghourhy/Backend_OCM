<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory , SoftDeletes;
    protected $guarded = ['id'];
    public function people(){
        return $this->belongsTo( \App\Models\People\People::class , 'people_id' , 'id' );
    }
    public function officer(){
        return $this->belongsTo( \App\Models\Officer\Officer::class , 'officer_id' , 'id' );
    }
    public function author(){
        return $this->belongsTo( \App\Models\User::class , 'created_by' , 'id' );
    }
    public function editor(){
        return $this->belongsTo( \App\Models\User::class , 'updated_by' , 'id' );
    }
}
