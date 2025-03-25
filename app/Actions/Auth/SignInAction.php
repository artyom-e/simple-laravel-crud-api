<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Data\Auth\SignInData;
use App\Models\User;
use Hash;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class SignInAction
{
    public function run(SignInData $data): string
    {
        $user = User::where('email', $data->email)->first();
        if (!$user || !Hash::check($data->password, $user->password)) {
            throw new UnauthorizedHttpException('', 'Invalid credentials');
        }

        return $user->createToken('auth_token')->plainTextToken;
    }
}
