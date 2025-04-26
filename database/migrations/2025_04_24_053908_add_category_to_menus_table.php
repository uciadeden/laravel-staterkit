<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryToMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            // Menambahkan kolom 'category' pada tabel 'menus'
            $table->string('category')->nullable()->after('icon'); // Sesuaikan posisi kolom jika perlu
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            // Menghapus kolom 'category' jika rollback
            $table->dropColumn('category');
        });
    }
}
