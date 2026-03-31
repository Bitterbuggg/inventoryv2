<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use Config\RepositoryServices;

class SupplierController extends BaseController
{
    public function index(): string
    {
        $keyword = trim((string) $this->request->getGet('q'));

        return view('admin/suppliers/index', [
            'suppliers'  => RepositoryServices::supplierService()->listAll(false, $keyword),
            'searchTerm' => $keyword,
        ]);
    }

    public function store(): RedirectResponse
    {
        $rules = [
            'supplier_name'  => 'required|max_length[255]',
            'contact_person' => 'permit_empty|max_length[255]',
            'phone'          => 'permit_empty|max_length[50]',
            'email'          => 'permit_empty|valid_email|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            RepositoryServices::supplierService()->create([
                'supplier_name'  => (string) $this->request->getPost('supplier_name'),
                'contact_person' => $this->request->getPost('contact_person'),
                'phone'          => $this->request->getPost('phone'),
                'email'          => $this->request->getPost('email'),
                'is_active'      => $this->request->getPost('is_active') ?? '0',
            ]);
        } catch (\Throwable $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        return $this->redirectToIndex()->with('message', 'Supplier created.');
    }

    public function update(int $id): RedirectResponse
    {
        $rules = [
            'supplier_name'  => 'required|max_length[255]',
            'contact_person' => 'permit_empty|max_length[255]',
            'phone'          => 'permit_empty|max_length[50]',
            'email'          => 'permit_empty|valid_email|max_length[255]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            RepositoryServices::supplierService()->update($id, [
                'supplier_name'  => (string) $this->request->getPost('supplier_name'),
                'contact_person' => $this->request->getPost('contact_person'),
                'phone'          => $this->request->getPost('phone'),
                'email'          => $this->request->getPost('email'),
                'is_active'      => $this->request->getPost('is_active') ?? '0',
            ]);
        } catch (\Throwable $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        return $this->redirectToIndex()->with('message', 'Supplier updated.');
    }

    private function redirectToIndex(): RedirectResponse
    {
        $keyword = trim((string) $this->request->getPost('catalog_search'));
        $path = '/admin/suppliers';

        if ($keyword !== '') {
            $path .= '?' . http_build_query(['q' => $keyword]);
        }

        return redirect()->to($path);
    }
}
