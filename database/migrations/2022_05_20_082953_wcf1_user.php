<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('wcf1_user', static function (Blueprint $table) {
            $table->bigInteger('userID',1)->unique()->autoIncrement();
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->bigInteger('accessToken')->default(0);
            $table->boolean('multifactorActive')->default(false);
            $table->bigInteger('languageID')->default(1);
            $table->bigInteger('registrationDate')->default(0);
            $table->bigInteger('styleID')->default(0);
            $table->boolean('banned')->default(false);
            $table->mediumText('banReason')->nullable();
            $table->bigInteger('banExpires')->default(0);
            $table->boolean('activationCode')->default(0);
            $table->char('emailConfirmed', 40)->nullable();
            $table->integer('lastLostPasswordRequestTime')->default(0);
            $table->char('lostPasswordKey', 40)->nullable();
            $table->integer('lastUsernameChange')->default(0);
            $table->string('newEmail')->default('');
            $table->string('oldUsername')->default('');
            $table->integer('quitStarted')->default(0);
            $table->integer('reactivationCode')->default(0);
            $table->string('registrationIpAddress')->default('');
            $table->integer('avatarID')->nullable();
            $table->boolean('disableAvatar')->default(false);
            $table->text('disableAvatarReason')->nullable();
            $table->integer('disableAvatarExpires')->default(0);
            $table->boolean('enableGravatar')->default(false);
            $table->string('gravatarFileExtension')->default('');
            $table->text('signature')->nullable();
            $table->boolean('signatureEnableHtml')->default(false);
            $table->boolean('disableSignature')->default(false);
            $table->text('disableSignatureReason')->nullable();
            $table->integer('disableSignatureExpires')->default(0);
            $table->integer('lastActivityTime')->default(0);
            $table->integer('profileHits')->default(0);
            $table->integer('rankID')->nullable();
            $table->string('userTitle')->default('');
            $table->integer('userOnlineGroupID')->nullable();
            $table->integer('activityPoints')->default(0);
            $table->string('notificationMailToken',20)->default('');
            $table->string('authData')->default('');
            $table->integer('likesReceived')->default(0);
            $table->integer('trophyPoints')->default(0);
            $table->char('coverPhotoHash',40)->nullable();
            $table->string('coverPhotoExtension')->default('');
            $table->boolean('coverPhotoHasWebP')->default(false);
            $table->boolean('disableCoverPhoto')->default(false);
            $table->text('disableCoverPhotoReason')->nullable();
            $table->integer('disableCoverPhotoExpires')->default(0);
            $table->integer('articles')->default(0);
            $table->string('blacklistMatches')->default('');
            $table->integer('wbbPosts')->default(0);
            $table->integer('wbbBestAnswers')->default(0);
            $table->integer('mailingGroupID')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wcf1_user');
    }
};
