<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Supplier;
use App\Models\CltLayup;
use App\Models\CltLayer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

use PHPUnit\Framework\Attributes\Test;

class SupplierImportTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $supplier;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create([
            'role' => 'admin'
        ]);
        $this->supplier = Supplier::factory()->create([
            'user_id' => $this->user->id
        ]);
    }


    #[Test]
    public function it_can_import_with_overwrite_strategy()
    {
        // siapkan data awal (DB)
        $layup = CltLayup::create([
            'supplier_id' => $this->supplier->id,
            'name' => 'Panel A'
        ]);
        CltLayer::create([
            'layup_id' => $layup->id,
            'layer_order' => 1,
            'thickness' => 10,
            'width' => 100,
            'angle' => 0
        ]);

        // dummy file JSON
        $importData = [
            'layups' => [
                [
                    'name' => 'Panel A',
                    'layers' => [
                        ['layer_order' => 1, 'thickness' => 20, 'width' => 100, 'angle' => 0]
                    ]
                ]
            ]
        ];

        // eksekusi (http req)
        $response = $this->actingAs($this->user)
            ->postJson("/suppliers/{$this->supplier->id}/import", [
                'strategy' => 'overwrite',
                'dry_run' => false,
                'file' => $this->createFakeJsonFile($importData)
            ]);

        // assert (pastikan data di DB jadi 20)
        $response->assertStatus(200);
        $this->assertDatabaseHas('clt_layers', [
            'layup_id' => $layup->id,
            'thickness' => 20
        ]);
    }

    #[Test]
    public function it_skips_conflicting_layers_when_skip_strategy_is_used()
    {
        // 1. Data awal: thickness 10
        $layup = CltLayup::create(['supplier_id' => $this->supplier->id, 'name' => 'Panel A']);
        CltLayer::create(['layup_id' => $layup->id, 'layer_order' => 1, 'thickness' => 10, 'width' => 100, 'angle' => 0]);

        // 2. Import data: thickness 50 (seharusnya diabaikan)
        $importData = [
            'layups' => [['name' => 'Panel A', 'layers' => [['layer_order' => 1, 'thickness' => 50, 'width' => 100, 'angle' => 0]]]]
        ];

        $this->actingAs($this->user)->postJson("/suppliers/{$this->supplier->id}/import", [
            'strategy' => 'skip',
            'file' => $this->createFakeJsonFile($importData)
        ]);

        // 3. Assert: Data tetap 10
        $this->assertDatabaseHas('clt_layers', ['layup_id' => $layup->id, 'thickness' => 10]);
        $this->assertDatabaseMissing('clt_layers', ['thickness' => 50]);
    }

    #[Test]
    public function it_duplicates_layup_when_duplicate_strategy_is_used()
    {
        // 1. Data awal: Panel A
        CltLayup::create(['supplier_id' => $this->supplier->id, 'name' => 'Panel A']);

        // 2. Import data: Panel A (seharusnya jadi "Panel A (imported)")
        $importData = [
            'layups' => [['name' => 'Panel A', 'layers' => []]]
        ];

        $this->actingAs($this->user)->postJson("/suppliers/{$this->supplier->id}/import", [
            'strategy' => 'duplicate',
            'file' => $this->createFakeJsonFile($importData)
        ]);

        // 3. Assert: Sekarang ada 2 Layup
        $this->assertDatabaseHas('clt_layups', ['name' => 'Panel A']);
        $this->assertDatabaseHas('clt_layups', ['name' => 'Panel A (imported)']);
    }

    #[Test]
    public function it_rejects_entire_import_if_any_conflict_exists()
    {
        // 1. Data awal: Panel A
        $layup = CltLayup::create(['supplier_id' => $this->supplier->id, 'name' => 'Panel A']);
        CltLayer::create(['layup_id' => $layup->id, 'layer_order' => 1, 'thickness' => 10, 'width' => 100, 'angle' => 0]);

        // 2. Import data: Ada 1 yang baru (Panel B) dan 1 yang konflik (Panel A)
        $importData = [
            'layups' => [
                ['name' => 'Panel B', 'layers' => []], // Ini baru
                ['name' => 'Panel A', 'layers' => [['layer_order' => 1, 'thickness' => 99, 'width' => 100, 'angle' => 0]]] // Ini konflik
            ]
        ];

        $response = $this->actingAs($this->user)->postJson("/suppliers/{$this->supplier->id}/import", [
            'strategy' => 'reject',
            'file' => $this->createFakeJsonFile($importData)
        ]);

        // 3. Assert: Semua gagal, Panel B tidak boleh terbuat
        $response->assertStatus(200); // Response sukses mengirim laporannya
        $this->assertDatabaseMissing('clt_layups', ['name' => 'Panel B']);
        $this->assertEquals('rejected', $response->json('report.status'));
    }


    #[Test]
    public function it_does_not_save_data_on_dry_run()
    {
        $importData = [
            'layups' => [[
                'name' => 'New Panel', 
                'layers' => []
            ]]
        ];

        $this->actingAs($this->user)
            ->postJson("/suppliers/{$this->supplier->id}/import", [
                'strategy' => 'overwrite',
                'dry_run' => true,
                'file' => $this->createFakeJsonFile($importData)
            ]);

        // pastikan tabel tetap kosong karena dry_run
        $this->assertDatabaseCount('clt_layups', 0);
    }


    #[Test]
    public function it_provides_detailed_conflict_mapping_for_ui()
    {
        $layup = CltLayup::create(['supplier_id' => $this->supplier->id, 'name' => 'CLT-5-150-L']);
        CltLayer::create([
            'layup_id' => $layup->id,
            'layer_order' => 2,
            'thickness' => 30, // existing
            'width' => 150,
            'angle' => 90
        ]);

        $importData = [
            'layups' => [[
                'name' => 'CLT-5-150-L',
                'layers' => [
                    ['layer_order' => 2, 'thickness' => 35, 'width' => 150, 'angle' => 90] // imprting 35
                ]
            ]]
        ];

        $response = $this->actingAs($this->user)->postJson("/suppliers/{$this->supplier->id}/import", [
            'strategy' => 'skip',
            'dry_run' => true,
            'file' => $this->createFakeJsonFile($importData)
        ]);

        // cek apakah detail konflik mengandung info thickness yg berbeda
        $this->assertEquals('thickness', $response->json('report.conflicts_detail.0.layers.0.diff_fields.0'));
    }


    // HELPER =======================================================================================================
    private function createFakeJsonFile(array $data)
    {
        $path = tempnam(sys_get_temp_dir(), 'test') . '.json';
        file_put_contents($path, json_encode($data));
        return new \Illuminate\Http\UploadedFile($path, 'data.json', 'application/json', null, true);
    }

}
