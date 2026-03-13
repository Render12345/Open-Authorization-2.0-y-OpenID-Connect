<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            // OAuth provider info
            $table->string('provider');               // 'discord' | 'spotify'
            $table->string('provider_id');            // ID from the provider
            $table->string('provider_token')->nullable();
            $table->string('provider_refresh_token')->nullable();

            // Common profile fields
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('avatar')->nullable();
            $table->string('nickname')->nullable();

            $table->timestamps();

            $table->unique(['provider', 'provider_id']);
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
    }
};
