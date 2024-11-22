<?php

use App\Utils\DatabaseCleanupUtils;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        echo "Adding foreign key constraint test2image(outputid)->testoutput(id)...";
        $num_deleted = DatabaseCleanupUtils::deleteUnusedRows('test2image', 'outputid', 'testoutput', 'id');
        echo $num_deleted . ' invalid rows deleted' . PHP_EOL;
        Schema::table('test2image', function (Blueprint $table) {
            $table->foreign('outputid')->references('id')->on('testoutput')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('test2image', function (Blueprint $table) {
            $table->dropForeign(['outputid']);
        });
    }
};
