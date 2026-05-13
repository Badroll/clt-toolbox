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


    private function createFakeJsonFile(array $data)
    {
        $path = tempnam(sys_get_temp_dir(), 'test') . '.json';
        file_put_contents($path, json_encode($data));
        return new \Illuminate\Http\UploadedFile($path, 'data.json', 'application/json', null, true);
    }

}
