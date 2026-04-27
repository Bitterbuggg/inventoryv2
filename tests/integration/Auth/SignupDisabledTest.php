<?php

use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class SignupDisabledTest extends CIUnitTestCase
{
    use FeatureTestTrait;

    public function testSignupPageIsNotPubliclyAvailable(): void
    {
        $this->expectException(PageNotFoundException::class);

        $this->get('/signup');
    }

    public function testShieldRegistrationIsDisabled(): void
    {
        $this->assertFalse(config('Auth')->allowRegistration);
    }
}
