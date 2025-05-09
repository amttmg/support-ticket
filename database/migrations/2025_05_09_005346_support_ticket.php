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
        // Companies
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        // Departments
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        // Support Units
        Schema::create('support_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        // Support Topics
        Schema::create('support_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_unit_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });

        // Tickets
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_topic_id')->constrained()->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users');
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['not_assigned', 'assigned', 'in_progress', 'complete']);
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium');
            $table->timestamps();
            $table->softDeletes();
        });

        // Ticket-Agent Assignments
        Schema::create('ticket_agent_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('role')->nullable();
            $table->enum('status', ['assigned', 'in_progress', 'completed'])->default('assigned');
            $table->timestamps();
            $table->softDeletes();
        });

        // Ticket Replies
        Schema::create('ticket_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->text('message');
            $table->timestamps();
            $table->softDeletes();
        });

        // Ticket Internal Notes
        Schema::create('ticket_internal_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('note');
            $table->timestamps();
            $table->softDeletes();
        });

        // Ticket Attachments
        Schema::create('ticket_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_reply_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->timestamp('uploaded_at');
            $table->softDeletes();
        });

        // Files (Polymorphic)
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('file_path');
            $table->nullableMorphs('fileable'); // fileable_id & fileable_type
            $table->timestamps();
            $table->softDeletes();
        });

        // Custom Fields
        Schema::create('custom_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('support_topic_id')->constrained()->onDelete('cascade');
            $table->string('label');
            $table->string('field_type');
            $table->boolean('is_required')->default(false);
            $table->text('options')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        // Ticket Custom Field Values
        Schema::create('ticket_custom_field_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('custom_field_id')->constrained()->onDelete('cascade');
            $table->text('value')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('ticket_custom_field_values');
        Schema::dropIfExists('custom_fields');
        Schema::dropIfExists('files');
        Schema::dropIfExists('ticket_attachments');
        Schema::dropIfExists('ticket_internal_notes');
        Schema::dropIfExists('ticket_replies');
        Schema::dropIfExists('ticket_agent_assignments');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('support_topics');
        Schema::dropIfExists('support_units');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('companies');
    }
};
