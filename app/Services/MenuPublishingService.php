<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\Repas;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;

class MenuPublishingService
{
    /**
     * Publish the menu and automatically generate Repas instances for each date.
     */
    public function publish(Menu $menu): Menu
    {
        return DB::transaction(function () use ($menu) {
            $menu->update(['statut' => 'publie']);

            $period = CarbonPeriod::create($menu->date_debut, $menu->date_fin);

            foreach ($period as $date) {
                Repas::firstOrCreate(
                    [
                        'menu_id' => $menu->id,
                        'date' => $date->format('Y-m-d'),
                    ],
                    [
                        'statut' => 'planifie',
                    ]
                );
            }

            return $menu->fresh(['repas', 'plats']);
        });
    }
}
