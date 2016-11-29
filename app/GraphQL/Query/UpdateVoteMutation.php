<?php

namespace App\GraphQL\Query;

use GraphQL;
use Folklore\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use App\Vote;

class UpdateVoteMutation extends Mutation{
  protected $attributes = [
    'name' => 'UpdateVote'
  ];

  public function type()
  {
    return GraphQL::type('Vote');
  }

  public function args()
  {
    return [
      'song_id' => ['name' => 'song_id', 'type' => Type::nonNull(Type::int())],
      'score' => ['name' => 'score', 'type' => Type::nonNull(Type::int())]
    ];
  }

  public function resolve($root, $args)
  {
    if (! Auth::check()) {
      Throw new GraphQL\Error('You need to be logged in to vote.');
    }

    if ($args['score'] < 0 || $args['score'] > 100) {
      Throw new GraphQL\Error('You can only give scores between 0 and 100.');
    }

    $user_id = Auth::user()->id;
    $song_id = $args['song_id'];
    $scores = Vote::where('user_id', $user_id)
      ->where('song_id', '!=', $song_id)
      ->get()
      ->pluck('score');
    if (($scores->sum() + $args['score']) > 100) {
      Throw new GraphQL\Error('The sum of all your votes may not exceed 100.');
    }

    $vote = Vote::firstOrNew(compact(['song_id', 'user_id']));
    $vote->score = $args['score'];
    $vote->save();

    return $vote;
  }
}
