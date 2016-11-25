<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use App\Vote;

class VotesQuery extends Query {
  protected $attributes = [
    'name' => 'votes'
  ];

  public function type()
  {
    return Type::listOf(GraphQL::type('Vote'));
  }

  public function args()
  {
    return [
      'song_id' => ['name' => 'song_id', 'type' => Type::int()],
      'user_id' => ['name' => 'song_id', 'type' => Type::int()]
    ];
  }

  public function resolve($root, $args)
  {
    if(isset($args['song_id']))
    {
      return Vote::where('song_id' , $args['song_id'])->get();
    }
    else if(isset($args['user_id']))
    {
      return Vote::where('user_id' , $args['user_id'])->get();
    }
    else
    {
      return Vote::all();
    }
  }
}
