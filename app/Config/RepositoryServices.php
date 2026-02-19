<?php

namespace Config;

use App\Repositories\Contracts\Auth\UserRepositoryInterface;
use App\Repositories\EloquentLike\Auth\UserRepository;
use App\Services\Auth\AuthenticationService;
use App\Services\Auth\AuthorizationService;

class RepositoryServices
{
    private static ?UserRepositoryInterface $userRepository = null;
    private static ?AuthenticationService $authenticationService = null;
    private static ?AuthorizationService $authorizationService = null;

    public static function userRepository(): UserRepositoryInterface
    {
        if (self::$userRepository === null) {
            self::$userRepository = new UserRepository();
        }

        return self::$userRepository;
    }

    public static function authenticationService(): AuthenticationService
    {
        if (self::$authenticationService === null) {
            self::$authenticationService = new AuthenticationService(self::userRepository());
        }

        return self::$authenticationService;
    }

    public static function authorizationService(): AuthorizationService
    {
        if (self::$authorizationService === null) {
            self::$authorizationService = new AuthorizationService(self::userRepository());
        }

        return self::$authorizationService;
    }
}

