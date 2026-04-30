<?php

namespace App\Http\Controllers\Api\Hradmin\People;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\People\BirthCertificate as RecordModel;
use App\Http\Controllers\CrudController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

use FilippoToso\PdfWatermarker\Facades\ImageWatermarker;
use FilippoToso\PdfWatermarker\Support\Pdf;
use FilippoToso\PdfWatermarker\Watermarks\ImageWatermark;
use FilippoToso\PdfWatermarker\PdfWatermarker;
use FilippoToso\PdfWatermarker\Support\Position;


class BirthCertificateController extends Controller
{
    private $selectFields = [
        'id' ,
        'people_id' ,
        'birth_number' ,
        'book_number' ,
        'year' ,
        'province_id' ,
        'district_id' ,
        'commune_id' ,
        'firstname' ,
        'lastname' ,
        'enfirstname' ,
        'enlastname' ,
        'dob' ,
        'gender' ,
        'nationality' ,
        'national' ,
        'pob' ,
        'issued_date' ,
        'issued_location' ,
        'signed_name' ,
        'wedding_certificate_id' ,
        'pdf' ,
        'created_by' ,
        'updated_by' ,
        'created_at' ,
        'updated_at' ,
        'father_firstname' ,
        'father_lastname'  ,
        'father_enfirstname' ,
        'father_enlastname' ,
        'father_dob' ,
        'father_nationality' ,
        'father_pob' ,

        'mother_firstname' ,
        'mother_lastname' ,
        'mother_enfirstname' ,
        'mother_enlastname' ,
        'mother_dob' ,
        'mother_nationality' ,
        'mother_pob'
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

        $people = intval( $request->people_id ) > 0 ? \App\Models\People\People::find( intval( $request->people_id ) ) : null ;

        $queryString = [
            "where" => [
                'default' => [
                    $people == null ? [] 
                    : [
                        'field' => 'people_id' ,
                        'value' => $people->id
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
                //     [
                //         'field' => 'type' ,
                //         'value' => [4]
                //     ]
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
                    'birth_number' ,
                    'book_number' ,
                    'year' ,
                    'firstname' ,
                    'lastname' ,
                    'enfirstname' ,
                    'enlastname'
                ]
            ],
            "order" => [
                'field' => 'birth_number' ,
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
            'people' => [ 'id' , 'firstname' ,'lastname' , 'enfirstname' , 'enlastname' ] ,
            // 'province' => [ 'id' , 'name' , 'desp' ] ,
            // 'district' => [ 'id' , 'name' , 'desp' ] ,
            // 'commune' => [ 'id' , 'name' , 'desp' ] ,
            // 'weddingCertificate' => [ 'id' , 'book_number' ]
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
                $uniqeName = Storage::disk('certificate')->putFile( '' , new File( $_FILES['file']['tmp_name'] ) );
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
        /**
         * Save information of the regulator and its related information
         */
        $people = intval( $request->people_id ) > 0 ? \App\Models\People\People::find( intval( $request->people_id ) ) : null ;
        if( $people == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'សូមបញ្ជាក់ម្ចាស់ឯកសារ'
            ],500);
        }
        $record = RecordModel::create([
            'people_id' => $people->id ,
            'birth_number' => $request->birth_number?? '' ,
            'book_number' => $request->book_number?? '' ,
            'year' => strlen( $request->year ) ? $request->year : \Carbon\Carbon::now()->format('Y') ,
            'province_id' => $request->province_id?? 0 ,
            'district_id' => $request->district_id?? 0 ,
            'commune_id' => $request->commune_id?? 0 ,
            'firstname' => $request->firstname?? '' ,
            'lastname' => $request->lastname?? '' ,
            'enfirstname' => $request->enfirstname?? '' ,
            'enlastname' => $request->enlastname?? '' ,
            'dob' => strlen( $request->dob ) ? \Carbon\Carbon::parse( $request->dob )->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') ,
            'gender' => $request->gender?? 'male' ,
            'nationality' => $request->nationality?? 'ខ្មែរ' ,
            'national' => $request->national?? 'ខ្មែរ' ,
            'pob' => $request->pob?? '' ,
            'issued_date' => strlen( $request->issued_date ) ? \Carbon\Carbon::parse( $request->issued_date )->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') ,
            'issued_location' => $request->issued_location?? '' ,
            'signed_name' => $request->signed_name?? '' ,
            'wedding_certificate_id' => 0 ,
            'pdf' => '' ,
            
            'father_firstname' => $request->father_firstname?? '' ,
            'father_lastname' => $request->father_lastname?? '' ,
            'father_enfirstname' => $request->father_enfirstname?? '' ,
            'father_enlastname' => $request->father_enlastname?? '' ,
            'father_dob' => strlen( $request->father_dob ) ? \Carbon\Carbon::parse( $request->father_dob )->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') ,
            'father_nationality' => $request->father_nationality?? 'ខ្មែរ' ,
            'father_pob' => $request->father_pob?? '' ,

            'mother_firstname' => $request->mother_firstname?? '' ,
            'mother_lastname' => $request->mother_lastname?? '' ,
            'mother_enfirstname' => $request->mother_enfirstname?? '' ,
            'mother_enlastname' => $request->mother_enlastname?? '' ,
            'mother_dob' => strlen( $request->mother_dob ) ? \Carbon\Carbon::parse( $request->mother_dob )->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') ,
            'mother_nationality' => $request->mother_nationality?? 'ខ្មែរ' ,
            'mother_pob' => $request->mother_pob?? '' ,

            'created_by' => \Auth::user()->id ,
            'updated_by' => \Auth::user()->id ,
            'created_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d')
        ]);
        
        $responseData['message'] = __("crud.read.success");
        $responseData['ok'] = true ;
        $responseData['record'] = $record ;
        return response()->json($responseData, 200);
    }
    public function update(Request $request){
        if( isset( $request->id ) && $request->id > 0 && ( $record = RecordModel::find($request->id) ) !== null ){
            $people = intval( $request->people_id ) > 0 ? \App\Models\People\People::find( intval( $request->people_id ) ) : null ;
            if( $people == null ){
                return response()->json([
                    'ok' => false ,
                    'message' => 'សូមបញ្ជាក់ម្ចាស់ឯកសារ'
                ],500);
            }
            /**
             * Save information of the regulator and its related information
             */
            if( $record->update([
                'birth_number' => $request->birth_number?? '' ,
                'book_number' => $request->book_number?? '' ,
                'year' => strlen( $request->year ) > 0 ? $request->year : \Carbon\Carbon::now()->format('Y') ,
                'province_id' => $request->province_id?? 0 ,
                'district_id' => $request->district_id?? 0 ,
                'commune_id' => $request->commune_id?? 0 ,
                'firstname' => $request->firstname?? '' ,
                'lastname' => $request->lastname?? '' ,
                'enfirstname' => $request->enfirstname?? '' ,
                'enlastname' => $request->enlastname?? '' ,
                'dob' => strlen( $request->dob ) ? \Carbon\Carbon::parse( $request->dob )->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') ,
                'gender' => $request->gender?? 'male' ,
                'nationality' => $request->nationality?? 'ខ្មែរ' ,
                'national' => $request->national?? 'ខ្មែរ' ,
                'pob' => $request->pob?? '' ,
                'issued_date' => strlen( $request->issued_date ) ? \Carbon\Carbon::parse( $request->issued_date )->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') ,
                'issued_location' => $request->issued_location?? '' ,
                'signed_name' => $request->signed_name?? '' ,

                'father_firstname' => $request->father_firstname?? '' ,
                'father_lastname' => $request->father_lastname?? '' ,
                'father_enfirstname' => $request->father_enfirstname?? '' ,
                'father_enlastname' => $request->father_enlastname?? '' ,
                'father_dob' => strlen( $request->father_dob ) ? \Carbon\Carbon::parse( $request->father_dob )->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') ,
                'father_nationality' => $request->father_nationality?? 'ខ្មែរ' ,
                'father_pob' => $request->father_pob?? '' ,

                'mother_firstname' => $request->mother_firstname?? '' ,
                'mother_lastname' => $request->mother_lastname?? '' ,
                'mother_enfirstname' => $request->mother_enfirstname?? '' ,
                'mother_enlastname' => $request->mother_enlastname?? '' ,
                'mother_dob' => strlen( $request->mother_dob ) ? \Carbon\Carbon::parse( $request->mother_dob )->format('Y-m-d') : \Carbon\Carbon::now()->format('Y-m-d') ,
                'mother_nationality' => $request->mother_nationality?? 'ខ្មែរ' ,
                'mother_pob' => $request->mother_pob?? '' ,
                
                'updated_by' => \Auth::user()->id ,
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d')
            ]) ){
                $record->with('people');
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
        $certificate->with('people');
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
        $certificate->with('people');
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
}
