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
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('created_from_ip_address')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
            // Add foreign key constraint to link with branches table
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['branch_id']);
            $table->dropColumn('created_from_ip_address');
            $table->dropColumn('branch_id');
        });
    }
};
