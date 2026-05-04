<?php

namespace App\Console\Commands;

use App\Services\PuiResyncService;
use Illuminate\Console\Command;

class PuiResyncCommand extends Command
{
    protected $signature = 'pui:resync';
    protected $description = 'Resincroniza los reportes activos de PUI y reprograma búsqueda continua';

    public function handle(PuiResyncService $resyncService): int
    {
        $total = $resyncService->resincronizarActivos();

        $this->info("Reportes resincronizados: {$total}");

        return self::SUCCESS;
    }
}