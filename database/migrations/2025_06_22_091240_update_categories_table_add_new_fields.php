<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up()
    {
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'slug')) {
                $table->string('slug')->nullable()->after('name');
            }
            if (!Schema::hasColumn('categories', 'color')) {
                $table->string('color', 7)->default('#007bff')->after('description');
            }
            if (!Schema::hasColumn('categories', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('color');
            }
        });

        $this->generateSlugsForExistingCategories();

        // Only make slug non-nullable if needed, DO NOT add unique() if it already exists!
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'slug')) {
                $table->string('slug')->nullable(false)->change();
            }
        });
    }

    public function down()
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'slug')) {
                $table->dropColumn('slug');
            }
            if (Schema::hasColumn('categories', 'color')) {
                $table->dropColumn('color');
            }
            if (Schema::hasColumn('categories', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }

    private function generateSlugsForExistingCategories()
    {
        $categories = DB::table('categories')->get();

        foreach ($categories as $category) {
            $slug = Str::slug($category->name);
            $originalSlug = $slug;
            $counter = 1;

            while (DB::table('categories')->where('slug', $slug)->where('id', '!=', $category->id)->exists()) {
                $slug = $originalSlug . '-' . $counter;
                $counter++;
            }

            DB::table('categories')
                ->where('id', $category->id)
                ->update([
                    'slug' => $slug,
                    'color' => '#007bff',
                    'is_active' => true,
                    'updated_at' => now()
                ]);
        }
    }
};
