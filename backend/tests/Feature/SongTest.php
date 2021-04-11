<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Requests\CreateSong;
use Carbon\Carbon;

class SongTest extends TestCase
{

    // テストケースごとにデータベースをリフレッシュしてマイグレーションを再実行する
    use RefreshDatabase;

    /**
     * 各テストメソッドの実行前に呼ばれる
     */
    public function setUp(): void
    {
        parent::setUp();

        // テストケース実行前にフォルダデータを作成する
        $this->seed('SetlistsTableSeeder');
    }

    /**
     * 期限日が日付ではない場合はバリデーションエラー
     * @test
     */
    public function time_should_be_time()
    {
        $response = $this->post('/setlists/1/songs/create', [
            'band_name' => 'サンプルバンド',
            'title' => 'Sample',
            'time' => 123, // 不正なデータ（数値）
        ]);

        $response->assertSessionHasErrors([
            'time' => '時間 には時間を入力してください。',
        ]);
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
