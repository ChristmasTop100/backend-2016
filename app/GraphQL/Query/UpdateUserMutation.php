<?php

namespace App\GraphQL\Query;

use GraphQL;
use Folklore\GraphQL\Support\Mutation;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Auth;
use JWTAuth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class UpdateUserMutation extends Mutation{
    protected $attributes = [
        'name' => 'UpdateUser'
    ];

    public function type()
    {
        return GraphQL::type('User');
    }

    public function args()
    {
        return [
            'password' => ['name' => 'password', 'type' => Type::string()],
            'email' => ['name' => 'email', 'type' => Type::string()],
            'token' => ['name' => 'token', 'type' => Type::string()]
        ];
    }

    public function resolve($root, $args)
    {
        if (! Auth::check() && ! isset($args['token'])) {
            Throw new GraphQL\Error('You need to be logged in to change a password.');
        }

        if (isset($args['token'])) {
            if ( ! isset($args['email']) || ! isset($args['password'])) {
                Throw new GraphQL\Error('New password and current mail needed to use login token.');
            }
            $credentials = $args + ['password_confirmation' => $args['password']];
            $response = Password::broker()->reset($credentials, function ($user, $password) {
                $this->resetPassword($user, $password);

            });

            if ($response != Password::PASSWORD_RESET) {
                Throw new GraphQL\Error('Invalid token');
            }
            $user = Auth::user();
        }
        else {
            $user = Auth::user();
            $this->resetPassword($user, $args['password']);
        }

        return $user;
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->forceFill([
          'password' => bcrypt($password),
          'remember_token' => Str::random(60),
        ])->save();

        Auth::guard()->login($user);
    }
}
