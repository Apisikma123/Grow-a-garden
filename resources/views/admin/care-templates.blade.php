@extends('layouts.admin')

@push('head')
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(8px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes modalScale {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.25s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .animate-modal {
        animation: modalScale 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    .stage-row.dragging {
        opacity: 0.5;
        border-style: dashed;
        border-color: var(--color-primary);
        background-color: var(--color-surface-container-low);
    }
</style>
@endpush

@section('admin-content')
<div class="flex flex-col gap-8 animate-fade-in">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-2">
        <div class="flex flex-col gap-2">
            <h1 class="text-[32px] font-black text-on-surface tracking-tight">Growth Templates</h1>
            <p class="text-[15px] text-on-surface-variant max-w-[600px] leading-relaxed">Configure automated lifecycles and milestone alerts for botanical precision.</p>
        </div>
        <div class="flex items-center gap-3 shrink-0">
            <button class="flex items-center gap-2 bg-white border border-outline-variant/30 text-on-surface font-bold text-[13px] px-5 py-2.5 rounded-full hover:bg-surface-container-lowest transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px] text-[#10b981]">filter_list</span>
                Filter View
            </button>
            <button class="flex items-center gap-2 bg-white border border-outline-variant/30 text-on-surface font-bold text-[13px] px-5 py-2.5 rounded-full hover:bg-surface-container-lowest transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px] text-[#10b981]">sort</span>
                Sort
            </button>
            <button onclick="openCreateModal()" class="flex items-center gap-2 bg-[#006c49] hover:bg-[#005236] text-white font-bold text-[13px] px-6 py-2.5 rounded-full transition-all shadow-sm active:scale-[0.98]">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Add Template
            </button>
        </div>
    </div>

    {{-- Grid of Templates --}}
    <div id="templates-grid" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-2 gap-8">
        @include('admin.partials.template-grid')
    </div>

</div>

<!-- Create/Edit Modal -->
<div id="template-modal" class="hidden fixed inset-0 bg-black/55 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[32px] w-full max-w-[680px] max-h-[90vh] flex flex-col ambient-shadow relative animate-modal">
        
        <!-- Modal Header -->
        <div class="flex justify-between items-center px-8 py-6 border-b border-outline-variant/20 shrink-0">
            <h2 id="modal-title" class="text-[24px] font-black text-on-surface tracking-tight">Create Growth Template</h2>
            <button type="button" onclick="closeModal()" class="text-on-surface-variant hover:text-on-surface transition-colors p-1.5 rounded-full hover:bg-surface-container-low flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>

        <!-- Modal Body / Form -->
        <form id="template-form" onsubmit="saveTemplate(event)" class="flex-1 overflow-y-auto p-8 flex flex-col gap-6 no-scrollbar">
            @csrf
            <input type="hidden" id="template-id" name="id">

            <!-- General Info Section -->
            <div class="flex flex-col gap-4">
                <h3 class="text-[14px] font-black tracking-widest text-[#006c49] uppercase">General Information</h3>
                
                <!-- Template Name -->
                <div class="flex flex-col gap-1.5">
                    <label class="text-[13px] font-bold text-on-surface" for="form-name">Template Name *</label>
                    <input type="text" id="form-name" required class="w-full bg-surface-container-lowest border border-outline-variant/40 rounded-xl px-4 py-2.5 text-[14px] text-on-surface placeholder:text-on-surface-variant/40 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-sm" placeholder="e.g. Heirloom Tomato Mastery">
                </div>

                <!-- Row: Category & Duration -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Plant Category -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[13px] font-bold text-on-surface" for="form-category">Plant Category *</label>
                        <select id="form-category" required class="w-full bg-surface-container-lowest border border-outline-variant/40 rounded-xl px-4 py-2.5 text-[14px] text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-sm">
                            <option value="Nightshade">Nightshade</option>
                            <option value="Leafy Greens">Leafy Greens</option>
                            <option value="Herbs">Herbs</option>
                            <option value="Fruits">Fruits</option>
                            <option value="Others">Others</option>
                        </select>
                    </div>

                    <!-- Crop Duration -->
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[13px] font-bold text-on-surface">Crop Duration (Days) *</label>
                        <div class="flex items-center gap-2">
                            <input type="number" id="form-duration-min" required min="1" class="w-full bg-surface-container-lowest border border-outline-variant/40 rounded-xl px-3 py-2.5 text-[14px] text-on-surface text-center focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-sm" placeholder="Min (e.g. 85)">
                            <span class="text-on-surface-variant text-[13px] font-semibold">—</span>
                            <input type="number" id="form-duration-max" required min="1" class="w-full bg-surface-container-lowest border border-outline-variant/40 rounded-xl px-3 py-2.5 text-[14px] text-on-surface text-center focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-sm" placeholder="Max (e.g. 100)">
                        </div>
                    </div>
                </div>

                <!-- Thumbnail Image URL -->
                <div class="flex gap-4 items-end mt-1">
                    <div class="flex-1 flex flex-col gap-1.5">
                        <label class="text-[13px] font-bold text-on-surface" for="form-image">Thumbnail Image URL</label>
                        <input type="text" id="form-image" oninput="updateImagePreview()" class="w-full bg-surface-container-lowest border border-outline-variant/40 rounded-xl px-4 py-2.5 text-[14px] text-on-surface placeholder:text-on-surface-variant/40 focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all shadow-sm" placeholder="Paste image URL here">
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-surface-container-high border border-outline-variant/20 overflow-hidden flex items-center justify-center shrink-0">
                        <img id="image-preview" src="https://images.unsplash.com/photo-1463936575829-25148e1db1b8?w=150&h=150&fit=crop" class="w-full h-full object-cover" alt="Preview" onerror="this.src='https://images.unsplash.com/photo-1463936575829-25148e1db1b8?w=150&h=150&fit=crop'">
                    </div>
                </div>
            </div>

            <!-- Lifecycle Milestones Section -->
            <div class="flex flex-col gap-4 border-t border-outline-variant/20 pt-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-[14px] font-black tracking-widest text-[#006c49] uppercase">Lifecycle Milestones</h3>
                    <button type="button" onclick="addStageRow('', '', '', 'eco')" class="flex items-center gap-1.5 text-primary hover:text-primary/80 font-bold text-[13px] bg-[#10b981]/10 px-3.5 py-1.5 rounded-full transition-colors">
                        <span class="material-symbols-outlined text-[16px]">add</span>
                        Add Stage
                    </button>
                </div>

                <!-- Stages Container -->
                <div id="stages-container" class="flex flex-col gap-3">
                    <!-- Stage rows injected dynamically -->
                </div>
            </div>

            <!-- Error Banner -->
            <div id="error-banner" class="hidden bg-error/10 border border-error/20 rounded-2xl p-4 flex gap-3 text-error shrink-0">
                <span class="material-symbols-outlined text-[20px] shrink-0">error</span>
                <div class="flex flex-col gap-1">
                    <span class="text-[13px] font-black uppercase tracking-wider">Validation Error</span>
                    <ul id="error-list" class="text-[12px] list-disc pl-4 font-medium flex flex-col gap-0.5">
                    </ul>
                </div>
            </div>
            
            <button type="submit" class="hidden"></button>
        </form>

        <!-- Modal Footer -->
        <div class="px-8 py-5 border-t border-outline-variant/20 bg-surface-container-low/50 rounded-b-[32px] flex justify-end gap-3 shrink-0">
            <button type="button" onclick="closeModal()" class="px-5 py-2.5 rounded-full bg-white border border-outline-variant/30 text-on-surface font-bold text-[13px] hover:bg-surface-container-low transition-colors">
                Cancel
            </button>
            <button type="button" onclick="submitForm()" class="px-6 py-2.5 rounded-full bg-[#006c49] hover:bg-[#005236] text-white font-bold text-[13px] transition-colors shadow-sm">
                Save Template
            </button>
        </div>

    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="hidden fixed inset-0 bg-black/55 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-[32px] w-full max-w-[400px] p-8 flex flex-col gap-6 ambient-shadow animate-modal">
        <div class="flex flex-col gap-2">
            <h3 class="text-[20px] font-black text-on-surface tracking-tight">Delete Template?</h3>
            <p id="delete-message" class="text-[14px] text-on-surface-variant leading-relaxed">Are you sure you want to delete this template? This action cannot be undone.</p>
        </div>
        <div class="flex justify-end gap-3">
            <button onclick="closeDeleteModal()" class="px-5 py-2.5 rounded-full bg-white border border-outline-variant/30 text-on-surface font-bold text-[13px] hover:bg-surface-container-low transition-colors">
                Cancel
            </button>
            <button id="delete-confirm-btn" class="px-6 py-2.5 rounded-full bg-error text-white font-bold text-[13px] hover:bg-error/90 transition-colors shadow-sm">
                Delete
            </button>
        </div>
    </div>
</div>

<!-- Success Toast Notification -->
<div id="toast" class="hidden fixed bottom-6 right-6 z-50 bg-[#006c49] text-white rounded-2xl px-6 py-4 flex items-center gap-3 shadow-lg transition-all duration-300 transform translate-y-4 opacity-0">
    <span class="material-symbols-outlined text-[24px]">check_circle</span>
    <div class="flex flex-col">
        <span id="toast-title" class="font-bold text-[14px]">Success</span>
        <span id="toast-message" class="text-[12px] opacity-90">Template saved successfully.</span>
    </div>
</div>

@push('scripts')
<script>
    // Constants
    const ICONS_LIST = ['eco', 'psychiatry', 'yard', 'filter_vintage', 'nutrition', 'local_florist', 'agriculture', 'shopping_bag'];
    
    // State
    let activeTemplateId = null;
    let deleteTemplateId = null;
    let openMenuId = null;
    let dragSourceEl = null;

    // Card menu toggle logic
    function toggleCardMenu(id, event) {
        event.stopPropagation();
        const menu = document.getElementById(`card-menu-${id}`);
        const isHidden = menu.classList.contains('hidden');
        
        closeAllCardMenus();
        
        if (isHidden) {
            menu.classList.remove('hidden');
            openMenuId = id;
        }
    }

    function closeAllCardMenus() {
        document.querySelectorAll('[id^="card-menu-"]').forEach(menu => {
            menu.classList.add('hidden');
        });
        openMenuId = null;
    }

    document.addEventListener('click', () => {
        closeAllCardMenus();
        // Also close any open icon pickers
        document.querySelectorAll('.icon-picker-dropdown').forEach(d => d.remove());
    });

    // Image preview live update
    function updateImagePreview() {
        const url = document.getElementById('form-image').value.trim();
        const preview = document.getElementById('image-preview');
        if (url) {
            preview.src = url;
        } else {
            preview.src = 'https://images.unsplash.com/photo-1463936575829-25148e1db1b8?w=150&h=150&fit=crop';
        }
    }

    // Modal Control
    function openCreateModal() {
        activeTemplateId = null;
        document.getElementById('template-id').value = '';
        document.getElementById('template-form').reset();
        document.getElementById('modal-title').textContent = 'Create Growth Template';
        document.getElementById('stages-container').innerHTML = '';
        document.getElementById('error-banner').classList.add('hidden');
        
        // Seed default first stage
        addStageRow('Germination', 0, 7, 'eco');
        
        updateImagePreview();
        document.getElementById('template-modal').classList.remove('hidden');
    }

    function editTemplate(template) {
        activeTemplateId = template.id;
        document.getElementById('template-id').value = template.id;
        document.getElementById('form-name').value = template.name;
        document.getElementById('form-category').value = template.category;
        document.getElementById('form-duration-min').value = template.duration_min;
        document.getElementById('form-duration-max').value = template.duration_max;
        document.getElementById('form-image').value = template.image || '';
        document.getElementById('modal-title').textContent = 'Edit Growth Template';
        document.getElementById('stages-container').innerHTML = '';
        document.getElementById('error-banner').classList.add('hidden');

        if (template.stages && template.stages.length > 0) {
            template.stages.forEach(stage => {
                addStageRow(stage.stage_name, stage.start_day, stage.end_day, stage.icon);
            });
        } else {
            addStageRow('Germination', 0, 7, 'eco');
        }

        updateImagePreview();
        document.getElementById('template-modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('template-modal').classList.add('hidden');
    }

    // Stage Row Management
    function addStageRow(name = '', start = '', end = '', icon = 'eco') {
        const container = document.getElementById('stages-container');
        
        // Calculate dynamic start day if not provided
        if (start === '') {
            const rows = container.querySelectorAll('.stage-row');
            if (rows.length > 0) {
                const lastEnd = rows[rows.length - 1].querySelector('.stage-end').value;
                start = lastEnd ? parseInt(lastEnd) : 0;
            } else {
                start = 0;
            }
        }

        const rowId = 'stage-' + Date.now() + '-' + Math.floor(Math.random() * 1000);
        const html = `
            <div id="${rowId}" class="stage-row flex items-center gap-3 bg-surface-container-lowest border border-outline-variant/30 rounded-2xl p-4 shadow-sm relative group" draggable="true" ondragstart="handleDragStart(event)" ondragover="handleDragOver(event)" ondrop="handleDrop(event)" ondragend="handleDragEnd(event)">
                <!-- Drag Handle -->
                <div class="cursor-grab text-outline hover:text-on-surface-variant p-1 flex items-center justify-center handle">
                    <span class="material-symbols-outlined text-[20px] select-none">drag_indicator</span>
                </div>
                
                <!-- Stage Name -->
                <div class="flex-1 flex flex-col gap-1">
                    <input type="text" placeholder="Stage Name (e.g. Germination)" class="stage-name bg-transparent border-b border-transparent hover:border-outline-variant/50 focus:border-primary py-0.5 text-[14px] font-bold text-on-surface focus:outline-none transition-all" required value="${name}">
                </div>

                <!-- Range Days -->
                <div class="flex items-center gap-2">
                    <div class="flex flex-col items-center">
                        <span class="text-[9px] text-on-surface-variant font-bold uppercase tracking-wider">Start</span>
                        <input type="number" class="stage-start w-14 bg-surface-container-low text-center rounded-lg py-1 text-[13px] font-semibold text-on-surface-variant focus:outline-none" readonly value="${start}">
                    </div>
                    <span class="text-on-surface-variant mt-3 font-semibold">—</span>
                    <div class="flex flex-col items-center">
                        <span class="text-[9px] text-on-surface-variant font-bold uppercase tracking-wider">End</span>
                        <input type="number" min="1" class="stage-end w-14 bg-surface-container-low text-center rounded-lg py-1 text-[13px] font-bold text-on-surface focus:outline-none focus:ring-1 focus:ring-[#006c49] border border-transparent focus:border-[#006c49]" required value="${end}" onchange="recalculateStartDays()">
                    </div>
                </div>

                <!-- Icon Picker -->
                <div class="relative">
                    <button type="button" data-icon="${icon}" class="stage-icon-btn p-2 rounded-xl border border-outline-variant/40 hover:bg-surface-container-low flex items-center justify-center text-[#006c49]" onclick="toggleIconPicker(this, event)">
                        <span class="material-symbols-outlined text-[20px]">${icon}</span>
                    </button>
                </div>

                <!-- Reorder Arrows -->
                <div class="flex flex-col gap-0.5">
                    <button type="button" onclick="moveStageUp(this)" class="p-0.5 text-outline hover:text-primary transition-colors flex items-center justify-center" title="Move Up">
                        <span class="material-symbols-outlined text-[16px] font-black">keyboard_arrow_up</span>
                    </button>
                    <button type="button" onclick="moveStageDown(this)" class="p-0.5 text-outline hover:text-primary transition-colors flex items-center justify-center" title="Move Down">
                        <span class="material-symbols-outlined text-[16px] font-black">keyboard_arrow_down</span>
                    </button>
                </div>

                <!-- Remove Button -->
                <button type="button" onclick="removeStageRow(this)" class="p-1.5 text-outline hover:text-error transition-colors flex items-center justify-center hover:bg-error/5 rounded-full" title="Remove Stage">
                    <span class="material-symbols-outlined text-[18px]">close</span>
                </button>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', html);
        recalculateStartDays();
    }

    function removeStageRow(button) {
        const row = button.closest('.stage-row');
        row.remove();
        recalculateStartDays();
    }

    function recalculateStartDays() {
        const container = document.getElementById('stages-container');
        const rows = container.querySelectorAll('.stage-row');
        let prevEnd = 0;
        
        rows.forEach((row, index) => {
            const startInput = row.querySelector('.stage-start');
            const endInput = row.querySelector('.stage-end');
            
            startInput.value = prevEnd;
            
            // Auto-fill end day if empty or 0, just to keep it increasing
            if (endInput.value !== '') {
                const endVal = parseInt(endInput.value);
                prevEnd = endVal;
            } else {
                prevEnd = prevEnd + 7; // placeholder increment
            }
        });
    }

    // Keyboard Reordering
    function moveStageUp(button) {
        const row = button.closest('.stage-row');
        const prev = row.previousElementSibling;
        if (prev) {
            row.parentNode.insertBefore(row, prev);
            recalculateStartDays();
        }
    }

    function moveStageDown(button) {
        const row = button.closest('.stage-row');
        const next = row.nextElementSibling;
        if (next) {
            row.parentNode.insertBefore(next, row);
            recalculateStartDays();
        }
    }

    // Custom Icon Picker
    function toggleIconPicker(button, event) {
        event.stopPropagation();
        
        // Remove existing popovers first
        document.querySelectorAll('.icon-picker-dropdown').forEach(d => d.remove());
        
        const popover = document.createElement('div');
        popover.className = 'icon-picker-dropdown absolute top-12 right-0 bg-white border border-outline-variant/30 rounded-2xl shadow-lg p-3 z-30 grid grid-cols-4 gap-2 min-w-[160px] ambient-shadow';
        
        ICONS_LIST.forEach(ic => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'p-2 rounded-lg hover:bg-surface-container-low text-[#006c49] flex items-center justify-center transition-colors';
            btn.innerHTML = `<span class="material-symbols-outlined text-[20px]">${ic}</span>`;
            btn.onclick = (e) => {
                e.stopPropagation();
                button.setAttribute('data-icon', ic);
                button.querySelector('.material-symbols-outlined').textContent = ic;
                popover.remove();
            };
            popover.appendChild(btn);
        });
        
        button.parentNode.appendChild(popover);
    }

    // Drag and Drop
    function handleDragStart(e) {
        dragSourceEl = e.currentTarget;
        e.currentTarget.classList.add('dragging');
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/html', e.currentTarget.innerHTML);
    }

    function handleDragOver(e) {
        if (e.preventDefault) {
            e.preventDefault();
        }
        e.dataTransfer.dropEffect = 'move';
        
        const container = document.getElementById('stages-container');
        const draggingRow = container.querySelector('.dragging');
        const currentTarget = e.currentTarget;
        
        if (draggingRow && currentTarget && draggingRow !== currentTarget) {
            // Find current coordinates of mouse relative to the targeted row
            const rect = currentTarget.getBoundingClientRect();
            const midpoint = rect.top + rect.height / 2;
            
            if (e.clientY < midpoint) {
                container.insertBefore(draggingRow, currentTarget);
            } else {
                container.insertBefore(draggingRow, currentTarget.nextElementSibling);
            }
        }
        return false;
    }

    function handleDrop(e) {
        if (e.stopPropagation) {
            e.stopPropagation();
        }
        return false;
    }

    function handleDragEnd(e) {
        e.currentTarget.classList.remove('dragging');
        recalculateStartDays();
    }

    // Toast Notification helper
    function showToast(title, message) {
        const toast = document.getElementById('toast');
        document.getElementById('toast-title').textContent = title;
        document.getElementById('toast-message').textContent = message;
        
        toast.classList.remove('hidden');
        // Force layout repaint
        toast.offsetHeight;
        
        toast.classList.remove('opacity-0', 'translate-y-4');
        toast.classList.add('opacity-100', 'translate-y-0');
        
        setTimeout(() => {
            toast.classList.remove('opacity-100', 'translate-y-0');
            toast.classList.add('opacity-0', 'translate-y-4');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 300);
        }, 3000);
    }

    // Submit Form
    function submitForm() {
        // Trigger html5 form submit/validation
        const form = document.getElementById('template-form');
        if (form.reportValidity()) {
            form.requestSubmit();
        }
    }

    function saveTemplate(event) {
        event.preventDefault();
        
        const errorBanner = document.getElementById('error-banner');
        const errorList = document.getElementById('error-list');
        errorBanner.classList.add('hidden');
        errorList.innerHTML = '';
        
        // Client side validation
        const errors = [];
        
        const name = document.getElementById('form-name').value.trim();
        const category = document.getElementById('form-category').value;
        const durationMin = parseInt(document.getElementById('form-duration-min').value);
        const durationMax = parseInt(document.getElementById('form-duration-max').value);
        const image = document.getElementById('form-image').value.trim();
        
        if (!name) {
            errors.push("Template Name is required.");
        }
        
        if (isNaN(durationMin) || durationMin < 1) {
            errors.push("Min duration must be at least 1 day.");
        }
        
        if (isNaN(durationMax) || durationMax < durationMin) {
            errors.push("Max duration must be equal to or greater than Min duration.");
        }
        
        const rows = document.querySelectorAll('#stages-container .stage-row');
        if (rows.length === 0) {
            errors.push("At least one lifecycle milestone stage is required.");
        }
        
        const stagesData = [];
        let prevEnd = -1;
        
        rows.forEach((row, index) => {
            const stageName = row.querySelector('.stage-name').value.trim();
            const startDay = parseInt(row.querySelector('.stage-start').value);
            const endDay = parseInt(row.querySelector('.stage-end').value);
            const icon = row.querySelector('.stage-icon-btn').getAttribute('data-icon') || 'eco';
            
            if (!stageName) {
                errors.push(`Stage ${index + 1}: Name is required.`);
            }
            
            if (isNaN(endDay) || endDay <= startDay) {
                errors.push(`Stage ${index + 1} (${stageName || 'unnamed'}): End Day must be greater than Start Day (${startDay}).`);
            }
            
            if (startDay < prevEnd) {
                errors.push(`Stage ${index + 1} (${stageName || 'unnamed'}): Start Day must be equal or greater than the previous Stage's End Day.`);
            }
            
            prevEnd = endDay;
            stagesData.push({
                stage_name: stageName,
                start_day: startDay,
                end_day: endDay,
                icon: icon
            });
        });
        
        if (errors.length > 0) {
            errors.forEach(err => {
                const li = document.createElement('li');
                li.textContent = err;
                errorList.appendChild(li);
            });
            errorBanner.classList.remove('hidden');
            document.getElementById('template-form').scrollTop = 0;
            return;
        }

        const id = document.getElementById('template-id').value;
        const url = id ? `/admin/care-templates/${id}` : '/admin/care-templates';
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                name: name,
                category: category,
                duration_min: durationMin,
                duration_max: durationMax,
                image: image,
                stages: stagesData
            })
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(errData => {
                    throw errData;
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                closeModal();
                document.getElementById('templates-grid').innerHTML = data.html;
                showToast("Success", id ? "Template updated successfully!" : "Template created successfully!");
            }
        })
        .catch(err => {
            console.error(err);
            if (err.errors) {
                Object.keys(err.errors).forEach(key => {
                    const li = document.createElement('li');
                    li.textContent = err.errors[key][0];
                    errorList.appendChild(li);
                });
                errorBanner.classList.remove('hidden');
                document.getElementById('template-form').scrollTop = 0;
            } else {
                const li = document.createElement('li');
                li.textContent = err.message || "An unexpected error occurred.";
                errorList.appendChild(li);
                errorBanner.classList.remove('hidden');
                document.getElementById('template-form').scrollTop = 0;
            }
        });
    }

    // Duplicate Template AJAX
    function duplicateTemplate(id) {
        fetch(`/admin/care-templates/${id}/duplicate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('templates-grid').innerHTML = data.html;
                showToast("Duplicated", "Template duplicated successfully!");
            }
        })
        .catch(err => {
            console.error(err);
            alert("Failed to duplicate template.");
        });
    }

    // Delete Template Dialog & AJAX
    function confirmDeleteTemplate(id, name) {
        deleteTemplateId = id;
        document.getElementById('delete-message').textContent = `Are you sure you want to delete "${name}"? This action cannot be undone.`;
        document.getElementById('delete-modal').classList.remove('hidden');
        
        // Bind click handler once
        const confirmBtn = document.getElementById('delete-confirm-btn');
        confirmBtn.onclick = () => {
            executeDeleteTemplate(deleteTemplateId);
        };
    }

    function closeDeleteModal() {
        document.getElementById('delete-modal').classList.add('hidden');
        deleteTemplateId = null;
    }

    function executeDeleteTemplate(id) {
        fetch(`/admin/care-templates/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeDeleteModal();
                document.getElementById('templates-grid').innerHTML = data.html;
                showToast("Deleted", "Template deleted successfully.");
            }
        })
        .catch(err => {
            console.error(err);
            closeDeleteModal();
            alert("Failed to delete template.");
        });
    }
</script>
@endpush
@endsection
