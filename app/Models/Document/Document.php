<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;
    
    protected $guarded = ['id'] ;
    // protected $fillable = [];
    /**
     * ១. ឯកសារអាចត្រូវបានផ្ដល់កំណត់បង្ហាញដោយច្រើនអ្នក
     * ២. ឯកសារអាចត្រូវបានទទួលស្គាល់និងចុះហត្ថលេខាសង្ខេបច្រើនអ្នក
     * ៣. ឯកសារអាចត្រូវបានទទួលស្គាល់ចុះហត្ថលេខាដោយច្រើនអ្នក បញ្ចប់
     * ៤. ឯកសារត្រូវមានជា ២ ទម្រង់គឺ៖ Word និង PDF
     * ៤.១.‌ ​ឯកសារនេះនិងត្រូវបានកែសម្រួលជាច្រើនដំណាក់កាល
     * ៥. 
     **/

    /**
     * Relationships
     */
    public function transaction(){
        return $this->hasOne( \App\Models\Document\Transaction::class , 'document_id' , 'id' );
    }
    public function briefings(){
        return $this->hasMany( \App\Models\Document\Briefing::class , 'document_id' , 'id' );
    }
    public function shortSignatures(){
        return $this->hasMany( \App\Models\Document\ShortSignature::class , 'document_id' , 'id' );
    }
    public function signatures(){
        return $this->hasMany( \App\Models\Document\Signature::class , 'document_id' , 'id' );
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
    /**
     * Functions
     */
    public function getWordFile(){
        return $this->word_file ;
    }
    public function getPdfFile(){
        return $this->pdf_file ;
    }
    public function isSeen(){
        return $this->seen_at != null ? true : false ;
    }
    public function isSent(){
        return $this->sent_at != null ? true : false ;
    }
}
