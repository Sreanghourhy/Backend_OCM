<?php

namespace App\Http\Controllers\Api\Law\Book;

use App\Http\Controllers\Controller;
use App\Http\Controllers\CrudController;
use App\Models\Law\Book\Matra;
use App\Models\Law\Book\FavoriteMatra as RecordModel;
use Illuminate\Http\Request;

class FavoriteMatraController extends Controller
{
    private $selectedFields ;
    public function __construct(){
        $this->selectedFields = ['id', 'user_id', 'matra_id'] ;
    }
    public function index(){
        $user = \Auth::user();
        if( $user == null ) return response()->json( [
            'ok' => false ,
            'message' => "សូមចូលប្រើប្រព័ន្ធជាមុនសិន"
        ] , 403);
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 50 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        $queryString = [
            "where" => [
                'default' => [
                    [
                        'field' => 'user_id' ,
                        'value' => $user->id
                    ]
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
            "pivots" => [
                $unit ?
                [
                    "relationship" => 'matra',
                    "where" => [
                        // "in" => [
                        //     "field" => "id",
                        //     "value" => [$request->unit]
                        // ],
                        // "not"=> [
                        //     [
                        //         "field" => 'fieldName' ,
                        //         "value"=> 'value'
                        //     ]
                        // ],
                        "like"=>  [
                            $search !== false 
                                ? (
                                    array_map( function($term){
                                        return [
                                            "field"=> 'title' ,
                                            "value"=> $term
                                        ];
                                    }, explode( " " , trim($search) ) )
                                )
                                : []
                        ]
                    ]
                ]
                : []
            ],
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            // "search" => $search === false ? [] : [
            //     'value' => $search ,
            //     'fields' => [
            //         'title', 'number'
            //     ]
            // ],
            // "order" => [
            //     'field' => 'number' ,
            //     'by' => 'desc'
            // ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectedFields);
        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            "matra" => ['id','number','title','objective' , 
                [ 
                    "book" => ['id','title','description'] ,
                    "kunty" => ['id', 'number', 'title'],
                    "matika" => ['id', 'number', 'title'],
                    "chapter" => ['id', 'number', 'title'],
                    "part" => ['id', 'number', 'title'],
                    "section" => ['id', 'number', 'title']
                ]
            ]
        ]);
        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData);
    }
    public function getFavoriteIds(){
        $user = \Auth::user();
        if( $user == null ) return response()->json( [
            'ok' => false ,
            'message' => "សូមចូលប្រើប្រព័ន្ធជាមុនសិន"
        ] , 403);
        return response()->json([
            'ok' => true ,
            'records' => $user->favoriteMatras()->pluck('matra_id')->toArray() ,
            'message' => "រួសរាល់"
        ],200);
    }
}
