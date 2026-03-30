<?php

use App\Database\Seeds\AuthRbacSeeder;
use App\Models\Catalog\ProductModel;
use App\Models\Catalog\SupplierModel;
use CodeIgniter\Shield\Entities\User;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Test\FeatureTestTrait;

/**
 * @internal
 */
final class CatalogManagementFlowTest extends CIUnitTestCase
{
    use DatabaseTestTrait;
    use FeatureTestTrait;

    protected $refresh = true;
    protected $migrate = true;
    protected $seed = AuthRbacSeeder::class;
    protected $namespace = null;
    protected $DBGroup = 'tests';

    public function testAdminCanCreateAndUpdateProduct(): void
    {
        $admin = $this->findUserByEmail('admin@local.test');
        auth('session')->login($admin);

        $createResponse = $this->withSession(session()->get())->post(
            '/admin/products',
            $this->csrfPayload([
                'product_name' => 'Disposable Syringe',
                'unit' => 'box',
                'is_active' => '1',
            ]),
        );
        $createResponse->assertRedirectTo('/admin/products');

        /** @var ProductModel $products */
        $products = model(ProductModel::class);
        $created = $products->where('product_name', 'Disposable Syringe')->first();

        $this->assertNotNull($created);
        $this->assertNotSame('', (string) ($created['product_code'] ?? ''));

        $updateResponse = $this->withSession(session()->get())->post(
            '/admin/products/' . (int) ($created['id'] ?? 0),
            $this->csrfPayload([
                'product_name' => 'Disposable Syringe',
                'unit' => 'piece',
                'is_active' => '0',
            ]),
        );
        $updateResponse->assertRedirectTo('/admin/products');

        $updated = $products->find((int) ($created['id'] ?? 0));

        $this->assertIsArray($updated);
        $this->assertSame('piece', $updated['unit']);
        $this->assertSame(0, (int) $updated['is_active']);
    }

    public function testAdminCanCreateAndUpdateSupplier(): void
    {
        $admin = $this->findUserByEmail('admin@local.test');
        auth('session')->login($admin);

        $createResponse = $this->withSession(session()->get())->post(
            '/admin/suppliers',
            $this->csrfPayload([
                'supplier_name' => 'Northwind Medical',
                'contact_person' => 'Jane Doe',
                'phone' => '1234567',
                'email' => 'northwind@example.test',
                'is_active' => '1',
            ]),
        );
        $createResponse->assertRedirectTo('/admin/suppliers');

        /** @var SupplierModel $suppliers */
        $suppliers = model(SupplierModel::class);
        $created = $suppliers->where('supplier_name', 'Northwind Medical')->first();

        $this->assertNotNull($created);
        $this->assertNotSame('', (string) ($created['supplier_code'] ?? ''));

        $updateResponse = $this->withSession(session()->get())->post(
            '/admin/suppliers/' . (int) ($created['id'] ?? 0),
            $this->csrfPayload([
                'supplier_name' => 'Northwind Medical',
                'contact_person' => 'Jane Smith',
                'phone' => '7654321',
                'email' => 'northwind-updated@example.test',
                'is_active' => '0',
            ]),
        );
        $updateResponse->assertRedirectTo('/admin/suppliers');

        $updated = $suppliers->find((int) ($created['id'] ?? 0));

        $this->assertIsArray($updated);
        $this->assertSame('Jane Smith', $updated['contact_person']);
        $this->assertSame('7654321', $updated['phone']);
        $this->assertSame('northwind-updated@example.test', $updated['email']);
        $this->assertSame(0, (int) $updated['is_active']);
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
