<?php

// namespace App\Filament\Pages;

// use App\Models\Donation;
// use Filament\Pages\Page;

// class Homepage extends Page
// {
//     protected static string $view = 'filament.pages.homepage';

//     // ✅ Add these navigation properties
//     protected static ?string $navigationIcon = 'heroicon-o-home';
//     protected static ?string $navigationLabel = 'Homepage';
//     protected static ?string $title = 'Homepage';
//     protected static ?string $slug = 'home';
//     protected static ?int $navigationSort = 1;

//     // Make sure this returns true for logged in users
//     public static function canAccess(): bool
//     {
//         return auth()->check();
//     }

//     protected function getViewData(): array
//     {
//         return [
//             'donations' => Donation::query()->latest()->limit(5)->get(),
//         ];
//     }
// }
