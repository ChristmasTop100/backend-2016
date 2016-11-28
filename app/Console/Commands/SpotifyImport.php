<?php

namespace App\Console\Commands;

use App\Song;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;

class SpotifyImport extends Command
{

    /**
     * @var string
     */
    protected $signature = 'spotify:import';

    /**
     * @var string
     */
    protected $description = 'Import songs from the spotify christmastop100 list into the database.';

    public function handle()
    {
        $session = new Session(config('spotify.client.id'), config('spotify.client.secret'), null);
        $api = new SpotifyWebAPI();

        $session->requestCredentialsToken([]);
        $api->setAccessToken($session->getAccessToken());

        $playlist = $api->getUserPlaylist(config('spotify.playlist.author'), config('spotify.playlist.id'));

        $this->info('Removing all old songs.');

        DB::transaction(function () use ($playlist) {
            // Disable foreign key checks so the vote table stays correct.
            DB::statement('SET FOREIGN_KEY_CHECKS = 0');
            Song::truncate();

            Song::insert(collect($playlist->tracks->items)->map(function ($item) {
                $this->info('Importing -> ' . $item->track->name . ' - ' . collect($item->track->artists)->implode('name', ', '));

                return [
                    'image' => $item->track->album->images[0]->url,
                    'url'   => $item->track->external_urls->spotify,
                    'title'  => $item->track->name,
                    'artist' => collect($item->track->artists)->implode('name', ', '),
                ];
            })->toArray());

            DB::statement('SET FOREIGN_KEY_CHECKS = 1');
            $this->info('Done!');
        });
    }
}
