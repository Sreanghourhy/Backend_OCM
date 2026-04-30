<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use \App\Models\ErrorDetails as RecordModel ;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ErrorDetailsController extends Controller
{
    private $selectFields = [
        'id',
        'desp' ,
        'user_id' ,
        'module_name' ,
        'app_name' ,
        'page_name' ,
        'user_agent'
    ];
    /**
     * Listing function
     */
    public function index(Request $request){
        $user = \Auth::user();
        if( $user == null ){
            return response()->json([
                'message' => "សូមចូលប្រព័ន្ធជាមុនសិន។" ,
                'ok' =>false
            ],403);
        }

        // If the authenticated user is the "1 => super administrator" or "2 => Administrator" then don't filter the regulator base on the authenticated user
        $user = count( array_filter( $user->roles->toArray() , function( $role ){ return $role['id'] == 1 || $role['id'] == 2 ; } ) ) ? false : $user ;

        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        $queryString = [
            "where" => [
                'default' => [
                    $user ? [
                        'field' => 'user_id' ,
                        'value' => $user->id
                    ] : []
                ],
                // 'in' => [] ,
                // 'not' => [] ,
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
                    'module_name' ,
                    'app_name' ,
                    'page_name'
                ]
            ],
            "order" => [
                'field' => 'app_name' ,
                'by' => 'asc'
            ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields);
        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            "user" => ['id','firstname' , 'lastname' ]
        ]);

        $builder = $crud->getListBuilder()->whereNull('deleted_at');

        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    /**
     * Create an account
     */
    public function store(Request $request){
        // អ្នកប្រើប្រាស់ មិនទាន់មាននៅឡើយទេ
        $record = new RecordModel([
            'app_name' => $request->app_name??"",
            'module_name' => $request->module_name??"",
            'page_name' => $request->page_name??"",
            'user_id' => \Auth::user()->id ,
            'user_agent' => $request->user_agent??null // should get it from the request to server
        ]);
        $record->save();

        if( $record ){
            return response()->json([
                'record' => $record ,
                'message' => 'បង្កើតបានរួចរាល់'
            ], 200);

        }else {
            return response()->json([
                'user' => null ,
                'message' => 'មានបញ្ហា។'
            ], 201);
        }
    }
    /**
     * Create an account
     */
    public function update(Request $request){
        $record = isset( $request->id ) && $request->id > 0 ? RecordModel::find($request->id) : false ;
        if( $record ) {
            $record->update([
                'app_name' => $request->app_name??"",
                'module_name' => $request->module_name??"",
                'page_name' => $request->page_name??"",
                'user_id' => \Auth::user()->id ,
                'user_agent' => $request->user_agent??null // should get it from the request to server
            ]);
            return response()->json([
                'record' => $record ,
                'message' => 'កែប្រែព័ត៌មានរួចរាល់ !' ,
                'ok' => true
            ], 200);
        }else{
            // អ្នកប្រើប្រាស់មិនមាន
            return response([
                'record' => null ,
                'message' => 'គណនីដែលអ្នកចង់កែប្រែព័ត៌មាន មិនមានឡើយ។' ,
                'ok' => false
            ], 403);
        }
    }
}
