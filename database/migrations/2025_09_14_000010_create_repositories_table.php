<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repositories', static function (Blueprint $table) {
            $table->id();
            $table->string('package_name');
            $table->string('display_name');
            $table->string('feed_url');
            $table->string('website_url')->nullable();
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->text('username')->nullable();
            $table->text('password')->nullable();
            $table->unsignedSmallInteger('max_age_days')->default(365);
            $table->timestamps();
        });
    }
};
