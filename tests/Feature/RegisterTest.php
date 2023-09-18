<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{

    use RefreshDatabase;
    
    public function test_should_be_able_to_register_as_a_new_user()
    {
        // Arrange


        // Act
        $return = $this->post(route('register'), [
            'name' => 'Leonardo Matsui',
            'email' => 'teste@teste.com',
            'email_confirmation' => 'teste@teste.com',
            'password' => 'passwordP'
        ]);

        // Assert

        $return->assertRedirect('dashboard');

        $this->assertDatabaseHas('users', [
            'name' => 'Leonardo Matsui',
            'email' => 'teste@teste.com'
        ]);

        /** @var User $user */
        $user = User::whereEmail('teste@teste.com')->firstOrFail();

        $this->assertTrue(
            Hash::check('passwordP', $user->password),
            'Checking if password was saved and it is encrypted'
        );

    }

    public function test_name_should_be_required()
    {
        // Arrange

        // Act
        $this->post(route('register'), [

        ])->assertSessionHasErrors([
            'name' => __('validation.required', ['attribute' => 'name'])
        ]);
    }

    public function test_name_should_have_a_max_of_255_characters()
    {
        $this->post(route('register'), [
            'name' => str_repeat('a', 256)
        ])->assertSessionHasErrors([
            'name' => __('validation.max.string', ['attribute' => 'name', 'max' => 255])
        ]);

    }

    public function test_email_should_be_required()
    {
        $this->post(route('register'), [
            
        ])->assertSessionHasErrors([
            'email' => __('validation.required', ['attribute' => 'email'])
        ]);
    }


    public function test_email_should_be_a_valid_email()
    {
        $this->post(route('register'), [
            'email' => 'teste'            
        ])->assertSessionHasErrors([
            'email' => __('validation.email', ['attribute' => 'email'])
        ]);
    }

    public function test_email_should_be_a_unique()
    {

        // Arrange
        User::factory()->create([
            'email' => 'some@email.com'
        ]);

        // Act
        $return = $this->post(route('register'), [
            'email' => 'some@email.com'
        ]);

        // Assert
        $return->assertSessionHasErrors([
            'email' => __('validation.unique', ['attribute' => 'email'])
        ]);
    }

    public function test_email_should_be_confirmed()
    {
        $this->post(route('register'), [
            'email' => 'teste',
            'email_confirmation' => 'diff'
        ])->assertSessionHasErrors([
            'email' => __('validation.confirmed', ['attribute' => 'email'])
        ]);
    }

    public function test_password_should_be_required()
    {
        $this->post(route('register'), [

        ])->assertSessionHasErrors([
            'password' => __('validation.required', ['attribute' => 'password'])
        ]);
    }

    public function test_password_should_have_at_least_1_uppercase()
    {
        $this->post(route('register'), [
            'password' => 'password-without-uppercase'
        ])->assertSessionHasErrors([
            'password' => 'The password field must contain at least one uppercase and one lowercase letter.'
        ]);
    }
}
