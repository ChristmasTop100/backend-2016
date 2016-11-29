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
            $existingSongs = Song::all();

            $existingPlaylistSongs = collect($playlist->tracks->items)->map(function ($item) use ($existingSongs) {
                $song = [
                    'image' => $item->track->album->images[0]->url,
                    'url'   => $item->track->external_urls->spotify,
                    'title'  => $item->track->name,
                    'artist' => collect($item->track->artists)->implode('name', ', '),
                ];

                if (! empty($existingSongs->where('url', $song['url'])->all())) {
                    Song::where('url', $song['url'])->update($song);
                    $this->info("Updating: {$song['title']} - {$song['artist']}");
                } else {
                    Song::create($song);
                    $this->info("Creating: {$song['title']} - {$song['artist']}");
                }

                return $song['url'];
            })->toArray();

            Song::whereNotIn('url', $existingPlaylistSongs)->get()->map(function ($song) {
                $this->info("Removing: {$song['title']} - {$song['artist']}");
                $song->delete();
            });

            $this->info('Done!');
        });
    }
}
