<?php

namespace App\Http\Controllers\Api\Meeting\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\CrudController;
use App\Models\People\People as RecordModel;


class MemberController extends Controller
{
    private $model = null ;
    private $fields = [ 'id','firstname','lastname', 'mobile_phone' , 'email' ] ;
    private $renameFields = [
        // 'pid' => 'parentId'
    ];
    public function __construct(){
        $this->model = new RecordModel();
    }
    /**
     * Listing function
     */
    public function index(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && strlen( $request->search ) > 0 ? trim( $request->search ) : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 50 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;
        $queryString = [
            // "where" => [
            //     // 'default' => [
            //     //     $pid > 0 ? [
            //     //         'field' => 'pid' ,
            //     //         'value' => $pid
            //     //     ] : [] ,
            //     // ],
            //     // 'in' => [] ,
            //     // 'not' => [
            //     //     [
            //     //         'field' => 'id' ,
            //     //         'value' => 4
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
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'firstname' ,
                    'lastname' ,
                    'dob' ,
                    'mobile_phone' ,
                    'office_phone' ,
                    'email',
                    'nid'
                ]
            ],
            "order" => [
                'field' => 'lastname' ,
                'by' => 'asc'
            ],
        ];
        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->fields , false , $this->renameFields );
        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'countesies' => [ 'id' , 'name' ] ,
            'organizations' => [ 'id' , 'name' ] ,
            'positions' => [ 'id' , 'name' ] ,
            'user' => [ 'id' , 'firstname' , 'lastname' ]
        ]);

        $builder = $crud->getListBuilder();
        
        

        $responseData = $crud->pagination(true , $builder );
       
        $responseData['records'] = $responseData['records']->map(function($member){ 

            $member['user'] = isset( $member['user'] ) && intval( $member['user']['id'] ) > 0 ? \App\Models\User::find( $member['user']['id'] ) : null ;
            if( $member['user'] != null ){
                $member['user'] = [
                    'id' => $member['user']->id ,
                    'firstname' => $member['user']->firstname ,
                    'lastname' => $member['user']->lastname ,
                    'username' => $member['user']->username ,
                    'email' => $member['user']->email ,
                    'phone' => $member['user']->phone
                ];
            }
            return $member;
        });
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData);
    }
    public function save(Request $request){
        if( 
            strlen( $request->firstname ) > 0 
            && strlen( $request->lastname ) > 0 
            // && strlen( $request->phone ) > 0
        ){
            $personBuilder = \App\Models\People\People::where([
                'firstname' => $request->firstname , 
                'lastname' => $request->lastname 
                // , 'enfirstname' => $request->enfirstname
                // , 'enlastname' => $request->enlastname
                // , 'mobile_phone' => trim($request->phone)
            ]);
            // Check phone
            if( strlen( $request->phone ) > 0 ){
                $personBuilder->where('mobile_phone',$request->phone)
                ->orWhere('office_phone',$request->phone);
            }
            // Check email
            if( strlen( $request->email ) > 0 ){
                $personBuilder->where('email',$request->email);
            }
            // Check gender
            if( intval( $request->gender ) >= 0 ){
                $personBuilder->where('gender',$request->gender);
            }
            // Position
            if( intval( $request->position ) > 0 ){
                $personBuilder->whereHas('officers',function($builder) use( $request ){
                    $builder->where('position_id', $request->position );
                });
            }
            // Organization
            if( intval( $request->organization ) > 0 ){
                $personBuilder->whereHas('officers',function($builder) use( $request ){
                    $builder->where('organization_id', $request->organization );
                });
            }
            // Countesy
            if( intval( $request->organization ) > 0 ){
                $personBuilder->whereHas('officers',function($builder) use( $request ){
                    $builder->where('countesy_id', $request->countesy );
                });
            }
            $person = $personBuilder->first();
            if( $person == null ){
                $person = \App\Models\People\People::create([
                    'firstname' => $request->firstname , 
                    'lastname' => $request->lastname , 
                    'mobile_phone' => $request->phone??"" , 
                    'gender' => intval( $request->gender ) > 0 ? $request->gender : 0 , 
                    'email' => $request->email??""
                ]);
                // Countesy
                if( intval( $request->countesy ) ){
                    $person->countesies()->sync([ $request->countesy ]);
                }
                // Position
                if( intval( $request->position ) ){
                    $person->positions()->sync([ $request->position ]);
                }
                // Organization
                if( intval( $request->organization ) ){
                    $person->organizations()->sync([ $request->organization ]);
                }

                /**
                 * Create default account for this people
                 */
                \App\Models\User::create([
                    'people_id' => $person->id ,
                    'model' => get_class( $person ) ,
                    'firstname' => $person->firstname ,
                    'lastname' => $person->lastname ,
                    'phone' => $person->mobile_phone ,
                    'email' => $person->email ,
                    'username' => $person->email ,
                    'password' => bcrypt( 
                        $person->mobile_phone != null && strlen( $person->mobile_phone ) > 0 ? $person->mobile_phone : '123456'
                    ),
                    'active' => 0 // disabled this account for now
                ]);

                return response()->json([
                    'record' => $person ,
                    'ok' => true ,
                    'message' => 'រួចរាល់'
                ], 200 );
            }else{
                return response()->json([
                    'record' => $person ,
                    'ok' => false ,
                    'message' => 'មានរួចហើយ។'
                ], 200 );
            }
        }
        return response()->json([
            'ok' => false ,
            'record' => null ,
            'message' => 'ព័ត៌មានមិនគ្រប់គ្រាន់'
        ],422);
    }
}
