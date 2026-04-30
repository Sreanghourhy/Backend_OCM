<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Member;
use Carbon\Carbon;
use Google_Client;
use AppleSignIn\ASDecoder;
use Laravolt\Avatar\Avatar;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SocialAuthenticationController extends Controller
{

    public function fileDownload($sid,$id){
        $fbUrl = 'https://graph.facebook.com/'.$sid.'/picture?type=square&width=400&height=400';
        $file = 'avatars/'.$id.'/'.$sid.'.jpg';
        $cp = copy($fbUrl,storage_path("app/public/$file"));
        if($cp) return $file;
        return null;
    }
    /** Facebook Authentication */
    public function facebook(Request $request)
    {
        /**
         * facebook_id: int (facebook user ID)
         * facebook_token: String
         * firstname: String
         * lastname: String | nullable
         * email: String | nullable
         * phone: String | nullable
         * avatar_url: String
         */

        $CLIENT_TOKEN_FACEBOOK = $request->stoken;
        $FACEBOOK_APP_ID = env('FACEBOOK_APP_ID');
        $FACEBOOK_APP_SECRET = env('FACEBOOK_APP_SECRET');
        $FACEBOOK_APP_TOKEN = $FACEBOOK_APP_ID . "|" . $FACEBOOK_APP_SECRET;
        if ($CLIENT_TOKEN_FACEBOOK != "") {
            $client = new \GuzzleHttp\Client();
            $res = $client->request('GET', 'https://graph.facebook.com/debug_token?input_token=' . $CLIENT_TOKEN_FACEBOOK . '&access_token=' . $FACEBOOK_APP_TOKEN . '');
            $getFacbookUser = json_decode($res->getBody()->getContents());
            // return json_encode( $getFacbookUser );
            if (isset($getFacbookUser->data) && $getFacbookUser->data && $getFacbookUser->data->is_valid == true) {
                $profile = null;
                // Check facebook user id and app id)
                // Application name : wephone
                if ($getFacbookUser->data->user_id == $request->sid && $getFacbookUser->data->app_id == $FACEBOOK_APP_ID) {
                    /** Check user account */
                    /** Check user with his/her facebook_id or email or phone */
                    if (($user = \App\User::where(function ($query) use ($request) {
                        $query->where('facebook_user_id', $request->facebook_id)
                            ->whereNotNull('facebook_user_id');
                    })->orWhere(function ($query) use ($request) {
                        $query->where('email', $request->email)
                            ->whereNotNull('email');
                    })->orWhere(function ($query) use ($request) {
                        $query->where('phone', $request->phone)
                            ->whereNotNull('phone');
                    })->first()) !== null) {
                        $role = 5;
                        if($request->email && $request->email=='chamroeunoum@gmail.com'){
                            $role = 1;
                        }
                        /** If user is alredy exists */
                        /** Make sure the facebook_id and app_id are fullly fill */
                        $user->username = $request->username;
                        $user->facebook_user_id = $request->sid;
                        $user->roles()->sync([$role]); // user type is 5 for social
                        $user->save();
                    }

                    /** Just in case admin has deleted user from system with SoftDeletes */
                    else if (($user = \App\User::where(function ($query) use ($request) {
                        $query->where('facebook_user_id', $request->sid)
                            ->whereNotNull('facebook_user_id');
                    })->orWhere(function ($query) use ($request) {
                        $query->where('email', $request->email)
                            ->whereNotNull('email');
                    })->orWhere(function ($query) use ($request) {
                        $query->where('phone', $request->phone)
                            ->whereNotNull('phone');
                    })->onlyTrashed()->first()) !== null) {
                        $role = 5;
                        if($request->email && $request->email=='chamroeunoum@gmail.com'){
                            $role = 1;
                        }
                        $user->restore();
                        $user->username = $request->username;
                        $user->facebook_user_id = $request->sid;
                        $user->roles()->sync([$role]); // user type is 5 for social
                        $user->save();

                    } else {
                        /** If does not exist then create account for user */
                        $user = new User([
                            'firstname' => $request->firstname,
                            'lastname' => $request->lastname,
                            'username' => $request->username,
                            'name' => $request->lastname . ' ' . $request->firstname,
                            'email' => $request->email,
                            'phone' => $request->phone,
                            'password' => bcrypt('1234567890!@#$%^&*()'),
                            'role' => 4, // user type is 4 for facebook user
                            'facebook_user_id' => $request->facebook_id,
                            'active' => 1
                        ]);
                        $user->save();
                        if($user){
                           $profile = $this->fileDownload($request->sid,$user->id);
                        }
                    }


                    /** Create user profile picture */
                    if (!Storage::disk(env("FILESYSTEM_DRIVER","public"))->exists($user->avatar_url)) {
                        // $profile_picture = null;
                        // if( $request->avatar_url == ""){
                        //     $avatar = new Avatar();
                        //     $profile_picture = $avatar->create($user->name)->getImageObject()->encode('png');
                        // }else if ( ($content = file_get_contents($request->avatar_url)) !== false) {
                        //     $profile_picture = $content;
                        // }
                        // Storage::disk(env("FILESYSTEM_DRIVER","public"))->put('avatars/' . $user->id . '/avatar.png', (string) $profile_picture, "public");
                        // $user->avatar_url = 'avatars/' . $user->id . '/avatar.png';
                        $user->avatar_url = $profile;
                        $user->save();
                    }

                    /** Create Data Relationship To Member */
                    if (($member = \App\Member::where(function ($query) use ($request) {
                        $query->where('email', $request->email)
                            ->whereNotNull('email');
                    })->orWhere(function ($query) use ($request) {
                        $query->where('phone', $request->phone)
                            ->whereNotNull('phone');
                    })->first()) !== null) {
                        $user->member_id = $member->id;
                        $user->save();
                        $user->member;
                    } else if (($member = \App\Member::where(function ($query) use ($request) {
                        $query->where('email', $request->email)
                            ->whereNotNull('email');
                    })->orWhere(function ($query) use ($request) {
                        $query->where('phone', $request->phone)
                            ->whereNotNull('phone');
                    })->onlyTrashed()->first()) !== null) {
                        $member->restore();
                        $user->member_id = $member->id;
                        $user->save();
                        $user->member;
                    } else {
                        $member = new Member();
                        $member->firstname = $member->enfirstname = $user->firstname;
                        $member->lastname = $member->enlastname = $user->lastname;
                        $member->email = $user->email;
                        $member->phone = $user->phone;
                        $member->member_since = \Carbon\Carbon::today();
                        $member->save();
                        $member->code = "M" . sprintf("%04d", $member->id) . "-" . \Carbon\Carbon::today()->format('Ymd');
                        $member->save();
                        $user->member_id = $member->id;
                        $user->save();
                        $user->member;
                    }

                    /** Create Setting for user */
                    // if (\App\UserSetting::where('user_id', $user->id)->where('setting_id', 1)->first() === null) {
                    //     $notificationSetting = new \App\UserSetting();
                    //     $notificationSetting->user_id = $user->id;
                    //     $notificationSetting->setting_id = 1;
                    //     $notificationSetting->value = 'on';
                    //     $notificationSetting->save();
                    // }

                    /** Gernerate public link to profile picture */
                    if (Storage::disk(env("FILESYSTEM_DRIVER","public"))->exists($user->avatar_url)) {
                        $user->avatar_url = Storage::disk(env("FILESYSTEM_DRIVER","public"))->url($user->avatar_url);
                    } else {
                        $user->avatar_url = null;
                    }

                    /** Then create access token for api access */
                    $tokenResult = $user->createToken($user->name);
                    $token = $tokenResult->token;
                    if ($request->remember_me)
                        $token->expires_at = Carbon::now()->addWeeks(1);
                    $token->save();
                    $access_token = $tokenResult->accessToken;

                    return response()->json([
                        'record' => [
                            'id' => $user->id,
                            'username' => $user->username,
                            'email' => $user->email,
                            'active' => $user->active,
                            'facebook_user_id' => $user->facebook_user_id,
                            'firstname' => $user->firstname,
                            'lastname' => $user->lastname,
                            'position' => $user->position,
                            'department' => $user->department,
                            'avatar_url' => $user->avatar_url,
                            'bio' => $user->bio,
                            'facebook' => $user->facebook,
                            'instagram' => $user->instagram,
                            'twitter' => $user->twitter,
                            'created_at' => $user->created_at,
                            'updated_at' => $user->updated_at
                        ],
                        'access_token' => $access_token,
                        'message' => 'User has been authorized successfully !'
                    ], 200);
                } else {
                    return response()->json([
                        'result' => $getFacbookUser,
                        'message' => 'User information is not matched!'
                    ], 201);
                }
            }
        } else {
            return response()->json([
                'result' => null,
                'message' => 'There is a problem with Access Token !'
            ], 201);
        }
    }
    /** End of Facebook Authentication */
    /** Apple Signin */
    public function apple(Request $request)
    {
        /**
         * apple_id: String
         * apple_token: String
         * firstname: String | nullable
         * lastname: String | nullable
         * email: String | nullable
         */

        $clientUser = $request->sid;
        $identityToken = $request->stoken;
        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $email = $request->email;
        if ($clientUser != "" && $identityToken != "") {
            $appleSignInPayload = ASDecoder::getAppleSignInPayload($identityToken);

            /**
             * Determine whether the client-provided user is valid.
             */
            $isValid = $appleSignInPayload->verifyUser($clientUser);
            /**
             * Obtain the Sign In with Apple email and user creds.
             */
            // $email = $appleSignInPayload->getEmail();
            // $user = $appleSignInPayload->getUser();

            /** Check user with his/her facebook_id or email or phone */
            $queryBuilder = \App\User::
                /** Check apple_user_id with the provided id */
                where(function ($query) use ($request) {
                    $query->where('apple_user_id', $request->sid)
                        ->whereNotNull('apple_user_id');
                });
            /** Check with condition of email */
            if ($request->email != "") {
                $queryBuilder = $queryBuilder->orWhere(function ($query) use ($request) {
                    $query->where('email', $request->email)
                        ->whereNotNull('email');
                });
            }
            /** Check with the condition of phone */
            if ($request->phone != "") {
                $queryBuilder = $queryBuilder->orWhere(function ($query) use ($request) {
                    $query->where('phone', $request->phone)
                        ->whereNotNull('phone');
                });
            }
            /** User has alrady exists */
            if ( ( $user = $queryBuilder->first() ) !== null ) {
                /** Make sure the apple_user_id and app_id are fullly fill */
                $user->apple_user_id = $request->apple_id;
                if( $request->email != "" ) $user->email = $request->email ;
                $user->role = 4 ; // user type is 4 for socail user
                $user->save();
            }
            /** Just in case admin has deleted user from system with SoftDeletes */
            else if (($user = $queryBuilder->onlyTrashed()->first()) !== null) {
                $user->restore();
                $user->apple_user_id = $request->apple_id;
                if( $request->email != "" ) $user->email = $request->email ;
                $user->role = 4 ; // user type is 4 for socail user
                $user->save();
            } else {
            /** If does not exist then create account for user */
            $user = User::create([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'name' => $request->lastname . ' ' . $request->firstname,
                'email' => $request->email,
                'phone' => $request->phone != "" ? $request->phone : "",
                'password' => bcrypt('1234567890!@#$%^&*()'),
                'apple_user_id' => $request->apple_id,
                'active' => 1
            ]);
            $user->roles()->sync([5]); // user type is 5 for social
            $user->save();
            }

            /** Create user profile picture */
            if (!Storage::disk(env("FILESYSTEM_DRIVER","public"))->exists($user->avatar_url)) {
                $profile_picture = null;
                $avatar = new Avatar();
                $profile_picture = $avatar->create($user->name)->getImageObject()->encode('png');
                Storage::disk(env("FILESYSTEM_DRIVER","public"))->put('avatars/' . $user->id . '/avatar.png', (string) $profile_picture, "public");
                $user->avatar_url = 'avatars/' . $user->id . '/avatar.png';
                $user->save();
            }

            /** Create Data Relationship To Member */
            /** Check member with his/her email or phone */
            $queryBuilder = new \App\Member;
            /** Check with condition of email */
            if ($request->email != "") {
                $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                    $query->where('email', $request->email)
                        ->whereNotNull('email');
                });
            }
            /** Check with the condition of phone */
            if ($request->phone != "") {
                $queryBuilder = $queryBuilder->orWhere(function ($query) use ($request) {
                    $query->where('phone', $request->phone)
                        ->whereNotNull('phone');
                });
            }
            if (($member = $queryBuilder->first()) !== null) {
                $user->member_id = $member->id;
                $user->save();
                $user->member;
            } else if (($member = $queryBuilder->onlyTrashed()->first()) !== null) {
                $member->restore();
                $user->member_id = $member->id;
                $user->save();
                $user->member;
            } else {
                $member = new Member();
                $member->firstname = $member->enfirstname = $user->firstname;
                $member->lastname = $member->enlastname = $user->lastname;
                $member->email = $user->email;
                $member->phone = $user->phone;
                $member->member_since = \Carbon\Carbon::today();
                $member->save();
                $member->code = "M" . sprintf("%04d", $member->id) . "-" . \Carbon\Carbon::today()->format('Ymd');
                $member->save();
                $user->member_id = $member->id;
                $user->save();
                $user->member;
            }

            /** Create Setting for user */
            // if (\App\UserSetting::where('user_id', $user->id)->where('setting_id', 1)->first() === null) {
            //     $notificationSetting = new \App\UserSetting();
            //     $notificationSetting->user_id = $user->id;
            //     $notificationSetting->setting_id = 1;
            //     $notificationSetting->value = 'on';
            //     $notificationSetting->save();
            // }

            /** Gernerate public link to profile picture */
            if (Storage::disk(env("FILESYSTEM_DRIVER","public"))->exists($user->avatar_url)) {
                $user->avatar_url = Storage::disk(env("FILESYSTEM_DRIVER","public"))->url($user->avatar_url);
            } else {
                $user->avatar_url = null;
            }

            /** Then create access token for api access */
            $tokenResult = $user->createToken($user->name);
            $token = $tokenResult->token;
            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();
            $access_token = $tokenResult->accessToken;

            return response()->json([
                'record' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'active' => $user->active,
                    // 'apple_user_id' => $user->apple_user_id,
                    'firstname' => $user->firstname,
                    'lastname' => $user->lastname,
                    'position' => $user->position,
                    'department' => $user->department,
                    'avatar_url' => $user->avatar_url,
                    'bio' => $user->bio,
                    'facebook' => $user->facebook,
                    'instagram' => $user->instagram,
                    'twitter' => $user->twitter,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at
                ],
                'access_token' => $access_token,
                'message' => 'User has been authorized successfully !'
            ], 200);
        } else {
            return response()->json([
                'result' => null,
                'message' => 'There is a problem with Access Token !'
            ], 201);
        }
    }
    /** Google Server Side Confirmation */
    public function google(Request $request)
    {
        $CLIENT_ID_GOOGLE_IOS = env('GOOGLE_CLIENT_ID_IOS');
        $CLIENT_ID_GOOGLE_ANDROID = env('GOOGLE_CLIENT_ID_ANDROID');
        $CLIENT_ID_GOOGLE_WEBAPP = env('GOOGLE_CLIENT_ID');
        $clientIOS = new Google_client(['client_id' => $CLIENT_ID_GOOGLE_IOS]);  // iOS client
        $clientAndroid = new Google_Client(['client_id' => $CLIENT_ID_GOOGLE_ANDROID]);  // Android client
        $clientWebapp = new Google_Client(['client_id' => $CLIENT_ID_GOOGLE_WEBAPP]);  // Webapp
        $payload = null;
        $user_id = null;
        if (($payload = $clientIOS->verifyIdToken($request->stoken)) !== false) {
            $user_id = 'g' . $payload['sub'];
        } else if (($payload = $clientAndroid->verifyIdToken($request->stoken)) !== false) {
            $user_id = 'g' . $payload['sub'];
        } else if (($payload = $clientWebapp->verifyIdToken($request->stoken)) !== false) {
            $user_id = 'g' . $payload['sub'];
        } else {
            /** Error verify user_id */
        }
        return response()->json( [
            'payload' => $payload
        ],200 );

        /** Check user with his/her facebook_id or email or phone */
        $queryBuilder = \App\User::
            /** Check apple_user_id with the provided id */
            where(function ($query) use ($request) {
                $query->where('google_user_id', $request->sid)
                    ->whereNotNull('google_user_id');
            });
        /** Check with condition of email */
        if ($request->email != "") {
            $queryBuilder = $queryBuilder->orWhere(function ($query) use ($request) {
                $query->where('email', $request->email)
                    ->whereNotNull('email');
            });
        }
        /** Check with the condition of phone */
        if ($request->phone != "") {
            $queryBuilder = $queryBuilder->orWhere(function ($query) use ($request) {
                $query->where('phone', $request->phone)
                    ->whereNotNull('phone');
            });
        }
        /** User has alrady exists */
        if (($user = $queryBuilder->first()) !== null) {
            /** Make sure the apple_user_id and app_id are fullly fill */
            $user->google_user_id = $request->sid;
            if ($request->email != "") $user->email = $request->email;
            $user->roles()->sync([5]); // user type is 5 for social
            $user->save();
        }
        /** Just in case admin has deleted user from system with SoftDeletes */
        else if (($user = $queryBuilder->onlyTrashed()->first()) !== null) {
            $user->restore();
            $user->google_user_id = $request->sid;
            if ($request->email != "") $user->email = $request->email;
            $user->roles()->sync([5]); // user type is 5 for social
            $user->save();
        } else {
            /** If does not exist then create account for user */
            $user = new User([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'name' => $request->lastname . ' ' . $request->firstname,
                'email' => $request->email,
                'phone' => $request->phone != "" ? $request->phone : "",
                'password' => bcrypt('1234567890!@#$%^&*()'),
                'google_user_id' => $request->sid,
                'active' => 1
            ]);
            $user->roles()->sync([5]); // user type is 5 for social
            $user->save();
        }

        /** Create user profile picture */
        if (!Storage::disk(env("FILESYSTEM_DRIVER","public"))->exists($user->avatar_url)) {
            $profile_picture = null;
            if (file_exists($request->profile_url) && ($content = file_get_contents($request->profile_url)) !== false) {
                $profile_picture = $content;
            } else {
                $avatar = new Avatar();
                $profile_picture = $avatar->create($user->name)->getImageObject()->encode('png');
            }
            Storage::disk(env("FILESYSTEM_DRIVER","public"))->put('avatars/' . $user->id . '/avatar.png', (string) $profile_picture, "public");
            $user->avatar_url = 'avatars/' . $user->id . '/avatar.png';
            $user->save();
        }

        /** Create Data Relationship To Member */
        /** Check member with his/her email or phone */
        $queryBuilder = new \App\Member;
        /** Check with condition of email */
        if ($request->email != "") {
            $queryBuilder = $queryBuilder->where(function ($query) use ($request) {
                $query->where('email', $request->email)
                    ->whereNotNull('email');
            });
        }
        /** Check with the condition of phone */
        if ($request->phone != "") {
            $queryBuilder = $queryBuilder->orWhere(function ($query) use ($request) {
                $query->where('phone', $request->phone)
                    ->whereNotNull('phone');
            });
        }
        if (($member = $queryBuilder->first()) !== null) {
            $user->member_id = $member->id;
            $user->save();
            $user->member;
        } else if (($member = $queryBuilder->onlyTrashed()->first()) !== null) {
            $member->restore();
            $user->member_id = $member->id;
            $user->save();
            $user->member;
        } else {
            $member = new Member();
            $member->firstname = $member->enfirstname = $user->firstname;
            $member->lastname = $member->enlastname = $user->lastname;
            $member->email = $user->email;
            $member->phone = $user->phone;
            $member->member_since = \Carbon\Carbon::today();
            $member->save();
            $member->code = "M" . sprintf("%04d", $member->id) . "-" . \Carbon\Carbon::today()->format('Ymd');
            $member->save();
            $user->member_id = $member->id;
            $user->save();
            $user->member;
        }

        // /** Create Setting for user */
        // if (\App\UserSetting::where('user_id', $user->id)->where('setting_id', 1)->first() === null) {
        //     $notificationSetting = new \App\UserSetting();
        //     $notificationSetting->user_id = $user->id;
        //     $notificationSetting->setting_id = 1;
        //     $notificationSetting->value = 'on';
        //     $notificationSetting->save();
        // }

        /** Gernerate public link to profile picture */
        if (Storage::disk(env("FILESYSTEM_DRIVER","public"))->exists($user->avatar_url)) {
            $user->avatar_url = Storage::disk(env("FILESYSTEM_DRIVER","public"))->url($user->avatar_url);
        } else {
            $user->avatar_url = null;
        }

        /** Then create access token for api access */
        $tokenResult = $user->createToken($user->name);
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        $access_token = $tokenResult->accessToken;

        return response()->json([
            'record' => [
                'id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'active' => $user->active,
                'google_user_id' => $user->google_user_id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'avatar_url' => $user->avatar_url,
                'bio' => $user->bio,
                'facebook' => $user->facebook,
                'instagram' => $user->instagram,
                'twitter' => $user->twitter,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ],
            'access_token' => $access_token,
            'message' => 'User has been authorized successfully !'
        ], 200);
    }

}
