<?php

namespace App\Http\Controllers\Api\AuthenticationCenter\Officer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Officer\OfficerJobBackground as RecordModel;
use App\Models\Officer\Officer ;
use App\Http\Controllers\CrudController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

use FilippoToso\PdfWatermarker\Facades\ImageWatermarker;
use FilippoToso\PdfWatermarker\Support\Pdf;
use FilippoToso\PdfWatermarker\Watermarks\ImageWatermark;
use FilippoToso\PdfWatermarker\PdfWatermarker;
use FilippoToso\PdfWatermarker\Support\Position;

class OfficerJobBackgroundController extends Controller
{
    private $selectFields = [
        'id' ,
        'officer_id' , 
        'officer_job_id' ,
        'organization' , 
        'sub_organization' , 
        'position' , 
        'start' ,
        'end' , 
        'pdf' , 
        'skill_of_position' , 
        'sector' , 
        'is_primary' ,
        'created_at' , 
        'updated_at' ,
        'created_by' , 
        'updated_by' , 
        'deleted_by' 
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

        $officer = intval( $request->officer_id ) > 0 ? \App\Models\Officer\Officer::find( intval( $request->officer_id ) ) : null ;

        $queryString = [
            "where" => [
                'default' => [
                    $officer == null ? [] 
                    : [
                        'field' => 'officer_id' ,
                        'value' => $officer->id
                    ]
                ],
                // 'in' => [
                //     $user->roles()->pluck('name')->filter( function( $val , $key ) {
                //         return $val == 'backend' ;
                //     } )->count()
                //         ?[ 
                //             'field' => 'created_by' ,
                //             'value' => [$user->id] 
                //         ]
                //         : []
                //     // ,
                //     // [
                //     //     'field' => 'active' ,
                //     //     'value' => 1
                //     // ] ,
                //     // [
                //     //     'field' => 'publish' ,
                //     //     'value' => 1
                //     // ] ,
                //     // [
                //     //     'field' => 'accessibility' ,
                //     //     'value' => [ 1,2,3,4 ]
                //     // ]
                // ] ,
                // 'not' => [
                //     count( $weddingCertificateIds )
                //         ?[ 
                //             'field' => 'wedding_certificate_id' ,
                //             'value' => $weddingCertificateIds
                //         ]
                //         : []
                // ] ,
                // 'like' => [
                //     [
                //         'field' => 'fid' ,
                //         'value' => $fid === false ? "" : $fid
                //     ],
                //     [
                //         'field' => 'year' ,
                //         'value' => $date === false ? "" : $date
                //     ]
                // ] ,
            ] ,
            // "pivots" => [
            //     $types ?
            //     [
            //         "relationship" => 'type',
            //         "where" => [
            //             "in" => [
            //                 "field" => "document_type",
            //                 "value" => $types
            //             ],
            //         ]
            //     ]
            //     : [] ,
            // ],
            "pagination" => [
                'perPage' => $perPage,
                'page' => $page
            ],
            "search" => $search === false ? [] : [
                'value' => $search ,
                'fields' => [
                    'start' ,
                    'end' ,
                    'organization' ,
                    'position' ,
                    'sub_organization' ,
                    'skill_of_position'
                ]
            ],
            "order" => [
                'field' => 'organization' ,
                'by' => 'desc'
            ],
        ];

        $request->merge( $queryString );

        $crud = new CrudController(new RecordModel(), $request, $this->selectFields,[
            /**
             * custom the value of the field
             */
            'pdf' => function($record){
                $record->pdf = ( strlen( $record->pdf ) > 0 && \Storage::disk('certificate')->exists( $record->pdf ) )
                ? true
                // \Storage::disk('regulator')->url( $pdf ) 
                : false ;
                return $record->pdf ;
            }
        ]);

        $crud->setRelationshipFunctions([
            /** relationship name => [ array of fields name to be selected ] */
            'officer' => [
                'code',
                'official_date' ,
                'unofficial_date',
                'public_key',
                'user_id' ,
                'people_id' ,
                'email',
                'phone',
                'countesy_id' ,
                // Optional
                'organization_id' ,
                'position_id' ,
                'rank_id' ,
                'leader' ,
                'image' ,
                'pdf',
                'passport' ,
                'people' => [ 'id' , 'firstname' ,'lastname' , 'enfirstname' , 'enlastname' ]
            ]
        ]);

        $builder = $crud->getListBuilder();

        $responseData = $crud->pagination(true, $builder);
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        // $responseData['server'] = $_SERVER ;
        return response()->json($responseData, 200);
    }
    /**
     * View the pdf file
     */
    public function pdf(Request $request)
    {
        $certificate = RecordModel::findOrFail($request->id);
        if($certificate) {
            $pathPdf = storage_path('data') . '/certificates/' . $certificate->pdf ;
            $ext = pathinfo($pathPdf);
            $filename = $certificate->id . '-' .$certificate->field_name . "." . $ext['extension'];
        
            /**   Log the access of the user */
            // $user = \Auth::user() != null ? \Auth::user() : auth('api')->user() ;
            // if( $user != null ){
            //     \App\Models\Log\Log::regulator([
            //         'system' => 'client' ,
            //         'user_id' => $user->id ,
            //         'regulator_id' => $document->id
            //     ]);
            // }

            if(file_exists( $pathPdf ) && is_file($pathPdf)) {
                $pdfBase64 = base64_encode( file_get_contents( $pathPdf ) );
                return response([
                    'serial' => $certificate->pdf ,
                    "pdf" => 'data:application/pdf;base64,' . $pdfBase64 ,
                    "filename" => $filename,
                    "ok" => true 
                ],200);
            }else
            {
                return response([
                    'message' => 'មានបញ្ហាក្នុងការអានឯកសារ !' ,
                    'path' => $pathPdf
                ],500 );
            }
        }
    }
    public function upload(Request $request){
        $user = \Auth::user();
        if( $user ){
            $phpFileUploadErrors = [
                0 => 'មិនមានបញ្ហាជាមួយឯកសារឡើយ។',
                1 => "ទំហំឯកសារធំហួសកំណត់ " . ini_get("upload_max_filesize"),
                2 => 'ទំហំឯកសារធំហួសកំណត់នៃទំរង់បញ្ចូលទិន្នន័យ ' . ini_get('post_max_size'),
                3 => 'The uploaded file was only partially uploaded',
                4 => 'No file was uploaded',
                6 => 'Missing a temporary folder',
                7 => 'Failed to write file to disk.',
                8 => 'A PHP extension stopped the file upload.',
            ];
            if( isset( $_FILES['file'] ) && $_FILES['file']['error'] > 0 ){
                return response()->json([
                    'ok' => false ,
                    'message' => $phpFileUploadErrors[ $_FILES['file']['error'] ]
                ],403);
            }
            $kbFilesize = round( filesize( $_FILES['file']['tmp_name'] ) / 1024 , 4 );
            $mbFilesize = round( $kbFilesize / 1024 , 4 );
            if( ( $certificate = RecordModel::find($request->id) ) !== null ){
                $originalName = basename( str_replace( '\\' , '/' , strval( $_FILES['file']['name'] ?? 'document' ) ) );
                $safeOriginalName = trim( preg_replace( '/[^\\pL\\pN\\s._-]+/u' , '_' , $originalName ) );
                if( $safeOriginalName === '' ){
                    $safeOriginalName = 'document';
                }
                $uniqeName = Storage::disk('certificate')->putFileAs(
                    '' ,
                    new File( $_FILES['file']['tmp_name'] ) ,
                    str_replace( '.' , '' , uniqid( '', true ) ) . '__' . $safeOriginalName
                );
                $certificate->pdf = $uniqeName ;
                $certificate->save();
                if( Storage::disk('certificate')->exists( $certificate->pdf ) ){
                    $certificate->pdf = Storage::disk("certificate")->url( $certificate->pdf  );
                    return response([
                        'record' => $certificate ,
                        'message' => 'ជោគជ័យក្នុងការបញ្ចូលឯកសារយោង។'
                    ],200);
                }else{
                    return response([
                        'record' => $certificate ,
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
    public function create(Request $request){
        $user = \Auth::user() != null
            ? \Auth::user()
            : (
                auth('api')->user()
                    ? auth('api')->user()
                    : (
                        $request->user() != null
                            ? $request->user()
                            : 0
                    )
            );
        \Log::info("officer id: " . $request->officer_id);


        /**
         * Save information of the regulator and its related information
         */
        $officer = intval( $request->officer_id ) > 0 ? \App\Models\Officer\Officer::find( intval( $request->officer_id ) ) : null ;
        if( $officer == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់ម្ចាស់ឯកសារ'
            ],500);
        }
        $record = RecordModel::create([
            'officer_id' => $officer->id ,
            'officer_job_id' => null,
            'organization' => $request->organization?? '' ,
            'sub_organization' => $request->sub_organization?? '' ,
            'position' => $request->position?? '' ,
            'start' => $request->start?? '' ,
            'end' => $request->end?? '' ,
            'skill_of_position' => $request->skill_of_position?? '' ,
            'sector' => intval( $request->sector??0 ),
            'is_primary' => $request->boolean('is_primary'),
            'pdf' => '' ,
            'created_by' => $user->id ,
            'updated_by' => $user->id ,
            // 'created_by' => \Auth::user()->id ,
            // 'updated_by' => \Auth::user()->id ,
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d')
        ]);

        // $subOrganizations = preg_split('/[\s\-\/]+/', $request->sub_organization);
        // $normalized = str_replace(['-', '/'], ' ', $request->sub_organization);
        // $parts = explode(' ', $normalized); 
        if($request->organization == "ទីស្ដីការគណៈរដ្ឋមន្ត្រី"){
            //ទាញយកព័ត៌មានអង្គភាព
            $subOrganizations = explode(',', $request->sub_organization); 
            // បញ្ជាក់ជាមុនថាអង្គភាពដែលបានផ្ដល់មកគឺពិតជាមានក្នុងមូលទិន្នន័យ
            $organizations = \App\Models\Organization\Organization::whereIn('keyname',$subOrganizations)->whereHas('industry')->get();
            $lowestOrganization = null ;
            // ដោយសារតែមានការផ្ដល់មកមានអង្គភាពលើសពី ១ ដូចនេះត្រូវធ្វើការកំណត់ថាមួយណាដែលតូចជាងគេ
            if( $organizations->count() > 1 ){
                // ស្វែងរកអង្គភាពដែលផ្ទៀងផ្ទាត់ និងព័ត៌មានដែលផ្ដល់មក
                $matchedOrganizationsAsArray = $organizations->pluck('id')->all();
                // ធ្វើការកំណត់អង្គភាពដែលមានឋានានុក្រមតូចជាងគេ
                $theLowestIndustry = \App\Models\Organization\Industry::whereHas('organizations',function($organizationQuery) use($matchedOrganizationsAsArray) {
                    $organizationQuery->whereIn('id',$matchedOrganizationsAsArray);
                })->orderby('id','desc')
                ->first();
                // អានព័ត៌មានអង្គភាពដែលមាន ឋានានុក្រមតូចជាងគេ
                $lowestOrganization = $theLowestIndustry->organizations()->whereIn('id',$matchedOrganizationsAsArray)->first();
            }else if( $organizations->count() == 1 ){
                $lowestOrganization = $organizations->first();
            }
            
            if( $lowestOrganization != null && $lowestOrganization->id > 0 ){
                //  សសេរកូដនៃការរក្សារព័ត៌មានចូលទៅមូលទីន្នន័យ Officer_jobs នៅទីនេះ
                
                // ពិនិត្យព័ត៌មាននៃឈ្មោះតួនាទី
                $organizationStructurePosition = \App\Models\Organization\OrganizationStructurePosition::whereHas('organizationStructure',function($organizationStructureQuery) use( $lowestOrganization ){
                    $organizationStructureQuery->where('organization_id', $lowestOrganization->id );
                })
                ->whereHas('position',function($positionQuery) use($request){
                    $positionQuery->where('name',$request->position);
                })->first();

                if( $organizationStructurePosition != null ){
                    $officerJob = \App\Models\Officer\OfficerJob::create([
                        'organization_structure_position_id' => $organizationStructurePosition -> id ,
                        //ប្រើនៅពេលដែលតួនាទីរបស់មន្រ្តីមាន​សិទ្ធិស្មើនិងតួនាទីណាមួយ
                        'unofficial_position_id' => $request->organization_structure_unofficial_position_id ?? 0 ,
                        'officer_id' => $officer->id,
                        'is_primary' => $request->boolean('is_primary'),
                        'countesy_id' => $request->countesy_id ?? 0,
                        'start' => $request->start ?? \Carbon\Carbon::now()->format('Y-m-d'),
                        'created_by' => $user->id,
                    ]);
                    $record->update( [ 'officer_job_id' => $officerJob->id ]) ;
                    $record->officerJob;
                }
            } 
            
            
                       

            // $lastIndex = array_key_last($subOrganizations);
                // $subOrganizations[$lastIndex] = str_replace(' ', '', $subOrganizations[$lastIndex]);
                // $subOrganizations_id = \App\Models\Organization\Organization::where('keyname', $subOrganizations[$lastIndex])->value('id');
                // \Log::info("subOrganizations_id: " . $subOrganizations_id);

                // $organization_structure_id = \App\Models\Organization\OrganizationStructure::where('organization_id', $subOrganizations_id)->value('id');
                // \Log::info("organization_structure_id: " . $organization_structure_id);
                // //ទាញយកព័ត៌មានតួនាទី
                // $position_id = \App\Models\Position\Position::where('name', $request->position)->value('id');
                //             \Log::info("position_id: " . $position_id);

                // //ទាញយកព័ត៌មាន Organization Position
            
                // if( 
                //     ( 
                //         $organization_structure_position = \App\Models\Organization\OrganizationStructurePosition::where('organization_structure_id', $organization_structure_id)
                //         ->where('position_id', $position_id)->first() 
                //     ) != null 
                // ){
                //     \Log::info("organization_structure_position: " . $organization_structure_position ->id);
                //     $officerJob = \App\Models\Officer\OfficerJob::create([
                //         'organization_structure_position_id' => $organization_structure_position -> id ,
                //         //ប្រើនៅពេលដែលតួនាទីរបស់មន្រ្តីមាន​សិទ្ធិស្មើនិងតួនាទីណាមួយ
                //         'unofficial_position_id' => $request->organization_structure_unofficial_position_id ?? 0 ,
                //         'officer_id' => $request->officer_id,
                //         'is_primary' => $request->boolean('is_primary'),
                //         'countesy_id' => $request->countesy_id ?? null,
                //         'start' => $request->start ?? \Carbon\Carbon::now()->format('Y-m-d'),
                //         'created_by' => $user->id,
                //     ]);
                //     $record->update(['officer_job_id'=>$officerJob->id]);
                // }
        }

        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        $responseData['record'] = $record ;
        return response()->json($responseData, 200);
    }
    public function update(Request $request){
        if( isset( $request->id ) && $request->id > 0 && ( $record = RecordModel::find($request->id) ) !== null ){
            $officer = intval( $request->officer_id ) > 0 ? \App\Models\Officer\Officer::find( intval( $request->officer_id ) ) : null ;
            if( $officer == null ){
                return response()->json([
                    'ok' => false ,
                    'message' => 'សូមបញ្ជាក់ម្ចាស់ឯកសារ'
                ],500);
            }
            /**
             * Save information of the regulator and its related information
             */
            $clearPdf = intval( $request->clear_pdf ?? 0 ) > 0 ;
            $updateData = [
                'officer_id' => $officer->id ,
                'officer_job_id' => intval( $request->officer_job_id ?? 0 ) > 0 ? intval( $request->officer_job_id ) : $record->officer_job_id,
                'organization' => $request->organization?? '' ,
                'sub_organization' => $request->sub_organization?? '' ,
                'position' => $request->position?? '' ,
                'start' => $request->start?? '' ,
                'end' => $request->end?? '' ,
                'skill_of_position' => $request->skill_of_position?? '' ,
                'sector' => intval( $request->sector??0 ),
                'is_primary' => $request->has('is_primary') ? $request->boolean('is_primary') : boolval($record->is_primary),
                'updated_by' => \Auth::user()->id ,
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d')
            ];
            if( $clearPdf ){
                $updateData['pdf'] = '' ;
            }
            if( $record->update( $updateData ) ){
                $this->syncOfficerJobPrimary(
                    intval( $record->officer_id ?? 0 ),
                    intval( $record->officer_job_id ?? 0 ),
                    boolval( $record->is_primary )
                );
                $record->with('officer');
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
        $certificate = RecordModel::find($request->id);
        if( $certificate == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឯកសារដែលអ្នកត្រូវការមិនមានឡើយ។'
            ],201);
        }
        $certificate->with('officer');
        return response()->json([
            'record' => $certificate ,
            'ok' => true ,
            'message' => 'រួចរាល់។'
        ],200);
    }

    public function destroy(Request $request){
        if( !isset( $request->id ) || $request->id < 0 ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់អំពីលេខសម្គាល់ឯកសារ។'
            ],201);
        }
        $certificate = RecordModel::find($request->id);
        if( $certificate == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'ឯកសារស្វែករកបានជោគជ័យ។'
            ],201);
        }
        $certificate->with('officer');
        $tempRecord = $certificate;
        if( $certificate->delete() ){
            /**
             * Delete all the related documents own by this regulator
             */
            // if( $tempRecord->pdf !== null && $tempRecord->pdf !=="" && Storage::disk('certificate')->exists( $tempRecord->pdf ) ){
            //     Storage::disk("certificate")->delete( $tempRecord->pdf  );
            // }
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

    private function syncOfficerJobPrimary($officerId, $officerJobId, $isPrimary)
    {
        if( !$isPrimary || intval($officerId) <= 0 ){
            return;
        }

        $officerId = intval($officerId);
        $officerJobId = intval($officerJobId);

        // Keep one primary officer job per officer.
        \App\Models\Officer\OfficerJob::where('officer_id', $officerId)->update([
            'is_primary' => false
        ]);

        if( $officerJobId > 0 ){
            $targetJob = \App\Models\Officer\OfficerJob::where('officer_id', $officerId)
                ->where('id', $officerJobId)
                ->first();
        }else{
            // Fallback: pick most recently created job for this officer.
            $targetJob = \App\Models\Officer\OfficerJob::where('officer_id', $officerId)
                ->orderBy('id', 'desc')
                ->first();
        }

        if( $targetJob != null ){
            $targetJob->is_primary = true;
            $targetJob->save();
        }
    }
}
