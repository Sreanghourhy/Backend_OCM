<?php

namespace App\Http\Controllers\Api\Law\Book;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Law\Book\Book;
use App\Models\Law\Book\Kunty;
use App\Models\Law\Book\Chapter;
use App\Models\Law\Book\Matika;
use App\Models\Law\Book\Part;
use App\Models\Law\Book\Section;
use App\Models\Law\Book\Matra;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\CrudController;

use Telegram\Bot\Laravel\Facades\Telegram;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Telegram\TelegramUpdates;

class TelegramBotController extends Controller
{

    private $selectedFields ;
    private $server = "https://api.telegram.org/bot" ;
    private $token = "7203998499:AAHumUoUh75ycD9Pa4Nc_-lK4pfHts-56-w" ;
    public function __construct(){
        $this->server = $this->server.$this->token;
        $this->selectedFields = ['id', 'title','description', 'color' , 'cover' , 'complete' , 'created_by', 'updated_by' , 'pdf' , 'created_at', 'updated_at' ] ;
    }

    public function handleWebhook(Request $request)
    {
        $result = $this->curlPost( $this->server.'/sendMessage',[
            'chat_id' => "38846216" ,
            'text' => 'បានទទួលការហៅពី ខាង តេលេក្រាម។' ,
            'protect_content' => true
        ],true);
        return response()->json( json_decode( $result ) ,200) ;
    }

    public function getUpdates(Request $request){        

        $data = $this->curlGet( $this->server.'/getUpdates' );

        if( strlen( $data) > 0 ) {
            $data = json_decode( $data );
            if( $data->ok == true ){
                $messages = collect( $data->result );
                $chatId = $messages->last()->message->chat->id ;
                // return response()->json( $messages->last()->message ,200) ;
                $result = $this->curlPost( $this->server.'/sendMessage',[
                    'chat_id' => $chatId ,
                    'text' => 'សាកល្បង' ,
                    'protect_content' => true
                ],true);
                return response()->json( json_decode( $result ) ,200) ;
            }
        }
        return response()->json([
            'ok' => false ,
            'message' => "Failed"
        ],500);
    }


    /** Get a list of Archives */
    // public function index(Request $request){

    //     /** Format from query string */
    //     $search = isset( $request->search ) && $request->serach !== "" ? $request->search : false ;
    //     $perPage = isset( $request->perPage ) && $request->perPage !== "" ? $request->perPage : 50 ;
    //     $page = isset( $request->page ) && $request->page !== "" ? $request->page : 1 ;
    //     // $number = isset( $request->number ) && $request->number !== "" ? $request->number : false ;
    //     // $type = isset( $request->type ) && $request->type !== "" ? $request->type : false ;
    //     // $unit = isset( $request->unit ) && $request->unit !== "" ? $request->unit : false ;
    //     // $date = isset( $request->date ) && $request->date !== "" ? $request->date : false ;


    //     $queryString = [
    //         // "where" => [
    //         //     'default' => [
    //         //         [
    //         //             'field' => 'type_id' ,
    //         //             'value' => $type === false ? "" : $type
    //         //         ]
    //         //     ],
    //         //     'in' => [] ,
    //         //     'not' => [] ,
    //         //     'like' => [
    //         //         [
    //         //             'field' => 'number' ,
    //         //             'value' => $number === false ? "" : $number
    //         //         ],
    //         //         [
    //         //             'field' => 'year' ,
    //         //             'value' => $date === false ? "" : $date
    //         //         ]
    //         //     ] ,
    //         // ] ,
    //         // "pivots" => [
    //         //     $unit ?
    //         //     [
    //         //         "relationship" => 'units',
    //         //         "where" => [
    //         //             "in" => [
    //         //                 "field" => "id",
    //         //                 "value" => [$request->unit]
    //         //             ],
    //         //         // "not"=> [
    //         //         //     [
    //         //         //         "field" => 'fieldName' ,
    //         //         //         "value"=> 'value'
    //         //         //     ]
    //         //         // ],
    //         //         // "like"=>  [
    //         //         //     [
    //         //         //        "field"=> 'fieldName' ,
    //         //         //        "value"=> 'value'
    //         //         //     ]
    //         //         // ]
    //         //         ]
    //         //     ]
    //         //     : []
    //         // ],
    //         "pagination" => [
    //             'perPage' => $perPage,
    //             'page' => $page
    //         ],
    //         "search" => $search === false ? [] : [
    //             'value' => $search ,
    //             'fields' => [
    //                 'title', 'description' 
    //             ]
    //         ],
    //         "order" => [
    //             'field' => 'id' ,
    //             'by' => 'desc'
    //         ],
    //     ];

    //     $request->merge( $queryString );

    //     $crud = new CrudController(new RecordModel(), $request, $this->selectedFields);
    //     $crud->setRelationshipFunctions([
    //         /** relationship name => [ array of fields name to be selected ] */
    //         'createdBy' => ['id', 'firstname', 'lastname' ,'username'] ,
    //         'updatedBy' => ['id', 'firstname', 'lastname', 'username']
    //     ]);
    //     $builder = $crud->getListBuilder();

    //     /** Filter the record by the user role */
    //     // if( ( $user = $request->user() ) !== null ){
    //     //     /** In case user is the administrator, all archives will show up */
    //     //     if( array_intersect( $user->roles()->pluck('id')->toArray() , [2,3,4] ) ){
    //     //         /** In case user is the super, auditor, member then the archives will show up if only that archives are own by them */
    //     //         $builder->where('created_by',$user->id);
    //     //     }else{
    //     //         /** In case user is the customer */
    //     //         /** Filter archives by its type before showing to customer */
    //     //     }
    //     // }

    //     $responseData = $crud->pagination(true, $builder,[
    //         'description' => function($description){
    //             return html_entity_decode( strip_tags( $description ) );
    //         } ,
    //         'title' => function($title){
    //             return html_entity_decode( strip_tags( $title ) );
    //         }
    //     ]);
    //     $responseData['message'] = __("crud.read.success");
    //     $responseData['ok'] = true ;
    //     return response()->json($responseData);
    // }

    private function curlGet($url,$params=[]){
        libxml_use_internal_errors(true);
        $data = null;
        $url = $url . ( !empty($params) && $postMethod == false ? "?" . http_build_query($params, '', '&amp;') : "" ) ;
        $ch = curl_init();
        curl_setopt( $ch , CURLOPT_SSL_VERIFYPEER , 1 );
        curl_setopt( $ch , CURLOPT_URL , $url );
        curl_setopt( $ch , CURLOPT_RETURNTRANSFER , 1 );
        curl_setopt( $ch , CURLOPT_USERAGENT , "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13" );
        $data = curl_exec( $ch );
        curl_close( $ch );
        return $data ;
    }
    private function curlPost($url,$params=[]){
        libxml_use_internal_errors(true);
        $data = null;
        $ch = curl_init();
        curl_setopt( $ch , CURLOPT_POST , 1 );
        curl_setopt( $ch , CURLOPT_POSTFIELDS, http_build_query($params) ) ;
        curl_setopt( $ch , CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded'] ) ;

        curl_setopt( $ch , CURLOPT_SSL_VERIFYPEER , 1 );
        curl_setopt( $ch , CURLOPT_URL , $url );
        curl_setopt( $ch , CURLOPT_RETURNTRANSFER , 1 );
        
        curl_setopt( $ch , CURLOPT_USERAGENT , "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13" );
        $data = curl_exec( $ch );
        curl_close( $ch );
        return $data ;
    }
}