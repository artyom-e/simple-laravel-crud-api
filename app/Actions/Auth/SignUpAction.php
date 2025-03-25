<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Data\Auth\SignUpData;
use App\Models\User;
use Hash;

class SignUpAction
{
    public function run(SignUpData $data): string
    {
        $user = User::create([
            'name' => $data->name,
            'email' => $data->email,
            'password' => Hash::make($data->password),
        ]);
        $token = $user->createToken('auth_token');

        return $token->plainTextToken;
    }
}
