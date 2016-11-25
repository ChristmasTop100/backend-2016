<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;
use Folklore\GraphQL\Support\Facades\GraphQL;

class UserType extends GraphQLType {
  protected $attributes = [
    'name' => 'User',
    'description' => 'A user'
  ];

  public function fields()
  {
    return [
      'id' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'The id of the user'
      ],
      'name' => [
        'type' => Type::string(),
        'description' => 'The name of the user'
      ],
      'votes' => [
        'type' => Type::listOf(GraphQL::type('Vote')),
        'description' => 'A user\'s votes'
      ]
    ];
  }
}
