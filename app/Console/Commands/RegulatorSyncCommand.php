<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RegulatorSyncCommand extends Command 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'regulator:sync';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronizatio all the regulators from the old server of EDMS to new server.';
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /**
         * Create a directory for the regulators
         */
        /**
         * Get all the regulator's types
         */
        echo 'PREPARING SYNC' . PHP_EOL;
        echo '=> READING REGULATORS IDS' . PHP_EOL;
        $data = $this->curlReadWithUrl( 'http://192.168.200.100:8000/api/ids' );
        $data = json_decode( $data );
        echo '=> START READING REGULATORS BY ID' . PHP_EOL;
        foreach( $data->ids as $index => $id ){
            if( $id < 62953 ) continue;
            echo "=> " . ($index+1) . ". READ REGULATOR ID : " . $id . PHP_EOL;
            $data = $this->curlReadWithUrl( 'http://192.168.200.100:8000/api/read/'.$id.'/fetch' );
            $data = json_decode( $data );
            $document = $data->record;
            $regulator = null ;
            if( $document != null && $document != false ){
                echo "=> READ LOCAL REGULATOR" . PHP_EOL;
                $regulator = \App\Models\Regulator\Regulator::where([
                    'fid' => str_pad( $document->fid , 4, "0", STR_PAD_LEFT) ,
                    'year' => $document->document_year ,
                    'document_type' => $document->document_type
                ])->first();
                if( $regulator == null ){
                    echo "=> LOCAL REGULAOR IS NOT AVAILABLE. CREATING..." . PHP_EOL;
                    $creator = \App\Models\User::where([
                        'phone' => $document->created_by->phone,
                        'email' => $document->created_by->email,
                        'username' => $document->created_by->username
                    ])->first();
                    if( $creator == null ){
                        echo "=> CREATE CREATOR OF THE DOCUMENT..." . PHP_EOL;
                        $creator = \App\Models\User::create([
                            'people_id' => $document->created_by->people_id  ,
                            'lastname' => $document->created_by->lastname  ,
                            'firstname' => $document->created_by->firstname  ,
                            'phone' => $document->created_by->phone  ,
                            'username' => $document->created_by->username  ,
                            'email' => $document->created_by->email  ,
                            'password' => $document->created_by->password  ,
                            'active' => $document->created_by->active  
                        ]);
                        $person = \App\Models\People\People::create([
                            'firstname' => $creator->firstname , 
                            'lastname' => $creator->lastname , 
                            'gender' => 1 , 
                            'dob' => \Carbon\Carbon::now()->format('Y-m-d') , 
                            'mobile_phone' => $creator->phone , 
                            'email' => $creator->email , 
                        ]);
                        $creator->people_id = $person->id ;
                        $creator->save();
                    }
                    $editor = \App\Models\User::where([
                        'phone' => $document->updated_by->phone,
                        'email' => $document->updated_by->email,
                        'username' => $document->updated_by->username
                    ])->first();
                    if( $editor == null ){
                        echo "=> CREATE EDITOR OF THE DOCUMENT..." . PHP_EOL;
                        $editor = \App\Models\User::create([
                            'people_id' => $document->updated_by->people_id  ,
                            'lastname' => $document->updated_by->lastname  ,
                            'firstname' => $document->updated_by->firstname  ,
                            'phone' => $document->updated_by->phone  ,
                            'username' => $document->updated_by->username  ,
                            'email' => $document->updated_by->email  ,
                            'password' => $document->updated_by->password  ,
                            'active' => $document->updated_by->active  
                        ]);
                        $person = \App\Models\People\People::create([
                            'firstname' => $creator->firstname , 
                            'lastname' => $creator->lastname , 
                            'gender' => 1 , 
                            'dob' => \Carbon\Carbon::now()->format('Y-m-d') , 
                            'mobile_phone' => $creator->phone , 
                            'email' => $creator->email , 
                        ]);
                        $editor->people_id = $person->id ;
                        $editor->save();
                    }

                    $regulator = \App\Models\Regulator\Regulator::create([
                        'fid' => $document->fid  ,
                        'title' => $document->title  ,
                        'objective' => $document->objective  ,
                        'pdf' => $document->pdf  ,
                        'year' => $document->document_year  ,
                        'document_type' => $document->document_type  ,
                        'publish' => $document->publish  ,
                        'active' => $document->publish  ,
                        'pdf' => $document->pdf ,
                        'approved_by' => $document->approved_by  ,
                        'created_by' => $creator->id ,
                        'updated_by' => $editor->id
                    ]);

                    echo "=> LINK REGULATOR TO ORGANIZATIOS." . PHP_EOL;
                    
                    array_map(function($organization) use($document,$regulator){
                        // "id": 3,
                        // "countesy_id": 2,
                        // "name": " ប៊ុន អ៊ុយ",
                        $name = preg_replace('/\s+/', '', $organization->name ) ;
                        $organization = \App\Models\Organization\Organization::where(function($query){
                            $query->where('pid',163)
                            ->orWhere( 'model' , 'App\Models\Regulator\Tag\Orgainzation' );
                        })
                        ->where( 'name' , $name )
                        ->first();
                        if( $organization == null ){
                            $organization = \App\Models\Organization\Organization::create([
                                'name' => $name ,
                                'pid' => 163 ,
                                'tpid' => 163 ,
                            ]);
                        }
                        $regulator->organizations()->sync([$organization->id]);
                    }, $document->ministries );
                    
                    echo "=> LINK REGULATOR TO OWN ORGANIZATIOS." . PHP_EOL;
                    array_map(function($own_organization) use($document,$regulator){
                        // "id": 3,
                        // "countesy_id": 2,
                        // "name": " ប៊ុន អ៊ុយ",
                        $name = preg_replace('/\s+/', '', $own_organization->name ) ;
                        $own_organization = \App\Models\Organization\Organization::where(function($query){
                            $query->where('pid',163)
                            ->orWhere( 'model' , 'App\Models\Regulator\Tag\Orgainzation' );
                        })
                        ->where( 'name' , $name )
                        ->first();
                        if( $own_organization == null ){
                            $own_organization = \App\Models\Organization\Organization::create([
                                'name' => $name ,
                                'pid' => 163 ,
                                'tpid' => 163 ,
                            ]);
                        }
                        $regulator->ownOrganizations()->sync([$own_organization->id]);
                    }, $document->own_ministries );
                    
                    echo "=> LINK REGULATOR TO RELATED ORGANIZATIOS." . PHP_EOL;
                    array_map(function($related_organization) use($document,$regulator){
                        // "id": 3,
                        // "countesy_id": 2,
                        // "name": " ប៊ុន អ៊ុយ",
                        $name = preg_replace('/\s+/', '', $related_organization->name ) ;
                        $related_organization = \App\Models\Organization\Organization::where(function($query){
                            $query->where('pid',163)
                            ->orWhere( 'model' , 'App\Models\Regulator\Tag\Orgainzation' );
                        })
                        ->where( 'name' , $name )
                        ->first();
                        if( $related_organization == null ){
                            $related_organization = \App\Models\Organization\Organization::create([
                                'name' => $name ,
                                'pid' => 163 ,
                                'tpid' => 163 ,
                            ]);
                        }
                        $regulator->relatedOrganizations()->sync([$related_organization->id]);
                    },$document->related_ministries);
                    
                    echo "=> LINK REGULATOR TO SIGNATURES." . PHP_EOL;
                    array_map(function($signature) use($document,$regulator){
                        // "id": 3,
                        // "countesy_id": 2,
                        // "name": " ប៊ុន អ៊ុយ",
                        $name = preg_replace('/\s+/', '', $signature->name ) ;
                        $signature = \App\Models\Regulator\Tag\Signature::where(function($query){
                            $query->where('pid',37)
                            ->orWhere( 'model' , 'App\Models\Regulator\Tag\Signature' );
                        })
                        ->where( 'name' , $name )
                        ->first();
                        if( $signature == null ){
                            $signature = \App\Models\Regulator\Tag\Signature::create([
                                'name' => $name ,
                                'pid' => 37 ,
                                'tpid' => 37 ,
                            ]);
                        }
                        $regulator->signatures()->sync([$signature->id]);
                    }, $document->signatures );
                    $regulator->save();
                }
            }
        }
        // $regulatorTypeBuilder = \App\Models\Regulator\Tag\Type::where('model','App\Models\Regulator\Tag\Type')->first()->children();
        // if( $this->argument('types') != null && is_array( $this->argument( 'types' ) ) ){
        //     $regulatorTypeBuilder->whereIn('id', $this->argument('types') );
        // }
        // echo 'THERE ARE/IS '. ( $regulatorTypeBuilder->count() ) .' REGULATOR TYPE(s) WILL BE BACKING UP.' . PHP_EOL;
        // /**
        //  * Create backup folder
        //  */
        // echo "CHECK BACKUP FOLDER." ;
        // $folderName = 'backup-' . \Carbon\Carbon::now()->format('Y-m-d-H-i-s');
        // if( !\Storage::disk('regulator')->exists( $folderName ) ){
        //     \Storage::disk('regulator')->makeDirectory( $folderName );
        // }
        
        // // Succed create folder
        // echo "START BACKING UP THE REGULATORS TO FOLDER ( " . $folderName . " ) : " . PHP_EOL ;
        // $totalRegulators = 0 ;
        // $totalFailedCopies = 0 ;
        // $failedIds = [] ;
        // $regulatorTypeBuilder->get()->each(function( $regulatorType ) use( $folderName , &$totalRegulators , &$totalFailedCopies , $failedIds ) {
        //     echo "BACKING UP REGULATOR TYPE " . $regulatorType->id . " : " . $regulatorType->name . " ( " . $regulatorType->regulators->count() . " ) " . PHP_EOL ;
        //     $totalRegulators += $regulatorType->regulators->count();
        //     // Code backing up go here
        //     $regulatorType->regulators()->get()->each(function($regulator, $index) use( $regulatorType , $folderName , &$totalRegulators , &$totalFailedCopies , $failedIds  ) {

        //         $source = storage_path() . '/data/'. $regulator->pdf ;
        //         $destination = storage_path() . '/data/'.$folderName.'/'. str_replace( ['regulators','/'] , [''] , $regulator->pdf ) ;

        //         if( 
        //             $regulator->pdf != "" &&
        //             file_exists( $source ) &&
        //             is_file ( $source )
        //          ){
        //             // if( copy( $source , $destination ) ){
        //             //     echo "+ REGULATOR TYPE : " . $regulatorType->id . " - INDEX : " . $index + 1 . " - REGULATOR ID : " . $regulator->id . " => OK ." . PHP_EOL ;
        //             // }else{
        //             //     $totalFailedCopies++ ;
        //             //     $failedIds[] =  $regulatorType->id ;
        //                 // echo "+ REGULATOR TYPE : " . $regulatorType->id . " - INDEX : " . $index + 1 . " - REGULATOR ID : " . $regulator->id . " => FALIED ." . PHP_EOL ;
        //             // }
        //         }else{
        //             $totalFailedCopies++ ;
        //             $failedIds[] =  $regulatorType->id ;
        //             // echo " NO PDF FILE." . PHP_EOL;
        //         }
        //     });
        // });
        // echo "TOTAL REGULATOR(s) : " . $totalRegulators . " , FAILED COPIED : " . $totalFailedCopies . ' , SUCCEED : ' . ( $totalRegulators - $totalFailedCopies ) . PHP_EOL ;
        // if( $totalFailedCopies > 0 ){
        //     echo "ID(s) OF REGULATOR THAT FAILED TO COPY : " . PHP_EOL;
        //     echo implode( ' , ' , $failedIds ) . PHP_EOL;
        // }

        return Command::SUCCESS;
    }
    private function curlReadWithUrl($url){
        libxml_use_internal_errors(true);
        $data = null;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch,CURLOPT_URL, $url );
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13");
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
