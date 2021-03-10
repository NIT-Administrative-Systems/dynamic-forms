<?php

namespace Database\Seeders;

use App\Models\FormType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class FormTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        $rows = collect([
            ['name' => 'Application Form',      'slug' => FormType::APPLICATION],
            ['name' => 'Pre-submission Survey', 'slug' => FormType::PRE_SURVEY],
            ['name' => 'Endorsement',           'slug' => FormType::ENDORSEMENT],
            ['name' => 'Review',                'slug' => FormType::REVIEW],
            ['name' => 'Report Endorsement',    'slug' => FormType::REPORT_ENDORSEMENT],
            ['name' => 'Post-program Survey',   'slug' => FormType::POST_SURVEY],
        ])->map(function (array $row) use ($now) {
            return array_merge($row, ['created_at' => $now, 'updated_at' => $now]);
        });

        DB::table('form_types')->insert($rows->all());
    }
}
