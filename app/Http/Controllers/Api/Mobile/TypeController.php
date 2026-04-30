<?php

namespace App\Http\Controllers\Api\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\CrudController;
use App\Models\Type as RecordModel;


class TypeController extends Controller
{
    /**
     * Listing function
     */
    public function index(Request $request){
        // $perpage = 
        return response([
            'records' => \App\Models\Type::where('id','<>',4)->orderby('document_index','asc')->get(),
            'message' => 'អានព័ត៌មាននៃគណនីបានរួចរាល់ !' 
        ],200 );
    }
    /**
     * Listing function
     */
    public function byMinistry(Request $request){
        // $perpage = 
        return response([
            'records' => \App\Models\Type::where('id','<>',4)->orderby('document_index','asc')->get(),
            'message' => 'អានព័ត៌មាននៃគណនីបានរួចរាល់ !' 
        ],200 );
    }
    /** Mini display */
    public function compactList(Request $request)
    {
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 50 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;
        $queryString = [
            "where" => [
                // 'default' => [
                //     [
                //         'field' => 'regulator_id' ,
                //         'value' => $regulator_id === false ? "" : $regulator_id
                //     ],
                // ],
                // 'in' => [] ,
                'not' => [
                    [
                        'field' => 'id' ,
                        'value' => 4
                    ]
                ] ,
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
                    'name'
                ]
            ],
            "order" => [
                'field' => 'document_index' ,
                'by' => 'asc'
            ],
        ];
        $request->merge( $queryString );
        $crud = new CrudController(new RecordModel(), $request, ['id', 'name', 'document_index', 'format' ]);
        $builder = $crud->getListBuilder();
        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData);
    }

}
