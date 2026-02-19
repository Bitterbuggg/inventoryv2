<?php

namespace App\Filters;

use CodeIgniter\Exceptions\PageForbiddenException;
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

        throw PageForbiddenException::forPageForbidden('You do not have access to this resource.');
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return null;
    }
}

