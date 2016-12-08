<?php

namespace App\GraphQL\Query;

use GraphQL;
use Folklore\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Illuminate\Support\Facades\Hash;

class UpdateUserMutation extends Mutation{
  protected $attributes = [
    'name' => 'UpdateUser'
  ];

  public function type()
  {
    return GraphQL::type('User');
  }

  public function args()
  {
    return [
      'password' => ['name' => 'password', 'type' => Type::nonNull(Type::string())]
    ];
  }

  public function resolve($root, $args)
  {
    if (! Auth::check()) {
      Throw new GraphQL\Error('You need to be logged in to change a password.');
    }

    $user = Auth::user();
    $user->password = Hash::make($args['password']);
    $user->save();
    return $user;
  }
}
