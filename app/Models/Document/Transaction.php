<?php

namespace App\Models\Document;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use SoftDeletes; 
    protected $guarded = ['id'] ;
    const STATUS_DRAFT = 'draft' ;
    const STATUS_SENT = 'sent' ;
    const STATUS_PENDING = 'pending' ;
    const STATUS_PROGRESS = 'progress' ;
    const STATUS_FINISHED = 'finished' ;
    const STATUS_CANCELLED = 'cancelled' ;
    const STAUTS_TRASHED = 'deleted' ;
    const STATUSES = [
        self::STATUS_DRAFT ,
        self::STATUS_PENDING ,
        self::STATUS_SENT ,
        self::STATUS_PROGRESS ,
        self::STATUS_FINISHED ,
        self::STATUS_CANCELLED ,
        self::STAUTS_TRASHED
    ] ;

    /**
     * ការបញ្ជូនឯកសារត្រូវមានដូចជា៖ 
     * ១. ព័ត៌មានគោល៖ អ្នកបញ្ជូន(មានកន្លែងបញ្ជូន និងតួនាទីភ្ជាប់ជាមួយ) អ្នកទទួល(មានកន្លែងទទួល និងតួនាទីភ្ជាប់ជាមួយ) ឯកសារ(អាចជា Word និង PDF ឬមានតែWord)
     * ២. កត់ត្រា ម៉ោងផ្ញើរ ម៉ោងទទួល 
     * ៣​. កត់ត្រា ការបើកឯកសារ (បានមើល ឬទាញយក)
     * ៤. ភ្ជាប់ការដឹកជញ្ជូន (ភ្ជាប់ការដឹកជញ្ជូនដែល មានពីមុន និងបន្ទាប់ ជាមួយខ្លួនឯង)
     */
    protected $table="document_transactions" ;
    /**
     * Relationships
     */

    public function document(){
        return $this->belongsTo( \App\Models\Document\Document::class , 'document_id' , 'id' );
    }
    public function sender(){
        return $this->belongsTo( \App\Models\User::class , 'sender_id' , 'id' );
    }
    public function receivers(){
        return $this->belongsToMany( \App\Models\Officer\Officer::class , \App\Models\Document\Receiver::class , 'document_transaction_id' , 'receiver_id' );
    }
    public function receiversPivot(){
        return $this->hasMany( \App\Models\Document\Receiver::class , 'document_transaction_id' , 'id' );
    }
    public function previous(){
        return $this->hasOne( \App\Models\Document\Transaction::class , 'id' , 'previous_transaction_id' );
    }
    public function next(){
        return $this->hasOne( \App\Models\Document\Transaction::class , 'id' , 'next_transaction_id' );
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
    public function send(){
        $this->document->shortSignatures()->create([
            'document_id' => $this->document->id ,
            'user_id' => $this->sender->id ,
            'status' => 'sent'
        ]);
        $this->sent_at = \Carbon\Carbon::now()->format('Y-m-d H:i:s');
        // $this->status = self::STATUS_PROGRESS ;
        $this->status = self::STATUS_PENDING ;
        $this->save();
    }
    public function getTimeline(){
        $ids = $this->where('tpid','like', $this->id . '%' )->pluck('id')->toArray();
        return $this->whereIn('id', $ids )->get();
    }
        
}
