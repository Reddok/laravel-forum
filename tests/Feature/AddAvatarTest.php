<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_allowed_only_for_members()
    {
        $this->withExceptionHandling();

        $this->postJson(route('api.users.avatar.update', 1))
            ->assertStatus(401);
    }

    /** @test */
    public function it_must_be_valid_image()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $this->postJson(route('api.users.avatar.update', auth()->user()), [
            'avatar' => 'not an image',
        ])
            ->assertStatus(422);
    }

    /** @test */
    public function it_can_update_only_itself()
    {
        $this->withExceptionHandling();
        $this->signIn();

        $anotherUser = create(User::class);

        $this->postJson(route('api.users.avatar.update', $anotherUser))
            ->assertStatus(403);
    }

    /** @test */
    public function user_can_upload_avatar_for_yourself()
    {
        $this->signIn();
        $user = auth()->user();

        $this->postJson(route('api.users.avatar.update', $user), [
            'avatar' => $file = UploadedFile::fake()->image('avatar.jpg'),
        ])
            ->assertStatus(204);

        Storage::disk('public')->assertExists('avatars/'.$file->hashName());

        $this->assertEquals(asset('/storage/avatars/'.$file->hashName()), $user->fresh()->avatar_path);
    }

    /** @test */
    public function user_can_has_default_avatar()
    {
        $user = create(User::class);

        $this->assertEquals(asset('storage/avatars/default.png'), $user->avatar_path);

        $user->avatar_path = 'avatars/me.jpg';

        $this->assertEquals(asset('storage/avatars/me.jpg'), $user->avatar_path);
    }
}
