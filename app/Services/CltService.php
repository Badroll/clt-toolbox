<?php

namespace App\Services;

use App\Models\CltLayup;
use App\Models\CltLayer;
use Illuminate\Support\Facades\DB;

class CltService
{
    public function processImport($supplier, array $importedData, array $options)
    {
        $strategy = $options['strategy'] ?? 'skip';
        $isDryRun = $options['dry_run'] ?? false;

        $report = [
            'status' => 'success',
            'summary' => ['created' => 0, 'updated' => 0, 'skipped' => 0, 'conflicts' => 0],
            'conflicts_detail' => [] // Ini untuk mendukung image_484dfe.png
        ];

        DB::beginTransaction();

        try {
            foreach ($importedData['layups'] as $layupData) {
                $layup = CltLayup::where('supplier_id', $supplier->id)
                                 ->where('name', $layupData['name'])
                                 ->first();

                // Strategi Duplicate: Tambah suffix jika nama tabrakan
                if ($layup && $strategy === 'duplicate') {
                    $layupData['name'] .= ' (imported)';
                    $layup = null; 
                }

                if (!$layup) {
                    $layup = CltLayup::create([
                        'supplier_id' => $supplier->id,
                        'name' => $layupData['name']
                    ]);
                    $report['summary']['created']++;
                }

                $layupConflictInfo = [
                    'layup_name' => $layup->name,
                    'layers' => []
                ];

                foreach ($layupData['layers'] as $layerData) {
                    $existingLayer = CltLayer::where('layup_id', $layup->id)
                                             ->where('layer_order', $layerData['layer_order'])
                                             ->first();

                    if ($existingLayer) {
                        $diffs = $this->getDifferences($existingLayer, $layerData);
                        
                        if (!empty($diffs)) {
                            $report['summary']['conflicts']++;
                            
                            // Simpan detail untuk UI image_484dfe.png
                            $layupConflictInfo['layers'][] = [
                                'order' => $layerData['layer_order'],
                                'existing' => $existingLayer->only(['thickness', 'width', 'angle']),
                                'importing' => $layerData,
                                'diff_fields' => $diffs
                            ];

                            if ($strategy === 'reject') {
                                throw new \Exception("Conflict in {$layup->name} at layer {$layerData['layer_order']}");
                            }

                            if ($strategy === 'overwrite') {
                                $existingLayer->update($layerData);
                                $report['summary']['updated']++;
                            } else {
                                $report['summary']['skipped']++;
                            }
                        }
                    } else {
                        CltLayer::create(array_merge($layerData, ['layup_id' => $layup->id]));
                        $report['summary']['created']++;
                    }
                }

                if (!empty($layupConflictInfo['layers'])) {
                    $report['conflicts_detail'][] = $layupConflictInfo;
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

    private function getDifferences($existing, $incoming)
    {
        $fields = ['thickness', 'width', 'angle'];
        $diffFields = [];

        foreach ($fields as $field) {
            if ((float)$existing->$field !== (float)$incoming[$field]) {
                $diffFields[] = $field;
            }
        }

        return $diffFields;
    }
}