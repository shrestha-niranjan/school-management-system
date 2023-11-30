<?php

use App\Models\Grade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Grade::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->string('name');
            $table->unsignedInteger('internal_mark')->default(0);
            $table->unsignedInteger('external_mark')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
