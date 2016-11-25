<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use App\Song;

class SongsQuery extends Query {
  protected $attributes = [
    'name' => 'songs'
  ];

  public function type()
  {
    return Type::listOf(GraphQL::type('Song'));
  }

  public function args()
  {
    return [
      'title' => ['name' => 'title', 'type' => Type::string()]
    ];
  }

  public function resolve($root, $args, $context, ResolveInfo $info)
  {

    $fields = $info->getFieldSelection($depth = 3);
    $songs = Song::query();

    foreach ($fields as $field => $keys) {
      if ($field === 'votes') {
        $songs->with('Votes');
      }
    }

    return $songs->get();

    if(isset($args['title']))
    {
      return Song::where('title' , $args['title'])->get();
    }
    else
    {
      return Song::all();
    }
  }
}
