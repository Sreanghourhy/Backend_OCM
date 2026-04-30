<?php

namespace App\Http\Controllers\Api\AuthenticationCenter\Location;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Regulator\Regulator;
use App\Models\Location\Province AS RecordModel;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CrudController;

class ProvinceController extends Controller
{
    private $selectFields = [
        'id',
        'name_kh' ,
        'name_en' ,
        'code'
    ];
    /**
     * Listing function
     */
    public function index(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 1000 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        $queryString = [
            // "where" => [
            //     'default' => [
            //         [
            //             'field' => 'type_id' ,
            //             'value' => $type === false ? "" : $type
            //         ]
            //     ],
            //     'in' => [] ,
            //     'not' => [] ,
            //     'like' => [
            //         [
            //             'field' => 'number' ,
            //             'value' => $number === false ? "" : $number
            //         ],
            //         [
            //             'field' => 'year' ,
            //             'value' => $date === false ? "" : $date
            //         ]
            //     ] ,
            // ] ,
            // "pivots" => [
            //     $unit ?
            //     [
            //         "relationship" => 'units',
            //         "where" => [
            //             "in" => [
            //                 "field" => "id",
            //                 "value" => [$request->unit]
            //             ],
            //         // "not"=> [
            //         //     [
            //         //         "field" => 'fieldName' ,
            //         //         "value"=> 'value'
            //         //     ]
            //         // ],
            //         // "like"=>  [
            //         //     [
            //         //        "field"=> 'fieldName' ,
            //         //        "value"=> 'value'
            //         //     ]
            //         // ]
            //         ]
            //     ]
            //     : []
            // ],
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'name_kh' , 'name_en' , 'code'
                ]
            ],
            "order" => [
                'field' => 'name_kh' ,
                'by' => 'asc'
            ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields);
        $crud->setRelationshipFunctions([
            'districts' => [
                'id' , 'name_kh' , 'name_en' , 'code' , 'province_id'
                , 'communes' => [
                    'id' , 'name_kh' , 'name_en' , 'code' , 'district_id' , 'province_id'
                    , 'villages' => [
                        'id' , 'name_kh' , 'name_en' , 'code' , 'commune_id' , 'district_id' , 'province_id'
                    ]
                ]
            ]
        ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    public function pdcv(Request $request){
        return response()->json([
            'provinces' => \App\Models\Location\Province::select(['id','name_kh','name_en','code'])->get() ,
            'districts' => \App\Models\Location\District::select(['id','name_kh','name_en','code','province_id'])->get(),
            'communes' => \App\Models\Location\Commune::select(['id','name_kh','name_en','code','province_id','district_id'])->get(),
            'villages' => \App\Models\Location\Village::select(['id','name_kh','name_en','code','province_id','district_id','commune_id'])->get()
        ],200);
    }
}
