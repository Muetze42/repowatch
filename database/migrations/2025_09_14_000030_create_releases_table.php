<?php

use App\Models\Repository;
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
        Schema::create('releases', static function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Repository::class)->constrained()->cascadeOnDelete();
            $table->string('version');
            $table->string('version_normalized');
            $table->json('require');
            $table->json('require_dev');
            $table->json('files');
            $table->timestamp('released_at')->nullable();
            $table->timestamps();
        });
    }
};
