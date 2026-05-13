<?php

namespace App\Services;

use App\Repositories\Interfaces\CltLayupRepositoryInterface;
use App\Repositories\Interfaces\CltLayerRepositoryInterface;

class CltService
{
    protected $layupRepo;
    protected $layerRepo;

    public function __construct(
        CltLayupRepositoryInterface $layupRepo,
        CltLayerRepositoryInterface $layerRepo
    ) {
        $this->layupRepo = $layupRepo;
        $this->layerRepo = $layerRepo;
    }

    // TODO
}