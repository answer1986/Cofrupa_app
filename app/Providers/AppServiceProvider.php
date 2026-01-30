<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\Purchase;
use App\Models\ProcessOrder;
use App\Models\PlantProductionOrder;
use App\Models\Shipment;
use App\Models\Contract;
use App\Models\Supplier;
use App\Models\Client;
use App\Models\Maintenance;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Compartir contador de procesos pendientes con todas las vistas
        View::composer('*', function ($view) {
            // 1. Compras pendientes: aquellas que fueron creadas pero no tienen precio unitario o total_amount completado
            $pendingPurchasesCount = Purchase::where(function($query) {
                $query->where(function($q) {
                    $q->whereNull('unit_price')
                      ->orWhereNull('total_amount');
                })
                ->where(function($q) {
                    $q->where('payment_status', '!=', 'paid')
                      ->orWhereNull('payment_status');
                });
            })->count();
            
            // 2. Órdenes de proceso pendientes o en progreso
            $pendingProcessOrdersCount = ProcessOrder::whereIn('status', ['pending', 'in_progress'])->count();
            
            // 3. Órdenes de producción de plantas pendientes o en progreso
            $pendingPlantOrdersCount = PlantProductionOrder::whereIn('status', ['pending', 'in_progress'])->count();
            
            // 4. Envíos no completados (scheduled, in_transit, at_customs, loaded)
            $pendingShipmentsCount = Shipment::whereIn('status', ['scheduled', 'in_transit', 'at_customs', 'loaded'])->count();
            
            // 5. Contratos en borrador
            $draftContractsCount = Contract::where('status', 'draft')->count();
            
            // 6. Proveedores incompletos (creados desde recepción)
            $incompleteSuppliersCount = Supplier::where('is_incomplete', true)
                ->where(function($q) {
                    $q->whereNull('location')
                      ->orWhere('location', '');
                })
                ->count();

            // 7. Clientes incompletos (creados desde procesamiento - cliente externo)
            $incompleteClientsCount = Client::where('is_incomplete', true)->count();

            // 8. Mantenciones próximas o vencidas (próximos 14 días o ya vencidas)
            $upcomingMaintenancesCount = Maintenance::whereNotNull('next_maintenance_date')
                ->where('next_maintenance_date', '<=', now()->addDays(14))
                ->count();
            
            // Total de procesos pendientes (incluye mantenciones próximas)
            $totalPendingCount = $pendingPurchasesCount + $pendingProcessOrdersCount + $pendingPlantOrdersCount + $pendingShipmentsCount + $draftContractsCount + $incompleteSuppliersCount + $incompleteClientsCount + $upcomingMaintenancesCount;
            
            $view->with('pendingPurchasesCount', $pendingPurchasesCount);
            $view->with('pendingProcessOrdersCount', $pendingProcessOrdersCount);
            $view->with('pendingPlantOrdersCount', $pendingPlantOrdersCount);
            $view->with('pendingShipmentsCount', $pendingShipmentsCount);
            $view->with('draftContractsCount', $draftContractsCount);
            $view->with('incompleteSuppliersCount', $incompleteSuppliersCount);
            $view->with('incompleteClientsCount', $incompleteClientsCount);
            $view->with('upcomingMaintenancesCount', $upcomingMaintenancesCount);
            $view->with('totalPendingCount', $totalPendingCount);

            // Valor del dólar (CLP) - cache 1 hora solo cuando hay valor; API mindicador.cl
            $usdRateClp = Cache::get('usd_rate_clp');
            if ($usdRateClp === null) {
                try {
                    $response = Http::timeout(5)->get('https://mindicador.cl/api/dolar');
                    if ($response->successful()) {
                        $data = $response->json();
                        if (!empty($data['serie'][0]['valor'])) {
                            $usdRateClp = (float) $data['serie'][0]['valor'];
                            Cache::put('usd_rate_clp', $usdRateClp, 3600);
                        } elseif (!empty($data['valor'])) {
                            $usdRateClp = (float) $data['valor'];
                            Cache::put('usd_rate_clp', $usdRateClp, 3600);
                        }
                    }
                } catch (\Throwable $e) {
                    $usdRateClp = null;
                }
            }
            $view->with('usdRateClp', $usdRateClp);
        });
    }
}
