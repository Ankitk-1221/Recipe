<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\User;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
        	'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'added' => date('Y-m-d', strtotime($user->created_at))
        ];
    }
}