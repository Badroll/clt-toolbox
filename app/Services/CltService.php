<?php

namespace App\Services;

use App\Models\Supplier;
use App\Models\CltLayup;
use App\Models\CltLayer;
use Illuminate\Support\Facades\DB;

class CltService
{
    public function processImport(Supplier $supplier, array $importedData, array $options) {
        $strategy = $options['strategy'] ?? 'skip';
        $isDryRun = $options['dry_run'] ?? false;

        $report = [
            'status' => 'success',
            'summary' => ['created' => 0, 'updated' => 0, 'skipped' => 0, 'conflicts' => 0],
            'details' => []
        ];

        DB::beginTransaction();

        try {
            foreach ($importedData['layups'] as $layupData) {
                // cek layup first
                $layup = CltLayup::where('supplier_id', $supplier->id)
                                 ->where('name', $layupData['name'])
                                 ->first();

                // duplicate layup
                if ($layup && $strategy === 'duplicate') {
                    $layup = null; // Paksa buat baru
                    $layupData['name'] .= ' (imported)';
                }

                if (!$layup) {
                    $layup = CltLayup::create([
                        'supplier_id' => $supplier->id,
                        'name' => $layupData['name']
                    ]);
                    $report['summary']['created']++;
                }

                // proses layer
                foreach ($layupData['layers'] as $layerData) {
                    $existingLayer = CltLayer::where('layup_id', $layup->id)
                                             ->where('layer_order', $layerData['layer_order'])
                                             ->first();

                    if ($existingLayer && $this->hasDifferences($existingLayer, $layerData)) {
                        $report['summary']['conflicts']++;
                        
                        if ($strategy === 'reject') {
                            throw new \Exception("Conflict detected on Layup: {$layup->name}, Layer: {$layerData['layer_order']}");
                        }
                        if ($strategy === 'overwrite') {
                            $existingLayer->update($layerData);
                            $report['summary']['updated']++;
                        } else {
                            $report['summary']['skipped']++;
                        }
                    } elseif (!$existingLayer) {
                        CltLayer::create(array_merge($layerData, ['layup_id' => $layup->id]));
                        $report['summary']['created']++;
                    }
                }
            }

            if ($isDryRun) {
                DB::rollBack();
                $report['status'] = 'dry_run_completed';
            } else {
                DB::commit();
            }

        } catch (\Exception $e) {
            DB::rollBack();
            $report['status'] = 'rejected';
            $report['error_message'] = $e->getMessage();
        }

        return $report;
    }

    private function hasDifferences($existing, $incoming) {
        return (float)$existing->thickness !== (float)$incoming['thickness'] ||
               (float)$existing->width !== (float)$incoming['width'] ||
               (float)$existing->angle !== (float)$incoming['angle'];
    }
}