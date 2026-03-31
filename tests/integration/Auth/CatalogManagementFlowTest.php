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

    public function testAdminCanSearchProductCatalogRecords(): void
    {
        $admin = $this->findUserByEmail('admin@local.test');
        auth('session')->login($admin);

        /** @var ProductModel $products */
        $products = model(ProductModel::class);
        $products->insert([
            'product_code' => 'PRD-SEARCHA',
            'product_name' => 'Catalog Search Alpha Product',
            'unit' => 'box',
            'is_active' => 1,
        ]);
        $products->insert([
            'product_code' => 'PRD-SEARCHB',
            'product_name' => 'Catalog Search Beta Product',
            'unit' => 'pack',
            'is_active' => 1,
        ]);

        $response = $this->withSession(session()->get())->get('/admin/products?q=Alpha');

        $response->assertOK();
        $response->assertSee('Catalog Search Alpha Product');
        $response->assertDontSee('Catalog Search Beta Product');
    }

    public function testAdminCanSearchSupplierCatalogRecords(): void
    {
        $admin = $this->findUserByEmail('admin@local.test');
        auth('session')->login($admin);

        /** @var SupplierModel $suppliers */
        $suppliers = model(SupplierModel::class);
        $suppliers->insert([
            'supplier_code' => 'SUP-SEARCHA',
            'supplier_name' => 'Catalog Search Alpha Supplier',
            'contact_person' => 'Alice Contact',
            'phone' => '1000',
            'email' => 'alpha.supplier@example.test',
            'is_active' => 1,
        ]);
        $suppliers->insert([
            'supplier_code' => 'SUP-SEARCHB',
            'supplier_name' => 'Catalog Search Beta Supplier',
            'contact_person' => 'Bob Contact',
            'phone' => '2000',
            'email' => 'beta.supplier@example.test',
            'is_active' => 1,
        ]);

        $response = $this->withSession(session()->get())->get('/admin/suppliers?q=Alice');

        $response->assertOK();
        $response->assertSee('Catalog Search Alpha Supplier');
        $response->assertDontSee('Catalog Search Beta Supplier');
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
