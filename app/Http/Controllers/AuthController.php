<?php


namespace App\Http\Controllers;


use App\Contracts\OAuthServiceContract;
use App\Repositories\AuthRepository;
use App\Services\GithubService;
use App\Services\TwitchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController
{
    /**
     * @var AuthRepository
     */
    private $repository;

    public function __construct(AuthRepository $repository)
    {
        $this->repository = $repository;
    }


    public function authenticate(Request $request, string $provider)
    {
        $auth = $this->repository->authenticateUser($provider, $request->get('code'));

        if ($auth) {
            return response()->redirectTo('/?deubom');
        }
        return response()->redirectTo('/?deuruim');
    }

    public function logout()
    {
        Auth::logout();
        return response()->redirectTo('/?logout');
    }


}
