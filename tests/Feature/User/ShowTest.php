<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowTest extends TestCase
{
    use RefreshDatabase;

    private const API_URL = 'api/users';

    /**
     * 未ログインで実行した時、レスポンスが想定通りであることを確認する
     *
     * @return void
     */
    public function test_not_logged_in_can_view_data()
    {
        $user = User::factory()->create();

        $response = $this->get(self::API_URL.'/'.$user->id);

        $filteredUser = $user->only('id', 'name');

        $response
            ->assertStatus(200)
            ->assertExactJson($filteredUser);
    }

    /**
     * 管理者ユーザーが実行した時、レスポンスが想定通りであることを確認する
     *
     * @return void
     */
    public function test_admin_user_can_view_data()
    {
        $user = User::factory()->create([
            'is_admin' => 1,
        ]);

        $response = $this->actingAs($user)->get(self::API_URL.'/'.$user->id);

        $response
            ->assertStatus(200)
            ->assertExactJson($user->toArray());
    }

    /**
     * 一般ユーザーが実行した時、レスポンスが想定通りであることを確認する
     *
     * @return void
     */
    public function test_general_user_can_view_data()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(self::API_URL.'/'.$user->id);

        $filteredUser = $user->only('id', 'name');

        $response
            ->assertStatus(200)
            ->assertExactJson($filteredUser);
    }

    /**
     * 管理者ユーザーが実行した時、論理削除されたデータを参照できることを確認する
     *
     * @return void
     */
    public function test_admin_user_can_view_soft_delete_data()
    {
        $requestUser = User::factory()->create([
            'is_admin' => 1,
        ]);
        $otherUser = User::factory()->create([
            'deleted_at' => now(),
        ]);

        $response = $this->actingAs($requestUser)->get(self::API_URL.'/'.$otherUser->id);

        $response
            ->assertStatus(200)
            ->assertExactJson($otherUser->toArray());
    }

    /**
     * 一般ユーザーが実行した時、論理削除されたデータを参照できないことを確認する
     *
     * @return void
     */
    public function test_general_user_can_not_view_soft_delete_data()
    {
        $requestUser = User::factory()->create();
        $otherUser = User::factory()->create([
            'deleted_at' => now(),
        ]);

        $response = $this->actingAs($requestUser)->get(self::API_URL.'/'.$otherUser->id);

        $response->assertStatus(404);
    }

    /**
     * 存在しないデータを指定した時、実行できないことを確認する
     *
     * @return void
     */
    public function test_not_found()
    {
        $user = User::factory()->create([
            'is_admin' => 1,
        ]);

        $response = $this->actingAs($user)->get(self::API_URL.'/'. 2);

        $response->assertStatus(404);
    }
}
