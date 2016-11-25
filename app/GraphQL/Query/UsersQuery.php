<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use App\User;

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
        $users->with('Votes');
      }
    }

    return $users->get();

    if(isset($args['id']))
    {
      return User::where('id' , $args['id'])->get();
    }
    else
    {
      return User::all();
    }
  }
}
