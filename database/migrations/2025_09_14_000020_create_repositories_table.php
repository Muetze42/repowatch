<?php

use App\Models\Provider;
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
            $table->foreignIdFor(Provider::class)->constrained()->cascadeOnDelete();
            $table->string('display_name');
            $table->string('package_name');
            $table->string('custom_feed_url')->nullable();
            $table->string('website_url')->nullable();
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->unsignedSmallInteger('max_age_days')->nullable();
            $table->timestamps();
        });
    }
};
