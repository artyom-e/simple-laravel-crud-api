<?php

declare(strict_types=1);

namespace App\Data\TaskList;

use App\Models\User;
use Spatie\LaravelData\Attributes\FromAuthenticatedUser;
use Spatie\LaravelData\Data;

class IndexData extends Data
{
    public function __construct(
        #[FromAuthenticatedUser]
        public User $user,
        public int $page = 1,
    ) {
    }
}
