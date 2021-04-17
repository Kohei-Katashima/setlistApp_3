<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SongsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        foreach (range(1, 3) as $num) {
            DB::table('songs')->insert([
                'setlist_id' => 1,
                'band_name' => "サンプルバンド {$num}",
                'title' => "サンプル曲 {$num}",
                'time' => Carbon::now(),
                'order' => "{$num}",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
