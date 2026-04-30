<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Briefing extends Model
{
    use SoftDeletes ;
    protected $guarded = ['id'] ;
    protected $table = "document_briefings" ;
    /**
     * ១. ក្នុងការផ្ដល់យោបល់(កំណត់បង្ហាញ)នីមួយត្រូវមានការទទួលខុសត្រូវ(ចុះហត្ថលេខាសង្ខេប)
     */

    public function document(){
        return $this->belongsTo( \App\Models\Document\Briefing::class , 'document_id' , 'id' );
    }
    public function briefer(){
        return $this->belongsTo( \App\Models\User::class , 'user_id' , 'id' );
    }
    public function author(){
        return $this->belongsTo( \App\Models\User::class , 'created_by' , 'id' );
    }
    public function editor(){
        return $this->belongsTo( \App\Models\User::class , 'updated_by' , 'id' );
    }
    public function destroyer(){
        return $this->belongsTo( \App\Models\User::class , 'deleted_by' , 'id' );
    }
}
