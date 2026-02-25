<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\ItemStatus;
use App\Models\ItemSubcategory;
use App\Models\ItemType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DonationPublicController extends Controller
{
    public function create()
    {
        return view('donations.create', [
            'itemTypes' => ItemType::active()->orderBy('name')->get(),
            'itemSubcategories' => ItemSubcategory::active()->orderBy('name')->get(),
            'itemStatuses' => ItemStatus::active()->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'donor_name' => ['required', 'string', 'max:255'],
            'donor_phone' => ['required', 'regex:/^\d{8}$/'],
            'donor_address' => ['required', 'string', 'max:255'],
            'donation_type' => ['required', Rule::in(['item', 'cash'])],
            'item_type_id' => ['required_if:donation_type,item', 'nullable', 'exists:item_types,id'],
            'item_subcategory_id' => ['required_if:donation_type,item', 'nullable', 'exists:item_subcategories,id'],
            'item_status_id' => ['required_if:donation_type,item', 'nullable', 'exists:item_statuses,id'],
            'payment_method' => ['required_if:donation_type,cash', 'nullable', Rule::in(['cash', 'wish', 'omt', 'credit_card'])],
            'amount' => ['required_if:donation_type,cash', 'nullable', 'numeric', 'min:0'],
            'item_images' => ['nullable', 'array', 'max:5'],
            'item_images.*' => ['nullable', 'image', 'max:5120'],
            'item_video' => ['nullable', 'file', 'mimetypes:video/mp4,video/avi,video/mov,video/wmv', 'max:51200'],
            'pickup_date' => ['nullable', 'date', 'after_or_equal:today'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $donation = new Donation();
        $donation->fill($validated);
        $donation->user_id = auth()->id();
        $donation->current_status = 'pending';

        if ($request->hasFile('item_images')) {
            $paths = [];
            foreach ($request->file('item_images') as $file) {
                $paths[] = $file->storePublicly('donations/items', 'public');
            }
            $donation->item_images = $paths;
        }

        if ($request->hasFile('item_video')) {
            $donation->item_video = $request->file('item_video')->storePublicly('donations/videos', 'public');
        }

        $donation->save();

        return redirect()
            ->route('donations.create')
            ->with('success', 'تم استلام التبرع وسنتواصل معك قريباً.');
    }
}
