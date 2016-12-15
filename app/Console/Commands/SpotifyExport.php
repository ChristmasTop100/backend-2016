<?php

namespace App\Console\Commands;

use App\Song;
use Illuminate\Console\Command;

class SpotifyExport extends Command
{

    /**
     * @var string
     */
    protected $signature = 'spotify:export';

    /**
     * @var string
     */
    protected $description = 'Export all the songs as a list of url\'s.';

    public function handle()
    {
        Song::with('votes')->get()->sortByAsc(function ($song) {
            return $song->votes->sum('score');
        })->map(function ($song) {
            $this->info($song->url);
        });
    }
}
