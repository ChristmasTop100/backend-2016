<?php

namespace app\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

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
      'error' => [
        'type' => Type::string(),
        'description' => 'The error message if something went wrong'
      ]
    ];
  }
}
