<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use App\Vote;
use Illuminate\Support\Facades\Auth;
use GraphQL\Type\Definition\ResolveInfo;

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
      'song_id' => ['name' => 'song_id', 'type' => Type::int()]
    ];
  }

  public function resolve($root, $args, $context, ResolveInfo $info)
  {
    if (! Auth::check()) {
      return [];
    }

    $fields = $info->getFieldSelection($depth = 3);
    $votes = Vote::query();
    $votes->where('user_id', Auth::user()->id);

    foreach ($fields as $field => $keys) {
      if ($field === 'users')
      {
        $votes->with('users');
      }

      if ($field === 'songs')
      {
        $votes->with('songs');
      }
    }

    if(isset($args['song_id']))
    {
      $votes->where('song_id' , $args['song_id']);
    }

    return $votes->get();
  }
}
