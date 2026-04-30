<?php

namespace App\Http\Controllers\Api\Mobile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Document\Document;
use App\Models\Document\Folder;
use Illuminate\Support\Facades\Auth;


class FolderController extends Controller
{
    /**
     * Listing function
     */
    public function index(Request $request){

        
        // Create Query Builder 
        $queryBuilder = new Document();
        
        // dd( $queryBuilder );

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
        if( $request->document_type != "" ){
            $documentTypes = explode(',', $request->document_type );
            if( is_array( $documentTypes ) && !empty( $documentTypes ) ){
                $queryBuilder = $queryBuilder -> where( function ($query ) use ( $documentTypes ) {
                    foreach( $documentTypes as $type ) {
                        $query = $query -> orwhere ( 'document_type', $type ) ;
                    }
                } );
            }
        }

        // Get document type
        if( $request->document_ministry != "" ){
            $documentMinistries = explode(',', $request->document_ministry );
            if( is_array( $documentMinistries ) && !empty( $documentMinistries ) ){
                $queryBuilder = $queryBuilder -> wherein( 'id' , function ($query ) use ( $documentMinistries ) {
                    $query = $query->select('document_id')->wherein('ministry_id', $documentMinistries)->from('document_ministries');
                } );
            }
        }

        // Get document year
        if( $request->year != "" ){
            $queryBuilder = $queryBuilder -> where('year','LIKE','%'.$request->year.'%');
        }
        // Get document registration id
        if( $request -> fid != "" ){
            $queryBuilder = $queryBuilder -> where('fid','LIKE','%'.$request -> fid);
        }

        // return $queryBuilder -> toSql();

        // $perpage = 
        $records = $queryBuilder->orderby('id','desc')->get()
                ->map( function ($record, $index) {
                    $record->objective = strip_tags( $record->objective ) ;
                    $path = storage_path('data') . '/' . $record->pdf;
                    if( !is_file($path) ) $record->pdf = null ;
                    return $record ;
                });
        return response([
            'records' => $records ,
            'message' => count( $records ) > 0 ? "មានឯកសារចំនួន ៖ " . count( $records ) : "មិនមានឯកសារត្រូវជាមួយការស្វែងរកនេះឡើយ !"
        ],200 );
    }
    /**
     * Get Folders of a specific user which has authenticated
     */
    public function user(Request $request){

        // Create Query Builder 
        $queryBuilder = new Folder();

        // Get search string
        if( $request->search != "" ){
            $searchTerms = explode(' ' , $request->search) ;
            if( is_array( $searchTerms ) && !empty( $searchTerms ) ){
                $queryBuilder = $queryBuilder -> where( function ($query ) use ( $searchTerms ) {
                    foreach( $searchTerms as $term ) {
                        $query = $query -> orwhere ( 'name', 'LIKE' , "%".$term."%") ;
                    }
                } );
            }
        }

        $queryBuilder = $queryBuilder->where('user_id', Auth::user()->id );

        $records = $queryBuilder->orderby('name','asc')->get()
                ->map( function ($record, $index) {
                    foreach( $record->documents AS $index => $documentFolder ){
                        $documentFolder -> document ;
                        $documentFolder -> document -> type ;

                        $documentFolder -> document ->objective = strip_tags( $documentFolder -> document ->objective ) ; // clear some tags that product by the editor
                        $path = storage_path('data') . '/' . $documentFolder -> document -> pdf ; // create the link to pdf file
                        if( !is_file($path) ) $documentFolder -> document -> pdf = null ; // set the pdf link to null if it does not exist

                    }
                    return $record ;
                });

        return response([
            'records' => $records ,
            'message' => count( $records ) > 0 ? " មានឯកសារចំនូួន ៖ " . count( $records ) : "មិនមានកម្រងឯកសារត្រូវជាមួយការស្វែងរកនេះឡើយ !"
        ],200 );
    }
    // Save the folder 
    public function store(Request $request){
        if( $request->name != "" && Auth::user() != null ){
            $folder = new \App\Models\Document\Folder();
            $folder->name = $request->name ;
            $folder->user_id = Auth::user()->id ;
            $folder->save() ;
            $folder->user ;
            $folder->documents ;
            // User does exists
            return response([
                'record' => $folder ,
                'message' => 'កម្រងឯកសារ '.$folder->name.' បានរក្សារួចរាល់ !' 
                ],
                200
            );
        }else{
            // User does not exists
            return response([
                'user' => null ,
                'message' => 'សូមបញ្ចូលឈ្មោះកម្រងឯកសារជាមុនសិន !' ],
                201
            );
        }
    }
    // delete the folder 
    public function delete(Request $request){
        if( $request->id != "" && Auth::user() != null ){
            $folder = \App\Models\Document\Folder::find($request->id);
            if( $folder != null ){
                $record = $folder ;
                // Check for the documents within the folder
                // If there is/are documents within the folder then notify user first
                // process delete , also delete the related document within this folder [Note: we only delete the relationship of folder and document]
                foreach( $folder -> documents as $documentFolder ){
                    $documentFolder -> delete ();
                }
                $folder->delete();
                return response([
                    'record' => $record ,
                    'message' => "កម្រងឯកសារ " . $record->name . " បានលុបរួចរាល់ !" 
                    ],
                    200
                );
            }else{
                return response([
                    'record' => $folder ,
                    'message' => "កម្រងឯកសារនេះមិនមានឡើយ !"
                    ],
                    201
                );
            }
        }else{
            // User does not exists
            return response([
                'user' => null ,
                'message' => 'សូមបញ្ជាក់កម្រងឯកសារដែលអ្នកចង់លុប !' ],
                201
            );
        }
    }
    // Remove document from folder
    public function addDocumentToFolder($folderId, $documentId){
        if( $folderId != "" && $documentId != "" && Auth::user() != null ){
            $documentFolder = \App\Models\Document\DocumentFolder::where('folder_id', $folderId)
                ->where('document_id' , $documentId )->first();
            if( $documentFolder == null ){
                $documentFolder = new \App\Models\Document\DocumentFolder();
                $documentFolder -> folder_id = $folderId ;
                $documentFolder -> document_id = $documentId ;
                $documentFolder->save();
                return response([
                    'record' => $documentFolder ,
                    'message' => "បានបញ្ចូលឯកសារ ចូលទៅក្នុងកម្រងឯកសារ រួចរាល់ !"
                    ],
                    200
                );
            }else{
                return response([
                    'record' => $documentFolder ,
                    'message' => "ឯកសារនេះមានក្នុងកម្រងឯកសារនេះរួចរាល់ហើយ !"
                    ],
                    201
                );
            }
        }else{
            // User does not exists
            return response([
                'record' => null ,
                'message' => 'សូមបំពេញព័ត៌មាន អោយបានគ្រប់គ្រាន់ !' ],
                201
            );
        }
    }
    public function checkDocument(Request $request){
        $folder = \App\Models\Document\Folder::find( $request->id );
        if( $folder !== null ){
            if( count( $folder -> documents ) ){
                // There is/are document(s) within this folder
                return response([
                    'record' => $folder ,
                    'message' => 'កម្រងឯកសារនេះ មានឯកសារចំនួន '. count( $folder -> documents ) .' !' ],
                    200
                );
            }else{
                // There is no document within this folder
                return response([
                    'record' => $folder ,
                    'message' => 'កម្រងឯកសារនេះ មិនមានឯកសារឡើយ !' ],
                    201
                );
            }
        }else{
            return response([
                'record' => null ,
                'message' => 'កម្រងឯកសារនេះ មិនមានឡើយ !' ],
                201
            );
        }
    }
}
