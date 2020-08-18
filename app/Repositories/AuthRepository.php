<?php


namespace App\Repositories;


use App\Contracts\OAuthServiceContract;
use App\Services\GithubService;
use App\Services\SpotifyService;
use App\Services\TwitchService;
use App\User;
use Illuminate\Support\Facades\Auth;

class AuthRepository
{
    public function authenticateUser(string $provider, string $code)
    {
        try {
            $authService = $this->getService($provider);
            $authData = $authService->auth($code);

            $response = $authService->getAuthenticatedUser($authData['access_token']);
            $authUser = $this->findOrCreate($provider, $response);

            Auth::login($authUser);
            return true;

        } catch (\Exception $exception) {
            return false;
        }
    }

    public function findOrCreate(string $provider, $providerData): User
    {
        $auth = User::where('email', $providerData['email'])->first();
        if (!$auth) {
            return User::create([
                'name' => $providerData['name'],
                'email' => $providerData['email'],
                $provider . "_id" => $providerData['id'],
            ]);
        }

        if (empty($auth->{$provider . "_id"})) {
            $auth->update([
                $provider . "_id" => $providerData['id']
            ]);
            return $auth;
        }

        if ($auth->{$provider . "_id"} == $providerData['id']) {
            return $auth;
        }

        throw new \Exception('deu ruim');
    }

    private function getService(string $provider): OAuthServiceContract
    {
        if ($provider === "github") {
            return new GithubService();
        } else if ($provider === "twitch") {
            return new TwitchService();
        } else if ($provider === "spotify") {
            return new SpotifyService();
        } else {
            throw new \Exception('não existe esse provider');
        }
    }
}
