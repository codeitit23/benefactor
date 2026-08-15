<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Donation Submitted</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5; color: #1f2937;">
    <h2 style="margin-bottom: 12px;">A new donation has been submitted</h2>

    <p style="margin: 0 0 10px;">Donation details:</p>

    <ul style="margin: 0 0 14px; padding-left: 18px;">
        <li><strong>Donation #:</strong> {{ $donation->donation_number }}</li>
        <li><strong>Type:</strong> {{ $donation->donation_type === 'cash' ? 'Cash' : 'Item' }}</li>
        <li><strong>Donor Name:</strong> {{ $donation->donor_name ?? $donation->user?->name ?? 'Guest' }}</li>
        <li><strong>Donor Phone:</strong> {{ $donation->donor_phone ?? $donation->user?->phone ?? 'N/A' }}</li>
        <li><strong>Status:</strong> {{ $donation->current_status }}</li>
        @if($donation->donation_type === 'cash' && $donation->amount)
            <li><strong>Amount:</strong> {{ $donation->amount }}</li>
        @endif
    </ul>

    <p style="margin: 0;">Please log in to the admin panel to review and process it.</p>
</body>
</html>
