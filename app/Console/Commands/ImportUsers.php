<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class ImportUsers extends Command
{

    /**
     * @var string
     */
    public $description = 'Import users from a JSON file.';

    /**
     * @var string
     */
    public $signature = 'users:import {file}';

    public function handle()
    {

        $employees = json_decode(file_get_contents($this->argument('file')));
        if (is_object($employees) && isset($employees->users)) {
            collect($employees->users)->each(function($employee) {
                $user = User::firstOrNew(['email' => $employee->email]);
                $user->name = $employee->name;

                if ( ! $user->exists) {
                    $user->password = bcrypt(str_random(20));
                }

                $user->save();
            });
        }
    }

}
