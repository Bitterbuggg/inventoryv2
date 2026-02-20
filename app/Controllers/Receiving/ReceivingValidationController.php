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
            RepositoryServices::analyticsService()->trackCurrentUser(
                'receiving.validation_failed',
                'receiving',
                'receiving',
                $id,
                ['errors_count' => count($errors)],
            );

            return redirect()->to('/receiving/' . $id)->with('error', implode(' ', $errors));
        }

        RepositoryServices::analyticsService()->trackCurrentUser(
            'receiving.validated',
            'receiving',
            'receiving',
            $id,
        );

        return redirect()->to('/receiving/' . $id)->with('message', 'Receiving draft validation passed.');
    }
}
