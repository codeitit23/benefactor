<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تغيير حالة التبرع</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.8; color: #1f2937; direction: rtl; text-align: right;">
    <h2 style="margin-bottom: 12px;">تم تغيير حالة التبرع</h2>

    <p style="margin: 0 0 10px;">تم تحديث حالة التبرع رقم <strong>{{ $donation->donation_number }}</strong>.</p>

    <ul style="margin: 0 0 14px; padding-right: 18px;">
        <li><strong>الحالة السابقة:</strong> {{ \App\Models\Donation::statusLabel($oldStatus) }}</li>
        <li><strong>الحالة الجديدة:</strong> {{ \App\Models\Donation::statusLabel($newStatus) }}</li>
        @if($donation->status_note)
            <li><strong>ملاحظة الحالة:</strong> {{ $donation->status_note }}</li>
        @endif
    </ul>

    <p style="margin: 0;">يرجى تسجيل الدخول إلى لوحة التحكم لمراجعة تفاصيل التبرع.</p>
</body>
</html>