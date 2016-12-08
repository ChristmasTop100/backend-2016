<?php

namespace app\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;
use Folklore\GraphQL\Support\Facades\GraphQL;

class SessionType extends GraphQLType{
  protected $attributes = [
    'name' => 'Session',
    'description' => 'A session'
  ];

  public function fields()
  {
    return [
      'token' => [
        'type' => Type::string(),
        'description' => 'The session token'
      ],
      'user' => [
        'type' => GraphQL::type('User'),
        'description' => 'The user that is logged in.'
      ]
    ];
  }
}
