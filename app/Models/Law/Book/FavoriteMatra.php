<?php

namespace App\Models\Law\Book;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FavoriteMatra extends Model
{
    use HasFactory, UseSoftDeletes ;

    /**
     * Relationship
     */
    public function matra(){
        return $this->belongsTo( \App\Models\Law\Book\Matra::class , 'id' , 'matra_id' );
    }
    public function user(){
        return $this->belongsTo( \App\Models\User::class , 'id' , 'user_id' );
    }
}
