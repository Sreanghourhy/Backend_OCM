<?php

namespace App\Http\Controllers\Api\AuthenticationCenter\Attendant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendant\Attendant as RecordModel;
use App\Http\Controllers\CrudController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class AttendantController extends Controller
{
    private $selectFields = [
        'id',
        'user_id' ,
        'date' ,
        'worked_time' ,
        'duration' ,
        'late_or_early' ,
        'created_at' , 
        'updated_at'
    ];
        
    /**
     * Listing function
     */
    public function index(Request $request){
        $user = \Auth::user() != null ? \Auth::user() : false ;

        /** Format from query string */
        $search = isset( $request->search ) && $request->search !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        /**
         * Filter conditions
         */
        $userId = isset( $request->userId ) && intval( $request->userId ) > 0 ? $request->userId : false ;
        $date = isset( $request->date ) & strlen( $request->date ) >=10 ? \Carbon\Carbon::parse( $request->date ) : false ;
        $attendantType = isset( $request->attendantType ) && $request->attendantType != "" && in_array( $request->attendantType , Attendant::ATTENDANT_TYPES ) ? $request->attendantType : false ;

        $queryString = [
            "where" => [
                // 'default' => [
                //     $date != false
                //         ? [
                //             'field' => 'date' ,
                //             'value' => $date->format('Y-m-d')
                //         ] : [] 
                // ],
                // 'in' => [
                //     [
                //         'field' => 'type' ,
                //         'value' => isset( $request->type ) && $request->type !== null ? [$request->type] : false
                //     ]
                // ] ,
                // 'not' => [
                //     [
                //         'field' => 'type' ,
                //         'value' => [4]
                //     ]
                // ] ,
                'like' => [
                    $date != false
                        ? [
                            'field' => 'date' ,
                            'value' => $date->format('Y-m-d')
                        ] : [] 
                ]
            ] ,
            "pivots" => [
                $search != false ?
                [
                    "relationship" => 'user',
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
                            [
                                "field"=> 'firstname' ,
                                "value"=> $search
                            ],
                            [
                                "field"=> 'lastname' ,
                                "value"=> $search
                            ]
                        ]
                    ]
                ]
                : []
            ],
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" =>
                $search === false ? [] : [
                    'value' => $search ,
                    'fields' => [
                        'date'
                    ] 
                ]
            ,
            "order" => [
                'field' => 'date' ,
                'by' => 'desc'
            ],
        ];
        // if( isset( $request->type ) ) {
        //     $queryString['where']['default'] = [
        //         'in' => [
        //             [
        //                 'field' => 'type' ,
        //                 'value' =>  $request->type
        //             ]
        //         ]
        //     ];
        // }

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields);

        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'user' => [ 'id' , 'firstname' , 'lastname' ]
        ]);

        $builder = $crud->getListBuilder();

        // if( $search != false ){
        //     // return response()->json( $search ,200);
        //     $builder->whereHas('user',function($query) use($search){
        //         $query->where('firstname','like','%'.$search.'%')
        //         ->orWhere('lastname','like','%'.$search.'%')
        //         ;
        //     });
        // }
        // dd( $builder->toSql() );

        $responseData = $crud->pagination(true, $builder);
        $responseData['records'] = $responseData['records']->map(function($att){
            $tempAtt = RecordModel::find($att['id']);
            return [
                'id' => $tempAtt->id ,
                'date' => $tempAtt->date ,
                'calculateTime' => $tempAtt->calculateWorkingTime() ,
                'day_of_week' => \Carbon\Carbon::parse( $tempAtt->date )->dayOfWeek ,
                'user' => [
                    'id' => $tempAtt->user->id ,
                    'firstname' => $tempAtt->user->firstname ,
                    'lastname' => $tempAtt->user->lastname ,
                    'avatar_url' => $tempAtt->user->avatar_url != null && strlen( $tempAtt->user->avatar_url ) > 0 && \Storage::disk('public')->exists( $tempAtt->user->avatar_url ) ? \Storage::disk('public')->url( $tempAtt->user->avatar_url ) : null
                ]
            ];
        });
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    /**
     * Listing function
     */
    public function child(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
        $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 10 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        /**
         * Get the id of the regulator and its parents to exclude them from searching
         */
        $regulator = isset( $request->parent_id ) && $request->parent_id > 0 ? \App\Models\Regulator\Regulator::find( $request->parent_id ) : false ;

        $queryString = [
            "where" => [
                // 'default' => [
                //     [
                //         'field' => 'publish' ,
                //         'value' => 0
                //     ]
                // ],
                // 'in' => [
                //     [
                //         'field' => 'type' ,
                //         'value' => isset( $request->type ) && $request->type !== null ? [$request->type] : false
                //     ]
                // ] ,
                'not' => [
                    [
                        'field' => 'id' ,
                        'value' => [ $request->parent_id ]
                    ]
                ] // ,
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
                    'objective', 'fid', 'year'
                ]
            ],
            "order" => [
                'field' => 'id' ,
                'by' => 'desc'
            ],
        ];
        // if( isset( $request->type ) ) {
        //     $queryString['where']['default'] = [
        //         'in' => [
        //             [
        //                 'field' => 'type' ,
        //                 'value' =>  $request->type
        //             ]
        //         ]
        //     ];
        // }

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields);
        
        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'organizations' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'ownOrganizations' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'relatedOrganizations' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'signatures' => [ 'id' , 'name' , 'desp' , 'pid' ] ,
            'types' => [ 'id' , 'name' , 'desp' , 'pid' ]
        ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder,[
                'pdf' => function($record){
                    $record->pdf = ( $record->pdf !== "" && \Storage::disk('regulator')->exists( $record->pdf ) )
                    ? true
                    // \Storage::disk('regulator')->url( $pdf ) 
                    : false ;
                    return $record->pdf ;
                },
                'objective' => function($record){
                    return html_entity_decode( strip_tags( $record->objective ) );
                }
            ]
        );
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    /**
     * Listing document by its type within a specific ministry
     */
    public function byTypeWithinOrganization($id){

        // Create Query Builder 
        $documentIds = \App\Models\Regulator\RegulatorOrganization::where('ministry_id',$id)->first()->getRegulators();
        $queryBuilder = new Regulator();

        // Get search string
        if( $request->search != "" ){
            $searchTerms = explode(' ' , $request->search) ;
            if( is_array( $searchTerms ) && !empty( $searchTerms ) ){
                $queryBuilder = $queryBuilder -> where( function ($query ) use ( $searchTerms ) {
                    foreach( $searchTerms as $term ) {
                        $query = $query -> orwhere ( 'objective', 'LIKE' , "%".$term."%") ;
                    }
                } );
            }
        }
        // Get document type
        // if( $request->type != "" ){
        //     $documentTypes = explode(',', $request->type );
        //     if( is_array( $documentTypes ) && !empty( $documentTypes ) ){
        //         $queryBuilder = $queryBuilder -> where( function ($query ) use ( $documentTypes ) {
        //             foreach( $documentTypes as $type ) {
        //                 $query = $query -> orwhere ( 'type', $type ) ;
        //             }
        //         } );
        //     }
        // }
        // Get document year
        if( $request->year != "" ){
            $queryBuilder = $queryBuilder -> where('year','LIKE','%'.$request->year.'%');
        }
        // Get document registration id
        if( $request -> fid != "" ){
            $queryBuilder = $queryBuilder -> where('fid','LIKE','%'.$request -> fid);
        }

        $queryBuilder = $queryBuilder -> whereIn('id',$documentIds);
        // return $queryBuilder -> toSql();

        // $perpage = 
        return response([
            'records' => $queryBuilder->orderby('id','desc')->get()
                ->map( function ($record, $index) {
                    $record->objective = strip_tags( $record->objective ) ;
                    return $record ;
                })
            ,
            'message' => 'អានព័ត៌មាននៃគណនីបានរួចរាល់ !' 
        ],200 );
    }
    /**
     * View the pdf file
     */
    public function pdf(Request $request)
    {
        $document = RecordModel::findOrFail($request->id);
        if($document) {
            $path = storage_path('data') . '/' . $document->pdf;
            $ext = pathinfo($path);
            $filename = str_replace('/' , '-', $document->fid) . "." . $ext['extension'];
            
            /**   Log the access of the user */
            //   $user_id= Auth::user()->id;
            //   $current_date = date('Y-m-d H:i:s');
            //   DB::insert('insert into document_view_logs (user_id, document_id, date) values (?,?,?)', [$user_id, $id, $current_date]);

            if(is_file($path)) {
                $pdfBase64 = base64_encode( file_get_contents($path) );
                return response([
                    'serial' => str_replace(['documents','/','.pdf'],'',$document->pdf ) ,
                    "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
                    "filename" => $filename,
                    "ok" => true 
                ],200);
            }else
            {
                return response([
                    'message' => 'មានបញ្ហាក្នុងការអានឯកសារយោង !' ,
                    'path' => $path
                ],404 );
            }
        }
    }
    public function upload(Request $request){
        $user = \Auth::user();
        if( $user ){
            $kbFilesize = round( filesize( $_FILES['files']['tmp_name'] ) / 1024 , 4 );
            $mbFilesize = round( $kbFilesize / 1024 , 4 );
            if( ( $document = \App\Models\Regulator\Regulator::find($request->id) ) !== null ){
                list($year,$month,$day) = explode('-',$document->year);
                $uniqeName = Storage::disk('regulator')->putFile( 'documents' , new File( $_FILES['files']['tmp_name'] ) );
                $document->pdf = $uniqeName ;
                $document->save();
                if( Storage::disk('regulator')->exists( $document->pdf ) ){
                    $document->pdf = Storage::disk("document")->url( $document->pdf  );
                    return response([
                        'record' => $document ,
                        'message' => 'ជោគជ័យក្នុងការបញ្ចូលឯកសារយោង។'
                    ],200);
                }else{
                    return response([
                        'record' => $document ,
                        'message' => 'មិនមានឯកសារយោងដែលស្វែងរកឡើយ។'
                    ],403);
                }
            }else{
                return response([
                    'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់របស់ឯកសារយោង។'
                ],403);
            }
        }else{
            return response([
                'message' => 'សូមចូលប្រព័ន្ធជាមុនសិន។'
            ],403);
        }
    }
    /** Get the year(s) that there is/are documents published base on ministry_id and type_id */
    public function getRegulatorsAsOrganizationTypeYearMonth(Request $request){
        $ministries = \App\Models\Organization()->selectRaw('id, name')->orderby('name','asc')->get();
        // $tree = []
        // foreach( $ministries as $ministryIndex => $ministry ){
            
        //     foreach( $ministry->documents as $documentIndex => $document ){

        //     }
        // }    
    }
    public function create(Request $request){
        /**
         * Save information of the regulator and its related information
         */
        
        $record = RecordModel::create([
            'fid' => $request->number?? ''  ,
            'title' => $request->title?? '' ,
            'objective' => $request->objective ,
            'year' => $request->year?? \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
            'publish' => 1 , // $request->publish
            'active' => $request->active > 0 ? 1 : 0 ,
            'created_by' => \Auth::user()->id ,
            'updated_by' => \Auth::user()->id
        ]);
        /**
         * Create detail information of the owner of the account
         */
        $record->created_by = \Auth::user()->id ;
        // $person = \App\Models\People\People::create([
        //     'firstname' => $record->firstname , 
        //     'lastname' => $record->lastname , 
        //     'mobile_phone' => $record->phone , 
        //     'email' => $record->email
        // ]);
        // $record->people_id = $person->id ;

        /**
         * Sync the organizations
         */
        if( count( $request->organizations ) > 0 ){
            $record->organizations()->sync( $request->organizations );
        }
        if( count( $request->ownOrganizations ) > 0 ){
            $record->ownOrganizations()->sync( $request->ownOrganizations );
        }
        if( count( $request->relatedOrganizations ) > 0 ){
            $record->relatedOrganizations()->sync( $request->relatedOrganizations );
        }
        /**
         * Sync the signatures
         */
        if( count( $request->signatures ) > 0 ){
            $record->signatures()->sync( $request->signatures );
        }
        /**
         * Sync the types
         */
        if( isset( $request->types ) ){
            is_array( $request->types ) && count( $request->types ) > 0 
                ? $record->types()->sync( $request->types )
                :  $record->types()->sync( [$request->types] ) ;
        }
        
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        $responseData['record'] = $record ;
        return response()->json($responseData, 200);
    }
    public function update(Request $request){
        if( isset( $request->id ) && $request->id > 0 && ( $record = RecordModel::find($request->id) ) !== null ){
            /**
             * Save information of the regulator and its related information
             */
            if( $record->update([
                'fid' => $request->number ,
                'title' => $request->title ,
                'objective' => $request->objective ,
                'year' => $request->year ,
                // 'type' => $request->type_id ,
                'active' => $request->active > 0 ? 1 : 0 ,
                'updated_by' => \Auth::user()->id
            ]) ){

                /**
                 * Sync the organizations
                 */
                if( count( $request->organizations ) > 0 ){
                    $record->organizations()->sync( $request->organizations );
                }
                if( count( $request->ownOrganizations ) > 0 ){
                    $record->ownOrganizations()->sync( $request->ownOrganizations );
                }
                if( count( $request->relatedOrganizations ) > 0 ){
                    $record->relatedOrganizations()->sync( $request->relatedOrganizations );
                }
                /**
                 * Sync the signatures
                 */
                if( count( $request->signatures ) > 0 ){
                    $record->signatures()->sync( $request->signatures );
                }
                /**
                 * Sync the types
                 */
                if( isset( $request->types ) ){
                    is_array( $request->types ) && count( $request->types ) > 0 
                        ? $record->types()->sync( $request->types )
                        :  $record->types()->sync( [$request->types] ) ;
                }

                $responseData['message'] = __("crud.read.success");
                $responseData['ok'] = true ;
                $responseData['record'] = $record ;
                return response()->json($responseData, 200);
            }else{
                return response()->json([
                    'message' => 'មានបញ្ហាក្នុងការរក្សារព័ត៌មានឯកសារ។'
                ], 403);    
            }
        }else{
            return response()->json([
                'message' => 'សូមបញ្ជាក់លេខសម្គាល់ឯកសារ។'
            ], 403);
        }
    }
    public function read(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
            ],201);
        }
        $regulator = RecordModel::find($request->id);
        if( $regulator == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឯកសារដែលអ្នកត្រូវការមិនមានឡើយ។'
            ],201);
        }
        $regulator->with('ministries')->with('signatures')->with('ministries')->with('type');
        return response()->json([
            'record' => $regulator ,
            'ok' => true ,
            'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
        ],201);
    }

    public function destroy(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
            ],201);
        }
        $regulator = RecordModel::find($request->id);
        if( $regulator == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឯកសារស្វែករកបានជោគជ័យ។'
            ],201);
        }
        $regulator->with('ministries')->with('signatures')->with('ministries')->with('type');
        $tempRecord = $regulator;
        if( $regulator->delete() ){
            /**
             * Delete all the related documents own by this regulator
             */
            if( $tempRecord->pdf !== null && $tempRecord->pdf !=="" && Storage::disk('regulator')->exists( $tempRecord->pdf ) ){
                Storage::disk("document")->delete( $tempRecord->pdf  );
            }
            return response()->json([
                'record' => $tempRecord ,
                'ok' => true ,
                'message' => 'លុបទិន្នបានជោគជ័យ។'
            ],200);
        }
        return response()->json([
            'record' => $tempRecord ,
            'ok' => false ,
            'message' => 'មានបញ្ហាក្នុងការលុបទិន្ន័យ។'
        ],201);
    }
    public function activate(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
            ],422);
        }
        $record = RecordModel::find($request->id);
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឯកសារដែលអ្នកត្រូវការមិនមានឡើយ។'
            ],423);
        }
        $record->with('ministries')->with('signatures')->with('ministries')->with('type');
        return response()->json([
            'record' => $record ,
            'ok' => $record->update(['active'=>1]) ,
            'message' => 'បានបើកឯកសាររួចរាល់។'
        ],200);
    }
    public function deactivate(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
            ],422);
        }
        $record = RecordModel::find($request->id);
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឯកសារដែលអ្នកត្រូវការមិនមានឡើយ។'
            ],423);
        }
        $record->with('ministries')->with('signatures')->with('ministries')->with('type');
        return response()->json([
            'record' => $record ,
            'ok' => $record->update(['active'=>0]) ,
            'message' => 'បានបើកឯកសាររួចរាល់។'
        ],200);
    }

    public function userAttendants(Request $request){

        /** Format from query string */
        $search = isset( $request->search ) && $request->search !== "" ? $request->search : false ;
        $perPage = intval( $request->perPage ) > 0 ? $request->perPage : 50 ;
        $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;

        /**
         * Filter conditions
         */
        $user = isset( $request->userId ) && intval( $request->userId ) > 0 ? \App\Models\User::find($request->userId) : false ;
        $date = isset( $request->date ) & strlen( $request->date ) >= 6 ? \Carbon\Carbon::parse( $request->date ) : \Carbon\Carbon::now() ;

        if( $user == false || $user == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមជ្រើសរើសបុគ្គលិកជាមុនសិន។'
            ],500);
        }

        $queryString = [
            "where" => [
                'default' => [
                    [
                        'field' => 'user_id' ,
                        'value' => $user->id
                    ]
                ]
            ] ,
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" =>
                $search === false ? [] : [
                    'value' => $search ,
                    'fields' => [
                        'date'
                    ] 
                ]
            ,
            "order" => [
                'field' => 'date' ,
                'by' => 'asc'
            ],
        ];
        
        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields);

        $builder = $crud->getListBuilder();

        $builder->whereYear('date',$date->format('Y'))
        ->whereMonth('date',$date->format('m'));

        $responseData = $crud->pagination(true, $builder);
        $responseData['records'] = $responseData['records']->map(function($att){
            $tempAtt = RecordModel::find($att['id']);
            return [
                'id' => $tempAtt->id ,
                'date' => $tempAtt->date ,
                'calculateTime' => $tempAtt->calculateWorkingTime() ,
                'day_of_week' => \Carbon\Carbon::parse( $tempAtt->date )->dayOfWeek
            ];
        });
        $responseData['user'] = [
            'id' => $user->id ,
            'firstname' => $user->firstname ,
            'lastname' => $user->lastname ,
            'avatar_url' => $user->avatar_url != null && strlen( $user->avatar_url ) > 0 && \Storage::disk('public')->exists( $user->avatar_url ) ? \Storage::disk('public')->url( $user->avatar_url ) : null
        ];
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }

    /**
     * Check in and out with attendant without specified the timeslot.
     * Required information : Email or Phone of the User Account , check status (IN , OUT)
     */
    public function checkAttendantByEmailOrPhoneByOrganization(Request $request){
        $organization = null ;
        if( isset( $request->organization_id ) && strlen( trim ( $request->organization_id ) ) > 0 && intval( $request->organization_id ) > 0 ){
            $organization = \App\Models\Organization\Organization::where('id', intval( $request->organization_id ) )->first();
        }
        // return response()->json([
        //     'result' => $organization
        // ],200);
        $result = false ;
        if( isset( $request->email ) && strlen( trim ( $request->email ) ) > 0 ){
            $result = \App\Models\User::where('email',$request->email);
        }
        else if( isset( $request->phone ) && strlen( trim ( $request->phone ) ) > 0 ){
            $result = \App\Models\User::where('phone',$request->phone);
        }
        if( $result == false ){
            return response()->json([
                'ok'=> false ,
                'message' => 'សូមពិនិត្យព័ត៌មានដែលអ្នកបានផ្ដល់ម្ដងទៀត។'
            ],403);
        }
        if( $result != false && $result->count() > 1 ){
            return response()->json([
                'ok'=> false ,
                'message' => 'ព័ត៌មានដែលផ្ទៀងផ្ទាត់មានចំនួនច្រើន។' ,
                'result' => $result->get()
            ],403);
        }
        $user = $result->first();
        $now = \Carbon\Carbon::now();
        /**
         * Check whether the attendant has been registered once already
         */
        $attendant = $user->attendants()->where('date',$now->format('Y-m-d'))->orderBy('id','desc')->first() ;
        if( $attendant == null ){
            /**
             * The attendant of the date have not registered, yet
             */
            $attendant = RecordModel::create([
                'user_id' => $user->id ,
                'date' => $now->format('Y-m-d') ,
                'late_or_early' => 0.0 ,
                'worked_time' => 0.0 ,
                'duration' => 0.0 ,
                'created_at' => $now ,
                'updated_at' => $now
            ]);
        }
        /**
         * Create checktime of the attendant
         */
        $parentChecktime = $attendant->checktimes->count() > 0 
            ? $attendant->checkTimes()->orderby('id','desc')->first()
            : null ;

        // if the photo is provided
        $uniqeName = "" ;
        if( isset( $request->photo ) && strlen( trim ( $request->photo ) ) > 0 ){

            /**
             * Create backup folder
             */
            $folderName = \Carbon\Carbon::now()->format('Y-m-d');
            if( !\Storage::disk('attendant')->exists( $folderName ) ){
                if( ( $result = \Storage::disk('attendant')->makeDirectory( $folderName ) ) != true ){
                    return response()->json([
                        'ok' => false ,
                        'result' => $result ,
                        'message' => 'មានបញ្ហាក្នុងការបង្កើតថតដាក់រូបថត។'
                    ],403);
                }
            }

            list($base64,$data) = explode( 'base64,' , $request->photo );
            $data = base64_decode($data);
            $filename = md5( \Carbon\Carbon::now()->format('Y-m-d H:i:s')).'.jpg';
            if( Storage::disk('attendant')->put( $folderName.'/'.$filename , $data , 'public' ) == false ){
                return response()->json([
                    'ok' => false ,
                    'result' => $folderName.'/'.$filename ,
                    'message' => 'មានបញ្ហាដាក់រូបភាព។'
                ],403);
            }
            $uniqeName = $folderName.'/'.$filename ;
        }

        // $timeslot = \App\Models\Attendant\Timeslot::getTimeslot( $now ) ;
        
        // if( $timeslot == null ){
        //     return response()->json([
        //         'message' => 'គណនីនេះមិនទាន់វេនការងារនៅឡើយ។'
        //     ],503);
        // }

        $checktime = $attendant->checktimes()->create([
            'attendant_id' => $attendant->id ,
            // 'timeslot_id' => $timeslot->id ,
            'timeslot_id' => 0 ,
            'organization_id' => $organization == null ? 0 : $organization->id ,
            'checktime' => $now->format('H:i') ,
            
            // 'check_status' => $parentChecktime == null ? "IN" : "OUT" ,
            // 'checktype' => 'System' ,

            'checktype' => \App\Models\Attendant\AttendantCheckTime::CHECK_TYPE_PHONE_EMAIL ,
            'check_status' => $parentChecktime == null 
                ? \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_IN
                : (
                    $parentChecktime->check_status == \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_OUT 
                        ? \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_IN 
                        : \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_OUT
                ),
            'parent_checktime_id' => $parentChecktime == null ? 0 : $parentChecktime->id ,
            'meta' => $request->meta ,
            'created_at' => $now ,
            'updated_at' => $now ,
            'lat' => $request->lat ,
            'lng' => $request->lng ,
            'photo' => $uniqeName 
        ]);

        return response()->json([
            'ok' => true ,
            'message' => 'ជោគជ័យ។' ,
            'attendant' => $attendant ,
            'checktime' => $checktime 
        ],200);
    }

    /**
     * Check in and out with attendant without specified the timeslot.
     * Required information : Email or Phone of the User Account , check status (IN , OUT)
     */
    public function getAttendantByEmailOrPhone(Request $request){
        if( !isset( $request->term ) || strlen( trim ( $request->term ) ) <= 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីព័ត៌មានដែលត្រូវពិនិត្យផ្ទៀងផ្ទាត់។'
            ],422);
        }
        if( !isset( $request->type ) || in_array( $request->type , [ 'id' , 'phone' , 'email' ]) == false ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបំពេញព័ត៌មានឲ្យបានគ្រប់គ្រាន់។'
            ],422);
        }

        $result = null ;
        switch( $request->type ){
            case "phone" :
                $result = \App\Models\User::where( 'phone' , $request->term );
                break;
            case "email" :
                $result = \App\Models\User::where( 'email' , $request->term );
                break;
            case "id" :
                $result = \App\Models\User::where( 'id' , intval( $request->term ) );
                break;
        }
        /**
         * Check whether the matched case are many records
         */
        if( $result->count() > 1 ){
            return response()->json([
                'ok' => false ,
                'message' => 'ករណីផ្ទៀងផ្ទាត់ហាក់មានចំនួនច្រើន។'
            ],403);
        }
        $user = $result->first();
        $now = \Carbon\Carbon::now();
        if( $user == null ){
            return response()->json([
                'ok'=> false ,
                'message' => 'សូមពិនិត្យព័ត៌មានដែលអ្នកបានផ្ដល់ម្ដងទៀត។'
            ],403);
        }
        /**
         * Check whether the attendant has been registered once already
         */
        $attendant = $user->attendants()->where('date',$now->format('Y-m-d'))->orderBy('id','desc')->first() ;
        if( $attendant == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'មិនទាន់បានចុះវត្តមានឡើយ។'
            ],403);
        }

        $lastChecktime = $attendant->checktimes()->orderby('id','desc')->first();

        $checkStatus = $lastChecktime != null
            ? $lastChecktime->check_status == \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_OUT ? \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_IN : \App\Models\Attendant\AttendantCheckTime::CHECK_STATUS_OUT 
            // User does not have any attendant check yet
            : -1 ;

        return response()->json([
            'ok' => true ,
            'message' => 'បានចុះវត្តមានរួចហើយ។' ,
            'attendant' => $attendant ,
            'checktimes' => $attendant->checktimes()->orderby('id')->get()->each(function($ck){ $ck->organization; }) ,
            'check_status' => $checkStatus
        ],200);
    }
    public function readAttendantChecktimePhoto(Request $request){
        $checktime = intval( $request->id ) > 0 ? \App\Models\Attendant\AttendantCheckTime::find( $request->id ) : null ;
        if( $checktime != null ){
            $base64 = false ;
            if( strlen( $checktime->photo ) > 0 && \Storage::disk('attendant')->exists( $checktime->photo ) ){
                $path = storage_path('data/attendants') . '/' . $checktime->photo ;
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            }
            return response()->json([
                'ok' => true , 
                'base64' => $base64 ,
                'message' => 'ជោគជ័យ'
            ],200);
        }
        return response()->json([
            'ok' => false , 
            'message' => 'មានបញ្ហាអានរូបភាព។' ,
        ],403);
    }
}
