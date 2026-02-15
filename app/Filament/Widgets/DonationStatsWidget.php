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
        // Count of Donations per type
        $itemDonations = Donation::where('donation_type', 'item')->count();
        $cashDonations = Donation::where('donation_type', 'cash')->count();

        // Count of Donations per status
        $pending = Donation::where('current_status', 'pending')->count();
        $approved = Donation::where('current_status', 'approved')->count();
        $rejected = Donation::where('current_status', 'rejected')->count();
        $completed = Donation::where('current_status', 'completed')->count();

        // Count of Donors (users who have made donations)
        $donors = User::whereHas('donations')->count();

        // Count of beneficiaries - assuming beneficiaries are users with role 'beneficiary' or something, but from the model, perhaps count users who are not donors or something.
        // The user said "Count of beneficiaries", but in the context, perhaps users who received donations, but donations don't have beneficiary user, only images.
        // Perhaps count of donations with beneficiary_images or something.
        // But to match, perhaps count of users with role 'beneficiary' if exists, but from User model, roles are 'admin', 'user'.
        // Perhaps it's a mistake, or count of donations that have beneficiary_images.
//        $beneficiaries = Donation::whereNotNull('beneficiary_images')->count(); // assuming each donation with beneficiary_images counts as a beneficiary.
        $beneficiaries = Beneficiary::count();
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
        $uniqueAddresses = User::whereHas('donations')->distinct('address')->count('address');

        $stats = [

            Stat::make('Item Donations', $itemDonations)
                ->description('Total item donations')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),

            Stat::make('Cash Donations', $cashDonations)
                ->description('Total cash donations')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('info'),

            Stat::make('Pending Donations', $pending)
                ->description('Donations awaiting approval')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Approved Donations', $approved)
                ->description('Approved donations')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Rejected Donations', $rejected)
                ->description('Rejected donations')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),

            Stat::make('Completed Donations', $completed)
                ->description('Completed donations')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('gray'),

            Stat::make('Total Donors', $donors)
                ->description('Users who have made donations')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Beneficiaries', $beneficiaries)
                ->description('Total beneficiaries')
                ->descriptionIcon('heroicon-m-heart')
                ->color('rose'),

            Stat::make('Unique Addresses', $uniqueAddresses)
                ->description('Unique donor addresses')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('secondary'),
        ];

        return $stats;
    }
}
