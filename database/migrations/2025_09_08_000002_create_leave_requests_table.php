<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeaveRequestsTable extends Migration
{
    public function up()
    {
        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('type');
            $table->string('title');
            $table->text('content');
            $table->json('details')->nullable();
            $table->enum(
                'status',
                ['submitted', 'rejected', 'revision', 'approved', 'cancelled']
            )->default('submitted');
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->text('comment')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('set null');
        });
    }
    public function down()
    {
        Schema::dropIfExists('leave_requests');
    }
}
