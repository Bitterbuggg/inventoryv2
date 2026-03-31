<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use Config\RepositoryServices;

class ProductController extends BaseController
{
    public function index(): string
    {
        $keyword = trim((string) $this->request->getGet('q'));

        return view('admin/products/index', [
            'products'   => RepositoryServices::productService()->listAll(false, $keyword),
            'searchTerm' => $keyword,
        ]);
    }

    public function store(): RedirectResponse
    {
        $rules = [
            'product_name' => 'required|max_length[255]',
            'unit'         => 'required|max_length[50]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            RepositoryServices::productService()->create([
                'product_name' => (string) $this->request->getPost('product_name'),
                'unit'         => (string) $this->request->getPost('unit'),
                'is_active'    => $this->request->getPost('is_active') ?? '0',
            ]);
        } catch (\Throwable $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        return $this->redirectToIndex()->with('message', 'Product created.');
    }

    public function update(int $id): RedirectResponse
    {
        $rules = [
            'product_name' => 'required|max_length[255]',
            'unit'         => 'required|max_length[50]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            RepositoryServices::productService()->update($id, [
                'product_name' => (string) $this->request->getPost('product_name'),
                'unit'         => (string) $this->request->getPost('unit'),
                'is_active'    => $this->request->getPost('is_active') ?? '0',
            ]);
        } catch (\Throwable $exception) {
            return redirect()->back()->withInput()->with('error', $exception->getMessage());
        }

        return $this->redirectToIndex()->with('message', 'Product updated.');
    }

    private function redirectToIndex(): RedirectResponse
    {
        $keyword = trim((string) $this->request->getPost('catalog_search'));
        $path = '/admin/products';

        if ($keyword !== '') {
            $path .= '?' . http_build_query(['q' => $keyword]);
        }

        return redirect()->to($path);
    }
}
