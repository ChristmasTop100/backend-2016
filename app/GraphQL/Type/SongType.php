<?php

namespace App\GraphQL\Type;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;
use Folklore\GraphQL\Support\Facades\GraphQL;

class SongType extends GraphQLType {
  protected $attributes = [
    'name' => 'Song',
    'description' => 'A song'
  ];

  public function fields()
  {
    return [
      'title' => [
        'type' => Type::nonNull(Type::string()),
        'description' => 'The title of the song'
      ],
      'artist' => [
        'type' => Type::string(),
        'description' => 'The name of the artist of the song'
      ],
      'url' => [
        'type' => Type::string(),
        'description' => 'The spotify url of the song'
      ],
      'image' => [
        'type' => Type::string(),
        'description' => 'The image of the song'
      ],
      'votes' => [
        'type' => Type::listOf(GraphQL::type('Vote')),
        'description' => 'A songs\'s votes'
      ]
    ];
  }
}
