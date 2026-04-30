<?php

namespace App\Http\Controllers\Api\AuthenticationCenter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Mail\MobilePasswordResetRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Officer\Officer as RecordModel ;
use App\Http\Controllers\CrudController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Services\PeopleSearchService;
use \Gumlet\ImageResize;


class OfficerController extends Controller
{
    private $selectFields = [
        'id',
        'public_key' ,
        'code' ,
        'people_id' ,
        'official_date' ,
        'unofficial_date' ,
        'image' ,
        'leader' ,
        'organization_id' ,
        'position_id' ,
        'rank_id' ,
        'user_id' ,
        'countesy_id' ,
        'email' ,
        'phone' ,
        'passport' ,
        'salary_rank' ,
        'officer_type' ,
        'additional_officer_type'
    ];

    protected $searchService;

    public function __construct(PeopleSearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Listing function
     */
    public function index(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && strlen( $request->search ) > 0
            ? str_replace( [ '​' ] , '' , $request->search )
            : false ;
        $perPage = isset( $request->perPage ) && intval( $request->perPage ) > 0 ? $request->perPage : 10 ;
        $page = isset( $request->page ) && intval( $request->page ) > 0 ? $request->page : 1 ;

        $gender = isset( $request->gender ) && ( $request->gender == 0 || $request->gender == 1 ) ? $request->gender : false ;

        // Clean the search string with hidden_space of Khmer character


        $isWildSearch = isset( $request->wild_search ) && intval( $request->wild_search ) > 0 ? intval( $request->wild_search ) : 0 ;

        $positions = isset( $request->positions ) ? explode(',',$request->positions) : false ;
        if( is_array( $positions ) && !empty( $positions ) ){
            $positions = array_filter( $positions, function($position){
                return intval( $position ) > 0 ;
            } );
        }

        $unofficialPositions = isset( $request->unofficial_position_ids ) ? explode(',',$request->unofficial_position_ids) : false ;
        if( is_array( $unofficialPositions ) && !empty( $unofficialPositions ) ){
            $unofficialPositions = array_filter( $unofficialPositions, function($unofficialPosition){
                return intval( $unofficialPosition ) > 0 ;
            } );
        }

        $officerTypes = isset( $request->officer_types ) ? explode(',',$request->officer_types) : false ;
        if( is_array( $officerTypes ) && !empty( $officerTypes ) ){
            $officerTypes = array_filter( $officerTypes, function($officerType){
                return strlen( $officerType ) > 0 ;
            } );
        }

        $organizations = isset( $request->organizations ) ? explode(',',$request->organizations) : false ;
        if( is_array( $organizations ) && !empty( $organizations ) ){
            $organizations = array_filter( $organizations , function($organization){
                return intval( $organization ) > 0 ;
            } );
        }

        $officerIds = isset( $request->ids ) ? explode(',',$request->ids) : false ;
        if( is_array( $officerIds ) && !empty( $officerIds ) ){
            $officerIds = array_filter( $officerIds , function($officerIds){
                return intval( $officerIds ) > 0 ;
            } );
        }

        $educationLevels = isset( $request->education_levels ) ? explode(',',$request->education_levels) : false ;
        if( is_array( $educationLevels ) && !empty( $educationLevels ) ){
            $educationLevels = array_filter( $educationLevels , function($educationLevel){
                return intval( $educationLevel ) > 0 ;
            } );
        }

        $ranks = isset( $request->rank_ids ) ? explode(',',$request->rank_ids) : false ;
        if( is_array( $ranks ) && !empty( $ranks ) ){
            $ranks = array_filter( $ranks , function($rank){
                return intval( $rank ) > 0 ;
            } );
        }

        $dob = isset( $request->dob ) && strlen( $request->dob ) > 0 ? $request->dob : false ;

        $dobRange = isset( $request->dob_range )
            ? explode(',',$request->dob_range)
            : [];

        $unofficial_date = isset( $request->unofficial_date ) && strlen( $request->unofficial_date ) > 0 ? $request->unofficial_date : false ;
        $official_date = isset( $request->official_date ) && strlen( $request->official_date ) > 0 ? $request->official_date : false ;

        $searchFields = isset($request->search_fields) && strlen(trim($request->search_fields)) > 0 && !empty(explode($request->search_fields, ',')) ? explode($request->search_fields, ',') : [];
        // Filter the searchFields base on pivot table's fields
        $pivotDefaultSearchFields = [
            // This four fields has already implemented full text search
            // 'firstname', 'lastname', 'enfirstname', 'enlastname',
            'nid'
            // , 'dob' , 'mobile_phone' , 'office_phone' , 'email' , 'passport' , 'address'
        ];
        $pivotSearchFields = array_filter( $searchFields ,function ($field) {
            return in_array($field, [
                // 'firstname' , 'lastname' , 'enfirstname' , 'enlastname' ,
                'nid'
                // , 'dob' , 'mobile_phone' , 'office_phone' , 'email' , 'passport' , 'address'
            ] );
        });
        // Filter the searchFields to make sure it does exists within the table
        $searchFields = array_filter( $searchFields , function ($field) {
            return in_array($field, $this->selectFields);
        });

        $searchResults = $this->searchService->search(
            $search , $perPage
        )->pluck('id')->toArray();

        $queryString = [
            // "where" => [
            //     // 'default' => [
            //     //     $official_date != false
            //     //         ?[
            //     //             'field' => 'dob' ,
            //     //             'value' => $type === false ? "" : $type
            //     //         ]
            //     //         : []
            //     // ],
            //     'in' => [
            //         // Filer by officer ids
            //         is_array( $officerIds ) && !empty( $officerIds )
            //             ?   [
            //                 'field' => 'id' ,
            //                 'value' => $officerIds
            //             ]
            //             : []
            //     ] ,
            // //     'not' => [] ,
            //     'like' => [
            //         $official_date != false
            //         ?[
            //             'field' => 'official_date' ,
            //             'value' => $official_date
            //         ]
            //         : []
            //     ] ,
            // ] ,
            // "pivots" => [
            //     // is_array( $organizations ) && !empty( $organizations ) ?
            //     // [
            //     //     "relationship" => 'organization',
            //     //     "where" => [
            //     //         "in" => [
            //     //             "field" => "organization_id",
            //     //             "value" => $organizations
            //     //         ]
            //     //     ]
            //     // ]
            //     // : [] ,
            //     // is_array( $positions ) && !empty( $positions ) ?
            //     // [
            //     //     "relationship" => 'position',
            //     //     "where" => [
            //     //         "in" => [
            //     //             "field" => "position_id",
            //     //             "value" => $positions
            //     //         ]
            //     //     ]
            //     // ]
            //     // : [] ,
            //     strlen( $search ) > 0 ?
            //     [
            //         "relationship" => 'people',
            //         "where" => [
            //             "like" => [
            //                 "value" => $search ,
            //                 "fields" => !empty( $pivotSearchFields ) ? $pivotSearchFields : $pivotDefaultSearchFields
            //             ]
            //         ]
            //     ]
            //     : []
            // ],
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            // "search" =>
            //     $search === false
            //         ? []
            //         : [
            //             'value' => $search ,
            //             'fields' => [
            //                 'code' ,
            //                 'official_date' ,
            //                 'unofficial_date'
            //             ]
            //         ]
            // ,
            "order" => [
                'field' => 'id' ,
                'by' => 'desc'
            ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields,[
            'image' => function( $officer ){
                return $officer['image'] != null && \Storage::disk('public')->exists( $officer['image'] )
                ? \Storage::disk('public')->url( $officer['image'] )
                : (
                    isset( $officer['user'] ) && $officer['user']['avatar_url'] != null && \Storage::disk('public')->exists( $officer['user']['avatar_url'] )
                    ? \Storage::disk('public')->url( $officer['user']['avatar_url'] )
                    : (
                        isset( $officer['people'] ) && $officer['people']['image'] != null && \Storage::disk('public')->exists( $officer['people']['image'] )
                        ? \Storage::disk('public')->url( $officer['people']['image'] )
                        : false
                    )
                );
            }
        ],false ,[
            'current_job' => function( $officer ){
                $officer = RecordModel::find( $officer['id'] ) ;
                $job = $officer == null ? null : $officer->getCurrentJob() ;
                if( $job != null && $job->organizationStructurePosition != null ){
                    $job->organizationStructurePosition->position;
                    if( $job->organizationStructurePosition->organizationStructure != null ){
                        $job->organizationStructurePosition->organizationStructure->organization;
                    }
                    $job->unofficialPosition;
                }
                if( $job != null ){
                    $job->unofficialPosition;
                }
                return $officer == null || $job == null ? null : $job ;
            }
        ]);
        $crud->setRelationshipFunctions([
            //     /** relationship name => [ array of fields name to be selected ] */
            //     "person" => ['id','firstname' , 'lastname' , 'gender' , 'dob' , 'pob' , 'picture' ] ,
            //     "roles" => ['id','name', 'tag'] ,
            'user' => [
                'id' , 'username' , 'phone' , 'email' , 'avatar_url' , 'firstname' , 'lastname' ,
                'roles' => [ 'id' , 'name' ]
            ] ,
            'rank' => [ 'id' , 'name' , 'ank' , 'krobkhan' , 'krobkhan_name' , 'rank' , 'thnak' , 'prefix' ] ,
            "people" => [
                'id','firstname' , 'lastname' , 'enfirstname' , 'enlastname' , 'gender' , 'dob' , 'pob' , 'image' , 'mobile_phone' , 'office_phone' , 'passport' , 'nid' , 'marry_status' , 'email' , 'nationality' , 'national' , 'death' , 'body_condition' , 'body_condition_desp' ,
                'address' ,
                'address_province_id' ,
                'address_district_id' ,
                'address_commune_id' ,
                'address_village_id' ,
                'addressProvince' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'addressDistrict' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'addressCommune' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'addressVillage' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'current_address' ,
                'current_address_province_id' ,
                'current_address_district_id' ,
                'current_address_commune_id' ,
                'current_address_village_id' ,
                'currentAddressProvince' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'currentAddressDistrict' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'currentAddressCommune' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'currentAddressVillage' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'pob_province_id' ,
                'pob_district_id' ,
                'pob_commune_id' ,
                'pob_village_id' ,
                'pobProvince' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'pobDistrict' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'pobCommune' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'pobVillage' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'emergency_lastname' ,
                'emergency_firstname' ,
                'emergency_gender' ,
                'emergency_profession' ,
                'emergency_relationship' ,
                'emergency_phone' ,
                'emergency_email' ,
                'emergency_address' ,
                'emergency_address_province_id' ,
                'emergency_address_district_id' ,
                'emergency_address_commune_id' ,
                'emergency_address_village_id' ,
                'emergencyProvince' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'emergencyDistrict' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'emergencyCommune' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                'emergencyVillage' => [ 'id' , 'name_en' , 'name_kh' , 'code'] ,
                // father
                'father_firstname' ,
                'father_lastname' ,
                'father_enfirstname' ,
                'father_enlastname' ,
                'father_dob' ,
                'father_nationality' ,
                'father_national' ,
                'father_pob' ,
                'father_address' ,
                'father_address_province_id' ,
                'father_address_district_id' ,
                'father_address_commune_id' ,
                'father_address_village_id' ,
                'father_death' ,
                'father_profession' ,
                'father_nid' ,

                // mother
                'mother_firstname' ,
                'mother_lastname'  ,
                'mother_enfirstname' ,
                'mother_enlastname' ,
                'mother_dob' ,
                'mother_nationality' ,
                'mother_national' ,
                'mother_pob' ,
                'mother_address' ,
                'mother_address_province_id' ,
                'mother_address_district_id' ,
                'mother_address_commune_id' ,
                'mother_address_village_id' ,
                'mother_death' ,
                'mother_profession' ,
                'mother_nid' ,

                'weddingCertificates' => [
                    'id' ,
                    'wedding_number' ,
                    'book_number' ,
                    'year' ,
                    'province_id' ,
                    'district_id' ,
                    'commune_id' ,
                    'issued_date' ,
                    'issued_location' ,
                    'signed_name' ,
                    'pdf' ,
                    'spouse_death' ,
                    // Spouse
                    'spouse_id' ,
                    'spouse_firstname',
                    'spouse_lastname',
                    'spouse_enfirstname' ,
                    'spouse_enlastname' ,
                    'spouse_national' ,
                    'spouse_nationality' ,
                    'spouse_dob' ,
                    'spouse_profession' ,
                    'spouse_profession_organization' ,
                    'spouse_pob' ,
                    'spouse_address' ,

                    // Father information
                    'spouse_father_firstname' ,
                    'spouse_father_lastname' ,
                    'spouse_father_enfirstname' ,
                    'spouse_father_enlastname' ,
                    'spouse_father_dob' ,
                    'spouse_father_nationality' ,
                    'spouse_father_national' ,
                    'spouse_father_pob' ,
                    'spouse_father_address' ,
                    'spouse_father_profession' ,
                    'spouse_father_picture' ,
                    'spouse_father_death' ,

                    // Mother information
                    'spouse_mother_firstname' ,
                    'spouse_mother_lastname' ,
                    'spouse_mother_enfirstname' ,
                    'spouse_mother_enlastname' ,
                    'spouse_mother_dob' ,
                    'spouse_mother_nationality' ,
                    'spouse_mother_national' ,
                    'spouse_mother_pob' ,
                    'spouse_mother_address' ,
                    'spouse_mother_profession' ,
                    'spouse_mother_picture' ,
                    'spouse_mother_death'
                ],
                'certificates' => [ 'id' , 'field_name' , 'start' , 'end' , 'place_name' , 'certificate_note' , 'certificate_group_id' ]
            ],
            // 'position' => [ 'id' , 'name' , 'desp' , 'prefix' ] ,
            // 'organization' => [ 'id' , 'name' , 'desp' , 'prefix' ] ,
            'countesy' => [ 'id' , 'name' , 'desp' , 'prefix' ] ,
            'jobs' => [ 'id' , 'organization_structure_position_id' , 'officer_id' ,'countesy_id' , 'start' , 'end' ,
                'unoficialPosition' => [ 'id' , 'name' , 'desp' , 'prefix'] ,
                'organizationStructurePosition' => [
                    'id' , 'name' , 'pid' , 'tpid' , 'cids' , 'image' , 'organization_structure_id' , 'position_id' , 'job_desp' ,
                    'position' => [ 'id' , 'name' , 'desp' , 'prefix' ] ,
                    'organizationStructure' => [
                        'id' , 'organization_id' , 'pid' , 'name' , 'tpid' , 'cids' , 'desp' , 'active'
                        , 'organization' => [ 'id' , 'name' , 'desp' , 'prefix' ]
                    ]
                ]
            ],
            'card' => [
                'id' ,
                'uuid',
                'number',
                'people_id',
                'officer_id',
                'start',
                'end',
                'active',
                'author' => [ 'id' , 'firstname' , 'lastname' ],
                'editor' => [ 'id' , 'firstname' , 'lastname' ]
            ]
        ]);

        $builder = $crud->getListBuilder()->whereNull('deleted_at');

        if( count( $dobRange) > 1 ){
            $builder->whereHas('people', function ($q) use ($dobRange) {
                $q->whereBetween('dob', $dobRange );
            });
        }
        else if( $dob != false ){
            $builder->whereHas('people', function ($q) use ($dob) {
                $q->where('dob', 'like', '%' . $dob . '%');
            });
        }

        if( $gender !== false ){
            $builder->whereHas('people', function ($q) use ($gender) {
                $q->where('gender', $gender );
            });
        }

        if( !empty( $educationLevels ) ){
            $builder->whereHas('people', function ($q) use ( $educationLevels ){
                $q->whereHas('certificates', function ($q) use ( $educationLevels ){
                    $q->whereIn('certificate_group_id', $educationLevels);
                });
            });
        }

        // return response()->json([
        //     'ranks' => $ranks 
        // ],200);
        if( !empty( $ranks ) ){
            $builder->whereHas('rank', function ($q) use ( $ranks ){
                $q->whereIn('id', $ranks );
            });
        }

        if (
            !empty($organizations) ||
            !empty($positions) ||
            !empty($unofficialPositions)
        ) {
            $builder->whereHas('jobs', function ($jobQuery) use ($organizations, $positions, $unofficialPositions) {
                $jobQuery->whereHas('organizationStructurePosition', function ($positionQuery) use ($organizations, $positions) {
                    if (is_array($positions) && !empty($positions)) {
                        $positionQuery->whereIn('position_id', $positions);
                    }
                    if (is_array($organizations) && !empty($organizations)) {
                        $positionQuery->whereHas('organizationStructure', function ($query) use ($organizations) {
                            $query->whereIn('organization_id', $organizations);
                        });
                    }
                });
                if (!empty($unofficialPositions)) {
                    $jobQuery->whereHas('unofficialPosition', function ($q) use ($unofficialPositions) {
                        $q->whereIn('unofficial_position_id', $unofficialPositions);
                    });
                }
            });
        }

        if( !empty( $officerTypes ) ){
            $builder->whereIn('additional_officer_type', $officerTypes);
        }

        if( $builder->count() > 0 ){
            if( !empty( $searchResults ) ){
                $builder->whereIn('people_id', $searchResults );
            }
        } else {
            if( !empty( $searchResults ) ){
                $builder->whereIn('people_id', $searchResults);
            }
        }

        /**
         * Filter the officers to get only the officer that is not admin and super admin
         */
        $builder->whereHas('user',function($query){
            $query->whereHas('roles',function($query){
                $query->whereNot('name',['super','admin']);
            });
        });

        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        $responseData['fts'] = [
            'search_results' => $searchResults ,
            'total' => $builder->count() ,
            'ranks' => $ranks 
            // 'succestions' => $suggestions ,
            // 'fuzzySearch' => $fuzzySearch ,
            // 'searchWithHighlight' => $searchWithHighlight ,
            // 'combinedSearch' => $combinedSearch
        ];
        return response()->json($responseData, 200);
    }
    /**
     * Read people without any conditions
     */
    public function getPeopleByIds(Request $request){
        /** Format from query string */
        $ids = isset( $request->ids ) && $request->ids !== "" && strlen( $request->ids ) > 0 ? explode( ',' , $request->ids ) : false ;
        if( !is_array( $ids ) && empty( $ids ) ){
            return response()->json([
                'message' => 'សូមបញ្ជាក់លេខសម្គាល់។'
            ],500);
        }
        $search = isset( $request->search ) && strlen( $request->search ) > 0
            ? str_replace( [ '​' ] , '' , $request->search )
            : false ;

        $queryString = [
            "where" => [
                // 'default' => [
                //     [
                //         'field' => 'type_id' ,
                //         'value' => $type === false ? "" : $type
                //     ]
                // ],
                'in' => [
                    is_array( $ids )
                        ? [
                            'field' => 'id' ,
                            'value' => $ids
                        ] : []
                ] ,
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
                'perPage' => 20,
                'page' => 1
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
                'field' => 'id' ,
                'by' => 'desc'
            ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields);
        $crud->setRelationshipFunctions([
            "countesies" => [ 'id', 'name' , 'desp' , 'pid' , 'record_index' ] ,
            "organizations" => [ 'id', 'name' , 'desp' , 'pid' , 'record_index' ] ,
            "positions" => [ 'id', 'name' , 'desp' , 'pid' , 'record_index' ] ,
            'user' => [ 'id' , 'username' , 'phone' , 'email' , 'avatar_url' ]
        ]);

        $builder = $crud->getListBuilder()
        ->whereNull('deleted_at')
        ->whereIn('id', $ids );

        $responseData = $crud->pagination(true, $builder);
        $responseData['records'] = $responseData['records']->map(function($record){
            $record['image'] = $record['image'] != null && \Storage::disk('public')->exists( $record['image'] )
                ? \Storage::disk('public')->url( $record['image'] )
                : (
                    $record['user']['avatar_url'] != null && \Storage::disk('public')->exists( $record['user']['avatar_url'] )
                    ? \Storage::disk('public')->url( $record['user']['avatar_url'] )
                    : false
                );
            return $record;
        });
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        return response()->json($responseData, 200);
    }
    /**
     * Create an account
     */
    public function storeOfficer(Request $request){
        $isDraftCreate = filter_var($request->draft, FILTER_VALIDATE_BOOLEAN) || intval($request->draft) > 0;
        $request->merge([
            'firstname' => trim((string) ($request->firstname ?? '')),
            'lastname' => trim((string) ($request->lastname ?? '')),
            'enfirstname' => trim((string) ($request->enfirstname ?? '')),
            'enlastname' => trim((string) ($request->enlastname ?? ''))
        ]);

        $validated = $request->validate([
            // 'code' => 'required|unique:officers|max:50',
            // 'nid' => 'required|unique:people|max:50' ,
            // 'organization_id' => 'required',
            // 'position_id' => 'required',
            'firstname' => $isDraftCreate ? 'nullable' : 'required' ,
            'lastname' => $isDraftCreate ? 'nullable' : 'required' ,
            'enfirstname' => $isDraftCreate ? 'nullable' : 'required' ,
            'enlastname' => $isDraftCreate ? 'nullable' : 'required'
        ]);


        // Check the ranking of the officer
        $ank = isset( $request->ank ) && strlen( $request->ank ) > 0 ? trim($request->ank) : false ;
        $krobkhan = isset( $request->krobkhan ) && strlen( $request->krobkhan ) > 0 ? trim($request->krobkhan) : false ;
        $rank = isset( $request->rank ) && strlen( $request->rank ) > 0 ? trim($request->rank) : false ;
        $thnak = isset( $request->thnak ) && strlen( $request->thnak ) > 0 ? trim($request->thnak) : false ;
        $rank_object = null ;
        if( $ank != false && $krobkhan != false && $rank != false && $thnak != false ){
            $rank_object = \App\Models\Officer\Rank::where([
                'ank' => $ank ,
                'krobkhan' => $krobkhan ,
                'rank' => $rank ,
                'thnak' => $thnak
            ])->first();
        }

        // Check whether the officer has been assigned a position yet
        $organizationStructurePosition = intval( $request->organization_structure_position_id ) > 0 ? \App\Models\Organization\OrganizationStructurePosition::find( $request->organization_structure_position_id ) : null ;
        $unOfficialPosition = intval( $request->unofficial_position_id ) > 0 ? \App\Models\Position\Position::find( $request->unofficial_position_id ) : null ;
        $position = $organizationStructurePosition != null && $organizationStructurePosition->position != null ? $organizationStructurePosition->position : null ;
        $organization = $organizationStructurePosition != null && $organizationStructurePosition->organizationStructure != null && $organizationStructurePosition->organizationStructure->organization != null ? $organizationStructurePosition->organizationStructure->organization : null ;

        $formattedDob = isset($request->dob) && strlen(trim((string) $request->dob)) > 0
            ? \Carbon\Carbon::parse($request->dob)->format('Y-m-d')
            : null;

        $email = isset($request->email) ? trim((string) $request->email) : '';
        $mobilePhone = isset($request->mobile_phone) ? trim((string) $request->mobile_phone) : '';

        $peopleWhereConditions = [
            'firstname' => $request->firstname ,
            'lastname' => $request->lastname ,
            'enfirstname' => $request->enfirstname ,
            'enlastname' => $request->enlastname ,
        ];
        $duplicateIdentityFieldCount = 0;
        if( isset($request->nid) && strlen(trim((string) $request->nid)) > 0 ){
            $peopleWhereConditions['nid'] = trim((string) $request->nid);
            $duplicateIdentityFieldCount++;
        }
        if( $formattedDob != null ){
            $peopleWhereConditions['dob'] = $formattedDob;
            $duplicateIdentityFieldCount++;
        }
        if( strlen( $mobilePhone ) > 0 ){
            $peopleWhereConditions['mobile_phone'] = $mobilePhone;
            $duplicateIdentityFieldCount++;
        }
        if( strlen( $email ) > 0 ){
            $peopleWhereConditions['email'] = $email;
            $duplicateIdentityFieldCount++;
        }
        $people = $duplicateIdentityFieldCount > 0
            ? \App\Models\People\People::where( $peopleWhereConditions )->first()
            : null;

        if( $people != null && $people->officer != null ){

            // អ្នកប្រើប្រាស់បានចុះឈ្មោះរួចរាល់ហើយ
            return response([
                    'message' => 'អ្នកកំពុងព្យាយាមបញ្ចូលព័ត៌មានដែលមានរួចហើយ ' . implode( " , " , [ ( $people->officer != null ? $people->officer->code : '' ) , $people->lastname , $people->firstname ] )
                ],
                500
            );
        }
        $user = \Auth::user() == null ? null : \Auth::user() ;
        /**
         * Create detail information of the owner of the account
         */
        if( $people == null ){
            $people = \App\Models\People\People::create([
                'public_key' => md5(
                    \Carbon\Carbon::now()->format('YmdHis') .
                    $request->enfirstname .
                    $request->enlastname .
                    $request->gender .
                    ($formattedDob ?? '') .
                    $request->nid .
                    $request->mobile_phone .
                    $request->office_phone
                ) ,

                'namekh' => $request->lastname.$request->firstname ,
                'namekhreverse' => $request->firstname.$request->lastname ,
                'nameen' => $request->enlastname.$request->enfirstname ,
                'nameenreverse' => $request->enfirstname.$request->enlastname ,

                'firstname' => $request->firstname ,
                'lastname' => $request->lastname ,
                'enfirstname' => $request->enfirstname ,
                'enlastname' => $request->enlastname ,
                'gender' => $request->gender ,
                'dob' => $formattedDob ,
                'nid' => $request->nid ,
                'nid_start_at' => isset($request->nid_start_at) && strlen(trim((string) $request->nid_start_at)) > 0
                    ? \Carbon\Carbon::parse($request->nid_start_at)->format('Y-m-d')
                    : null,
                'nid_expired_at' => isset($request->nid_expired_at) && strlen(trim((string) $request->nid_expired_at)) > 0
                    ? \Carbon\Carbon::parse($request->nid_expired_at)->format('Y-m-d')
                    : null,
                'marry_status' => $request->marry_status ,
                'mobile_phone' => $mobilePhone ,
                'office_phone' => $request->office_phone ,
                'email' => $email ,
                'body_condition' => intval($request->body_condition ?? 0),
                'body_condition_desp' => $request->body_condition_desp ?? '' ,
                'nationality' => $request->nationality ?? '' ,
                'national' => $request->national ?? '' ,
                'address' => $request->address ?? '' ,
                'address_province_id' => intval( $request->address_province_id ) > 0 ? intval( $request->address_province_id ) : 0 ,
                'address_district_id' => intval( $request->address_district_id ) > 0 ? intval( $request->address_district_id ) : 0 ,
                'address_commune_id' => intval( $request->address_commune_id ) > 0 ? intval( $request->address_commune_id ) : 0 ,
                'address_village_id' => intval( $request->address_village_id ) > 0 ? intval( $request->address_village_id ) : 0 ,
                'current_address' => $request->current_address ?? '' ,
                'current_address_province_id' => intval( $request->current_address_province_id ) > 0 ? intval( $request->current_address_province_id ) : 0 ,
                'current_address_district_id' => intval( $request->current_address_district_id ) > 0 ? intval( $request->current_address_district_id ) : 0 ,
                'current_address_commune_id' => intval( $request->current_address_commune_id ) > 0 ? intval( $request->current_address_commune_id ) : 0 ,
                'current_address_village_id' => intval( $request->current_address_village_id ) > 0 ? intval( $request->current_address_village_id ) : 0 ,
                'pob' => $request->pob ?? '' ,
                'education_level' => $request->education_level ?? '' ,
                'pob_province_id' => intval( $request->pob_province_id ) > 0 ? intval( $request->pob_province_id ) : 0 ,
                'pob_district_id' => intval( $request->pob_district_id ) > 0 ? intval( $request->pob_district_id ) : 0 ,
                'pob_commune_id' => intval( $request->pob_commune_id ) > 0 ? intval( $request->pob_commune_id ) : 0 ,
                'pob_village_id' => intval( $request->pob_village_id ) > 0 ? intval( $request->pob_village_id ) : 0 ,
                'created_by' => $user == null ? 0 : $user->id ,
                'updated_by' => $user == null ? 0 : $user->id ,
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if( strlen( $email ) <= 0 ){
            $people->update([
                'email' => strtolower( $request->enlastname.'.'.$request->enfirstname.str_pad( $people->id , 3 , '0' , STR_PAD_LEFT ).'@ocm.gov.kh' )
            ]);
        }

        if ($people->weddingCertificates->isEmpty()) {
            $people->weddingCertificates()->create([
                'people_id' => $people->id,
                'wedding_number' =>  '',
                'book_number' =>  '',
                'year' => '',
                'province_id' => 0,
                'district_id' => 0,
                'commune_id' => 0,
                'issued_date' => '',
                // 'issued_location' => $request->issued_location?? '' ,
                // 'signed_name' => $request->signed_name?? '' ,
                'pdf' => '',
                // spouse
                'spouse_id' => 0,
                'spouse_firstname' =>  '',
                'spouse_lastname' =>  '',
                'spouse_enfirstname' =>  '',
                'spouse_enlastname' =>  '',
                'spouse_nid' =>  '',
                'spouse_passport' =>  '',
                'spouse_profession' =>  '',
                'spouse_profession_organization' =>  '',
                'spouse_dob' => '',
                'spouse_nationality' =>  '',
                'spouse_national' =>  '',
                'spouse_pob' =>  '',
                'spouse_address' =>  '',
                'spouse_death' => 0,
                // spouse father
                'spouse_father_firstname' =>  '',
                'spouse_father_lastname' =>  '',
                'spouse_father_enfirstname' =>  '',
                'spouse_father_enlastname' =>  '',
                'spouse_father_dob' => '',
                'spouse_father_nationality' =>  '',
                'spouse_father_pob' =>  '',
                'spouse_father_death' => 0,
                // spouse mother
                'spouse_mother_firstname' =>  '',
                'spouse_mother_lastname' =>  '',
                'spouse_mother_enfirstname' =>  '',
                'spouse_mother_enlastname' =>  '',
                'spouse_mother_dob' => '',
                'spouse_mother_nationality' =>  '',
                'spouse_mother_pob' =>  '',
                'spouse_mother_death' => 0,

                'created_by' => \Auth::user()->id,
                'updated_by' => \Auth::user()->id,
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d')
            ]);
            \Log::info("Officer Wedding: " . $people->weddingCertificates);
        }
        $people->wedding_certificates = $people->weddingCertificates->map(function( $weddingCertificate ){
            $weddingCertificate->birthCertificates;

            return $weddingCertificate;
        }) ;

        $resolvedOrganizationId = $organization != null
            ? intval($organization->id)
            : (intval($request->organization_id) > 0 ? intval($request->organization_id) : 0);
        $resolvedPositionId = $position != null
            ? intval($position->id)
            : (intval($request->position_id) > 0 ? intval($request->position_id) : 0);
        $officerDate = isset($request->date) && strlen(trim((string) $request->date)) > 0
            ? \Carbon\Carbon::parse($request->date)->format('Y-m-d')
            : \Carbon\Carbon::now()->format('Y-m-d');
        $officerOfficialDate = isset($request->official_date) && strlen(trim((string) $request->official_date)) > 0
            ? \Carbon\Carbon::parse($request->official_date)->format('Y-m-d')
            : \Carbon\Carbon::now()->format('Y-m-d');
        $officerUnofficialDate = isset($request->unofficial_date) && strlen(trim((string) $request->unofficial_date)) > 0
            ? \Carbon\Carbon::parse($request->unofficial_date)->format('Y-m-d')
            : \Carbon\Carbon::now()->format('Y-m-d');

        /**
         * Create officer
         */
        $officer = $people->officers()->create([
            'public_key' => md5(
                \Carbon\Carbon::now()->format('YmdHis') .
                $request->code  .
                $people->id .
                $resolvedOrganizationId .
                $resolvedPositionId .
                $request->countesy_id .
                ($formattedDob ?? '')
            ),
            'date' => $officerDate ,
            // 'officer_type' => $request->officer_type?? '' ,
            'officer_type' => $request->ank?? '' ,
            'additional_officer_type' => $request->additional_officer_type?? '' ,
            'code' => strlen( $request->code ) > 0 ? $request->code : '' ,
            'organization_id' => $resolvedOrganizationId ,
            'position_id' => $resolvedPositionId ,
            'countesy_id' => $request->countesy_id?? 0  ,
            'rank_id' => $rank_object == null ? 0 : $rank_object->id ,
            'salary_rank' => $rank_object != null ? $rank_object->prefix : '' ,
            'code' => isset( $request->code ) && strlen( $request->code ) > 0 ? $request->code : '' ,
            'unofficial_date' => $officerUnofficialDate,
            'official_date' => $officerOfficialDate,
            'leader' => 0 ,
            'phone' => $people->officer_phone ?? $people->mobile_phone ,
            'passport' => $request->officer_passport ?? '' ,
            'email' => $request->officer_email ?? $people->email ,
            'created_by' => $user == null ? 0 : $user->id ,
            'updated_by' => $user == null ? 0 : $user->id ,
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ]);


        if ($organizationStructurePosition != null) {
            $officer->jobs()->create([
                'organization_structure_position_id' => $organizationStructurePosition->id ,
                'unofficial_position_id' => $unOfficialPosition == null ? 0 : $unOfficialPosition->id ,
                'officer_id' => $officer->id ,
                'countesy_id' => $request->countesy_id?? 0  ,
                'start' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                'end' => null ,
                'created_by' => $user == null ? 0 : $user->id ,
                'updated_by' => $user == null ? 0 : $user->id ,
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        // $card = $people->cards()->create([
        //     'number' => "OCM-". str_pad( $people->id , 4 , "0" , STR_PAD_LEFT ) ,
        //     'uuid' => md5( \Carbon\Carbon::now()->format('YmdHis') . $people->id ) ,
        //     'people_id' => $people->id ,
        //     'officer_id' => $officer->id ,
        //     'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
        //     'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        // ]);

        $account = $officer->user()->create([
            'firstname' => $people->firstname,
            'lastname' => $people->lastname,
            'email' => $people->email,
            'username' => $people->email,
            'active' => 0 ,
            'phone' => $people->mobile_phone ,
            'password' => bcrypt(
                $officer->phone != null && strlen( $officer->phone ) > 0
                    ? $officer->phone
                    : (
                        $people->mobile_phone != null && strlen( $people->mobile_phone ) > 0
                            ? $request->mobile_phone
                            : 'ocm@123456'
                    )
            ),
            'created_by' => $user == null ? 0 : $user->id ,
            'updated_by' => $user == null ? 0 : $user->id ,
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ]);

        $officer->update([
            'people_id' => $people->id ,
            'user_id' => $account->id ,
        ]);

        /**
         * Assign role
         */
        $backendMemberRole = \App\Models\Role::where('name','backend')->first();
        if( $backendMemberRole != null ){
            $account->roles()->sync([ $backendMemberRole->id ]);
        }
        $account->save();

        $officer->user ;
        $officer->organization;
        $officer->countesy;
        $officer->position;
        $officer->people;
        $officer->jobs;

        if( $officer->people != null ){
            $officer->people->weddingCertificates ;
            
        }

        return response()->json([
            'ok' => true ,
            'message' => 'បង្កើតបានជោគជ័យ !',
            'record' => $officer
        ], 200);
    }
    /**
     * Create an account
     */
    public function storeNonOfficer(Request $request){
        $validated = $request->validate([
            // 'nid' => 'required|unique:people|max:50' ,
            // 'code' => 'required|unique:officers|max:50',
            'organization_structure_position_id' => 'required',
            // 'organization_id' => 'required',
            // 'position_id' => 'required',
            'firstname' => 'required' ,
            'lastname' => 'required' ,
            'enfirstname' => 'required' ,
            'enlastname' => 'required'
        ]);

        // $validated = $request->validate([
        //     // 'nid' => 'required|unique:people|max:50',
        //     'firstname' => 'required' ,
        //     'lastname' => 'required' ,
        //     'enfirstname' => 'required' ,
        //     'enlastname' => 'required' ,
        //     'organization_id' => 'required',
        //     'position_id' => 'required',
        //     'countesy_id' => 'required'
        // ]);


        // Check the ranking of the officer
        $ank = isset( $request->ank ) && strlen( $request->ank ) > 0 ? trim($request->ank) : false ;
        $krobkhan = isset( $request->krobkhan ) && strlen( $request->krobkhan ) > 0 ? trim($request->krobkhan) : false ;
        $rank = isset( $request->rank ) && strlen( $request->rank ) > 0 ? trim($request->rank) : false ;
        $thnak = isset( $request->thnak ) && strlen( $request->thnak ) > 0 ? trim($request->thnak) : false ;
        $rank_object = null ;
        if( $ank != false && $krobkhan != false && $rank != false && $thnak != false ){
            $rank_object = \App\Models\Officer\Rank::where([
                'ank' => $ank ,
                'krobkhan' => $krobkhan ,
                'rank' => $rank ,
                'thnak' => $thnak
            ])->first();
        }

        // Check whether the officer has been assigned a position yet
        $organizationStructurePosition = intval( $request->organization_structure_position_id ) > 0 ? \App\Models\Organization\OrganizationStructurePosition::find( $request->organization_structure_position_id ) : null ;
        $unOfficialPosition = intval( $request->unofficial_position_id ) > 0 ? \App\Models\Position\Position::find( $request->unofficial_position_id ) : null ;
        $position = $organizationStructurePosition != null && $organizationStructurePosition->position != null ? $organizationStructurePosition->position : null ;
        $organization = $organizationStructurePosition != null && $organizationStructurePosition->organizationStructure != null && $organizationStructurePosition->organizationStructure->organization != null ? $organizationStructurePosition->organizationStructure->organization : null ;

        $peopleWhereConditions = [
            'nid' => $request->nid ,
            'firstname' => $request->firstname ,
            'lastname' => $request->lastname ,
            'enfirstname' => $request->enfirstname ,
            'enlastname' => $request->enlastname ,
            'dob' => \Carbon\Carbon::parse( $request->dob )->format('Y-m-d')
        ];
        if( strlen( $request->nid ) > 0 ) $peopleWhereConditions['nid'] = $request->nid ;
        if( strlen( $request->mobile_phone ) > 0 ) $peopleWhereConditions['mobile_phone'] = $request->mobile_phone ;
        if( strlen( $request->email ) > 0 ) $peopleWhereConditions['mobile_phone'] = $request->email ;
        $people = \App\Models\People\People::where( $peopleWhereConditions )->first();

        if( $people != null && $people->officer != null ){
            // អ្នកប្រើប្រាស់បានចុះឈ្មោះរួចរាល់ហើយ
            return response([
                    'message' => 'អ្នកកំពុងព្យាយាមបញ្ចូលព័ត៌មានដែលមានរួចហើយ ' . implode( " , " , [ ( $people->officer != null ? $people->officer->code : '' ) , $people->lastname , $people->firstname ] )
                ],
                500
            );
        }

        $user = \Auth::user() == null ? null : \Auth::user() ;
        if( $people == null ){
            /**
             * Create detail information of the owner of the account
             */
            $people = \App\Models\People\People::create([
            'public_key' => md5(
                \Carbon\Carbon::now()->format('YmdHis') .
                $request->enfirstname .
                $request->enlastname .
                $request->gender .
                \Carbon\Carbon::parse( $request->dob )->format( 'Y-m-d' ) .
                $request->nid .
                $request->mobile_phone .
                $request->office_phone
            ) ,

            'namekh' => $request->lastname.$request->firstname ,
            'namekhreverse' => $request->firstname.$request->lastname ,
            'nameen' => $request->enlastname.$request->enfirstname ,
            'nameenreverse' => $request->enfirstname.$request->enlastname ,

            'firstname' => $request->firstname ,
            'lastname' => $request->lastname ,
            'enfirstname' => $request->enfirstname ,
            'enlastname' => $request->enlastname ,
            'gender' => $request->gender ,
            'dob' => \Carbon\Carbon::parse( $request->dob )->format('Y-m-d') ,
            'nid' => $request->nid ,
            'marry_status' => $request->marry_status ,
            'mobile_phone' => $request->mobile_phone ?? '' ,
            'office_phone' => $request->office_phone ,
            'email' => $request->email ?? '' ,
            'address' => $request->address ?? '' ,
            'address_province_id' => intval( $request->address_province_id ) > 0 ? intval( $request->address_province_id ) : 0 ,
            'address_district_id' => intval( $request->address_district_id ) > 0 ? intval( $request->address_district_id ) : 0 ,
            'address_commune_id' => intval( $request->address_commune_id ) > 0 ? intval( $request->address_commune_id ) : 0 ,
            'address_village_id' => intval( $request->address_village_id ) > 0 ? intval( $request->address_village_id ) : 0 ,
            'current_address' => $request->current_address ?? '' ,
            'current_address_province_id' => intval( $request->current_address_province_id ) > 0 ? intval( $request->current_address_province_id ) : 0 ,
            'current_address_district_id' => intval( $request->current_address_district_id ) > 0 ? intval( $request->current_address_district_id ) : 0 ,
            'current_address_commune_id' => intval( $request->current_address_commune_id ) > 0 ? intval( $request->current_address_commune_id ) : 0 ,
            'current_address_village_id' => intval( $request->current_address_village_id ) > 0 ? intval( $request->current_address_village_id ) : 0 ,
            'pob' => $request->pob ?? '' ,
            'pob_province_id' => intval( $request->pob_province_id ) > 0 ? intval( $request->pob_province_id ) : 0 ,
            'pob_district_id' => intval( $request->pob_district_id ) > 0 ? intval( $request->pob_district_id ) : 0 ,
            'pob_commune_id' => intval( $request->pob_commune_id ) > 0 ? intval( $request->pob_commune_id ) : 0 ,
            'pob_village_id' => intval( $request->pob_village_id ) > 0 ? intval( $request->pob_village_id ) : 0 ,
            'body_condition' => $request->body_condition?? 0 ,
            'body_condition_desp' => $request->body_condition_desp??'' ,
            'nationality' => $request->nationality?? '' ,
            'national' => $request->national?? '' ,
            'created_by' => $user == null ? 0 : $user->id ,
            'updated_by' => $user == null ? 0 : $user->id ,
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if( strlen( $request->email ) <= 0 ){
            $people->update([
                'firstname' => $request->firstname ,
                'lastname' => $request->lastname ,
                'enfirstname' => $request->enfirstname ,
                'enlastname' => $request->enlastname ,
                'email' => strtolower( $request->enlastname.'.'.$request->enfirstname.str_pad( $people->id , 3 , '0' , STR_PAD_LEFT ).'@ocm.gov.kh' )
            ]);
        }

        /**
         * Create officer
         */
        $officer = $people->officers()->create([
            'public_key' => md5(
                \Carbon\Carbon::now()->format('YmdHis') .
                $request->code  .
                $people->id .
                $request->organization_id .
                $request->position_id .
                $request->countesy_id .
                \Carbon\Carbon::parse( $request->officer_dob )->format( 'Y-m-d' )
            ),
            'date' => isset( $request->date ) && strlen( $request->date ) > 0 ? \Carbon\Carbon::parse( $request->date )->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') ,
            'salary_rank' => $request->salary_rank?? 'ក.៣.៤' ,
            'officer_type' => $request->officer_type?? '' ,
            'additional_officer_type' => $request->additional_officer_type?? '' ,
            'code' => 'OCM-'.str_pad( $people->id.'-'.$people->officers->count() , 6 , '0' , STR_PAD_LEFT ) ,
            'organization_id' => $organization->id ,
            'position_id' => $position->id ,
            'countesy_id' => $request->countesy_id?? 0 ,
            'rank_id' => $rank_object == null ? 0 : $rank_object->id ,
            'unofficial_date' => strlen( $request->unofficial_date ) > 0 ? \Carbon\Carbon::parse( $request->unofficial_date )->format('Y-m-d') :\Carbon\Carbon::now()->format( 'Y-m-d' ),
            'official_date' => strlen( $request->official_date ) > 0 ? \Carbon\Carbon::parse( $request->official_date )->format('Y-m-d') :\Carbon\Carbon::now()->format( 'Y-m-d' ),
            'leader' => 0 ,
            'phone' => $people->officer_phone ?? $people->mobile_phone ,
            'passport' => $request->officer_passport ?? '' ,
            'email' => $request->officer_email ?? $people->email ,
            'created_by' => $user == null ? 0 : $user->id ,
            'updated_by' => $user == null ? 0 : $user->id ,
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ]);

        $officer->jobs()->create([
            'organization_structure_position_id' => $organizationStructurePosition->id ,
            'unofficial_position_id' => $unOfficialPosition == null ? 0 : $unOfficialPosition->id ,
            'officer_id' => $officer->id ,
            'countesy_id' => $request->countesy_id?? 0  ,
            'start' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
            'end' => null ,
            'created_by' => $user == null ? 0 : $user->id ,
            'updated_by' => $user == null ? 0 : $user->id ,
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ]);

        // $card = $people->cards()->create([
        //     'number' => "OCM-". str_pad( $people->id , 4 , "0" , STR_PAD_LEFT ) ,
        //     'uuid' => md5( \Carbon\Carbon::now()->format('YmdHis') . $people->id ) ,
        //     'people_id' => $people->id ,
        //     'officer_id' => $officer->id ,
        //     'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
        //     'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        // ]);

        $account = $officer->user()->create([
            'firstname' => $people->firstname,
            'lastname' => $people->lastname,
            'email' => $people->email,
            'username' => $people->email,
            'active' => 0 ,
            'phone' => $people->mobile_phone ,
            'password' => bcrypt(
                $officer->phone != null && strlen( $officer->phone ) > 0
                    ? $officer->phone
                    : (
                        $people->mobile_phone != null && strlen( $people->mobile_phone ) > 0
                            ? $request->mobile_phone
                            : 'ocm@123456'
                    )
            ),
            'created_by' => $user == null ? 0 : $user->id ,
            'updated_by' => $user == null ? 0 : $user->id ,
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ]);

        $officer->update([
            'people_id' => $people->id ,
            'user_id' => $account->id ,
        ]);

        /**
         * Assign role
         */
        $backendMemberRole = \App\Models\Role::where('name','backend')->first();
        if( $backendMemberRole != null ){
            $account->roles()->sync([ $backendMemberRole->id ]);
        }
        $account->save();

        $officer->user ;
        $officer->organization;
        $officer->countesy;
        $officer->position;
        $officer->people;
        $officer->jobs;

        if( $officer->people != null ){
            $officer->people->weddingCertificates ;
        }

        return response()->json([
            'ok' => true ,
            'message' => 'បង្កើតបានជោគជ័យ !'
        ], 200);
    }
    /**
     * Update Officer and its relavant data
     */
    public function update(Request $request){
        // Check whether the officer has been assigned a position yet
        $organizationStructurePosition = intval( $request->organization_structure_position_id ) > 0 ? \App\Models\Organization\OrganizationStructurePosition::find( $request->organization_structure_position_id ) : null ;
        $position = $organizationStructurePosition != null && $organizationStructurePosition->position != null ? $organizationStructurePosition->position : null ;
        $unofficialPosition = isset( $request->unofficial_position_id ) && intval( $request->unofficial_position_id ) > 0 ? \App\Models\Position\Position::find( $request->unofficial_position_id ) : null ;
        $organization = $organizationStructurePosition != null && $organizationStructurePosition->organizationStructure != null && $organizationStructurePosition->organizationStructure->organization != null ? $organizationStructurePosition->organizationStructure->organization : null ;
        $organization = intval( $request->organization_id ) > 0 ? \App\Models\Organization\Organization::find( $request->organization_id ) : null ;

        // Check the ranking of the officer
        $ank = isset( $request->ank ) && strlen( $request->ank ) > 0 ? trim($request->ank) : false ;
        $krobkhan = isset( $request->krobkhan ) && strlen( $request->krobkhan ) > 0 ? trim($request->krobkhan) : false ;
        // $rank = isset( $request->rank ) && strlen( $request->rank ) > 0 ? trim($request->rank) : false ;
        $thnak = isset( $request->thnak ) && strlen( $request->thnak ) > 0 ? trim($request->thnak) : false ;
        $rank_object = null ;
        // if( $ank != false && $krobkhan != false && $rank != false && $thnak != false ){
        //     $rank_object = \App\Models\Officer\Rank::where([
        //         'ank' => $ank ,
        //         'krobkhan' => $krobkhan ,
        //         'rank' => $rank ,
        //         'thnak' => $thnak
        //     ])->first();
        // }
        
        // return response()->json( [
        //         'code' => $request->code ,
        //         // 'organization_id' => $organization != null && intval( $organization->id ) > 0 ? $organization->id : null ,
        //         // 'position_id' => $position != null && intval( $position->id ) > 0 ? $position->id : null ,
        //         // 'rank_id' => $rank_object == null ? $officer->rank_id : $rank_object->id ,
        //         'rank_id' => $rank_object == null ? null : $rank_object->id ,
        //         'countesy_id' => intval( $request->countesy_id ) ,
        //         'passport' => $request->passport ,
        //         'email' => $request->email ,
        //         'phone' => $request->phone ,
        //         'unofficial_date' => strlen( $request->unofficial_date ) > 0 ? \Carbon\Carbon::parse( $request->unofficial_date )->format('Y-m-d') : '' ,
        //         'official_date' => strlen( $request->official_date ) > 0 ? \Carbon\Carbon::parse( $request->official_date )->format('Y-m-d') : '' ,
        //         'salary_rank' => $request->salary_rank?? ( $rank_object != null ? $rank_object->prefix : '' ) ,
        //         'officer_type' => $request->officer_type?? '' ,
        //         'additional_officer_type' => $request->additional_officer_type?? '' ,
        //         'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        //     ] );
        $user = \Auth::user() == null ? null : \Auth::user() ;
        $officer = intval( $request->id ) > 0 ? RecordModel::find( $request->id ) : null ;
        if( $request->people != null ){
            $officer->people->update([
                'firstname' => $request->people['firstname'] ,
                'lastname' => $request->people['lastname'] ,
                'enfirstname' => $request->people['enfirstname'] ,
                'enlastname' => $request->people['enlastname'] ,
                'gender' => intval($request->people['gender']) >= 0 ? intval( $request->people['gender'] ) :  1 ,
                'email' => $request->people['email'] ,
                'dob' => \Carbon\Carbon::parse( $request->people['dob'] )->format('Y-m-d') ,
                'nid' => $request->people['nid'] ,
                'mobile_phone' => $request->people['mobile_phone'] ,
                'office_phone' => $request->people['office_phone'] ,
                'marry_status' => $request->people['marry_status'] != null && $request->people['marry_status'] != '' ? $request->people['marry_status'] : 'single' ,
                'address' => isset( $request->people['address'] ) ? $request->people['address'] : '' ,
                'address_province_id' => isset( $request->people['address_province_id'] ) && intval( $request->people['address_province_id'] ) > 0 ? intval( $request->people['address_province_id'] ) : 0 ,
                'address_district_id' => isset( $request->people['address_district_id'] ) && intval( $request->people['address_district_id'] ) > 0 ? intval( $request->people['address_district_id'] ) : 0 ,
                'address_commune_id' => isset( $request->people['address_commune_id'] ) && intval( $request->people['address_commune_id'] ) > 0 ? intval( $request->people['address_commune_id'] ) : 0 ,
                'address_village_id' => isset( $request->people['address_village_id'] ) && intval( $request->people['address_village_id'] ) > 0 ? intval( $request->people['address_village_id'] ) : 0 ,
                'current_address' => $request->people['current_address'] ?? '' ,
                'current_address_province_id' => isset( $request->people['current_address_province_id'] ) && intval( $request->people['current_address_province_id'] ) > 0 ? intval( $request->people['current_address_province_id'] ) : 0 ,
                'current_address_district_id' => isset( $request->people['current_address_district_id'] ) && intval( $request->people['current_address_district_id'] ) > 0 ? intval( $request->people['current_address_district_id'] ) : 0 ,
                'current_address_commune_id' => isset( $request->people['current_address_commune_id'] ) && intval( $request->people['current_address_commune_id'] ) > 0 ? intval( $request->people['current_address_commune_id'] ) : 0 ,
                'current_address_village_id' => isset( $request->people['current_address_village_id'] ) && intval( $request->people['current_address_village_id'] ) > 0 ? intval( $request->people['current_address_village_id'] ) : 0 ,
                'pob' => $request->people['pob'] ?? '' ,
                'education_level' => $request->people['education_level'] ?? ( $request->education_level ?? '' ) ,
                'pob_province_id' => isset( $request->people['pob_province_id'] ) && intval( $request->people['pob_province_id'] ) > 0 ? intval( $request->people['pob_province_id'] ) : 0 ,
                'pob_district_id' => isset( $request->people['pob_district_id'] ) && intval( $request->people['pob_district_id'] ) > 0 ? intval( $request->people['pob_district_id'] ) : 0 ,
                'pob_commune_id' => isset( $request->people['pob_commune_id'] ) && intval( $request->people['pob_commune_id'] ) > 0 ? intval( $request->people['pob_commune_id'] ) : 0 ,
                'pob_village_id' => isset( $request->people['pob_village_id'] ) && intval( $request->people['pob_village_id'] ) > 0 ? intval( $request->people['pob_village_id'] ) : 0 ,
                'body_condition' => intval( $request->people['body_condition'] ) ,
                'body_condition_desp' => $request->people['body_condition_desp']??'' ,
                'nationality' => $request->people['nationality'] ?? '' ,
                'national' => $request->people['national'] ?? '' ,
                // father
                'father_firstname' => $request->people['father_firstname'] ?? '' ,
                'father_lastname' => $request->people['father_lastname'] ?? '' ,
                'father_enfirstname' => $request->people['father_enfirstname'] ?? '' ,
                'father_enlastname' => $request->people['father_enlastname'] ?? '' ,
                'father_dob' => $request->people['father_dob'] ?? '' ,
                'father_nationality' => $request->people['father_nationality'] ?? '' ,
                'father_national' => $request->people['father_national'] ?? '' ,
                'father_nid' => $request->people['father_nid'] ?? '' ,
                'father_pob' => $request->people['father_pob'] ?? '' ,
                'father_address' => $request->people['father_address'] ?? '' ,
                'father_address_province_id' => isset( $request->people['father_address_province_id'] ) && intval( $request->people['father_address_province_id'] ) > 0 ? intval( $request->people['father_address_province_id'] ) : 0 ,
                'father_address_district_id' => isset( $request->people['father_address_district_id'] ) && intval( $request->people['father_address_district_id'] ) > 0 ? intval( $request->people['father_address_district_id'] ) : 0 ,
                'father_address_commune_id' => isset( $request->people['father_address_commune_id'] ) && intval( $request->people['father_address_commune_id'] ) > 0 ? intval( $request->people['father_address_commune_id'] ) : 0 ,
                'father_address_village_id' => isset( $request->people['father_address_village_id'] ) && intval( $request->people['father_address_village_id'] ) > 0 ? intval( $request->people['father_address_village_id'] ) : 0 ,
                'father_death' => array_key_exists('father_death', $request->people)
                    ? intval($request->people['father_death'] ?? 0)
                    : intval($people->father_death ?? 0),
                'father_profession' => $request->people['father_profession'] ?? '' ,
                // mother
                'mother_firstname' => $request->people['mother_firstname'] ?? '' ,
                'mother_lastname' => $request->people['mother_lastname'] ?? '' ,
                'mother_enfirstname' => $request->people['mother_enfirstname'] ?? '' ,
                'mother_enlastname' => $request->people['mother_enlastname'] ?? '' ,
                'mother_dob' => $request->people['mother_dob'] ?? '' ,
                'mother_nationality' => $request->people['mother_nationality'] ?? '' ,
                'mother_national' => $request->people['mother_national'] ?? '' ,
                'mother_nid' => $request->people['mother_nid'] ?? '' ,
                'mother_pob' => $request->people['mother_pob'] ?? '' ,
                'mother_address' => $request->people['mother_address'] ?? '' ,
                'mother_address_province_id' => isset( $request->people['mother_address_province_id'] ) && intval( $request->people['mother_address_province_id'] ) > 0 ? intval( $request->people['mother_address_province_id'] ) : 0 ,
                'mother_address_district_id' => isset( $request->people['mother_address_district_id'] ) && intval( $request->people['mother_address_district_id'] ) > 0 ? intval( $request->people['mother_address_district_id'] ) : 0 ,
                'mother_address_commune_id' => isset( $request->people['mother_address_commune_id'] ) && intval( $request->people['mother_address_commune_id'] ) > 0 ? intval( $request->people['mother_address_commune_id'] ) : 0 ,
                'mother_address_village_id' => isset( $request->people['mother_address_village_id'] ) && intval( $request->people['mother_address_village_id'] ) > 0 ? intval( $request->people['mother_address_village_id'] ) : 0 ,
                'mother_death' => array_key_exists('mother_death', $request->people)
                    ? intval($request->people['mother_death'] ?? 0)
                    : intval($people->mother_death ?? 0),
                'mother_profession' => $request->people['mother_profession'] ?? '' ,
                // Emergency
                 'emergency_lastname' => array_key_exists('emergency_lastname', $request->people)
                    ? ($request->people['emergency_lastname'] ?? '')
                    : ($people->emergency_lastname ?? ''),
                'emergency_firstname' => array_key_exists('emergency_firstname', $request->people)
                    ? ($request->people['emergency_firstname'] ?? '')
                    : ($people->emergency_firstname ?? ''),
                'emergency_gender' => array_key_exists('emergency_gender', $request->people)
                    ? intval($request->people['emergency_gender'] ?? 0)
                    : intval($people->emergency_gender ?? 0),
                'emergency_relationship' => array_key_exists('emergency_relationship', $request->people)
                    ? ($request->people['emergency_relationship'] ?? '')
                    : ($people->emergency_relationship ?? ''),
                'emergency_profession' => array_key_exists('emergency_profession', $request->people)
                    ? ($request->people['emergency_profession'] ?? '')
                    : ($people->emergency_profession ?? ''),
                'emergency_phone' => array_key_exists('emergency_phone', $request->people)
                    ? ($request->people['emergency_phone'] ?? '')
                    : ($people->emergency_phone ?? ''),
                'emergency_email' => array_key_exists('emergency_email', $request->people)
                    ? ($request->people['emergency_email'] ?? '')
                    : ($people->emergency_email ?? ''),
                'emergency_address' => array_key_exists('emergency_address', $request->people)
                    ? ($request->people['emergency_address'] ?? '')
                    : ($people->emergency_address ?? ''),
                'emergency_address_province_id' => isset( $request->people['emergency_address_province_id'] ) && intval( $request->people['emergency_address_province_id'] ) > 0 ? $request->people['emergency_address_province_id'] : 0 ,
                'emergency_address_district_id' => isset( $request->people['emergency_address_district_id'] ) && intval( $request->people['emergency_address_district_id'] ) > 0 ? $request->people['emergency_address_district_id'] : 0 ,
                'emergency_address_commune_id' => isset( $request->people['emergency_address_commune_id'] ) && intval( $request->people['emergency_address_commune_id'] ) > 0 ? $request->people['emergency_address_commune_id'] : 0 ,
                'emergency_address_village_id' => isset( $request->people['emergency_address_village_id'] ) && intval( $request->people['emergency_address_village_id'] ) > 0 ? $request->people['emergency_address_village_id'] : 0 ,
                'updated_by' => $user == null ? 0 : $user->id ,
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        $whereCondition = [
                'code' => $request->code ,
                // 'organization_id' => $organization != null && intval( $organization->id ) > 0 ? $organization->id : null ,
                // 'position_id' => $position != null && intval( $position->id ) > 0 ? $position->id : null ,
                // 'rank_id' => $rank_object == null ? $officer->rank_id : $rank_object->id ,
                'rank_id' => $rank_object == null ? $officer->rank_id : $rank_object->id ,
                'salary_rank' => $rank_object != null ? $rank_object->prefix : $officer->salary_rank ,
                'code' => isset( $request->code ) && strlen( $request->code ) > 0 ? $request->code : '' ,
                'countesy_id' => intval( $request->countesy_id ) ,
                'passport' => $request->passport ,
                'email' => $request->email ,
                'phone' => $request->phone ,
                'unofficial_date' => strlen( $request->unofficial_date ) > 0 ? \Carbon\Carbon::parse( $request->unofficial_date )->format('Y-m-d') : '' ,
                'official_date' => strlen( $request->official_date ) > 0 ? \Carbon\Carbon::parse( $request->official_date )->format('Y-m-d') : '' ,
                'officer_type' => $request->officer_type?? '' ,
                'additional_officer_type' => $request->additional_officer_type?? '' ,
                'updated_by' => $user == null ? 0 : $user->id ,
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ];
        $officer->update( $whereCondition );

        $currentJob = $officer->getCurrentJob();
        if( $organizationStructurePosition != null ){
            if( $currentJob == null ){
                $currentJob = $officer->jobs()->create([
                    'organization_structure_position_id' => $organizationStructurePosition->id ,
                    'unofficial_position_id' => $unofficialPosition == null ? 0 : $unofficialPosition->id ,
                    'officer_id' => $officer->id ,
                    'countesy_id' => intval( $request->countesy_id ) ,
                    'start' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                    'end' => null ,
                    'created_by' => $user == null ? 0 : $user->id ,
                    'updated_by' => $user == null ? 0 : $user->id ,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ]);
            }else{
                $currentJob->update([
                    'organization_structure_position_id' => $organizationStructurePosition->id ,
                    'unofficial_position_id' => $unofficialPosition == null ? 0 : $unofficialPosition->id ,
                    'countesy_id' => intval( $request->countesy_id ) > 0 ? intval( $request->countesy_id ) : $currentJob->countesy_id ,
                    'updated_by' => $user == null ? 0 : $user->id ,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ]);
            }
        }

        $officer->user ;
        $officer->organization;
        $officer->countesy;
        $officer->currentJobs;
        $officer->position;
        $officer->people;
        $officer->rank;
        $officer->jobs;

        $job = $officer == null ? null : $officer->getCurrentJob() ;
        if( $job != null && $job->organizationStructurePosition != null ){
            $job->organizationStructurePosition->position;
            if( $job->organizationStructurePosition->organizationStructure != null ){
                $job->organizationStructurePosition->organizationStructure->organization;
            }
        }
        $officer->current_job = $job ;


        if( $officer->people != null ){
            $officer->people->weddingCertificates ;
        }

        return response()->json([
            'record' => $officer ,
            'message' => 'កែប្រែព័ត៌មានរួចរាល់ !' ,
            'ok' => true
        ], 200);
    }
    public function updateOfficerProfile(Request $request){
        // Check whether the officer has been assigned a position yet
        $organizationStructurePosition = intval( $request->organization_structure_position_id ) > 0 ? \App\Models\Organization\OrganizationStructurePosition::find( $request->organization_structure_position_id ) : null ;
        $position = $organizationStructurePosition != null && $organizationStructurePosition->position != null ? $organizationStructurePosition->position : null ;
        $unofficialPosition = isset( $request->unofficial_position_id ) && intval( $request->unofficial_position_id ) > 0 ? \App\Models\Position\Position::find( $request->unofficial_position_id ) : null ;
        $organization = $organizationStructurePosition != null && $organizationStructurePosition->organizationStructure != null && $organizationStructurePosition->organizationStructure->organization != null ? $organizationStructurePosition->organizationStructure->organization : null ;
        // $organization = intval( $request->organization_id ) > 0 ? \App\Models\Organization\Organization::find( $request->organization_id ) : null ;

        // Check the ranking of the officer
        $ank = isset( $request->ank ) && strlen( $request->ank ) > 0 ? trim($request->ank) : false ;
        $krobkhan = isset( $request->krobkhan ) && strlen( $request->krobkhan ) > 0 ? trim($request->krobkhan) : false ;
        $rank = isset( $request->rank ) && strlen( $request->rank ) > 0 ? trim($request->rank) : false ;
        $thnak = isset( $request->thnak ) && strlen( $request->thnak ) > 0 ? trim($request->thnak) : false ;
        $rank_object = null ;
        if( $ank != false && $krobkhan != false && $rank != false && $thnak != false ){
            $rank_object = \App\Models\Officer\Rank::where([
                'ank' => $ank ,
                'krobkhan' => $krobkhan ,
                'rank' => $rank ,
                'thnak' => $thnak
            ])->first();
        }
        $user = \Auth::user() == null ? null : \Auth::user() ;
        $officer = intval( $request->id ) > 0 ? RecordModel::find( $request->id ) : null ;
        if( $officer == null ){
            return response()->json([
                [
                    'ok' => true ,
                    'message' => 'មន្ត្រីមិនមានក្នុងប្រព័ន្ធ។'
                ],
                500
            ]);
        }
        if( $request->people != null ){
            /**
             * Update fields of table by one by one and check it value
             */
            /**
             * String type
             */
            $stringFields = [
                // Office People Profile
                'firstname','lastname','enfirstname','enlastname','email','nid','mobile_phone','office_phone','address','current_address','pob','body_condition','body_condition_description','nationality','national' ,
                // Father
                'father_firstname','father_lastname','father_enfirstname','father_enlastname','father_nationality','father_national','father_nid','father_pob','father_address','father_death','father_profession',
                // Mother
                'mother_firstname','mother_lastname','mother_enfirstname','mother_enlastname','mother_nationality','mother_national','mother_nid','mother_pob','mother_address','mother_death','mother_profession',
                // Emergency
                'emergency_lastname','emergency_firstname','emergency_gender','emergency_relationship','emergency_profession','emergency_phone','emergency_email','emergency_address',
                ];
            foreach( $stringFields as $field ){
                if( isset( $request->people[ $field ] ) && strlen( $request->people[ $field ] ) > 0 ){
                    $officer->people->$field = $request->people[$field];
                }
            }
            $officer->people->save();
            /**
             * Date type
             */
            $dateFields = ['dob','father_dob','mother_dob'];
            foreach( $dateFields as $field ){
                if( isset( $request->people[ $field ] ) && strlen( $request->people[ $field ] ) > 0 ){
                    $officer->people->$field = \Carbon\Carbon::parse( $request->people[$field] )->format('Y-m-d');
                }
            }
            $officer->people->save();
            /**
             * Number type
             */

            $officer->people->update([
                // User Profile Background
                'gender' => intval($request->people['gender']) >= 0 ? intval( $request->people['gender'] ) :  1 ,
                'marry_status' => $request->people['marry_status'] != null && $request->people['marry_status'] != '' ? $request->people['marry_status'] : 'single' ,

                'address_province_id' => isset( $request->people['address_province_id'] ) && intval( $request->people['address_province_id'] ) > 0 ? intval( $request->people['address_province_id'] ) : 0 ,
                'address_district_id' => isset( $request->people['address_district_id'] ) && intval( $request->people['address_district_id'] ) > 0 ? intval( $request->people['address_district_id'] ) : 0 ,
                'address_commune_id' => isset( $request->people['address_commune_id'] ) && intval( $request->people['address_commune_id'] ) > 0 ? intval( $request->people['address_commune_id'] ) : 0 ,
                'address_village_id' => isset( $request->people['address_village_id'] ) && intval( $request->people['address_village_id'] ) > 0 ? intval( $request->people['address_village_id'] ) : 0 ,

                'current_address_province_id' => isset( $request->people['current_address_province_id'] ) && intval( $request->people['current_address_province_id'] ) > 0 ? intval( $request->people['current_address_province_id'] ) : 0 ,
                'current_address_district_id' => isset( $request->people['current_address_district_id'] ) && intval( $request->people['current_address_district_id'] ) > 0 ? intval( $request->people['current_address_district_id'] ) : 0 ,
                'current_address_commune_id' => isset( $request->people['current_address_commune_id'] ) && intval( $request->people['current_address_commune_id'] ) > 0 ? intval( $request->people['current_address_commune_id'] ) : 0 ,
                'current_address_village_id' => isset( $request->people['current_address_village_id'] ) && intval( $request->people['current_address_village_id'] ) > 0 ? intval( $request->people['current_address_village_id'] ) : 0 ,

                'pob_province_id' => isset( $request->people['pob_province_id'] ) && intval( $request->people['pob_province_id'] ) > 0 ? intval( $request->people['pob_province_id'] ) : 0 ,
                'pob_district_id' => isset( $request->people['pob_district_id'] ) && intval( $request->people['pob_district_id'] ) > 0 ? intval( $request->people['pob_district_id'] ) : 0 ,
                'pob_commune_id' => isset( $request->people['pob_commune_id'] ) && intval( $request->people['pob_commune_id'] ) > 0 ? intval( $request->people['pob_commune_id'] ) : 0 ,
                'pob_village_id' => isset( $request->people['pob_village_id'] ) && intval( $request->people['pob_village_id'] ) > 0 ? intval( $request->people['pob_village_id'] ) : 0 ,

                // father
                'father_address_province_id' => isset( $request->people['father_address_province_id'] ) && intval( $request->people['father_address_province_id'] ) > 0 ? intval( $request->people['father_address_province_id'] ) : 0 ,
                'father_address_district_id' => isset( $request->people['father_address_district_id'] ) && intval( $request->people['father_address_district_id'] ) > 0 ? intval( $request->people['father_address_district_id'] ) : 0 ,
                'father_address_commune_id' => isset( $request->people['father_address_commune_id'] ) && intval( $request->people['father_address_commune_id'] ) > 0 ? intval( $request->people['father_address_commune_id'] ) : 0 ,
                'father_address_village_id' => isset( $request->people['father_address_village_id'] ) && intval( $request->people['father_address_village_id'] ) > 0 ? intval( $request->people['father_address_village_id'] ) : 0 ,

                // mother
                'mother_address_province_id' => isset( $request->people['mother_address_province_id'] ) && intval( $request->people['mother_address_province_id'] ) > 0 ? intval( $request->people['mother_address_province_id'] ) : 0 ,
                'mother_address_district_id' => isset( $request->people['mother_address_district_id'] ) && intval( $request->people['mother_address_district_id'] ) > 0 ? intval( $request->people['mother_address_district_id'] ) : 0 ,
                'mother_address_commune_id' => isset( $request->people['mother_address_commune_id'] ) && intval( $request->people['mother_address_commune_id'] ) > 0 ? intval( $request->people['mother_address_commune_id'] ) : 0 ,
                'mother_address_village_id' => isset( $request->people['mother_address_village_id'] ) && intval( $request->people['mother_address_village_id'] ) > 0 ? intval( $request->people['mother_address_village_id'] ) : 0 ,

                // Emergency
                'emergency_address_province_id' => isset( $request->people['emergency_address_province_id'] ) && intval( $request->people['emergency_address_province_id'] ) > 0 ? $request->people['emergency_address_province_id'] : 0 ,
                'emergency_address_district_id' => isset( $request->people['emergency_address_district_id'] ) && intval( $request->people['emergency_address_district_id'] ) > 0 ? $request->people['emergency_address_district_id'] : 0 ,
                'emergency_address_commune_id' => isset( $request->people['emergency_address_commune_id'] ) && intval( $request->people['emergency_address_commune_id'] ) > 0 ? $request->people['emergency_address_commune_id'] : 0 ,
                'emergency_address_village_id' => isset( $request->people['emergency_address_village_id'] ) && intval( $request->people['emergency_address_village_id'] ) > 0 ? $request->people['emergency_address_village_id'] : 0 ,

                'updated_by' => $user == null ? 0 : $user->id ,
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        // Update Officer Information

        /**
         * Officer Rank should not be updated this way
         * It should be update in the way of
         * 1. Promote Upgrade
         * 2. Promote Downgrade
         * And the record shall be kept as history timeline
         * and new record shall created with in the officer_jobs
         * also the reference file (pdf) shall with attached with as an evidence
         */

        $officer->update( [
            'code' => strlen( $request->code ) > 0 ? $request->code : $officer->code ,
            'countesy_id' => intval( $request->countesy_id ) > 0 ? intval( $request->countesy_id ) : 0 ,
            'passport' => strlen( $request->passport ) > 0 ? $request->passport : $officer->passport ,
            'email' => strlen( $request->email ) > 0 ? $request->email : $officer->email ,
            'phone' => strlen( $request->phone ) > 0 ? $request->phone : $officer->phone ,
            'unofficial_date' => strlen( $request->unofficial_date ) > 0 ? \Carbon\Carbon::parse( $request->unofficial_date )->format('Y-m-d') : $officer->unofficial_date ,
            'official_date' => strlen( $request->official_date ) > 0 ? \Carbon\Carbon::parse( $request->official_date )->format('Y-m-d') : $officer->official_date ,
            'officer_type' => strlen( $request->officer_type ) > 0 ? $request->officer_type : $officer->officer_type ,
            'additional_officer_type' => strlen( $request->additional_officer_type ) > 0 ? $request->additional_officer_type : $officer->additional_officer_type ,
            'updated_by' => $user == null ? 0 : $user->id ,
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ] );

        $officer->user ;
        $officer->organization;
        $officer->countesy;
        $officer->currentJobs;
        $officer->position;
        if( $officer->people ) {
            // $officer->people->birthCertificates;
            $officer->people->selfBirthCertificates = $officer->people->selfBirthCertificates()->get();
            $officer->people->weddingCertificates;
        }
        $officer->rank;
        $officer->jobs;

        $job = $officer->getCurrentJob();
        if( $job != null && $job->organizationStructurePosition != null ){
            $job->organizationStructurePosition->position;
            if( $job->organizationStructurePosition->organizationStructure != null ){
                $job->organizationStructurePosition->organizationStructure->organization;
            }
        }
        $officer->current_job = $job ;

        return response()->json([
            'record' => $officer ,
            'message' => 'កែប្រែព័ត៌មានរួចរាល់ !' ,
            'ok' => true
        ], 200);

    }
    /**
     * Active function of the account
     */
    public function active(Request $request){
        $user = RecordModel::find($request->id) ;
        if( $user ){
            $user->active = $request->active ;
            $user->save();
            // User does exists
            return response([
                'user' => $user ,
                'ok' => true ,
                'message' => 'គណនី '.$user->name.' បានបើកដោយជោគជ័យ !'
                ],
                200
            );
        }else{
            // User does not exists
            return response([
                'user' => null ,
                'ok' => false ,
                'message' => 'សូមទោស គណនីនេះមិនមានទេ !'
                ],
                201
            );
        }
    }
    /**
     * Function delete an account
     */
    public function destroy(Request $request){
        $officer = RecordModel::find($request->id) ;
        if( $officer ){
            if( $officer->user != null ){
                $officer->user->deleted_at = \Carbon\Carbon::now() ;
                $officer->user->save();
            }
            if( $officer->people != null ){
                $officer->people->deleted_at = \Carbon\Carbon::now() ;
                $officer->people->save();
            }
            return response([
                'ok' => true ,
                'officer' => $officer ,
                'message' => 'បានលុបដោយជោគជ័យ !' ,
                'ok' => true
            ],200
            );
        }else{
            // User does not exists
            return response([
                'ok' => false ,
                'user' => null ,
                'message' => 'សូមទោស ព័ត៌មាននេះមិនមានទេ !' ],
                201
            );
        }
    }
    public function read(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់គណនី។'
            ],422);
        }
        $record = RecordModel::find($request->id);
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ស្វែងរកគណនីមិនឃើញឡើយ។'
            ],403);
        }

        $record->user;
        $record->card;
        $record->countesy;
        $record->position;
        $record->organization;
        $record->people;

        if( $record->people != null ){
            $record->people->weddingCertificates ;
        }

        $record->job = $record->getCurrentJob();
        if( $record->job != null && $record->job->organizationStructurePosition != null ){
            $record->job->organizationStructurePosition->position;
            $record->job->organizationStructurePosition->permissions;
            if( $record->job->organizationStructurePosition->organizationStructure != null ){
                $record->job->organizationStructurePosition->organizationStructure->organization;
            }
        }
        $record->image = $record->image != null && trim($record->image ) != "" && \Storage::disk('public')->exists( $record->image )
            ? \Storage::disk('public')->url( $record->image )
            : (
                $record->user != null && $record->user->avatar_url != null && trim($record->user->avatar_url) != "" && \Storage::disk('public')->exists( $record->user->avatar_url )
                ? \Storage::disk('public')->url( $record->user->avatar_url )
                : false
            );
        return response()->json([
            'record' => $record ,
            'ok' => true ,
            'message' => 'រួចរាល់'
        ],200);
    }
    public function readPublic(Request $request){

        if( !isset( $request->key ) || strlen( $request->key ) <= 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់គណនី។'
            ],422);
        }

        $record = RecordModel::where( 'public_key' , $request->key )->first();
        if( $record==null && ( isset( $request->key ) || strlen( $request->key ) > 0 ) ){
            $record = RecordModel::find( $request->key );
        }

        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ស្វែងរកគណនីមិនឃើញឡើយ។'
            ],403);
        }

        $record->user;
        $record->card;
        $record->countesy;
        $record->position;
        $record->organization;
        $record->people;

        if( $record->people != null ){
            $record->people->weddingCertificates ;
        }

        $record->job = $record->getCurrentJob();
        if( $record->job != null && $record->job->organizationStructurePosition != null ){
            $record->job->organizationStructurePosition->position;
            $record->job->organizationStructurePosition->permissions;
            if( $record->job->organizationStructurePosition->organizationStructure != null ){
                $record->job->organizationStructurePosition->organizationStructure->organization;
            }
        }
        $record->image = $record->image != null && trim($record->image ) != "" && \Storage::disk('public')->exists( $record->image )
            ? \Storage::disk('public')->url( $record->image )
            : (
                $record->user != null && $record->user->avatar_url != null && trim($record->user->avatar_url) != "" && \Storage::disk('public')->exists( $record->user->avatar_url )
                ? \Storage::disk('public')->url( $record->user->avatar_url )
                : false
            );

        return response()->json([
            'record' => $record ,
            'ok' => true ,
            'message' => 'រួចរាល់'
        ],200);
    }
    public function publicPhoto(Request $request){

        if( !isset( $request->key ) || strlen( $request->key ) <= 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់គណនី។'
            ],422);
        }

        $record = RecordModel::where( 'public_key' , $request->key )->first();
        if( $record==null && ( isset( $request->key ) || strlen( $request->key ) > 0 ) ){
            $record = RecordModel::find( $request->key );
        }

        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ស្វែងរកគណនីមិនឃើញឡើយ។'
            ],403);
        }

        $record->user;
        $record->people;

        $imageBase64 = false ;
        $imagePath = $record->image != null && \Storage::disk('public')->exists($record->image )
            ? \Storage::path( 'public/'. $record->image )
            : (
                isset( $record->user ) && $record->user->avatar_url != null && \Storage::disk('public')->exists( $record->user->avatar_url )
                ? \Storage::path( 'public/'. $record->user->avatar_url )
                : (
                    isset( $record->people ) && $record->people->image != null && \Storage::disk('public')->exists( $record->people->image )
                    ? \Storage::path( 'public/'. $record->people->image )
                    : false
                )
            );
        if( $imagePath != false && strlen( $imagePath ) ){
            try{
                $image = new ImageResize( $imagePath );
                // $image->scale(50);
                $imageBase64 = base64_encode( $image->getImageAsString(IMAGETYPE_PNG, 10) );
            } catch (ImageResizeException $e) {
                echo "Something went wrong" . $e->getMessage();
            }
        }

        return response()->json([
            'photo' => $imageBase64 ,
            'ok' => true ,
            'message' => 'រួចរាល់'
        ],200);
    }
    // public function upload(Request $request){
    //     $user = \Auth::user();
    //     if( $user ){
    //         if( isset( $_FILES['files']['tmp_name'] ) && $_FILES['files']['tmp_name'] != "" ) {
    //             if( ( $user = RecordModel::find($request->id) ) !== null ){
    //                 $uniqeName = Storage::disk('public')->putFile( 'avatars/'.$user->id , new File( $_FILES['files']['tmp_name'] ) );
    //                 $user->avatar_url = $uniqeName ;
    //                 $user->save();
    //                 if( Storage::disk('public')->exists( $user->avatar_url ) ){
    //                     $user->avatar_url = Storage::disk('public')->url( $user->avatar_url  );
    //                     return response([
    //                         'record' => $user ,
    //                         'message' => 'ជោគជ័យក្នុងការបញ្ចូលរូបថត។'
    //                     ],200);
    //                 }else{
    //                     return response([
    //                         'record' => $user ,
    //                         'message' => 'គណនីនេះមិនមានរូបថតឡើយ។'
    //                     ],403);
    //                 }
    //             }else{
    //                 return response([
    //                     'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់របស់គណនី។'
    //                 ],403);
    //             }
    //         }else{
    //             return response([
    //                 'result' => $_FILES ,
    //                 'message' => 'មានបញ្ហាជាមួយរូបភាពដែលអ្នកបញ្ជូនមក។'
    //             ],403);
    //         }

    //     }else{
    //         return response([
    //             'message' => 'សូមចូលប្រព័ន្ធជាមុនសិន។'
    //         ],403);
    //     }
    // }
    /**
     * Active function of the account
     */
    public function updateOrganizationCode(Request $request){
        $organization = intval( $request->organization_id ) > 0 ? \App\Models\Organization\Organization::find($request->organization_id) : null ;
        if( $organization == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អង្គភាព។'
            ],403);
        }
        $people = intval( $request->people_id ) > 0 ? \App\Models\People\People::find($request->people_id) : null ;
        if( $people == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់មន្ត្រីក្នុងអង្គភាព។'
            ],403);
        }
        $organizationPeople = \App\Models\Organization\OrganizationPeople::where('organization_id',$organization->id)
        ->where('people_id',$people->id)->first();
        if( $organizationPeople == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'មន្ត្រីនេះមិនស្ថិតក្នុងអង្គភាពនេះឡើយ។'
            ],403);
        }
        $organizationPeople->code = $request->code ;
        $organizationPeople->save();
        // User does exists
        return response([
            'record' => $organizationPeople ,
            'ok' => true ,
            'message' => 'ជោគជ័យ !'
        ], 200);
    }
    public function updateWorkHistory(Request $request){
        $validated = $request->validate([
            'id' => 'required|integer|exists:officers,id',
            'unofficial_date' => 'required|date',
            'official_date' => 'nullable|date|after_or_equal:unofficial_date',
        ]);

        $officer = RecordModel::find($validated['id']);
        if( $officer == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'Officer not found.'
            ],404);
        }

        $officer->unofficial_date = \Carbon\Carbon::parse($validated['unofficial_date'])->format('Y-m-d');

        if( $request->exists('official_date') ){
            $officer->official_date = $validated['official_date'] != null
                ? \Carbon\Carbon::parse($validated['official_date'])->format('Y-m-d')
                : null;
        }

        $officer->save();

        return response()->json([
            'record' => $officer,
            'ok' => true,
            'message' => 'Work history updated.'
        ],200);
    }
    public function mybackground(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់គណនី។'
            ],422);
        }
        $record = RecordModel::find($request->id);
        if( $record == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ស្វែងរកគណនីមិនឃើញឡើយ។'
            ],403);
        }


        $record->user;
        $record->card;
        $record->rank;
        $record->countesy;
        $record->position;
        $record->organization;
        $record->people;

        if( $record->people != null ){
            if ($record->people->weddingCertificates->isEmpty()) {
                $record->people->weddingCertificates()->create([
                    'people_id' => $record->people->id,
                    'wedding_number' =>  '',
                    'book_number' =>  '',
                    'year' => '',
                    'province_id' => 0,
                    'district_id' => 0,
                    'commune_id' => 0,
                    'issued_date' => '',
                    // 'issued_location' => $request->issued_location?? '' ,
                    // 'signed_name' => $request->signed_name?? '' ,
                    'pdf' => '',
                    // spouse
                    'spouse_id' => 0,
                    'spouse_firstname' =>  '',
                    'spouse_lastname' =>  '',
                    'spouse_enfirstname' =>  '',
                    'spouse_enlastname' =>  '',
                    'spouse_nid' =>  '',
                    'spouse_passport' =>  '',
                    'spouse_profession' =>  '',
                    'spouse_profession_organization' =>  '',
                    'spouse_dob' => '',
                    'spouse_nationality' =>  '',
                    'spouse_national' =>  '',
                    'spouse_pob' =>  '',
                    'spouse_address' =>  '',
                    'spouse_death' => 0,
                    // spouse father
                    'spouse_father_firstname' =>  '',
                    'spouse_father_lastname' =>  '',
                    'spouse_father_enfirstname' =>  '',
                    'spouse_father_enlastname' =>  '',
                    'spouse_father_dob' => '',
                    'spouse_father_nationality' =>  '',
                    'spouse_father_pob' =>  '',
                    'spouse_father_death' => 0,
                    // spouse mother
                    'spouse_mother_firstname' =>  '',
                    'spouse_mother_lastname' =>  '',
                    'spouse_mother_enfirstname' =>  '',
                    'spouse_mother_enlastname' =>  '',
                    'spouse_mother_dob' => '',
                    'spouse_mother_nationality' =>  '',
                    'spouse_mother_pob' =>  '',
                    'spouse_mother_death' => 0,

                    'created_by' => \Auth::user()->id,
                    'updated_by' => \Auth::user()->id,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d'),
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d')
                ]);
            }
            $record->people->wedding_certificates = $record->people->weddingCertificates->map(function( $weddingCertificate ){
                $weddingCertificate->birthCertificates;
                return $weddingCertificate;
            }) ;
            $record->people->passports;

            $certificates['first'] = $record->people->certificatesHighSchool();
            $certificates['middle'] = $record->people->certificatesPostGraduated();
            $certificates['others'] = $record->people->certificatesOthers();
            // $record->people->birthCertificates;
            $record->people->selfBirthCertificates = $record->people->selfBirthCertificates()->get();
            $record->people->certificates = $certificates ;
            $record->people->languages;
            $record->jobBackgrounds;
            $record->officer_ranks = $record->officerRanks->map(function($officerRank){
                $officerRank->rank;
                $officerRank->countesy;
                return $officerRank;
            });
            $record->krob_khans = $record->officer_ranks;
            $record->ranking_by_certificates = $record->rankingByCertificates->map(function($rank){
                $rank->rank;
                $rank->previousRank;
                return $rank;
            });
            $record->ranking_by_workings = $record->rankingByWorkings->map(function($rank){
                $rank->rank;
                $rank->previousRank;
                return $rank;
            });
            $record->rankingByWorkings;
            $record->pendingWorks;
            $record->paneltyHistories;
            $record->medalHistories;

        }

        $record->job = $record->getCurrentJob();
        if( $record->job != null && $record->job->organizationStructurePosition != null ){
            $record->job->organizationStructurePosition->position;
            $record->job->organizationStructurePosition->permissions;
            if( $record->job->organizationStructurePosition->organizationStructure != null ){
                $tpid = $record->job->organizationStructurePosition->organizationStructure->tpid;
                 // Convert string into array
                $indexes = array_filter(explode(':', $tpid), fn($v) => $v !== '');
                $indexes = array_values($indexes); // reindex

                if (!isset($indexes[3])) {  //use count function is > 3
                    // index 3 doesn't exist → use index 2
                    $targetIndexes = isset($indexes[2]) ? [$indexes[2]] : [];
                } else {
                    // index 3 exists → use index 3 and everything after
                    $targetIndexes = array_slice($indexes, 3);
                }
                $organizations = [];
                foreach ($targetIndexes as $id) {
                    // Example: fetch organization by ID
                    $organization = \App\Models\Organization\OrganizationStructure::where('id', $id)->first();
                    $organizations[$id] = $organization->organization;
                }
                $record->job->organizationStructurePosition->organizationStructure->organizations = $organizations;
                // $record->job->organizationStructurePosition->organizationStructure->organization;
            }
        }

        $record->image = $record->image != null && trim($record->image ) != "" && \Storage::disk('public')->exists( $record->image )
            ? \Storage::disk('public')->url( $record->image )
            : (
                $record->user != null && $record->user->avatar_url != null && trim($record->user->avatar_url) != "" && \Storage::disk('public')->exists( $record->user->avatar_url )
                ? \Storage::disk('public')->url( $record->user->avatar_url )
                : false
            );

        return response()->json([
            'record' => $record ,
            'ok' => true ,
            'message' => 'រួចរាល់'
        ],200);
    }
    public function delete(Request $request){
        $officer = RecordModel::find($request->id);

        if( $officer ){
            if( $officer->user != null ){
                $officer->user->deleted_at = \Carbon\Carbon::now();
                $officer->user->save();
            }
            if( $officer->people != null ){
                $officer->people->deleted_at = \Carbon\Carbon::now();
                $officer->people->save();
            }

            $officer->delete();

            return response()->json([
                'ok' => true,
                'officer' => $officer,
                'message' => 'លុបមន្ត្រីបានសម្រេច។'
            ],200);
        }

        return response()->json([
            'ok' => false,
            'officer' => null,
            'message' => 'រកមិនឃើញមន្ត្រីនេះទេ។'
        ],404);
    }
    public function officersSignatures(){
        return response()->json([
            'ok' => true ,
            'message' => 'រួចរាល់' ,
            'records' => RecordModel::whereNull('deleted_at')
                ->whereHas('people')
                ->get()->map(function($officer){
                    return [
                        'id' => $officer['id'] ,
                        'name' => $officer['people'] != null ? $officer['people']['firstname'] . ' ' . $officer['people']['lastname'] : '' ,
                        'enname' => $officer->people != null ? $officer['people']['enfirstname'] . ' ' . $officer['people']['enlastname'] : '' ,
                        'image' => $officer['image'] != null && \Storage::disk('public')->exists( $officer['image'] )
                            ? \Storage::disk('public')->url( $officer['image'] )
                            : (
                                isset( $officer['user'] ) && $officer['user']['avatar_url'] != null && \Storage::disk('public')->exists( $officer['user']['avatar_url'] )
                                ? \Storage::disk('public')->url( $officer['user']['avatar_url'] )
                                : (
                                    isset( $officer['people'] ) && $officer['people']['image'] != null && \Storage::disk('public')->exists( $officer['people']['image'] )
                                    ? \Storage::disk('public')->url( $officer['people']['image'] )
                                    : false
                                )
                        )
                    ];
                })
        ],200);
    }
    /**
     * Reports
     */
    public function officersOfGeneralDepartment(Request $request){
        /** Format from query string */
        $search = isset( $request->search ) && strlen( $request->search ) > 0 ? $request->search : false ;
        $perPage = isset( $request->perPage ) && intval( $request->perPage ) > 0 ? $request->perPage : 10 ;
        $page = isset( $request->page ) && intval( $request->page ) > 0 ? $request->page : 1 ;

        $positions = isset( $request->positions ) ? explode(',',$request->positions) : false ;
        if( is_array( $positions ) && !empty( $positions ) ){
            $positions = array_filter( $positions, function($position){
                return intval( $position ) > 0 ;
            } );
        }

        $organizations = isset( $request->organizations ) ? explode(',',$request->organizations) : false ;
        if( is_array( $organizations ) && !empty( $organizations ) ){
            $organizations = array_filter( $organizations , function($organization){
                return intval( $organization ) > 0 ;
            } );
        }

        $organizationStructure = \App\Models\Organization\OrganizationStructure::whereIn('organization_id', $organizations)->first();
        if( $organizationStructure == null ){
            return response()->json([
                'message'=> 'អង្គភាពនេះមិនមានឡើយ។'
            ],500);
        }

        // return response()->json([
        //     'organizationStructure' => $organizationStructure
        // ],200)

        $totalOfficersWithInPosition = [];
        $totalOfficers = 0;
        $organizationsList = collect();
        $organizationStructure->organization;
        if( $organizationStructure->structurePositions()->count() ){
            $organizationStructure->structure_positions = $organizationStructure->structurePositions()->whereHas('officerJobs', function ($officerJobQuery) {
                $officerJobQuery->whereHas('officer', function ($officerQuery) {
                    $officerQuery->whereNull('deleted_at');
                });
            })->orderby('index','asc')->get()->map(function ($organizationStructurePosition ) use( &$totalOfficersWithInPosition , &$totalOfficers ) {
                if( isset( $totalOfficersWithInPosition[ $organizationStructurePosition->position->name ] ) ){
                    $totalOfficersWithInPosition[ $organizationStructurePosition->position->name ] += $organizationStructurePosition->officerJobs()->count() ;
                }else{
                    $totalOfficersWithInPosition[ $organizationStructurePosition->position->name ] = $organizationStructurePosition->officerJobs()->count() ;
                }
                $totalOfficers += $organizationStructurePosition->officerJobs()->count();
                if( $organizationStructurePosition->officerJobs()->count() ){
                    $organizationStructurePosition->officer_jobs = $organizationStructurePosition->officerJobs()->get()->map(function ($officerJob) {
                        $officerJob->unofficialPosition;
                        if ($officerJob->officer) {
                            $officerJob->officer->current_job = $officerJob->officer->getCurrentJob();
                            if( $officerJob->officer->current_job != null ){
                                if( $officerJob->officer->current_job->organizationStructurePosition ){
                                    $officerJob->officer->current_job->organizationStructurePosition->position;
                                }
                            }
                            if( $officerJob->officer->people ){
                                $officerJob->officer->people->certificates;
                            }
                        }
                        return $officerJob;
                    });
                }
                return $organizationStructurePosition;
            });
        }
        $organizationsList->push($organizationStructure);

        $childIds = array_filter( explode(',', $organizationStructure->cids) , function($childId){
            return intval( $childId ) > 0;
        });

        if (is_array($childIds) && !empty($childIds)) {
            \App\Models\Organization\OrganizationStructure::whereIn('id', $childIds )->get()->map(function ($organizationStructure) use( $organizationsList , &$totalOfficersWithInPosition  , &$totalOfficers ){
                $organizationStructure->organization;
                if( $organizationStructure->structurePositions()->count() ){
                    $organizationStructure->structure_positions = $organizationStructure->structurePositions()->whereHas('officerJobs', function ($officerJobQuery) {
                        $officerJobQuery->whereHas('officer', function ($officerQuery) {
                            $officerQuery->whereNull('deleted_at');
                        });
                    })->orderby('index','asc')->get()->map(function ($organizationStructurePosition  ) use( &$totalOfficersWithInPosition  , &$totalOfficers) {
                        if( isset( $totalOfficersWithInPosition[ $organizationStructurePosition->position->name ] ) ){
                            $totalOfficersWithInPosition[ $organizationStructurePosition->position->name ] += $organizationStructurePosition->officerJobs()->count() ;
                        }else{
                            $totalOfficersWithInPosition[ $organizationStructurePosition->position->name ] = $organizationStructurePosition->officerJobs()->count() ;
                        }
                        $totalOfficers += $organizationStructurePosition->officerJobs()->count();
                        if( $organizationStructurePosition->officerJobs()->count() ){
                            $organizationStructurePosition->officer_jobs = $organizationStructurePosition->officerJobs()->get()->map(function ($officerJob) {
                                $officerJob->unofficialPosition;
                                if ($officerJob->officer) {
                                    $officerJob->officer->current_job = $officerJob->officer->getCurrentJob();
                                    if( $officerJob->officer->current_job != null ){
                                        if( $officerJob->officer->current_job->organizationStructurePosition ){
                                            $officerJob->officer->current_job->organizationStructurePosition->position;
                                        }
                                    }
                                    if( $officerJob->officer->people ){
                                        $officerJob->officer->people->certificates;
                                    }
                                }
                                return $officerJob;
                            });
                        }
                        return $organizationStructurePosition;
                    });
                }
                $organizationsList->push($organizationStructure);

                $childIds = array_filter( explode(',', $organizationStructure->cids) , function($childId){
                    return intval( $childId ) > 0;
                });
                if (is_array($childIds) && !empty($childIds)) {
                    $organizationStructure->cids = \App\Models\Organization\OrganizationStructure::whereIn('id', $childIds)->get()->map(function ($organizationStructure) use( $organizationsList , &$totalOfficersWithInPosition  , &$totalOfficers) {
                        $organizationStructure->organization;
                        if( $organizationStructure->structurePositions()->count() ){
                            $organizationStructure->structure_positions = $organizationStructure->structurePositions()->whereHas('officerJobs', function ($officerJobQuery) {
                                $officerJobQuery->whereHas('officer', function ($officerQuery) {
                                    $officerQuery->whereNull('deleted_at');
                                });
                            })->orderby('index','asc')->get()->map(function ($organizationStructurePosition ) use( &$totalOfficersWithInPosition , &$totalOfficers ) {
                                if( isset( $totalOfficersWithInPosition[ $organizationStructurePosition->position->name ] ) ){
                                    $totalOfficersWithInPosition[ $organizationStructurePosition->position->name ] += $organizationStructurePosition->officerJobs()->count() ;
                                }else{
                                    $totalOfficersWithInPosition[ $organizationStructurePosition->position->name ] = $organizationStructurePosition->officerJobs()->count() ;
                                }
                                $totalOfficers += $organizationStructurePosition->officerJobs()->count();
                                if( $organizationStructurePosition->officerJobs()->count() ){
                                    $organizationStructurePosition->officer_jobs = $organizationStructurePosition->officerJobs()->get()->map(function ($officerJob) {
                                        $officerJob->unofficialPosition;
                                        if ($officerJob->officer) {
                                            $officerJob->officer->current_job = $officerJob->officer->getCurrentJob();
                                            if( $officerJob->officer->current_job != null ){
                                                if( $officerJob->officer->current_job->organizationStructurePosition ){
                                                    $officerJob->officer->current_job->organizationStructurePosition->position;
                                                }
                                            }
                                            if( $officerJob->officer->people ){
                                                $officerJob->officer->people->certificates;
                                            }
                                        }
                                        return $officerJob;
                                    });
                                }
                                return $organizationStructurePosition;
                            });
                        }
                        $organizationsList->push($organizationStructure);
                    });
                }

            });
        }

        return response()->json([
            'positions' => $positions ,
            'totalOfficersWithInPosition' => $totalOfficersWithInPosition ,
            'totalOfficers' => $totalOfficers ,
            'organization' => $organizationStructure ,
            'organizations' => \App\Models\Organization\OrganizationStructure::where('tpid','like', $organizationStructure->tpid . '%' )->orderby('tpid')->get() ,
            'record' => $organizationsList
        ],200);
    }
}
