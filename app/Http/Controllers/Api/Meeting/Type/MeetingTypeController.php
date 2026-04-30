<?php

namespace App\Http\Controllers\Api\Meeting\Type;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\CrudController;
use App\Models\Meeting\MeetingType as RecordModel;


class MeetingTypeController extends Controller
{
    private $model = null ;
    private $fields = [ 'id','name','desp' , 'pid' , 'tpid' , 'record_index'  ] ;
    private $renameFields = [
        'pid' => 'parentId'
    ];
    /**
     * Listing function
     */
    public function index(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 50 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;
        
        $id = isset( $request->id ) && intval( $request->id ) > 0? intval( $request->id ) : false ;
        $root = $id > 0 
            ? RecordModel::where('id',$id)->first()
            : null ;
        if( $root != null ){
            $root->parentNode;
            $root->totalChilds = $root->totalChildNodesOfAllLevels();   
        }

        $queryString = [
            "where" => [
                // 'default' => [
                //     $pid > 0 ? [
                //         'field' => 'pid' ,
                //         'value' => $pid
                //     ] : [] ,
                // ],
                // 'in' => [] ,
                // 'not' => [
                //     [
                //         'field' => 'id' ,
                //         'value' => 4
                //     ]
                // ] ,
                // 'like' => [
                //     [
                //         'field' => 'number' ,
                //         'value' => $number === false ? "" : $number
                //     ],
                //     [
                //         'field' => 'year' ,
                //         'value' => $date === false ? "" : $date
                //     ]
                // ] ,
            ] ,
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'name' , 'desp'
                ]
            ],
            "order" => [
                'field' => 'record_index' ,
                'by' => 'asc'
            ],
        ];
        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->fields , false , $this->renameFields );
        $builder = $crud->getListBuilder();
        if( $root != null && $root->id > 0 ){
            $builder = $builder->where('tpid', "LIKE" , ( intval( $root->pid ) > 0 ? $root->pid.":" : '' ) . $root->id . "%");
            $root->parentId = null ;
        }

        $responseData = $crud->pagination(true , $builder );
        // $responseData['records'] = $responseData['records']->prepend( $root );
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData);
    }
    /** Mini display */
    public function compact(Request $request)
    {
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 1000 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;
        $queryString = [
            "where" => [
                // 'default' => [
                //     [
                //         'field' => 'model' ,
                //         'value' => ''
                //     ],
                // ],
                // 'in' => [] ,
                // 'not' => [
                //     [
                //         'field' => 'id' ,
                //         'value' => 4
                //     ]
                // ] ,
                // 'like' => [
                //     [
                //         'field' => 'number' ,
                //         'value' => $number === false ? "" : $number
                //     ],
                //     [
                //         'field' => 'year' ,
                //         'value' => $date === false ? "" : $date
                //     ]
                // ] ,
            ] ,
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'name' , 'desp'
                ]
            ],
            "order" => [
                'field' => 'record_index' ,
                'by' => 'asc'
            ],
        ];
        $request->merge( $queryString );
        $crud = new CrudController(new RecordModel(), $request, $this->fields );
        $responseData = $crud->pagination(true, $this->model->children()->orderby('record_index','asc') );
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData);
    }
}
