<?php

use App\Database\Seeds\AuthRbacSeeder;
use App\Models\Analytics\AnalyticsEventModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class AnalyticsTrackingTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $migrate = true;
    protected $seed = AuthRbacSeeder::class;
    protected $namespace = null;
    protected $DBGroup = 'tests';

    protected function tearDown(): void
    {
        try {
            auth('session')->logout();
        } catch (\Throwable) {
            // No active auth context in some runs.
        }

        parent::tearDown();
    }

    public function testReportViewCreatesAnalyticsEvent(): void
    {
        $admin = $this->findUserByEmail('admin@local.test');
        auth('session')->login($admin);

        $response = $this->withSession(session()->get())->get('/reports/stock-balance');
        $response->assertOK();

        /** @var AnalyticsEventModel $eventModel */
        $eventModel = model(AnalyticsEventModel::class);

        $event = $eventModel
            ->where('event_name', 'report.viewed')
            ->where('module', 'reports')
            ->orderBy('id', 'DESC')
            ->first();

        $this->assertNotNull($event);
        $this->assertSame((int) $admin->id, (int) $event['actor_id']);
        $this->assertStringContainsString('stock_balance', (string) ($event['metadata_json'] ?? ''));
    }

    public function testFailedLoginCreatesAnalyticsEvent(): void
    {
        $payload = $this->csrfPayload([
            'identifier' => 'admin@local.test',
            'password'   => 'wrong-password',
        ]);

        $response = $this->withSession(session()->get())->post('/login', $payload);
        $response->assertRedirect();

        /** @var AnalyticsEventModel $eventModel */
        $eventModel = model(AnalyticsEventModel::class);

        $event = $eventModel
            ->where('event_name', 'auth.login_failed')
            ->where('module', 'auth')
            ->orderBy('id', 'DESC')
            ->first();

        $this->assertNotNull($event);
        $this->assertNull($event['actor_id']);
    }

    private function findUserByEmail(string $email): User
    {
        $user = model(UserModel::class)->findByCredentials(['email' => $email]);

        if (! $user instanceof User) {
            throw new RuntimeException("User {$email} not found in test setup.");
        }

        return $user;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return array<string, mixed>
     */
    private function csrfPayload(array $data): array
    {
        return $data + [csrf_token() => csrf_hash()];
    }
}
