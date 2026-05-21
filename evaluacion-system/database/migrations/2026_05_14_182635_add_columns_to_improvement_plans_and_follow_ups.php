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
        Schema::table('improvement_plans', function (Blueprint $table) {
            $table->foreignId('evaluation_id')->constrained()->cascadeOnDelete();
            $table->text('aspects_to_improve')->nullable();
            $table->text('worker_commitment')->nullable();
            $table->string('status')->default('Pendiente');
        });

        Schema::table('follow_ups', function (Blueprint $table) {
            $table->foreignId('improvement_plan_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->text('comments')->nullable();
            $table->dateTime('follow_up_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('improvement_plans', function (Blueprint $table) {
            $table->dropForeign(['evaluation_id']);
            $table->dropColumn(['evaluation_id', 'aspects_to_improve', 'worker_commitment', 'status']);
        });

        Schema::table('follow_ups', function (Blueprint $table) {
            $table->dropForeign(['improvement_plan_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn(['improvement_plan_id', 'user_id', 'comments', 'follow_up_date']);
        });
    }
};
