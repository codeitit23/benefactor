<?php

namespace Tests\Feature;

use App\Filament\Resources\DonationResource\Pages\CreateDonation;
use App\Filament\Resources\DonationResource\Pages\EditDonation;
use App\Filament\Resources\DonationResource\Pages\ListDonations;
use App\Models\Donation;
use App\Models\ItemStatus;
use App\Models\ItemSubcategory;
use App\Models\ItemType;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Livewire\Livewire;
use Tests\TestCase;

class DonationContributorsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Mail::fake();
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs(User::factory()->create(['role' => 'admin', 'active' => true]));
    }

    private function donationData(User $donor): array
    {
        $type = ItemType::create(['name' => 'Furniture', 'is_active' => true]);
        $subcategory = ItemSubcategory::create([
            'name' => 'Chair', 'item_type_id' => $type->id, 'is_active' => true,
        ]);
        $status = ItemStatus::create(['name' => 'Good', 'is_active' => true]);

        return [
            'user_id' => $donor->id,
            'donation_type' => 'item',
            'item_type_id' => $type->id,
            'item_subcategory_id' => $subcategory->id,
            'item_status_id' => $status->id,
            'current_status' => 'pending',
        ];
    }

    public function test_admin_can_create_update_and_remove_contributors(): void
    {
        $donor = User::factory()->create();
        $payer = User::factory()->create();

        Livewire::test(CreateDonation::class)
            ->fillForm([
                ...$this->donationData($donor),
                'contributors' => [
                    ['user_id' => $payer->id, 'amount' => '50.25', 'notes' => 'Item cost'],
                    ['user_id' => $payer->id, 'amount' => '10.00', 'notes' => 'Delivery'],
                ],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $donation = Donation::firstOrFail();
        $this->assertSame($donor->id, $donation->user_id);
        $this->assertNull($donation->amount);
        $this->assertCount(2, $donation->contributors);
        $contribution = $donation->contributors->first();
        $this->assertSame('50.25', $contribution->amount);
        $this->assertSame($payer->name, $contribution->user->name);
        $this->assertSame($donation->id, $payer->donationContributions()->first()->donation_id);

        Livewire::test(EditDonation::class, ['record' => $donation->id])
            ->set('data.contributors', [
                'record-'.$contribution->id => [
                    'user_id' => $payer->id, 'amount' => '75.00', 'notes' => 'Updated cost',
                ],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertCount(1, $donation->fresh()->contributors);
        $this->assertSame('75.00', $contribution->fresh()->amount);
        $this->assertSame($donor->id, $donation->fresh()->user_id);

        Livewire::test(EditDonation::class, ['record' => $donation->id])
            ->set('data.contributors', [])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertDatabaseCount('donation_contributors', 0);
    }

    public function test_contributions_require_a_user_and_a_positive_amount(): void
    {
        Livewire::test(CreateDonation::class)
            ->fillForm([
                ...$this->donationData(User::factory()->create()),
                'contributors' => ['invalid' => ['user_id' => null, 'amount' => '-1']],
            ])
            ->call('create')
            ->assertHasFormErrors([
                'contributors.invalid.user_id' => 'required',
                'contributors.invalid.amount' => 'min',
            ]);

        $this->assertDatabaseCount('donation_contributors', 0);
    }

    public function test_non_admin_edit_preserves_existing_contributions(): void
    {
        $donor = User::factory()->create(['role' => 'user', 'active' => true]);
        $donation = Donation::create($this->donationData($donor));
        $contribution = $donation->contributors()->create([
            'user_id' => User::factory()->create()->id, 'amount' => '25.00',
        ]);
        $this->actingAs($donor);

        Livewire::test(EditDonation::class, ['record' => $donation->id])
            ->fillForm(['notes' => 'New item notes'])
            ->set('data.contributors', [])
            ->call('save')
            ->assertHasNoFormErrors();

        $this->assertSame('New item notes', $donation->fresh()->notes);
        $this->assertSame('25.00', $contribution->fresh()->amount);
    }

    public function test_donor_column_opens_contributors_dialog(): void
    {
        $donor = User::factory()->create();
        $payer = User::factory()->create(['name' => 'Additional Payer']);
        $donation = Donation::create($this->donationData($donor));
        $donation->contributors()->create([
            'user_id' => $payer->id, 'amount' => '50.25', 'notes' => 'Paid for delivery',
        ]);
        $donation->contributors()->create(['user_id' => $payer->id, 'amount' => '10.00']);

        Livewire::test(ListDonations::class)
            ->assertSee('عرض المساهمين الإضافيين')
            ->mountTableAction('view_contributors', $donation)
            ->assertTableActionMounted('view_contributors')
            ->assertSee('Additional Payer')
            ->assertSee('USD 50.25')
            ->assertSee('USD 10.00')
            ->assertSee('Paid for delivery');
    }

    public function test_contributors_dialog_is_unavailable_without_contributors_or_for_non_admins(): void
    {
        $donor = User::factory()->create(['role' => 'user', 'active' => true]);
        $donation = Donation::create($this->donationData($donor));

        Livewire::test(ListDonations::class)
            ->assertDontSee('عرض المساهمين الإضافيين')
            ->assertTableActionHidden('view_contributors', $donation);

        $donation->contributors()->create(['user_id' => $donor->id, 'amount' => '25.00']);
        $this->actingAs($donor);

        Livewire::test(ListDonations::class)
            ->assertTableActionHidden('view_contributors', $donation)
            ->mountTableAction('view_contributors', $donation)
            ->assertTableActionNotMounted('view_contributors')
            ->assertDontSee('USD 25.00');
    }
}
