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
        echo "Adding foreign key constraint buildfailure2argument(buildfailureid)->buildfailure(id)...";
        $num_deleted = DatabaseCleanupUtils::deleteUnusedRows('buildfailure2argument', 'buildfailureid', 'buildfailure', 'id');
        echo $num_deleted . ' invalid rows deleted' . PHP_EOL;
        Schema::table('buildfailure2argument', function (Blueprint $table) {
            $table->foreign('buildfailureid')->references('id')->on('buildfailure')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('buildfailure2argument', function (Blueprint $table) {
            $table->dropForeign(['buildfailureid']);
        });
    }
};
