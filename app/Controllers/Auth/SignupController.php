<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use Config\RepositoryServices;
use DomainException;

class SignupController extends BaseController
{
    public function index(): string
    {
        return view('auth/signup');
    }

    public function store(): RedirectResponse
    {
        $rules = [
            'username'         => 'required|min_length[3]|max_length[30]|regex_match[/\A[a-zA-Z0-9\.]+\z/]',
            'email'            => 'required|valid_email|max_length[254]',
            'password'         => 'required|min_length[8]|max_length[255]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()
                ->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $service = RepositoryServices::authenticationService();

        try {
            $service->register([
                'username' => (string) $this->request->getPost('username'),
                'email'    => (string) $this->request->getPost('email'),
                'password' => (string) $this->request->getPost('password'),
            ]);
        } catch (DomainException $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        $service->login(
            (string) $this->request->getPost('email'),
            (string) $this->request->getPost('password'),
        );

        return redirect()->to('/');
    }
}

