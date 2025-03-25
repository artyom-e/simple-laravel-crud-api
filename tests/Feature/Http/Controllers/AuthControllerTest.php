<?php

declare(strict_types=1);

namespace Tests\Feature\Http\Controllers;

use App\Http\Controllers\AuthController;
use App\Models\User;
use Faker\Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    public static function signUpValidationErrorsProvider(): array
    {
        return [
            'name, email, password field is required' => [
                fn(Generator $faker) => [], // payload
                ['name', 'email', 'password'], // validation errors
            ],
            'name must be greater than or equal to 3' => [
                fn(Generator $faker) => [
                    'name' => 'ab',
                ], // payload
                ['name'], // validation errors
            ],
            'name must be less than or equal to 64' => [
                fn(Generator $faker) => [
                    'name' => $faker->realTextBetween(65, 128),
                ], // payload
                ['name'], // validation errors
            ],
            'email field must be email' => [
                fn(Generator $faker) => [
                    'email' => $faker->word(),
                ], // payload
                ['email'], // validation errors
            ],
            'password must be less than or equal to 32' => [
                fn(Generator $faker) => [
                    'password' => $faker->realTextBetween(33, 64),
                ], // payload
                ['password'], // validation errors
            ],
            'password must be greater than or equal to 8' => [
                fn(Generator $faker) => [
                    'password' => 'qwerty',
                ], // payload
                ['password'], // validation errors
            ],
        ];
    }

    public static function signInValidationErrorsProvider(): array
    {
        return [
            'email field must be email' => [
                fn(Generator $faker) => [
                    'email' => $faker->word(),
                ], // payload
                ['email'], // validation errors
            ],
            'password must be less than or equal to 32' => [
                fn(Generator $faker) => [
                    'password' => $faker->realTextBetween(33, 64),
                ], // payload
                ['password'], // validation errors
            ],
            'password must be greater than or equal to 8' => [
                fn(Generator $faker) => [
                    'password' => 'qwerty',
                ], // payload
                ['password'], // validation errors
            ],
        ];
    }

    #[DataProvider('signUpValidationErrorsProvider')]
    public function testSignUpWithValidationErrors(callable $payloadGenerator, array $validationErrors): void
    {
        $this->postJson(action([AuthController::class, 'signUp']), $payloadGenerator($this->faker))
            ->assertJsonValidationErrors($validationErrors);
    }

    public function testSignUpWithExistingEmail(): void
    {
        $user = User::factory()->create();
        $this->postJson(action([AuthController::class, 'signUp']), [
            'email' => $user->email,
            'password' => $this->faker->realTextBetween(8, 16),
            'name' => $this->faker->name(),
        ])->assertJsonValidationErrors('email');
    }

    public function testSignUp(): void
    {
        $this->postJson(action([AuthController::class, 'signUp']), [
            'email' => $this->faker->safeEmail(),
            'password' => $this->faker->realTextBetween(8, 16),
            'name' => $this->faker->name(),
        ])->assertCreated()
            ->assertJsonStructure(['token']);
    }

    public function testSignUpAuthenticated(): void
    {
        $this->actingAs(User::factory()->create());
        $this->postJson(action([AuthController::class, 'signUp']), [
            'email' => $this->faker->safeEmail(),
            'password' => $this->faker->realTextBetween(8, 16),
            'name' => $this->faker->name(),
        ])->assertForbidden();
    }

    #[DataProvider('signInValidationErrorsProvider')]
    public function testSignInWithValidationErrors(callable $payloadGenerator, array $validationErrors): void
    {
        $this->postJson(action([AuthController::class, 'signIn']), $payloadGenerator($this->faker))
            ->assertJsonValidationErrors($validationErrors);
    }

    public function testSignInWithNotExistingEmail(): void
    {
        $this->postJson(action([AuthController::class, 'signIn']), [
            'email' => $this->faker->safeEmail(),
            'password' => $this->faker->realTextBetween(8, 16),
        ])->assertJsonValidationErrors(['email']);
    }

    public function testSignIn(): void
    {
        $password = '12345678';
        $user = User::factory(['password' => $password])->create();
        $this->postJson(action([AuthController::class, 'signIn']), [
            'email' => $user->email,
            'password' => $password,
        ])->assertOk()
            ->assertJsonStructure(['token']);
    }

    public function testSignOutUnauthorizedUser(): void
    {
        $this->postJson(action([AuthController::class, 'signOut']))->assertUnauthorized();
    }

    public function testSignOut(): void
    {
        $this->actingAs(User::factory()->create());
        $this->postJson(action([AuthController::class, 'signOut']))->assertNoContent();
    }
}
