<?php


namespace App\Services;


use App\Contracts\OAuthServiceContract;
use GuzzleHttp\Client;

class TwitchService implements OAuthServiceContract
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.twitch.tv/kraken/',
            'timeout' => 5.0
        ]);
    }

    public function auth(string $code): array
    {
        $uri = "https://id.twitch.tv/oauth2/token";
        $response = $this->client->request('POST', $uri, [
            'form_params' => [
                'client_id' => env('TWITCH_OAUTH_ID'),
                'client_secret' => env('TWITCH_OAUTH_SECRET'),
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => env('TWITCH_REDIRECT_URI')
            ]
        ]);
        return json_decode($response->getBody(), true);
    }

    public function getAuthenticatedUser(string $token): array
    {
        $uri = "user";
        $response = $this->client->request('GET', $uri, [
            'headers' => [
                'Client-ID' => env('TWITCH_OAUTH_ID'),
                'Authorization' => 'OAuth ' . $token,
                'Accept' => 'application/vnd.twitchtv.v5+json'
            ]
        ]);

        $result = json_decode($response->getBody(), true);

        return [
            'id' => $result['_id'],
            'email' => $result['email'],
            'name' => $result['name'],
        ];
    }
}
