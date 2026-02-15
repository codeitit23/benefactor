<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\DonationStatsWidget;
use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
	protected static ?string $navigationIcon = 'heroicon-o-home';
	protected static ?string $title = 'Dashboard';

	protected static string $view = 'filament-panels::pages.dashboard';

		public function getWidgets(): array
		{
			return [
				 DonationStatsWidget::class
			];
		}

	public function getHeaderWidgets(): array
	{
		return [];
	}

	public static function shouldRegisterNavigation(): bool
	{
		$user = auth()->user();
        return true;
		return $user && $user->role === 'admin';
	}
}
