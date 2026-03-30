<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use function auth;

class RoleFilter implements FilterInterface
{
    /**
     * @param string[]|null $arguments
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $authenticator = auth('session');

        if (! $authenticator->loggedIn()) {
            return redirect()->to(site_url('login'));
        }

        if ($arguments === null || $arguments === []) {
            return null;
        }

        $user = $authenticator->user();

        if ($user === null) {
            return redirect()->to(site_url('login'));
        }

        foreach ($arguments as $group) {
            if ($user->inGroup($group)) {
                return null;
            }
        }

        return app_forbidden_response(service('response'));
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
