<?php

use App\Models\Grade;
use App\Models\Course;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_grade', function (Blueprint $table) {
            $table->foreignIdFor(Grade::class)
                ->constrained();

            $table->foreignIdFor(Course::class)
                ->constrained();

            $table->unsignedInteger('internal_mark')
            ->default(100);

            $table->unsignedInteger('external_mark')
                ->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_grade');
    }
};
