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
        Schema::table('support_units', function (Blueprint $table) {
            if (!Schema::hasColumn('support_units', 'slack_url')) {
                $table->string('slack_url')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('support_units', 'slack_url')) {
            Schema::table('support_units', function (Blueprint $table) {
                $table->dropColumn('slack_url');
            });
        }
    }
};
