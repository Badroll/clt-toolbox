<?php

namespace App\Repositories\Interfaces;

interface CltLayerRepositoryInterface
{
    public function findByLayup($layupId);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
}