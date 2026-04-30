<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class ReadUsersCommand extends Command 
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'read:accounts';
    // { types } // normal variable
    // { types* } // array variable. input with space
    // { type?* } // array variable but optional with the '?'

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Read people card user and create officer is need';

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
        echo 'PREPARING IMPORTING USERS...' . PHP_EOL;
        $inputFileName = storage_path('ocmstaffs.xlsx');
        /** Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $worksheet = $spreadsheet->getActiveSheet();
        
        $maxDataRow = $worksheet->getHighestDataRow();
        $maxDataColumn = $worksheet->getHighestDataColumn();
        $columnName = [ 'a' , 'b' , 'c' , 'd' , 'e' , 'f' , 'g' , 'h' , 'i' , 'g' , 'k' ];
        $rowIndex = 1 ;
        $records = [];
        $creator = \App\Models\User::whereHas('roles',function($q){ $q->where('role_id',2); })->first();
        while( $rowIndex <= $maxDataRow ) {
            $record = [
                'officerId' => null ,
                'khname' => [
                    'lastname' => '' ,
                    'firstname' => ''
                ] ,
                'enname' => [
                    'lastname' => '' ,
                    'firstname' => ''
                ] ,
                'gender' => null ,
                'dob' => null
            ] ;
            if( intval( $worksheet->getCell( 'b'.$rowIndex )->getValue() ) > 0 ){
                // Column index 
                // index , card_code , lastname + firstname , english name , dob , gender , address , organization , position , qrcode , photo
                $record['officerId'] = $worksheet->getCell('b'.$rowIndex)->getValue() ;
                if( strlen( $worksheet->getCell('c'.$rowIndex)->getValue() ) > 0 ){
                    $names = explode( ' ' , $worksheet->getCell('c'.$rowIndex)->getValue()  );
                    if( count( $names ) > 0 ){
                        $record['khname']['lastname'] = trim($names[0]);
                        unset( $names[0] );
                        $record['khname']['firstname'] = trim( implode( ' ' , $names ) );
                    }
                }
                if( strlen( $worksheet->getCell('d'.$rowIndex)->getValue() ) > 0 ){
                    $names = explode( ' ' , $worksheet->getCell('d'.$rowIndex)->getValue()  );
                    if( count( $names ) > 0 ){
                        $record['enname']['lastname'] = trim($names[0]);
                        unset( $names[0] );
                        $record['enname']['firstname'] = trim( implode( ' ' , $names ) );
                    }
                }
                if( strlen( $worksheet->getCell('e'.$rowIndex)->getValue() ) > 0 ){
                    $record['gender'] = $worksheet->getCell('e'.$rowIndex)->getValue() == "ប" ? 1 : ( $worksheet->getCell('e'.$rowIndex)->getValue() == "ស" ? 0 : null ) ;
                }
                // $record['gender'] = strlen( $worksheet->getCell('e'.$rowIndex)->getValue() ) ? ( $worksheet->getCell('f'.$rowIndex)->getValue() == 'ប' ? 1 : 0 )  : '' ;
                if( strlen( $worksheet->getCell('f'.$rowIndex)->getFormattedValue() ) >= 10 ){
                    list($d,$m,$y) = explode( '/' , $worksheet->getCell('f'.$rowIndex)->getFormattedValue() );
                    $record['dob'] = \Carbon\Carbon::parse( $y.'-'.$m.'-'.$d )->format('Y-m-d') ;
                }

                $builder = \App\Models\People\People::where([
                    'firstname' => $record['khname']['firstname'] ,
                    'lastname' => $record['khname']['lastname']
                ]);
                // // if( intval( $record['gender'] ) >= 0 ){
                // //     $builder->where('gender' , $record['gender'] );
                // // }
                $people = $builder->first();
                if( $people != null ){
                    
                    $people->update([
                        'public_key' => md5( \Carbon\Carbon::now()->format('YmdHis') . $record['officerId'] . $people->id ) ,
                        'enfirstname' => $record['enname']['firstname'] ,
                        'enlastname' => $record['enname']['lastname'] ,
                        'dob' => $record['dob']
                    ]);
                    echo $rowIndex . ". UPDATED PEOPLE: " . $people->enlastname . ' ' . $people->enfirstname . ' , DOB : ' . $people->dob . PHP_EOL ;
                    // echo ( $rowIndex. ". " . $people->lastname . ' ' . $people->firstname . ' , OFFICER : ' . $people->officers->count() ) . PHP_EOL ;
                    // if( $people->officers->count() > 0 && strlen( $people->officers->first()->code ) <= 0 ){
                    //     $people->officers->first()->update([
                    //         'code' => $record['officerId']
                    //     ]);
                    //     echo "UPDATED : " . $people->officers->first()->code . PHP_EOL ;
                    // }
                    // echo ( $rowIndex . ". " . $people->id . " => " . $people->lastname . ' ' . $people->firstname ) . PHP_EOL ;
                    // $people->update([
                    //     'public_key' => md5( \Carbon\Carbon::now()->format('YmdHis') . $record['officerId'] ) ,
                    //     'enfirstname' => $record['enname']['firstname'] ,
                    //     'enlastname' => $record['enname']['lastname'] ,
                    //     'email' => strtolower( $record['enname']['lastname'].'.'.$record['enname']['firstname'].'@ocm.gov.kh' ),
                    //     'dob' => $record['dob'] ,
                    // ]);
                    // if( $people->officers->count() <= 0 ){
                    //     $officer = $people->officers()->create([
                    //         'code' => $record['officerId'] ,
                    //         'people_id' => $people->id ,
                    //         'date' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    //         'organization_id' => 0 ,
                    //         'position_id' => 0 ,
                    //         'rank' => 0
                    //     ]);
                    //     echo  $rowIndex . ". CREATE OFFICER : " . $officer->code . PHP_EOL ;
                    // }

                    // if( $people->cards()->count() <= 0 ){
                    //     $card = $people->cards()->create([
                    //         'number' => "OCM-ORG-".str_pad( $people->id , 4 , '0' , STR_PAD_LEFT ) ,
                    //         'uuid' => md5( \Carbon\Carbon::now()->format('YmdHis') . $record['officerId'] . $people->id ) ,
                    //         'people_id' => $people->id ,
                    //         'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                    //         'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                    //     ]);
                    //     echo  $rowIndex . ". CREATE CARD : " . $card->number . PHP_EOL ;
                    // }

                    // if( $people->users()->count() <= 0 ){
                    //     $user = \App\Models\User::create([
                    //         'firstname' => $people->firstname,
                    //         'lastname' => $people->lastname,
                    //         'name' => $people->lastname . ' ' . $people->firstname ,
                    //         'username' => $people->enlastname.''.$people->enfirstname,
                    //         'email' => strtolower( $people->email ),
                    //         'active' => 1 , // Unactive user
                    //         'avartar_url' => '' ,
                    //         'password' => bcrypt('1234567890+1') ,
                    //         'email_verified_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    //         'created_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    //         'updated_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    //         'people_id' => $people->id
                    //     ]);
                    //     $clientClientRole = \App\Models\Role::where('name','backend')->orWhere('name','backend')->first();
                    //     if( $clientClientRole != null ){
                    //         $user->assignRole( $clientClientRole );
                    //     }
                    // }    

                    $rowIndex++;
                    continue;
                }

                /**
                 * Create people information
                 */
                // $people = \App\Models\People\People::create([
                //     'public_key' => md5( \Carbon\Carbon::now()->format('YmdHis') . $record['officerId'] ) ,
                //     'firstname' => $record['khname']['firstname'] ,
                //     'lastname' => $record['khname']['lastname'] ,
                //     'enfirstname' => $record['enname']['firstname'] ,
                //     'enlastname' => $record['enname']['lastname'] ,
                //     'email' => $record['enname']['lastname'].'.'.$record['enname']['firstname'].'@ocm.gov.kh' ,
                //     'gender' => $record['gender'] ,
                //     'dob' => $record['dob'] ,
                //     'address' => null ,
                //     'image' => null ,
                //     'created_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                //     'updated_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                //     'created_by' => $creator == null ? 0 : $creator->id ,
                //     'updated_by' => $creator == null ? 0 : $creator->id
                // ]);


                // $officer = $people->officers()->create([
                //     'code' => $record['officerId'] ,
                //     'people_id' => $people->id ,
                //     'date' => \Carbon\Carbon::now()->format('Y-m-d') ,
                //     'organization_id' => 0 ,
                //     'position_id' => 0 ,
                //     'rank' => 0
                // ]);

                // $card = $people->cards()->create([
                //     'number' => "OCM-ORG-".str_pad( $people->id , 4 , '0' , STR_PAD_LEFT ) ,
                //     'uuid' => md5( \Carbon\Carbon::now()->format('YmdHis') . $record['officerId'] . $people->id ) ,
                //     'people_id' => $people->id ,
                //     'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                //     'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                // ]);

                // // /**
                // //  * Create people's account
                // //  */

                // $user = \App\Models\User::create([
                //     'firstname' => $people->firstname,
                //     'lastname' => $people->lastname,
                //     'name' => $people->lastname . ' ' . $people->firstname ,
                //     'username' => $people->enlastname.''.$people->enfirstname,
                //     'email' => $people->email,
                //     'active' => 1 , // Unactive user
                //     'avartar_url' => '' ,
                //     'password' => bcrypt('1234567890+1') ,
                //     'email_verified_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                //     'created_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                //     'updated_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                //     'people_id' => $people->id
                // ]);
                // $clientClientRole = \App\Models\Role::where('name','backend')->orWhere('name','backend')->first();
                // if( $clientClientRole != null ){
                //     $user->assignRole( $clientClientRole );
                // }
                // echo ( $rowIndex . ". " . $officer->code . " => " . $people->lastname . ' ' . $people->firstname ) . PHP_EOL ;
            }
            $records[] = $record ;
            $rowIndex++;
        }
        echo "FINISHED" ;
        return Command::SUCCESS;
    }
    public function readOne(){
        /**
         * Create a directory for the regulators
         */
        /**
         * Get all the regulator's types
         */
        echo 'PREPARING IMPORTING USERS...' . PHP_EOL;
        $inputFileName = storage_path('ldlist.xlsx');
        /** Load $inputFileName to a Spreadsheet Object  **/
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $worksheet = $spreadsheet->getActiveSheet();
        
        $maxDataRow = $worksheet->getHighestDataRow();
        $maxDataColumn = $worksheet->getHighestDataColumn();
        $columnName = [ 'a' , 'b' , 'c' , 'd' , 'e' , 'f' , 'g' , 'h' , 'i' , 'g' , 'k' ];
        $rowIndex = 1 ;
        $records = [];
        $creator = \App\Models\User::whereHas('roles',function($q){ $q->where('role_id',2); })->first();
        
        while( $rowIndex <= $maxDataRow ) {
            $record = [] ;
            if( intval( $worksheet->getCell( 'a'.$rowIndex )->getValue() ) > 0 ){
                // Column index 
                // index , card_code , lastname + firstname , english name , dob , gender , address , organization , position , qrcode , photo
                $record['index'] = $worksheet->getCell('a'.$rowIndex)->getValue();
                $record['card_code'] = $worksheet->getCell('b'.$rowIndex)->getValue() ;
                $record['khname'] = strlen( $worksheet->getCell('c'.$rowIndex)->getValue() ) ? $worksheet->getCell('c'.$rowIndex)->getValue() : '' ;
                list($lastname,$firstname) = explode( ' ' , $record['khname'] );
                $record['enname'] = strlen( $worksheet->getCell('d'.$rowIndex)->getValue() ) ? $worksheet->getCell('d'.$rowIndex)->getValue() : '' ;
                list($enlastname,$enfirstname) = explode( ' ' , $record['enname'] );
                $record['dob'] = \Carbon\Carbon::parse( $worksheet->getCell('e'.$rowIndex)->getFormattedValue() )->format('Y-m-d') ;
                // floatval( $worksheet->getCell('e'.$rowIndex)->getValue() ) > 0 ? \Carbon\Carbon::createFromTimestamp( $worksheet->getCell('e'.$rowIndex)->getValue() )->format( "Y-m-d") : '' ;
                $record['gender'] = strlen( $worksheet->getCell('f'.$rowIndex)->getValue() ) ? $worksheet->getCell('f'.$rowIndex)->getValue() : '' ;
                $record['address'] = strlen( $worksheet->getCell('g'.$rowIndex)->getValue() ) ? $worksheet->getCell('g'.$rowIndex)->getValue() : '' ;
                $record['organization'] = strlen( $worksheet->getCell('h'.$rowIndex)->getValue() ) ? $worksheet->getCell('h'.$rowIndex)->getValue() : '' ;
                $record['position'] = strlen( $worksheet->getCell('i'.$rowIndex)->getValue() ) ? $worksheet->getCell('i'.$rowIndex)->getValue() : '' ;
                $record['photo'] = '' ;

                /**
                 * Create people information
                 */
                $people = \App\Models\People\People::create([
                    'firstname' => $firstname ,
                    'lastname' => $lastname ,
                    'enfirstname' => $enfirstname ,
                    'enlastname' => $enlastname ,
                    'email' => $enlastname.'.'.$enfirstname.'@ocm.gov.kh' ,
                    'gender' => $record['gender'] == 'ប្រុស' ? 1 : 0 ,
                    'dob' => $record['dob'] ,
                    'address' => $record['address'] ,
                    'image' => $record['photo'] ,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'created_by' => $creator == null ? 0 : $creator->id ,
                    'updated_by' => $creator == null ? 0 : $creator->id
                ]);

                $card = $people->cards()->create([
                    'number' => $record['card_code'] ,
                    'uuid' => md5( \Carbon\Carbon::now()->format('YmdHis') . $people->id ) ,
                    'people_id' => $people->id ,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s') ,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
                ]);

                /**
                 * Create people's account
                 */

                $user = \App\Models\User::create([
                    'firstname' => $people->firstname,
                    'lastname' => $people->lastname,
                    'name' => $people->lastname . ' ' . $people->firstname ,
                    'username' => $people->enlastname.''.$people->enfirstname,
                    'email' => $people->email,
                    'active' => 1 , // Unactive user
                    'avartar_url' => $record['photo'] ,
                    'password' => bcrypt('1234567890+1') ,
                    'email_verified_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'created_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'updated_at' => \Carbon\Carbon::now()->format('Y-m-d') ,
                    'people_id' => $people->id
                ]);

                /**
                 * Assign role to user
                 */
                $clientClientRole = \App\Models\Role::where('name','backend')->orWhere('name','backend')->first();
                if( $clientClientRole != null ){
                    $user->assignRole( $clientClientRole );
                }

                if ( 
                    $worksheet->getCell('k'.$rowIndex)->getValue() != null &&
                    strlen( $worksheet->getCell('k'.$rowIndex)->getValue() ) > 0 &&
                    file_exists( storage_path('').'/'. str_replace( '\\' , '/' , $worksheet->getCell('k'.$rowIndex)->getValue() ) )
                ) {
                    $uniqeName = Storage::disk('public')->putFile( "avatars/".$user->id, new File( storage_path('').'/'. str_replace( '\\' , '/' , $worksheet->getCell('k'.$rowIndex)->getValue() ) ) );
                    $user->avatar_url = $people->image = $uniqeName;
                    $user->save();
                    $people->save();
                }
            }
            $records[] = $record ;
            $rowIndex++;
        }
    }
}
