<?php

namespace App\GraphQL\Query;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use GraphQL\Type\Definition\ResolveInfo;
use App\Song;
use Illuminate\Support\Facades\Auth;

class SongsQuery extends Query
{
    protected $attributes = [
      'name' => 'songs',
    ];

    public function type()
    {
        return Type::listOf(GraphQL::type('Song'));
    }

    public function args()
    {
        return [
          'id' => ['name' => 'id', 'type' => Type::int()],
        ];
    }

    public function resolve($root, $args, $context, ResolveInfo $info)
    {

        $fields = $info->getFieldSelection($depth = 3);
        $songs = Song::query();

        foreach ($fields as $field => $keys) {
            if ($field === 'votes') {
                $songs->with(
                  [
                    'votes' => function ($query) {
                        $user_id = Auth::check() ? Auth::user()->id : 0;
                        $query->where('user_id', $user_id);
                    },
                  ]
                );
            }
        }

        if (isset($args['id'])) {
            $songs->where('id', $args['id']);
        }

        return $songs->get();
    }
}
