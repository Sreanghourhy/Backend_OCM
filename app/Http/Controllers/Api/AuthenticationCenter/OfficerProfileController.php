<?php
namespace App\Http\Controllers\Api\AuthenticationCenter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Mail\MobilePasswordResetRequest;
use Illuminate\Support\Facades\Mail;
use App\Models\Officer\Officer as RecordModel ;
use App\Http\Controllers\CrudController;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Services\PeopleSearchService;
use \Gumlet\ImageResize;


class OfficerProfileController extends Controller
{
    public function updatePropleProfile(Request $request){
        $user = \Auth::user() == null ? null : \Auth::user() ;
        $people = isset( $request->people_id ) && intval( $request->people_id ) > 0
            ? \App\Models\People\People::find( $request->people_id )
            : null ;
        if( $people == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានបញ្ជាក់អំពីម្ចាស់ព័ត៌មាន។'
            ],201);
        }
        if( $people->officer == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានព័ត៌មានមន្ត្រីនេះឡើយ។'
            ],201);
        }
        // Information of People
        $firstname = isset( $request->firstname ) && strlen( $request->firstname ) >= 0 ? $request->firstname : false ;
        $lastname = isset( $request->lastname ) && strlen( $request->lastname ) >= 0 ? $request->lastname : false ;
        $enfirstname = isset( $request->enfirstname ) && strlen( $request->enfirstname ) >= 0 ? $request->enfirstname : false ;
        $enlastname = isset( $request->enlastname ) && strlen( $request->enlastname ) >= 0 ? $request->enlastname : false ;
        $pob = isset( $request->pob ) && strlen( $request->pob ) >= 0 ? $request->pob : false ;
        $currentAddress = isset( $request->current_address ) && strlen( $request->current_address ) >= 0 ? $request->current_address : false ;
        $address = isset( $request->address ) && strlen( $request->address ) >= 0 ? $request->address : false ;
        $mobilePhone = isset( $request->mobile_phone ) && strlen( $request->mobile_phone ) >= 0 ? $request->mobile_phone : false ;
        $national = isset( $request->national ) && strlen( $request->national ) >= 0 ? $request->national : false ;
        $nationality = isset( $request->nationality ) && strlen( $request->nationality ) >= 0 ? $request->nationality : false ;
        $email = isset( $request->email ) && strlen( $request->email ) >= 0 ? $request->email : false ;
        // $passport = isset( $request->passport ) && strlen( $request->passport ) ? $request->passport : false ;
        $bodyConditionDesp = isset( $request->body_condition_desp ) && strlen( $request->body_condition_desp ) ? $request->body_condition_desp : false ;
        $nid = isset( $request->nid ) && strlen( $request->nid ) ? $request->nid : false ;

        $dob = isset( $request->dob ) && strlen( $request->dob ) ? \Carbon\Carbon::parse( $request->dob ) : false ;
        $nidStart = isset( $request->nid_start_at ) && strlen( $request->nid_start_at ) ? \Carbon\Carbon::parse( $request->nid_start_at ) : false ;
        $nidExpiredAt = isset( $request->nid_expired_at ) && strlen( $request->nid_expired_at ) ? \Carbon\Carbon::parse( $request->nid_expired_at ) : false ;
    
        $gender = isset( $request->gender ) && intval( $request->gender ) > 0 ? intval( $request->gender )  : false ;
        $countesyId = isset( $request->countesy_id ) && intval( $request->countesy_id ) > 0 ? intval( $request->countesy_id )  : false ;
        $bodyCondition = isset( $request->body_condition ) && intval( $request->body_condition ) > 0 ? intval( $request->body_condition ) : false ;
        
        // Information of Officer
        $code = isset( $request->code ) && strlen( $request->code ) >= 0 ? $request->code : false ;

        $peopleData = [];
        
        if( $firstname != false ){
            $peopleData['firstname'] = $firstname ;
        }
        if( $lastname != false ){
            $peopleData['lastname'] = $lastname ;
        }
        if( $enfirstname != false ){
            $peopleData['enfirstname'] = $enfirstname ;
        }
        if( $enlastname != false ){
            $peopleData['enlastname'] = $enlastname ;
        }
        if( $pob != false ){
            $peopleData['pob'] = $pob ;
        }
        if( $address != false ){
            $peopleData['address'] = $address ;
        }
        if( $currentAddress != false ){
            $peopleData['current_address'] = $currentAddress ;
        }
        if( $mobilePhone != false ){
            $peopleData['mobile_phone'] = $mobilePhone ;
        }
        if( $bodyCondition != false ){
            $peopleData['body_condition'] = $bodyCondition ;
        }
        if( $bodyConditionDesp != false ){
            $peopleData['body_condition_desp'] = $bodyConditionDesp ;
        }
        if( $nid != false ){
            $peopleData['nid'] = $nid ;
        }
        // if( $passport != false ){
        //     $peopleData['passport'] = $passport ;
        // }
        if( $email != false ){
            $peopleData['email'] = $email ;
        }
        if( $national != false ){
            $peopleData['national'] = $national ;
        }
        if( $nationality != false ){
            $peopleData['nationality'] = $nationality ;
        }
        if( $dob != false ){
            $peopleData['dob'] = $dob->format('Y-m-d') ;
        }
        if( $nidStart != false ){
            $peopleData['nid_start_at'] = $nidStart->format('Y-m-d') ;
        }
        if( $nidExpiredAt != false ){
            $peopleData['nid_expired_at'] = $nidExpiredAt->format('Y-m-d') ;
        }
        if( $gender != false ){
            $peopleData['gender'] = $gender;
        }
        if( $countesyId != false ){
            $peopleData['countesy_id'] = $countesyId;
        }
        if( $countesyId != false ){
            $peopleData['countesy_id'] = $countesyId;
        }

        $people->update( $peopleData );

        if( $code != false ){
            $people->officer->update(['code'=> $code ]);
        }
        
        return response()->json([
            'ok' => true ,
            'message' => 'ព័ត៌មានផ្ទាល់ខ្លួនបានរក្សារទុករួចរាល់។'
        ],200);
    }
    public function updateBirthCertificate(Request $request){
        $user = \Auth::user() == null ? null : \Auth::user() ;
        $people = isset( $request->people_id ) && intval( $request->people_id ) > 0
            ? \App\Models\People\People::find( $request->people_id )
            : null ;
        if( $people == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានបញ្ជាក់អំពីម្ចាស់ព័ត៌មាន។'
            ],201);
        }

        // Information of People's Birth Certificate
        $year = isset( $request->year ) && strlen( $request->year ) >= 0 ? $request->year : false ;
        $birthNumber = isset( $request->birth_number ) && strlen( $request->birth_number ) >= 0 ? $request->birth_number : false ;
        $bookNumber = isset( $request->book_number ) && strlen( $request->book_number ) >= 0 ? $request->book_number : false ;
        $dob = isset( $request->dob ) && strlen( $request->dob ) > 0 ? \Carbon\Carbon::parse( $request->dob ) : false ;
        $issuedDate = isset( $request->issued_date ) && strlen( $request->issued_date ) >= 0 ? $request->issued_date : false ;
        $province = isset( $request->province_id ) && intval( $request->province_id ) > 0 ? \App\Models\Location\Province::find( $request->province_id ) : null ;
        $district = isset( $request->district_id ) && intval( $request->district_id ) > 0 ? \App\Models\Location\Province::find( $request->district_id ) : null ;
        $commune = isset( $request->commune_id ) && intval( $request->commune_id ) > 0 ? \App\Models\Location\Province::find( $request->commune_id ) : null ; 

        if( $people->birthCertificates != null ){
            if( $people->birthCertificates()->count() == 0 ){
                $people->birthCertificates()->create([
                    'year' => $year ,
                    'birth_number' => $birthNumber ,
                    'book_number' => $bookNumber ,
                    'dob' => $dob->format('Y-m-d') ,
                    'issued_date' => $issuedDate->format('Y-m-d') ,
                    'province_id' => $province != null ? $province->id : 0 ,
                    'district_id' => $district != null ? $district->id : 0 ,
                    'commune_id' => $commune != null ? $commune->id : 0 ,
                    'wedding_certificate_id' => null ,
                    'created_by' => $user->id ,
                    'updated_by' => $user->id ,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ]);
            }else{
                $people->birthCertificates()->first()->update([
                    'year' => $year ,
                    'birth_number' => $birthNumber ,
                    'book_number' => $bookNumber ,
                    'dob' => $dob->format('Y-m-d') ,
                    'issued_date' => $issuedDate->format('Y-m-d') ,
                    'province_id' => $province != null ? $province->id : 0 ,
                    'district_id' => $district != null ? $district->id : 0 ,
                    'commune_id' => $commune != null ? $commune->id : 0 ,
                    // 'wedding_certificate_id' => null ,
                    'updated_by' => $user->id ,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ]);
            }
            return response()->json([
                'ok' => true ,
                'message' => 'ព័ត៌មានសំបុត្រកំណើតសម្រាប់បុគ្គលនេះរក្សារទុករួចរាល់។'
            ],201);
        }else{
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានព័ត៌មានសំបុត្រកំណើតសម្រាប់បុគ្គលនេះឡើយ។'
            ],201);
        }
    }
    public function updateWeddingCertificate(Request $request){
        $user = \Auth::user() == null ? null : \Auth::user() ;
        $people = isset( $request->people_id ) && intval( $request->people_id ) > 0
            ? \App\Models\People\People::find( $request->people_id )
            : null ;
        if( $people == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានបញ្ជាក់អំពីម្ចាស់ព័ត៌មាន។'
            ],201);
        }
        $marryStatus = isset( $request->marry_status ) && strlen( $request->marry_status ) > 0 && in_array( trim( $request->marry_status ) , [ 'married' , 'single' , 'divorced' ] ) ? $request->marry_status : false ;
        $firstname = isset( $request->spouse_firstname ) && strlen( $request->spouse_firstname ) >= 0 ? $request->spouse_firstname : false ;
        $lastname = isset( $request->spouse_lastname ) && strlen( $request->spouse_lastname ) >= 0 ? $request->spouse_lastname : false ;
        $enfirstname = isset( $request->spouse_enfirstname ) && strlen( $request->spouse_enfirstname ) >= 0 ? $request->spouse_enfirstname : false ;
        $enlastname = isset( $request->spouse_enlastname ) && strlen( $request->spouse_enlastname ) >= 0 ? $request->spouse_enlastname : false ;
        $pob = isset( $request->spouse_pob ) && strlen( $request->spouse_pob ) >= 0 ? $request->spouse_pob : false ;
        $address = isset( $request->spouse_address ) && strlen( $request->spouse_address ) >= 0 ? $request->spouse_address : false ;
        $nid = isset( $request->spouse_nid ) && strlen( $request->spouse_nid ) >= 0 ? $request->spouse_nid : false ;
        $profession = isset( $request->spouse_profession ) && strlen( $request->spouse_profession ) >= 0 ? $request->spouse_profession : false ;
        $death = isset( $request->spouse_death ) && strlen( $request->spouse_death ) >= 0 ? $request->spouse_death : false ;
        $organization = isset( $request->spouse_profession_organization ) && strlen( $request->spouse_profession_organization ) >= 0 ? $request->spouse_profession_organization : false ;
        $dob = isset( $request->spouse_dob ) && strlen( $request->spouse_dob ) >= 0 ? $request->spouse_dob : false ;

        // $birthNumber = isset( $request->birth_number ) && strlen( $request->birth_number ) >= 0 ? $request->birth_number : false ;
        // $bookNumber = isset( $request->book_number ) && strlen( $request->book_number ) >= 0 ? $request->book_number : false ;
        // $national = isset( $request->spouse_national ) && strlen( $request->spouse_national ) >= 0 ? $request->spouse_national : false ;
        // $nationality = isset( $request->spouse_nationality ) && strlen( $request->spouse_nationality ) >= 0 ? $request->spouse_nationality : false ;

        if( $people->weddingCertificates != null ){
            if( $people->weddingCertificates()->count() == 0 ){
                $people->weddingCertificates()->create([
                    'dob' => $dob->format('Y-m-d') ,
                    'spouse_firstname' => $firstname ,
                    'spouse_lastname' => $lastname ,
                    'spouse_enfirstname' => $enfirstname ,
                    'spouse_enlastname' => $enlastname ,
                    'spouse_pob' => $pob ,
                    'spouse_address' => $address ,
                    'spouse_profession' => $profession ,
                    'spouse_profession_organization' => $organization ,
                    'spouse_nid' => $nid ,
                    'spouse_death' => $death ,
                    'created_by' => $user->id ,
                    'updated_by' => $user->id ,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ]);
                $people->update(['marry_status' => $marryStatus ]);
            }else{
                $people->weddingCertificates()->first()->update([
                    'dob' => $dob->format('Y-m-d') ,
                    'spouse_firstname' => $firstname ,
                    'spouse_lastname' => $lastname ,
                    'spouse_enfirstname' => $enfirstname ,
                    'spouse_enlastname' => $enlastname ,
                    'spouse_pob' => $pob ,
                    'spouse_address' => $address ,
                    'spouse_profession' => $profession ,
                    'spouse_profession_organization' => $organization ,
                    'spouse_nid' => $nid ,
                    'spouse_death' => $death ,
                    'updated_by' => $user->id ,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ]);
                $people->update(['marry_status' => $marryStatus ]);
            }
            return response()->json([
                'ok' => true ,
                'message' => 'ព័ត៌មានសំបុត្រអាពាហ៍ពិពាហ៍សម្រាប់បុគ្គលនេះរក្សារទុករួចរាល់។'
            ],201);
        }else{
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានព័ត៌មានសំបុត្រអាពាហ៍ពិពាហ៍សម្រាប់បុគ្គលនេះឡើយ។'
            ],201);
        }
    }
    public function updateParentInformation(Request $request){
        $user = \Auth::user() == null ? null : \Auth::user() ;
        $people = isset( $request->people_id ) && intval( $request->people_id ) > 0
            ? \App\Models\People\People::find( $request->people_id )
            : null ;
        if( $people == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានបញ្ជាក់អំពីម្ចាស់ព័ត៌មាន។'
            ],201);
        }

        // Father fields
        $father_firstname = isset( $request->father_firstname ) && strlen( $request->father_firstname ) >= 0 ? $request->father_firstname : false ;
        $father_lastname = isset( $request->father_lastname ) && strlen( $request->father_lastname ) >= 0 ? $request->father_lastname : false ;
        $father_enfirstname = isset( $request->father_enfirstname ) && strlen( $request->father_enfirstname ) >= 0 ? $request->father_enfirstname : false ;
        $father_enlastname = isset( $request->father_enlastname ) && strlen( $request->father_enlastname ) >= 0 ? $request->father_enlastname : false ;
        $father_pob = isset( $request->father_pob ) && strlen( $request->father_pob ) >= 0 ? $request->father_pob : false ;
        $father_address = isset( $request->father_address ) && strlen( $request->father_address ) >= 0 ? $request->father_address : false ;
        $father_profession = isset( $request->father_profession ) && strlen( $request->father_profession ) >= 0 ? $request->father_profession : false ;
        $father_death = isset( $request->father_death ) && strlen( $request->father_death ) >= 0 ? $request->father_death : false ;
        $father_dob = isset( $request->father_dob ) && strlen( $request->father_dob ) >= 0 ? $request->father_dob : false ;
        // Mother fields
        $mother_firstname = isset( $request->mother_firstname ) && strlen( $request->mother_firstname ) >= 0 ? $request->mother_firstname : false ;
        $mother_lastname = isset( $request->mother_lastname ) && strlen( $request->mother_lastname ) >= 0 ? $request->mother_lastname : false ;
        $mother_enfirstname = isset( $request->mother_enfirstname ) && strlen( $request->mother_enfirstname ) >= 0 ? $request->mother_enfirstname : false ;
        $mother_enlastname = isset( $request->mother_enlastname ) && strlen( $request->mother_enlastname ) >= 0 ? $request->mother_enlastname : false ;
        $mother_pob = isset( $request->mother_pob ) && strlen( $request->mother_pob ) >= 0 ? $request->mother_pob : false ;
        $mother_address = isset( $request->mother_address ) && strlen( $request->mother_address ) >= 0 ? $request->mother_address : false ;
        $mother_profession = isset( $request->mother_profession ) && strlen( $request->mother_profession ) >= 0 ? $request->mother_profession : false ;
        $mother_death = isset( $request->mother_death ) && strlen( $request->mother_death ) >= 0 ? $request->mother_death : false ;
        $mother_dob = isset( $request->mother_dob ) && strlen( $request->mother_dob ) >= 0 ? $request->mother_dob : false ;
        
        $people->update([
            // Father information
            'father_dob' => $father_dob->format('Y-m-d') ,
            'father_firstname' => $father_firstname ,
            'father_lastname' => $father_lastname ,
            'father_enfirstname' => $father_enfirstname ,
            'father_enlastname' => $father_enlastname ,
            'father_pob' => $father_pob ,
            'father_address' => $father_address ,
            'father_profession' => $father_profession ,
            'father_death' => $father_death ,
            // Mother information
            'mother_dob' => $mother_dob->format('Y-m-d') ,
            'mother_firstname' => $mother_firstname ,
            'mother_lastname' => $mother_lastname ,
            'mother_enfirstname' => $mother_enfirstname ,
            'mother_enlastname' => $mother_enlastname ,
            'mother_pob' => $mother_pob ,
            'mother_address' => $mother_address ,
            'mother_profession' => $mother_profession ,
            'mother_death' => $mother_death ,
            // Editor information
            'updated_by' => $user->id ,
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ]);
        return response()->json([
            'ok' => true ,
            'message' => 'កែប្រែព័ត៌មានឪពុកម្ដាយរួចរាល់។'
        ],201);
    }
    public function updateEmmergency(Request $request){
        $user = \Auth::user() == null ? null : \Auth::user() ;
        $people = isset( $request->people_id ) && intval( $request->people_id ) > 0
            ? \App\Models\People\People::find( $request->people_id )
            : null ;
        if( $people == null ){
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានបញ្ជាក់អំពីម្ចាស់ព័ត៌មាន។'
            ],201);
        }

        // Emgerfency fields
        $emergency_firstname = isset( $request->emergency_firstname ) && strlen( $request->emergency_firstname ) >= 0 ? $request->emergency_firstname : false ;
        $emergency_lastname = isset( $request->emergency_lastname ) && strlen( $request->emergency_lastname ) >= 0 ? $request->emergency_lastname : false ;
        $emergency_gender = isset( $request->emergency_gender ) && intval( $request->emergency_gender ) >= 0 ? $request->emergency_gender : false ;
        $emergency_phone = isset( $request->emergency_phone ) && strlen( $request->emergency_phone ) >= 0 ? $request->emergency_phone : false ;
        $emergency_email = isset( $request->emergency_email ) && strlen( $request->emergency_email ) >= 0 ? $request->emergency_email : false ;
        $emergency_address = isset( $request->emergency_address ) && strlen( $request->emergency_address ) >= 0 ? $request->emergency_address : false ;
        $emergency_profession = isset( $request->emergency_profession ) && strlen( $request->emergency_profession ) >= 0 ? $request->emergency_profession : false ;
        $emergency_relationship = isset( $request->emergency_relationship ) && strlen( $request->emergency_profession ) >= 0 ? $request->emergency_profession : false ;
    
        $people->update([
            // father information
            'emergency_firstname' => $emergency_firstname ,
            'emergency_lastname' => $emergency_lastname ,
            'emergency_gender' => $emergency_gender ,
            'emergency_phone' => $emergency_phone ,
            'emergency_email' => $emergency_email ,
            'emergency_address' => $emergency_address ,
            'emergency_profession' => $emergency_profession ,
            'emergency_relationship' => $emergency_relationship ,
            // Editor information
            'updated_by' => $user->id ,
            'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
        ]);
        
        return response()->json([
            'ok' => true ,
            'message' => 'កែប្រែព័ត៌មានទំនាក់ទំនងក្នុងករណីមានអាសន្នរួចរាល់។'
        ],201);
    }
}