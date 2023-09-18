<?php

namespace Tests\Feature;

use App\Mail\Invitation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvitationTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_be_able_to_invite_someone_to_the_platform()
    {
        // Arrange
        Mail::fake();

        // Preciso um usuário que vá convidar
        $user = User::factory()->create();

        // Preciso estar logado
        $this->actingAs($user);

        // Act
        $this->post('invite', ['email' => 'novo@email.com']);

        // Assert
        // Email foi enviado
        Mail::assertSent(Invitation::class, function($mail) {
            return $mail->hasTo('novo@email.com');
        });

        // Criou um convite no banco de dados
        $this->assertDatabaseHas('invites', ['email' => 'novo@email.com']);
    }
}
