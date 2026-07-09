<?php

namespace App\Libraries;

/**
 * Compatibility bridge untuk Patch 7 lama.
 * Sistem utama memakai App\Services\MooraService untuk engine hitung
 * dan App\Services\MooraResultQueryService untuk query hasil.
 */
class MooraService extends \App\Services\MooraResultQueryService
{
}
