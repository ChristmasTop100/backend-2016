<?php

namespace App\GraphQL\Query;

use GraphQL;
use Folklore\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class CreateSessionMutation extends Mutation
{
    protected $attributes = [
      'name' => 'CreateSession',
    ];

    public function type()
    {
        return GraphQL::type('Session');
    }

    public function args()
    {
        return [
          'email' => [
            'name' => 'email',
            'type' => Type::nonNull(Type::string()),
          ],
          'password' => [
            'name' => 'password',
            'type' => Type::nonNull(Type::string()),
          ],
        ];
    }

    public function rules()
    {
        return [
          'email' => ['required', 'email'],
          'password' => ['required'],
        ];
    }

    public function resolve($root, $args)
    {
        if (Auth::check()) {
            Throw new GraphQL\Error('Already logged in');
        }

        if (!$token = JWTAuth::attempt(
          ['email' => $args['email'], 'password' => $args['password']]
        )
        ) {
            Throw new GraphQL\Error('Invalid credentials');
        }

        return compact('token');
    }
}
