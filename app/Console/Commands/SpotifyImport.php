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

        DB::transaction(function () use ($playlist) {
            $existingPlaylistSongs = collect($playlist->tracks->items)->map(function ($item) {
                $song = [
                    'image' => $item->track->album->images[0]->url,
                    'url'   => $item->track->external_urls->spotify,
                    'title'  => $item->track->name,
                    'artist' => collect($item->track->artists)->implode('name', ', '),
                ];

                Song::updateOrCreate(['url' => $song['url']], $song);

                return $song['url'];
            })->toArray();

            Song::whereNotIn('url', $existingPlaylistSongs)->delete();
        });
    }
}