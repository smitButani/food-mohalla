<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\User;
use App\Models\UserSetting;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->boolean('notification_active')->default(0);
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
        
        $users = User::all();
        foreach($users as $user){
            $setting = new UserSetting;
            $setting->user_id = $user->id;
            $setting->notification_active = 1;
            $setting->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_settings');
    }
}
