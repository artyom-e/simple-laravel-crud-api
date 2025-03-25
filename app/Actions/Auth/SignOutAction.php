<?php

declare(strict_types=1);

namespace App\Actions\Auth;

use App\Models\User;

class SignOutAction
{
    public function run(User $user): void
    {
        if (!($token = $user->currentAccessToken())) {
            return;
        }

        $token->delete();
    }
}
