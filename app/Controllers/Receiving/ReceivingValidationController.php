<?php

namespace App\Controllers\Receiving;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RedirectResponse;
use Config\RepositoryServices;

class ReceivingValidationController extends BaseController
{
    public function validateDraft(int $id): RedirectResponse
    {
        $errors = RepositoryServices::receivingService()->validateDraft($id);

        if ($errors !== []) {
            return redirect()->to('/receiving/' . $id)->with('error', implode(' ', $errors));
        }

        return redirect()->to('/receiving/' . $id)->with('message', 'Receiving draft validation passed.');
    }
}
