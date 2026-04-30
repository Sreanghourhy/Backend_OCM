<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShortSignature extends Model
{
    use SoftDeletes; 
    protected $guarded = ['id'] ;
    /**
     * ការចុះហត្ថលេខាសង្ខេបលើឯកសារ
     */
    protected $table = 'document_short_signatures';
    public function document(){
        return $this->belongsTo( \App\Models\Document\Document::class , 'document_id' , 'id' );
    }
    public function signer(){
        return $this->belongsTo( \App\Models\User::class , 'user_id' , 'id' );
    }
}
