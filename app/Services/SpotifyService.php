<?php


namespace App\Services;


use App\Contracts\OAuthServiceContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class SpotifyService implements OAuthServiceContract
{

    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.spotify.com',
            'timeout' => 5.0
        ]);
    }

    public function auth(string $code): array
    {
        $url = "https://accounts.spotify.com/api/token";
        try {
            $response = $this->client->request('POST', $url, [
                'form_params' => [
                    'client_id' => env('SPOTIFY_OAUTH_ID'),
                    'client_secret' => env('SPOTIFY_OAUTH_SECRET'),
                    'code' => $code,
                    'redirect_uri' => env('SPOTIFY_REDIRECT_URI'),
                    'grant_type' => 'authorization_code'
                ],
                'headers' => [
                    'Accept' => 'application/json'
                ]
            ]);
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $exception) {
            return ["deu merda: " . $exception->getMessage()];
        }
    }

    public function getAuthenticatedUser(string $token): array
    {
        $uri = "/v1/me";
        $response = $this->client->request('GET', $uri, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $result = json_decode($response->getBody(), true);

        return [
            'id' => $result['id'],
            'email' => $result['email'],
            'name' => $result['display_name'],
        ];
    }
}
