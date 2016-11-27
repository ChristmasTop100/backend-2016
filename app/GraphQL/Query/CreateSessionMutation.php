<?php

namespace App\GraphQL\Query;

use GraphQL;
use Folklore\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use JWTAuth;

class CreateSessionMutation extends Mutation{
  protected $attributes = [
    'name' => 'CreateSession'
  ];

  public function type()
  {
    return GraphQL::type('Session');
  }

  public function args()
  {
    return [
      'email' => ['name' => 'email', 'type' => Type::nonNull(Type::string())],
      'password' => ['name' => 'password', 'type' => Type::nonNull(Type::string())]
    ];
  }

  public function resolve($root, $args)
  {
    if (Auth::check()) {
      return ['error' => 'logged_in'];
    }

    if (! $token = JWTAuth::attempt(['email' => $args['email'], 'password' => $args['password']])) {
      return ['error' => 'invalid_credentials'];
    }

    return compact('token');
  }
}
