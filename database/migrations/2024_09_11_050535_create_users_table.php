<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('public_key', 191)->default( strtotime('now') )->comment('The public of the people, use when accessing from the outside of the system.');
            $table->string('lastname', 191)->nullable();
            $table->string('firstname', 191)->nullable();
            $table->string('phone', 191)->nullable();
            $table->string('username', 100)->nullable();
            $table->string('email', 191);
            $table->string('model', 191)->nullable()->comment('Model type of the user');
            $table->string('role', 191)->default('0')->comment('1: admin,0: member');
            $table->string('email_verified_at', 191)->default('')->comment('field of email verification');
            $table->string('password', 191);
            $table->integer('login_count')->nullable();
            $table->string('avatar_url', 191)->nullable();
            $table->string('avatar', 191)->nullable();
            $table->string('activation_token', 191)->nullable();
            $table->string('forgot_password_token', 191)->nullable();
            $table->rememberToken();
            $table->timestamp('last_login')->nullable();
            $table->timestamp('last_logout')->nullable();
            $table->string('active', 191)->nullable()->comment('0 : Blocked , 1 : Active , 2 : Verifing , 4 : Activitated');
            $table->string('ip', 100)->nullable();
            $table->string('authenip', 100)->nullable()->comment('The IP that registed with system. if defined then only the defined ip can login');
            $table->string('mac_address', 191)->nullable()->comment('The MAC address that registed with system. if defined then only the defined MAC address can login');
            $table->string('image', 191)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->string('api_token', 191)->nullable();
            $table->integer('authy_id')->default(0);
            $table->string('google_user_id', 191)->nullable()->comment('The user id of the google account');
            $table->string('google_user_phone', 191)->nullable()->comment('The user phone of the google account');
            $table->string('google_user_email', 191)->nullable()->comment('The user email of the google account');
            $table->string('google_user_firstname', 191)->nullable()->comment('The user firstname of the google account');
            $table->string('google_user_lastname', 191)->nullable()->comment('The user lastname of the google account');
            $table->string('google_user_fullname', 191)->nullable()->comment('The user fullname of the google account');
            $table->string('google_user_picture', 191)->nullable()->comment('The user profile picture of the google account');
            $table->string('telegram_user_id', 191)->nullable()->comment('The user id of the telegram account');
            $table->string('telegram_user_auth_date', 191)->nullable()->comment('The user auth date of the telegram account');
            $table->string('telegram_user_hash', 191)->nullable()->comment('The user email of the telegram account');
            $table->string('telegram_user_firstname', 191)->nullable()->comment('The user firstname of the telegram account');
            $table->string('telegram_user_lastname', 191)->nullable()->comment('The user lastname of the telegram account');
            $table->string('telegram_user_username', 191)->nullable()->comment('The user fullname of the telegram account');
            $table->string('telegram_user_picture', 191)->nullable()->comment('The user profile picture of the telegram account');
            $table->string('passcode', 191)->nullable()->comment('The user passcode of the localsystem');
            $table->integer('people_id')->nullable()->default(0);
            $table->integer('created_by')->nullable(true);
            $table->integer('updated_by')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
