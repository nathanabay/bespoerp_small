<?php

namespace Webkul\Tender;

use Filament\Panel;
use Webkul\PluginManager\Package;
use Webkul\PluginManager\PackageServiceProvider;

class TenderServiceProvider extends PackageServiceProvider
{
    public static string $name = 'tenders';

    public static string $viewNamespace = 'tenders';

    public function configureCustomPackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasViews()
            ->hasTranslations()
            ->hasMigrations([
                '2026_02_17_174000_create_tender_opportunities_table',
                '2026_02_17_174100_create_tender_rankings_table',
                '2026_02_17_174200_create_tender_competitors_table',
                '2026_02_17_182000_update_tender_opportunities_table',
                '2026_02_17_182100_create_tender_proposal_sections_table',
                '2026_02_17_182200_create_tender_team_members_table',
                '2026_02_17_183000_create_tender_tasks_table',
                '2026_02_17_184000_create_cost_estimations_table',
                '2026_02_17_184100_create_bid_decision_matrices_table',
                '2026_02_17_184200_create_performance_bonds_table',
                '2026_02_17_185000_create_tender_child_tables',
                '2026_02_17_190000_create_final_tender_tables',
            ])
            ->runsMigrations()
            ->hasInstallCommand(function ($command) {
                $command
                    ->installDependencies()
                    ->runsMigrations()
                    ->runsSeeders();
            });
    }

    public function packageRegistered(): void
    {
        Panel::configureUsing(function (Panel $panel): void {
            $panel->plugin(TenderPlugin::make());
        });
    }
}
