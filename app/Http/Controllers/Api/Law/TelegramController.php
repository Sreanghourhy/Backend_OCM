<?php

namespace App\Http\Controllers\Api\Law;

use App\Models\User;
use App\Models\People\People;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Laravolt\Avatar\Avatar;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class TelegramController extends Controller
{

    public function updateOrCreate(Request $request){
        // User Data
        /*
        id: 38846216 -> telegram_user_id
        username: "chamroeunoum" -> telegram_user_username
        hash: "d344bc5f619baafc2287cbcca883c58cd89d90e6a13c51a061e85d9a7618fae5" -> telegram_user_hash
        auth_date: 1718086049 -> telegram_user_auth_date
        first_name: "Chamroeun" -> telegram_user_firstname
        last_name: "OUM" -> telegram_user_lastname
        photo_url: "https://t.me/i/userpic/320/17SfzmPHs2An_FggmTXO5jGxH-MlK1RrKPQ4sT3OG0E.jpg" -> telegram_user_picture
        */
        $email = '' ;
        if( isset( $request->email ) && strlen(trim($request->email))>0 ){
            $email = $request->email;
        }else{
            $email = $request->first_name . $request->last_name . $request->id . "@telegram.otc";
        }
        // Check whether has been already registerd
        $user = \App\Models\User::
            where('telegram_user_id', $request->id)
            ->whereNotNull('telegram_user_id')
            ->first();
        
        // Check whether the user is already our member with the email
        if( $user == null ){
            $user = \App\Models\User::
            where('email', $email)
            ->whereNotNull('email')
            ->first();
        }

        // Check whether the user is already our member with the email
        if( $user == null && $request->hash != "" ){
            $user = \App\Models\User::
            where('telegram_user_hash', $request->hash)
            ->whereNotNull('telegram_user_hash')
            ->first();
        }

        /**
         * Check roles
         * Check whether the admin and super admin come to visit
         * This does not allow the admin and super admin to visit
         */
        if( $user != null && !empty( array_intersect( $user->roles->pluck('id')->toArray() , \App\Models\Role::where('name','super')->orWhere('name','admin')->pluck('id')->toArray() ) ) ){
            $user = null ;
            return response()->json([
                'ok' => false ,
                'message' => 'មិនមានសិទ្ធិប្រើប្រាស់ប្រព័ន្ធនេះឡើយ។' ,
            ],403);
        }

        /** Just in case admin has deleted user from system with SoftDeletes */
        if (( $user = \App\Models\User::where(function($query) use($request, $email ){
                $query->where('telegram_user_id', $request->id)
                ->whereNotNull('telegram_user_id');
            })
            ->orWhere(function($query) use($request, $email){
                $query->where('telegram_user_hash', $request->hash)
                ->whereNotNull('telegram_user_hash');
            })
            ->orWhere(function($query) use($request, $email){
                $query->where('email', $email)
                ->whereNotNull('email');
            })
            ->onlyTrashed()
            ->first() ) !== null) {
            /**
             * Check roles
             * Check whether the admin and super admin come to visit
             * This does not allow the admin and super admin to visit
             */
            if( $user != null && !empty( array_intersect( $user->roles->pluck('id')->toArray() , \App\Models\Role::where('name','super')->orWhere('name','admin')->pluck('id')->toArray() ) ) ){
                // if the deleted account is the admin or super admin type then this operation does not allow
                $user == null ;
                return response()->json([
                    'ok' => false ,
                    'message' => 'មិនមានសិទ្ធិប្រើប្រាស់ប្រព័ន្ធនេះឡើយ។' ,
                ],403);
            }else{
                $user->restore();
            }
        }

        if( $user != null ){
            $user
            ->update([
                'telegram_user_id' => $request->id ,
                'telegram_user_lastname' => $request->lastname ,
                'telegram_user_firstname' => $request->firstname ,
                'telegram_user_username' => $request->username ,
                'telegram_user_picture' => $request->photo_url ,
                'telegram_user_hash' => $request->hash , 
                'telegram_user_auth_date' => $request->auth_date ,
            ]);
            if( strlen(trim($user->email)) <= 0 || $user->email == null ){
                $user->update(['email' => $email ]);
            }
            if( strlen(trim($user->username)) <= 0 || $user->username == null ){
                $user->update(['username' => $request->username ]);
            }
        }else{
            /** If does not exist then create account for user */
            $user = \App\Models\User::create([
                'firstname' => $request->last_name,
                'lastname' => $request->first_name,
                'name' => $request->last_name . ' ' . $request->first_name,
                'password' => bcrypt('1234567890!@#$%^&*()'),
                'active' => 1,
                'telegram_user_id' => $request->id ,
                'telegram_user_lastname' => $request->last_name ,
                'telegram_user_firstname' => $request->first_name ,
                'telegram_user_username' => $request->username ,
                'telegram_user_picture' => $request->photo_url ,
                'telegram_user_hash' => $request->hash ,
                'telegram_user_auth_date' => $request->auth_date ,
                'email' => $email ,
                'username' => $request->username
            ]);
            $clientClientRole = \App\Models\Role::where('name','client')->orWhere('name','Client')->first();
            if( $clientClientRole != null ){
                $user->assignRole( $clientClientRole );
            }
        }

        /** Check member with his/her email or phone */
        if( $user->person == null ){
            if( $user->people_id > 0 && ( $person = \App\Models\People\People::find( $user->people_id )->onlyTrashed()->first() ) != null ){
                $person->restore();
                $person->update([
                    'firstname' => $request->first_name ,
                    'lastname' => $request->last_name ,
                    'email' => $email
                ]);
            }else{
                $person = \App\Models\People\People::create([
                    'firstname' => $request->first_name ,
                    'lastname' => $request->last_name ,
                    'email' => $email
                ]);
            }
            $user->people_id = $person->id;
            $user->save();
            $user->person;
        }
        /** Then create access token for api access */
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        /** Create user profile picture */
        if ( $user->avatar_url == null || strlen(trim($user->avatar_url))<1 || 
            (
                strlen(trim($user->avatar_url))>0 && !Storage::disk(env("FILESYSTEM_DRIVER","public"))->exists($user->avatar_url)
            )
        ) {
            $profile_picture = null;
            if (file_exists($request->picture) && ($content = file_get_contents($request->picture)) !== false) {
                $profile_picture = $content;
            } else {
                $avatar = new Avatar();
                $profile_picture = $avatar->create($user->name)->getImageObject()->encode('png');
            }
            $path = 'avatars/' . $user->id ;
            if( !Storage::disk('public')->exists( $path ) ){
                if( Storage::makeDirectory( $path ) ){
                    $uniqeName = md5( $user->name );
                    Storage::disk(env("FILESYSTEM_DRIVER","public"))->put( $path . '/'.$uniqeName.'.png', (string) $profile_picture, "public");
                    $user->avatar_url = $path . '/'.$uniqeName.'.png';
                    $user->save();
                }
            }
        }

        if( $user ){
            if( $user->avatar_url !== null && Storage::disk('public')->exists( $user->avatar_url ) ){
                $user->avatar_url = Storage::disk("public")->url( $user->avatar_url  );
            }
        }

        return response()->json([
            'ok' => true ,
            'upload_max_filesize' => ini_get("upload_max_filesize") ,
            'token' => [
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString()
            ],
            'record' => $user ,
            'message' => 'ចូលប្រើប្រាស់បានជោគជ័យ !'
        ],200);
    }

}
