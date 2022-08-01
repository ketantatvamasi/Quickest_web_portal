<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameAndAddcolumnInTestimonialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->renameColumn('description', 'description_one');
            $table->renameColumn('rating', 'rating_one');
            $table->string('client_name_one')->nullable()->after('name');
            $table->string('client_name_two')->nullable()->after('rating');
            $table->text('description_two')->nullable()->after('rating');
            $table->float('rating_two', 3, 2)->default(0.00)->after('rating');
            $table->text('image_two')->nullable()->after('rating');
            $table->string('client_name_three')->nullable()->after('rating_two');
            $table->text('description_three')->nullable()->after('rating_two');
            $table->float('rating_three', 3, 2)->default(0.00)->after('rating_two');
            $table->text('image_three')->nullable()->after('rating_two');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('testimonials', function (Blueprint $table) {
            $table->renameColumn('description_one', 'description');
            $table->renameColumn('rating_one', 'rating');
            $table->dropColumn('client_name_one');
            $table->dropColumn('client_name_two');
            $table->dropColumn('description_two');
            $table->dropColumn('rating_two');
            $table->dropColumn('image_two');
            $table->dropColumn('client_name_three');
            $table->dropColumn('description_three');
            $table->dropColumn('rating_three');
            $table->dropColumn('image_three');
        });
    }
}
