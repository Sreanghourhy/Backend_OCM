<?php

namespace App\Http\Controllers\Api\Meeting\Room;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meeting\MeetingRoom as RecordModel;
use App\Http\Controllers\CrudController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class MeetingRoomController extends Controller
{
    private $selectFields = [
        'id',
        'organization_id' ,
        'meeting_id' ,
        'room_id',
        'remark' ,
        'date' ,
        'start' ,
        'end'
    ];
        
    /**
     * Listing function
     */
    public function index(Request $request){
        $user = \Auth::user() != null ? \Auth::user() : false ;

        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        $queryString = [
            // "where" => [
            //     // 'default' => [
            //     //     [
            //     //         'field' => 'date' ,
            //     //         'value' => $user->id
            //     //     ]
            //     // ],
            //     // 'in' => [
            //     //     [
            //     //         'field' => 'type' ,
            //     //         'value' => isset( $request->type ) && $request->type !== null ? [$request->type] : false
            //     //     ]
            //     // ] ,
            //     // 'not' => [
            //     //     [
            //     //         'field' => 'type' ,
            //     //         'value' => [4]
            //     //     ]
            //     // ] ,
            //     // 'like' => [
            //     //     [
            //     //         'field' => 'number' ,
            //     //         'value' => $number === false ? "" : $number
            //     //     ],
            //     //     [
            //     //         'field' => 'year' ,
            //     //         'value' => $date === false ? "" : $date
            //     //     ]
            //     // ] ,
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
                    'remark', 'date' , 'start' , 'end'
                ]
            ],
            "order" => [
                'field' => 'id' ,
                'by' => 'desc'
            ],
        ];

        if( $search != false ) {
            $queryString['where']['default'] = [
                'in' => [
                    [
                        'field' => 'type' ,
                        'value' =>  $request->type
                    ]
                ]
            ];
        }

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields);

        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'meeting' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'rooms' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'organization' => [ 'id' , 'name' , 'desp' , 'pid' ]
        ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    /**
     * View the pdf file
     */
    public function pdf(Request $request)
    {
        $regulator = RecordModel::findOrFail($request->id);
        if($regulator) {
            $path = storage_path('data') . '/regulators/' . $regulator->pdf;
            $ext = pathinfo($path);
            $filename = str_replace('/' , '-', $regulator->fid) . "." . $ext['extension'];

            if(is_file($path)) {
                $pdfBase64 = base64_encode( file_get_contents($path) );
                return response([
                    'serial' => str_replace(['regulators','documents','/','.pdf'],'',$regulator->pdf ) ,
                    "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
                    "filename" => $filename,
                    "ok" => true 
                ],200);
            }else
            {
                return response([
                    'regulator' => $regulator ,
                    'message' => 'មានបញ្ហាក្នុងការអានឯកសារយោង !' ,
                    'path' => $path
                ],404 );
            }
        }
    }
}
