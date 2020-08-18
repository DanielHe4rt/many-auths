<?php


namespace App\Services;


use App\Contracts\OAuthServiceContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GithubService implements OAuthServiceContract
{
    /**
     * @var Client
     */
    private $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.github.com',
            'timeout' => 5.0,
        ]);
    }

    public function auth(string $code): array
    {
        $url = "https://github.com/login/oauth/access_token";
        try {
            $response = $this->client->request('POST', $url, [
                'form_params' => [
                    'client_id' => env('GITHUB_OAUTH_ID'),
                    'client_secret' => env('GITHUB_OAUTH_SECRET'),
                    'code' => $code,
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
        $uri = "/user";
        $response = $this->client->request('GET', $uri, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token
            ]
        ]);

        $result = json_decode($response->getBody(), true);

        return [
            'id' => $result['id'],
            'email' => $result['email'],
            'name' => $result['name'],
        ];
    }
}
