<?php

declare(strict_types=1);

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use function auth;

class PermissionFilter implements FilterInterface
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

        foreach ($arguments as $permission) {
            if (method_exists($user, 'can') && $user->can((string) $permission)) {
                return null;
            }
        }

        return service('response')->setStatusCode(403, 'Forbidden');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}
