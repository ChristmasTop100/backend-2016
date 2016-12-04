<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use App\User;
use Illuminate\Support\Facades\Auth;

class UsersQuery extends Query {
  protected $attributes = [
    'name' => 'users'
  ];

  public function type()
  {
    return Type::listOf(GraphQL::type('User'));
  }

  public function args()
  {
    return [
      'id' => ['name' => 'id', 'type' => Type::string()]
    ];
  }

  public function resolve($root, $args, $context, ResolveInfo $info)
  {
    $fields = $info->getFieldSelection($depth = 3);
    $users = User::query();

      foreach ($fields as $field => $keys) {
          if ($field === 'votes') {
              $users->with(
                [
                  'votes' => function ($query) {
                      $user_id = Auth::check() ? Auth::user()->id : 0;
                      $query->where('user_id', $user_id);
                  },
                ]
              );
          }
      }

    if(isset($args['id']))
    {
      $users->where('id' , $args['id']);
    }

    return $users->get();
  }
}
