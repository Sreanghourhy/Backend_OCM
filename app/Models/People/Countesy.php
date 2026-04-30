<?php

namespace App\Models\People;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Countesy extends Model
{
    use HasFactory , SoftDeletes;
    protected $guarded = ['id'];

    protected static function getTree($nodeId=false){
        $node = intval( $nodeId ) ? self::find( intval($nodeId) ) : [] ;
        if( $node != null && $node->childNodes != null && !$node->childNodes->isEmpty() ){
            $node->childNodes = $node->childNodes()->select('id','name','desp')->where('active',1)->orderby('record_index','asc')->get()->map(function($c){
                return self::getChilds( $c );
            }) ;
        }
        return $node ;
    }
    private static function getChilds($node){
        if( !$node->childNodes->isEmpty() ){
            return $node->childNodes()->select('id','name','desp')->where('active',1)->orderby('record_index','asc')->get()->map(function($c){ 
                return self::getChilds( $c );
            });
        }
        return $node ;
    }
    public function childNodes(){
        return $this->hasMany(self::class,'pid','id');
    }
    public function parentNode(){
        return $this->hasOne(self::class,'id','pid');
    }
    public function totalChildNodesOfAllLevels(){
        return self::where('tpid',"LIKE", $this->tpid . "%" )->count();
    }
    /**
     * Relationships
     */
    public function people(){
        return $this->hasMany('\App\Models\People\People','countesy_id','id');
    }
    public static function move(){
        foreach( \DB::table('tags')->where('pid',15)->get() AS $key => $countesy ){
            $node = Countesy::create([
                'name' => $countesy->name ,
                'desp' => $countesy->desp ,
                'tpid' => 0 ,
                'pid' => 0 ,
                'record_index' => $countesy->record_index ,
                'active' => $countesy->active ,
                'created_at' => $countesy->created_at ,
                'updated_at' => $countesy->updated_at ,
                'prefix' => $countesy->code
            ]);
            // Update the relationship
            \DB::table('people_countesies')->where('countesy_id',$countesy->id)->update([
                'countesy_id'=>$node->id ,
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }
    }
    public function officers(){
        return $this->hasMany( \App\Models\People\Coutesy::class , 'countesy_id' , 'id' );
    }
    public function officerRanks(){
        return $this->hasMany( \App\Models\People\Coutesy::class , 'countesy_id' , 'id' );
    }
    public function ranks(){
        return $this->hasManyThrough( \App\Models\People\Coutesy::class , \App\Models\Officer\OfficerRank::class , 'countesy_id' , 'rank_id' );
    }
}
