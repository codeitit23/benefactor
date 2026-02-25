<!doctype html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>إضافة تبرع</title>
        <style>
            :root {
                color-scheme: dark;
                --bg: #0b0f14;
                --surface: #111827;
                --surface-2: #0f172a;
                --border: #1f2a44;
                --text: #e5e7eb;
                --muted: #94a3b8;
                --primary: #f59e0b;
                --primary-2: #d97706;
                --success-bg: #0f2c1e;
                --success-border: #14532d;
                --success-text: #bbf7d0;
            }
            body {
                font-family: "Noto Kufi Arabic", "Segoe UI", Tahoma, Arial, sans-serif;
                background:
                    radial-gradient(1200px 600px at 80% -10%, rgba(245, 158, 11, 0.08), transparent 60%),
                    radial-gradient(1000px 700px at -10% 10%, rgba(56, 189, 248, 0.08), transparent 55%),
                    var(--bg);
                color: var(--text);
                margin: 0;
                padding: 32px 16px;
            }
            .container {
                max-width: 940px;
                margin: 0 auto;
                background: linear-gradient(180deg, var(--surface), var(--surface-2));
                border-radius: 18px;
                padding: 28px;
                border: 1px solid var(--border);
                box-shadow: 0 18px 40px rgba(0,0,0,0.45);
            }
            h1 {
                margin: 0 0 18px;
                font-size: 28px;
                letter-spacing: 0.2px;
            }
            fieldset {
                border: 1px solid var(--border);
                border-radius: 14px;
                padding: 18px;
                margin: 18px 0;
                background: rgba(15, 23, 42, 0.4);
            }
            legend {
                padding: 0 10px;
                font-weight: 600;
                color: var(--text);
            }
            .grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
                gap: 14px;
            }
            .uploader {
                border: 1px dashed #2b3a55;
                background: rgba(11, 18, 32, 0.6);
                border-radius: 12px;
                padding: 14px;
                text-align: center;
                transition: border-color .2s, background .2s;
            }
            .uploader.dragover {
                border-color: var(--primary);
                background: rgba(245, 158, 11, 0.08);
            }
            .uploader input[type="file"] {
                display: none;
            }
            .uploader .title {
                font-size: 14px;
                font-weight: 600;
                margin-bottom: 6px;
            }
            .uploader .hint {
                color: var(--muted);
                font-size: 12px;
                margin-bottom: 10px;
            }
            .uploader .btn {
                display: inline-block;
                padding: 8px 14px;
                border-radius: 10px;
                background: #111827;
                border: 1px solid var(--border);
                color: var(--text);
                font-size: 13px;
                cursor: pointer;
            }
            .preview-grid {
                margin-top: 12px;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
                gap: 8px;
            }
            .preview {
                border: 1px solid var(--border);
                border-radius: 10px;
                overflow: hidden;
                background: #0b1220;
                position: relative;
            }
            .preview img,
            .preview video {
                width: 100%;
                height: 80px;
                object-fit: cover;
                display: block;
            }
            .preview button {
                position: absolute;
                top: 6px;
                left: 6px;
                border: 0;
                background: rgba(15, 23, 42, 0.8);
                color: var(--text);
                width: 22px;
                height: 22px;
                border-radius: 999px;
                cursor: pointer;
                font-size: 14px;
                line-height: 1;
            }
            label {
                display: block;
                font-size: 13px;
                margin-bottom: 6px;
                color: var(--muted);
            }
            input, select, textarea {
                width: 100%;
                padding: 11px 12px;
                border: 1px solid var(--border);
                border-radius: 10px;
                font-size: 14px;
                background: #0b1220;
                color: var(--text);
                outline: none;
                transition: border-color .2s, box-shadow .2s;
            }
            input:focus, select:focus, textarea:focus {
                border-color: var(--primary);
                box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.15);
            }
            textarea {
                min-height: 90px;
                resize: vertical;
            }
            .actions {
                display: flex;
                justify-content: flex-start;
                margin-top: 16px;
            }
            button {
                background: linear-gradient(135deg, var(--primary), var(--primary-2));
                color: #111827;
                border: 0;
                padding: 12px 22px;
                border-radius: 12px;
                font-size: 15px;
                font-weight: 600;
                cursor: pointer;
            }
            .alert {
                background: var(--success-bg);
                border: 1px solid var(--success-border);
                color: var(--success-text);
                padding: 10px 12px;
                border-radius: 10px;
                margin-bottom: 12px;
            }
            .error {
                color: #fca5a5;
                font-size: 12px;
                margin-top: 6px;
            }
            .hidden {
                display: none;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1>إضافة تبرع</h1>

            @if (session('success'))
                <div class="alert">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('donations.store') }}" enctype="multipart/form-data">
                @csrf

                <fieldset>
                    <legend>بيانات المتبرع</legend>
                    <div class="grid">
                        <div>
                            <label for="donor_name">اسم المتبرع</label>
                            <input id="donor_name" name="donor_name" value="{{ old('donor_name') }}" required>
                            @error('donor_name') <div class="error">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label for="donor_phone">هاتف المتبرع</label>
                            <input id="donor_phone" name="donor_phone" value="{{ old('donor_phone') }}" required>
                            @error('donor_phone') <div class="error">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label for="donor_address">عنوان المتبرع</label>
                            <input id="donor_address" name="donor_address" value="{{ old('donor_address') }}" required>
                            @error('donor_address') <div class="error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>بيانات التبرع</legend>
                    <div class="grid">
                        <div>
                            <label for="donation_type">نوع التبرع</label>
                            <select id="donation_type" name="donation_type" required>
                                <option value="item" @selected(old('donation_type', 'item') === 'item')>تبرع عيني</option>
                                <option value="cash" @selected(old('donation_type') === 'cash')>تبرع نقدي</option>
                            </select>
                            @error('donation_type') <div class="error">{{ $message }}</div> @enderror
                        </div>
                        <div class="item-only">
                            <label for="item_type_id">نوع العنصر</label>
                            <select id="item_type_id" name="item_type_id">
                                <option value="">اختر</option>
                                @foreach ($itemTypes as $type)
                                    <option value="{{ $type->id }}" @selected(old('item_type_id') == $type->id)>{{ $type->name }}</option>
                                @endforeach
                            </select>
                            @error('item_type_id') <div class="error">{{ $message }}</div> @enderror
                        </div>
                        <div class="item-only">
                            <label for="item_subcategory_id">الفئة الفرعية</label>
                            <select id="item_subcategory_id" name="item_subcategory_id">
                                <option value="">اختر</option>
                                @foreach ($itemSubcategories as $sub)
                                    <option value="{{ $sub->id }}" data-type-id="{{ $sub->item_type_id }}" @selected(old('item_subcategory_id') == $sub->id)>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('item_subcategory_id') <div class="error">{{ $message }}</div> @enderror
                        </div>
                        <div class="item-only">
                            <label for="item_status_id">حالة العنصر</label>
                            <select id="item_status_id" name="item_status_id">
                                <option value="">اختر</option>
                                @foreach ($itemStatuses as $status)
                                    <option value="{{ $status->id }}" @selected(old('item_status_id') == $status->id)>{{ $status->name }}</option>
                                @endforeach
                            </select>
                            @error('item_status_id') <div class="error">{{ $message }}</div> @enderror
                        </div>
                        <div class="cash-only hidden">
                            <label for="payment_method">طريقة الدفع</label>
                            <select id="payment_method" name="payment_method">
                                <option value="">اختر</option>
                                <option value="cash" @selected(old('payment_method') === 'cash')>نقداً</option>
                                <option value="wish" @selected(old('payment_method') === 'wish')>ويش</option>
                                <option value="omt" @selected(old('payment_method') === 'omt')>OMT</option>
                                <option value="credit_card" @selected(old('payment_method') === 'credit_card')>بطاقة ائتمان</option>
                            </select>
                            @error('payment_method') <div class="error">{{ $message }}</div> @enderror
                        </div>
                        <div class="cash-only hidden">
                            <label for="amount">المبلغ (USD)</label>
                            <input id="amount" name="amount" type="number" step="0.01" min="0" value="{{ old('amount') }}">
                            @error('amount') <div class="error">{{ $message }}</div> @enderror
                        </div>
                        <div class="item-only">
                            <label>صور العنصر (حد أقصى 5)</label>
                            <div class="uploader" data-input="item_images" data-multiple="true">
                                <div class="title">اسحب الصور هنا أو اختر من الجهاز</div>
                                <div class="hint">PNG, JPG حتى 5 صور</div>
                                <label class="btn" for="item_images">اختيار الصور</label>
                                <input id="item_images" name="item_images[]" type="file" accept="image/*" multiple>
                                <div class="preview-grid" id="item_images_preview"></div>
                            </div>
                            @error('item_images') <div class="error">{{ $message }}</div> @enderror
                            @error('item_images.*') <div class="error">{{ $message }}</div> @enderror
                        </div>
                        <div class="item-only">
                            <label>فيديو العنصر</label>
                            <div class="uploader" data-input="item_video" data-multiple="false">
                                <div class="title">اسحب الفيديو هنا أو اختر من الجهاز</div>
                                <div class="hint">MP4, AVI, MOV, WMV حتى 50MB</div>
                                <label class="btn" for="item_video">اختيار الفيديو</label>
                                <input id="item_video" name="item_video" type="file" accept="video/*">
                                <div class="preview-grid" id="item_video_preview"></div>
                            </div>
                            @error('item_video') <div class="error">{{ $message }}</div> @enderror
                        </div>
                        <div>
                            <label for="notes">ملاحظات</label>
                            <textarea id="notes" name="notes">{{ old('notes') }}</textarea>
                            @error('notes') <div class="error">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </fieldset>

                <div class="actions">
                    <button type="submit">إرسال التبرع</button>
                </div>
            </form>
        </div>

        <script>
            const donationType = document.getElementById('donation_type');
            const itemOnly = Array.from(document.querySelectorAll('.item-only'));
            const cashOnly = Array.from(document.querySelectorAll('.cash-only'));
            const itemTypeSelect = document.getElementById('item_type_id');
            const subcategorySelect = document.getElementById('item_subcategory_id');

            function toggleDonationType() {
                const isItem = donationType.value === 'item';
                itemOnly.forEach((el) => el.classList.toggle('hidden', !isItem));
                cashOnly.forEach((el) => el.classList.toggle('hidden', isItem));
            }

            function filterSubcategories() {
                const typeId = itemTypeSelect.value;
                const options = Array.from(subcategorySelect.options);
                options.forEach((opt) => {
                    if (!opt.value) return;
                    const matches = opt.dataset.typeId === typeId;
                    opt.hidden = typeId ? !matches : false;
                });
                if (typeId) {
                    const selected = subcategorySelect.options[subcategorySelect.selectedIndex];
                    if (selected && selected.hidden) {
                        subcategorySelect.value = '';
                    }
                }
            }

            donationType.addEventListener('change', toggleDonationType);
            itemTypeSelect.addEventListener('change', filterSubcategories);
            toggleDonationType();
            filterSubcategories();

            function buildPreview(container, file, onRemove) {
                const wrapper = document.createElement('div');
                wrapper.className = 'preview';
                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.textContent = '×';
                removeBtn.addEventListener('click', () => onRemove(file));
                wrapper.appendChild(removeBtn);
                if (file.type.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    wrapper.appendChild(img);
                } else if (file.type.startsWith('video/')) {
                    const video = document.createElement('video');
                    video.src = URL.createObjectURL(file);
                    video.muted = true;
                    video.playsInline = true;
                    wrapper.appendChild(video);
                }
                container.appendChild(wrapper);
            }

            function wireUploader(uploader) {
                const inputId = uploader.dataset.input;
                const input = document.getElementById(inputId);
                const preview = document.getElementById(`${inputId}_preview`);
                const isMultiple = input.hasAttribute('multiple');
                let filesStore = [];

                const syncInputFiles = () => {
                    const dataTransfer = new DataTransfer();
                    filesStore.forEach((file) => dataTransfer.items.add(file));
                    input.files = dataTransfer.files;
                };

                const handleFiles = (files, append = false) => {
                    const incoming = Array.from(files);
                    if (!isMultiple) {
                        filesStore = incoming.slice(0, 1);
                    } else if (append) {
                        filesStore = filesStore.concat(incoming);
                    } else {
                        filesStore = incoming;
                    }
                    preview.innerHTML = '';
                    filesStore.forEach((file) => buildPreview(preview, file, (target) => {
                        filesStore = filesStore.filter((f) => f !== target);
                        handleFiles(filesStore, false);
                    }));
                    syncInputFiles();
                };

                uploader.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    uploader.classList.add('dragover');
                });
                uploader.addEventListener('dragleave', () => uploader.classList.remove('dragover'));
                uploader.addEventListener('drop', (e) => {
                    e.preventDefault();
                    uploader.classList.remove('dragover');
                    if (!e.dataTransfer || !e.dataTransfer.files) return;
                    handleFiles(e.dataTransfer.files, true);
                });

                input.addEventListener('change', () => handleFiles(input.files, isMultiple));
            }

            document.querySelectorAll('.uploader').forEach(wireUploader);
        </script>
    </body>
</html>
