<div class="flex items-center gap-x-2" wire:key="donation-current-status-filter">
    <label for="donation-current-status" class="sr-only">الحالة الحالية</label>
    <select
        id="donation-current-status"
        wire:model.live="currentStatus"
        class="fi-select-input block w-full rounded-lg border-gray-300 text-sm shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
    >
        <option value="">كل الحالات</option>
        <option value="pending">قيد الانتظار</option>
        <option value="approved">معتمد</option>
        <option value="rejected">مرفوض</option>
        <option value="completed">مكتمل</option>
    </select>
</div>
