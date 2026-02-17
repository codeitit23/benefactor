<?php

namespace App\Filament\Widgets;

use App\Models\Beneficiary;
use App\Models\Donation;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DonationStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        $user = auth()->user();
        // Count of Donations per type (scope to user if not admin)
        $itemDonations = Donation::when(!$user?->isAdmin(), fn($q) => $q->where('user_id', $user->id))
            ->where('donation_type', 'item')
            ->count();
        $cashDonations = Donation::when(!$user?->isAdmin(), fn($q) => $q->where('user_id', $user->id))
            ->where('donation_type', 'cash')
            ->count();

        // Count of Donations per status (scope to user if not admin)
        $pending = Donation::when(!$user?->isAdmin(), fn($q) => $q->where('user_id', $user->id))
            ->where('current_status', 'pending')
            ->count();
        $approved = Donation::when(!$user?->isAdmin(), fn($q) => $q->where('user_id', $user->id))
            ->where('current_status', 'approved')
            ->count();
        $rejected = Donation::when(!$user?->isAdmin(), fn($q) => $q->where('user_id', $user->id))
            ->where('current_status', 'rejected')
            ->count();
        $completed = Donation::when(!$user?->isAdmin(), fn($q) => $q->where('user_id', $user->id))
            ->where('current_status', 'completed')
            ->count();

        // Count of Donors (users who have made donations) — for non-admins show their donations count instead
        if ($user?->isAdmin()) {
            $donors = User::whereHas('donations')->count();
            $donorsLabel = 'اجمالي المتبرعين';
            $donorsDescription = 'المستخدمون الذين قدموا تبرعات';
        } else {
            $donors = Donation::where('user_id', $user->id)->count();
            $donorsLabel = 'تبرعاتك';
            $donorsDescription = 'التبرعات التي قدمتها';
        }

        // Count of beneficiaries - assuming beneficiaries are users with role 'beneficiary' or something, but from the model, perhaps count users who are not donors or something.
        // The user said "Count of beneficiaries", but in the context, perhaps users who received donations, but donations don't have beneficiary user, only images.
        // Perhaps count of donations with beneficiary_images or something.
        // But to match, perhaps count of users with role 'beneficiary' if exists, but from User model, roles are 'admin', 'user'.
        // Perhaps it's a mistake, or count of donations that have beneficiary_images.
//        $beneficiaries = Donation::whereNotNull('beneficiary_images')->count(); // assuming each donation with beneficiary_images counts as a beneficiary.
        // Count of beneficiaries (scope to user if not admin)
        if ($user?->isAdmin()) {
            $beneficiaries = Beneficiary::count();
        } else {
            $beneficiaries = Donation::where('user_id', $user->id)->pluck('beneficiary_id')
                ->filter()
                ->unique()
                ->count();
        }
        // Count of Donations per address - but donations don't have address, users have address.
        // Perhaps group by user address.
        // But for stats, maybe count unique addresses or something.
        // The user said "Count of Donations per address", perhaps it's a typo or mean per status or type.
        // But to implement, perhaps count donations grouped by user address.
        // But for stats, maybe show total donations, but per address doesn't make sense for overview.
        // Perhaps it's "Count of Donations per status" already there.
        // Maybe "Count of Donations per item type" or something.
        // But let's assume it's per status, and perhaps add per type.

        // For address, perhaps count of unique addresses.
        // Unique addresses for donors (scope to user if not admin)
        if ($user?->isAdmin()) {
            $uniqueAddresses = User::whereHas('donations')->distinct('address')->count('address');
        } else {
            $uniqueAddresses = 1; // current user's address (since we're scoping to their donations)
        }

        $stats = [

            Stat::make('تبرعات عينية', $itemDonations)
                ->description('اجمالي التبرعات العينية')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('تبرعات نقدية', $cashDonations)
                ->description('اجمالي التبرعات النقدية')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),

            Stat::make('تبرعات قيد الانتظار', $pending)
                ->description('تبرعات بانتظار الاعتماد')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('تبرعات معتمدة', $approved)
                ->description('تبرعات تم اعتمادها')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('تبرعات مرفوضة', $rejected)
                ->description('تبرعات تم رفضها')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('تبرعات مكتملة', $completed)
                ->description('تبرعات مكتملة')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('gray'),

            Stat::make($donorsLabel ?? 'اجمالي المتبرعين', $donors)
                ->description($donorsDescription ?? 'المستخدمون الذين قدموا تبرعات')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('المستفيدون', $beneficiaries)
                ->description('اجمالي المستفيدين')
                ->descriptionIcon('heroicon-m-heart')
                ->color('rose'),

            Stat::make('العناوين الفريدة', $uniqueAddresses)
                ->description('عناوين المتبرعين الفريدة')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('secondary'),
        ];

        return $stats;
    }
}
