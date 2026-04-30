<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Signature extends Model
{
    use SoftDeletes; 
    protected $guarded = ['id'] ;
     /**
     * ការចុះហត្ថលេខាលើឯកសារជាផ្លូវការ។ ថ្នាក់ដឹកនាំជាអ្នកធ្វើ
     */
    protected $table = 'document_signatures' ;
    public function document(){
        return $this->belongsTo( \App\Models\Document\Document::class , 'document_id' , 'user_id' );
    }
    public function signer(){
        return $this->belongsTo( \App\Models\User::class , 'user_id' , 'id' );
    }
}
