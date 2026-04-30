<?php

namespace App\Models\Regulator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * This class is use to identify the ministry of the regulator
 */
class Signature extends Model
{
    use SoftDeletes;
    /**
     * Abstract methods
     */
    protected static function getModel(){
        return self::class;
    }
    protected static function getRoot(){
        return self::where('model',self::class)->first();
    }
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
    public function regulators(){
        return $this->belongsToMany('\App\Models\Regulator\Regulator','regulator_signatures','signature_id','regulator_id');
    }
    public function user(){
        return $this->belongsToMany('\App\Models\Regulator\Tag\Signature','user_signatures','signature_id','user_id');
    }
}
