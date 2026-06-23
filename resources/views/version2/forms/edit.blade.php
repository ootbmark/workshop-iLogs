@extends('layouts.appV2')
@section('content')
    <div class="container-xl py-5">
        <!-- Builder Grid Panel -->
        <div class="row g-4 text-start">

            <!-- Left Sidebar - Metadata Config -->
            <div class="col-lg-4">
                <div class="card-custom p-4 mb-4">
                    <h3 class="fw-bold text-dark border-bottom pb-3 mb-3" style="font-size: 1.15rem;">Quiz Properties</h3>
                    <form action="{{ route('admin.builder.update', $quiz->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="quiz-title"
                                class="form-label text-uppercase text-muted fw-bold small tracking-wider mb-1"
                                style="font-size: 11px;">Workshop Quiz Title <span class="text-danger">*</span></label>
                            <input name="title" type="text" class="form-control py-2.5 bg-light border-0 shadow-none"
                                value="{{ $quiz->title }}">
                            @error('title')
                                <small class="mt-2 badge bg-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="quiz-desc"
                                class="form-label text-uppercase text-muted fw-bold small tracking-wider mb-1"
                                style="font-size: 11px;">Description / Guidelines</label>
                            <textarea name="description" rows="3" class="form-control py-2.5 bg-light border-0 shadow-none">{{ $quiz->description }}</textarea>
                            @error('description')
                                <small class="mt-2 badge bg-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="quiz-title"
                                class="form-label text-uppercase text-muted fw-bold small tracking-wider mb-1"
                                style="font-size: 11px;">Workshop Date <span class="text-danger">*</span></label>
                            <input name="date" type="date" class="form-control py-2.5 bg-light border-0 shadow-none"
                                value="{{ \Carbon\Carbon::parse($quiz->time_limit)->format('Y-m-d') }}">
                            @error('date')
                                <small class="mt-2 badge bg-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label for="quiz-group-track"
                                class="form-label text-uppercase text-muted fw-bold small tracking-wider mb-1"
                                style="font-size: 11px;">Select Group <span class="text-danger">*</span></label>
                            <select id="select-groups" class="form-select py-2.5 bg-light border-0 shadow-none"
                                name="groups[]" multiple>
                                @foreach ($groupList as $item)
                                    <option value="{{ $item->id }}"
                                        {{ in_array($item['id'], $quiz->groups->pluck('id')->toArray()) ? 'selected' : '' }}>
                                        {{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>

                            @error('group')
                                <small class="mt-2 badge bg-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="mb-0">
                            <label for="quiz-group-track"
                                class="form-label text-uppercase text-muted fw-bold small tracking-wider mb-1"
                                style="font-size: 11px;">Company <span class="text-danger">*</span></label>
                            <select class="form-select py-2.5 bg-light border-0 shadow-none" name="company">
                                @foreach ($companies as $item)
                                    <option value="{{ $item['id'] }}"
                                        {{ $item->company_id == $item['id'] ? 'selected' : '' }}>{{ $item['name'] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('company')
                                <small class="mt-2 badge bg-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button class="btn btn-primary w-100 mt-3">UPDATED</button>
                    </form>





                </div>

                <!-- SPREAD Target Entities Configurations -->
                <div class="card-custom p-4">
                    <div class="border-bottom pb-3 mb-3 d-flex align-items-center justify-content-between">
                        <h3 class="fw-bold text-dark mb-0" style="font-size: 1.15rem;">Self Verification</h3>
                    </div>
                    <div id="status-toast"
                        class="toast-notification alert d-flex align-items-center py-2 px-3 border-0 rounded-3 mb-0"
                        role="alert">
                        <i class="bi" id="toast-icon"></i>
                        <div id="toast-msg"></div>
                    </div>
                    {{-- verification_text_1 --}}
                    <div class="mb-4 edit-group-container">
                        <label for="quiz-1" class="form-label text-uppercase text-muted fw-bold small tracking-wider mb-2"
                            style="font-size: 11px;"></label>
                        <div class="input-group">
                            <input id="quiz-1" type="text"
                                class="form-control py-2.5 bg-light border-0 shadow-none text-secondary focal-input"
                                placeholder="e.g. John Doe (Drilling Engr.)" value="{{ $quiz->verification_text_1 }}"
                                disabled>

                            <button class="btn btn-outline-primary px-4 d-flex align-items-center gap-2 toggle-edit-btn"
                                type="button" data-index="1" data-quiz="{{ base64_encode($quiz->id) }}"
                                data-input="verification_text_1">
                                <i class="bi bi-pencil-square btn-icon"></i>
                                <small class="btn-text">Edit</small>
                            </button>
                        </div>
                    </div>
                    {{-- verification_text_2 --}}
                    <div class="mb-4 edit-group-container">
                        <label for="quiz-2" class="form-label text-uppercase text-muted fw-bold small tracking-wider mb-2"
                            style="font-size: 11px;"></label>
                        <div class="input-group">
                            <input id="quiz-2" type="text"
                                class="form-control py-2.5 bg-light border-0 shadow-none text-secondary focal-input"
                                placeholder="e.g. John Doe (Drilling Engr.)" value="{{ $quiz->verification_text_2 }}"
                                disabled>

                            <button class="btn btn-outline-primary px-4 d-flex align-items-center gap-2 toggle-edit-btn"
                                type="button" data-index="2" data-quiz="{{ base64_encode($quiz->id) }}"
                                data-input="verification_text_2">
                                <i class="bi bi-pencil-square btn-icon"></i>
                                <small class="btn-text">Edit</small>
                            </button>
                        </div>
                    </div>
                    {{-- verification_text_3 --}}
                    <div class="mb-4 edit-group-container">
                        <label for="quiz-1"
                            class="form-label text-uppercase text-muted fw-bold small tracking-wider mb-2"
                            style="font-size: 11px;"></label>
                        <div class="input-group">
                            <input id="quiz-3" type="text"
                                class="form-control py-2.5 bg-light border-0 shadow-none text-secondary focal-input"
                                placeholder="e.g. John Doe (Drilling Engr.)" value="{{ $quiz->verification_text_3 }}"
                                disabled>

                            <button class="btn btn-outline-primary px-4 d-flex align-items-center gap-2 toggle-edit-btn"
                                type="button" data-index="3" data-quiz="{{ base64_encode($quiz->id) }}"
                                data-input="verification_text_3">
                                <i class="bi bi-pencil-square btn-icon"></i>
                                <small class="btn-text">Edit</small>
                            </button>
                        </div>
                    </div>
                    {{-- verification_text_4 --}}
                    <div class="mb-4 edit-group-container">
                        <label for="quiz-4"
                            class="form-label text-uppercase text-muted fw-bold small tracking-wider mb-2"
                            style="font-size: 11px;"></label>
                        <div class="input-group">
                            <input id="quiz-4" type="text"
                                class="form-control py-2.5 bg-light border-0 shadow-none text-secondary focal-input"
                                placeholder="e.g. John Doe (Drilling Engr.)" value="{{ $quiz->verification_text_4 }}"
                                disabled>

                            <button class="btn btn-outline-primary px-4 d-flex align-items-center gap-2 toggle-edit-btn"
                                type="button" data-index="4" data-quiz="{{ base64_encode($quiz->id) }}"
                                data-input="verification_text_4">
                                <i class="bi bi-pencil-square btn-icon"></i>
                                <small class="btn-text">Edit</small>
                            </button>
                        </div>
                    </div>
                    {{-- verification_text_5 --}}
                    <div class="mb-4 edit-group-container">
                        <label for="quiz-1"
                            class="form-label text-uppercase text-muted fw-bold small tracking-wider mb-2"
                            style="font-size: 11px;"></label>
                        <div class="input-group">
                            <input id="quiz-5" type="text"
                                class="form-control py-2.5 bg-light border-0 shadow-none text-secondary focal-input"
                                placeholder="e.g. John Doe (Drilling Engr.)" value="{{ $quiz->verification_text_5 }}"
                                disabled>

                            <button class="btn btn-outline-primary px-4 d-flex align-items-center gap-2 toggle-edit-btn"
                                type="button" data-index="5" data-quiz="{{ base64_encode($quiz->id) }}"
                                data-input="verification_text_5">
                                <i class="bi bi-pencil-square btn-icon"></i>
                                <small class="btn-text">Edit</small>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Panel - Dynamic Question Builder -->
            <div class="col-lg-8">
                <div class="card-custom p-4">
                    <div
                        class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4 border-bottom pb-3">
                        <div>
                            <h3 class="fw-bold text-dark mb-1" style="font-size: 1.25rem;">Evaluation Questions</h3>
                        </div>
                        <button onclick="addQuestionCard()"
                            class="btn btn-spreadBlue py-2.5 px-3 rounded-3 d-inline-flex align-items-center gap-2 text-nowrap">
                            <i class="bi bi-plus-lg"></i> Add Question
                        </button>
                    </div>

                    <!-- List of dynamically built Questions -->
                    <div id="questions-list-container" class="vstack gap-4">
                        <!-- Live rendering cards -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #28a745 !important;
            border-color: #28a745 !important;
            color: white !important;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
            color: white !important;
        }


        /* Custom styled notification */
        .toast-notification {
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .toast-notification.show {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            // for Select
            $('#select-groups').select2({
                placeholder: "Select groups",
                width: '100%',
            });

            $('.toggle-edit-btn').on('click', function() {
                const $button = $(this);
                const index = $button.data('index');
                const input = $button.data('input');
                const quiz = $button.data('quiz')
                const $inputField = $('#quiz-' + index);
                const $btnText = $button.find('.btn-text');
                const $btnIcon = $button.find('.btn-icon');

                const $statusToast = $('#status-toast');
                const $toastIcon = $('#toast-icon');
                const $toastMsg = $('#toast-msg');

                if ($inputField.prop('disabled')) {
                    // --- SWITCH TO EDIT MODE ---
                    $inputField.prop('disabled', false);
                    $inputField.removeClass('bg-light text-secondary').addClass('bg-white').focus();

                    // Set cursor to the end of the input text
                    const val = $inputField.val();
                    $inputField.val('').val(val);

                    // Adjust button appearance for saving
                    $button.removeClass('btn-outline-primary btn-danger').addClass('btn-success');
                    $btnText.text('Update');
                    $btnIcon.removeClass().addClass('bi bi-check-lg');

                    // Hide any active notifications
                    $statusToast.removeClass('show');
                } else {
                    // --- ATTEMPT TO SAVE (AJAX UPDATE METHOD) ---
                    const updatedValue = $.trim($inputField.val());

                    // Client-side validation
                    if (updatedValue === '') {
                        $inputField.addClass('is-invalid');
                        return;
                    }
                    $inputField.removeClass('is-invalid');

                    // 1. Enter Loading State
                    // Disable input and button to prevent double-submissions
                    $inputField.prop('disabled', true);
                    $button.prop('disabled', true);
                    $btnText.text('Saving...');
                    $btnIcon.removeClass().addClass('spinner-border spinner-border-sm');

                    // 2. Perform the jQuery AJAX POST request
                    $.ajax({
                        url: "{{ route('admin.builder.verification-update') }}", // Real external endpoint for live demonstration
                        type: 'POST',
                        headers: {
                            // Grabs token from head meta tag to prevent 419 Token Mismatch errors
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        contentType: 'application/json; charset=UTF-8',
                        data: JSON.stringify({
                            id: input,
                            verification: updatedValue,
                            quiz: quiz
                        }),
                        success: function(response) {
                            // --- SUCCESS CALLBACK ---

                            // Finish updating input style state
                            $inputField.removeClass('bg-white').addClass(
                                'bg-light text-secondary');

                            // Restore button state
                            $button.prop('disabled', false)
                                .removeClass('btn-success btn-danger')
                                .addClass('btn-outline-primary');
                            $btnText.text('Edit');
                            $btnIcon.removeClass().addClass('bi bi-pencil-square');

                            // Config & trigger success toast
                            $statusToast.removeClass('alert-danger').addClass('alert-success');
                            $toastIcon.removeClass().addClass('bi bi-check-circle-fill me-2');
                            $toastMsg.html(' updated to: <strong>' +
                                updatedValue + '</strong>');
                            $statusToast.addClass('show');

                            // Auto-hide toast after 4 seconds
                            setTimeout(() => {
                                $statusToast.removeClass('show');
                            }, 4000);
                        },
                        error: function(xhr, status, error) {
                            // --- ERROR CALLBACK ---

                            // Re-enable the input field so the user doesn't lose their text
                            $inputField.prop('disabled', false);

                            // Restore button with an error color scheme to alert the user
                            $button.prop('disabled', false)
                                .removeClass('btn-success')
                                .addClass('btn-danger');
                            $btnText.text('Retry');
                            $btnIcon.removeClass().addClass('bi bi-exclamation-triangle-fill');

                            // Config & trigger error toast
                            $statusToast.removeClass('alert-success').addClass('alert-danger');
                            $toastIcon.removeClass().addClass(
                                'bi bi-exclamation-octagon-fill me-2');
                            $toastMsg.html('Failed to update ' + index +
                                '. Please try again.');
                            $statusToast.addClass('show');
                        }
                    });
                }
            });
        });
    </script>
    <script>
        // Question cards build state tracking inside current editor
        let activeQuestions = [];
        // Predefined question templates
        const questionTypes = [{
                value: 'radio',
                label: 'Single Choice (Radio Yes/No)'
            },
            {
                value: 'multiple',
                label: 'Multiple Choice (Checkboxes)'
            },
            {
                value: 'dropdown',
                label: 'Dropdown Selector'
            },
            {
                value: 'text',
                label: 'Short Answer Text Field'
            },
            {
                value: 'circling',
                label: 'Circling Checkbox + Description (High Precision)'
            },
            {
                value: 'file',
                label: 'Interface Design Selector (File type with mockup cards)'
            }
        ];
        document.addEventListener('DOMContentLoaded', function() {
            initBuilderState()
        });

        // Dynamic Builder Core State
        function initBuilderState() {
            const questionList = @json($questionList);
            if (questionList.length > 0) {
                questionList.forEach(q => {
                    // Extract option titles into a clean array of strings: ["Yes", "No"]
                    const optionTitles = q.answer ? q.answer.map(opt => opt.title) : [];
                    // Map 'is_required' integer (1/0) to boolean (true/false)
                    const isRequired = q.is_required === 1;

                    // Push it into the builder canvas
                    addQuestionCard(
                        q.question,
                        q.title,
                        q.type,
                        isRequired,
                        `Question ID: ${q.question_id}`, // Using ID as a placeholder tooltip
                        q.answer
                    );
                });
            }

            /* // Start with two default clean questions to preview
            addQuestionCard("Have you heard of Artificial Intelligence (AI) before?", "radio", true,
                "Provide basic user experience level checklist parameters.");
            addQuestionCard("Which workstation environments do you compile code in?", "multiple", false,
                "Choose all applicable compilers."); */
        }

        // Add a new question object to builder canvas
        function addQuestionCard(questionCode = null, title = "", type = "text", required = false, tooltip = "", options) {
            const qId = 'Q-' + Date.now() + Math.random().toString(36).substr(2, 4);
            const optionList = options === null ? [{
                    optionCode: null,
                    option: 'Yes'
                },
                {
                    optionCode: null,
                    option: 'No'
                }
            ] : options;
            const question = {
                id: qId,
                questionCode,
                title,
                type,
                required,
                tooltip,
                optionList // Default options for choice types
            };

            activeQuestions.push(question);
            renderQuestionCards();
        }

        // Remove a question card from canvas
        function removeQuestionCard(qId) {
            activeQuestions = activeQuestions.filter(q => q.id !== qId);
            renderQuestionCards();
        }

        // Render Cards List onto DOM
        function renderQuestionCards() {
            const container = document.getElementById('questions-list-container');
            container.innerHTML = '';

            if (activeQuestions.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-5 border border-dashed rounded-3 bg-light">
                        <i class="bi bi-patch-question fs-1 text-muted d-block mb-2"></i>
                        <span class="text-muted fw-bold small d-block">No questions defined yet</span>
                        <p class="text-muted small mt-1 mb-0">Click the top "Add Question" button to build your first questionnaire block.</p>
                    </div>
                `;
                return;
            }

            activeQuestions.forEach((q, index) => {
                const card = document.createElement('div');
                card.className = "p-4 rounded-3 border bg-light shadow-sm hover-border-secondary";
                // Construct Options Sub-panel if choice type
                let optionsHtml = '';
                if (['radio', 'multiple', 'dropdown'].includes(q.type)) {

                    const optionRows = q.optionList.map((opt, oIdx) => `
        <div class="d-flex align-items-center gap-2">
            <input
                type="text"
                value="${opt.option}"
                oninput="updateQuestionOption('${q.id}', ${oIdx}, this.value)"
                class="form-control form-control-sm bg-light border-0 py-2"
            >

            <button
                onclick="removeQuestionOption('${q.id}', ${oIdx})"
                class="btn btn-link text-muted p-1">
                <i class="bi bi-x-circle fs-5"></i>
            </button>
        </div>
    `).join('');
                    optionsHtml = `
        <div class="mt-3 bg-white p-3 rounded-3 border">
            <label class="form-label text-uppercase text-muted fw-extrabold mb-2"
                   style="font-size:10px;">
                Configure Selection Options
            </label>
            <div class="vstack gap-2" id="options-box-${q.id}">
               ${optionRows}
            </div>
            <button onclick="addQuestionOption('${q.id}')" class="btn btn-link text-decoration-none text-spreadBlue-500 fw-bold small p-0 mt-2 d-inline-flex align-items-center gap-1">
                <i class="bi bi-plus-circle"></i>
                Add Option
            </button>
        </div>`;
                } else if (q.type === 'circling') {
                    optionsHtml = `
                        <div class="mt-3 bg-light p-3 rounded-3 border text-muted small" style="font-size: 13px;">
                            <i class="bi bi-info-circle-fill text-primary mr-1"></i>
                            <strong>Circling Block Template:</strong> This renders evaluation choices (e.g., Latency, Stability, Throughput) along with a descriptive, required comments text-area.
                        </div>
                    `;
                } else if (q.type === 'file') {
                    optionsHtml = `
                        <div class="mt-3 bg-white p-3 rounded-3 border text-muted small">
                            <div class="fw-bold text-dark mb-2">Preview Image Grid Mockup Options:</div>
                            <div class="d-flex flex-wrap gap-3">
                                <div class="border bg-light p-2 rounded text-center" style="width: 100px;">
                                    <span class="fw-bold text-primary" style="font-size: 10px;">Chat Panel</span>
                                    <div class="bg-primary bg-opacity-10 rounded mt-1" style="height: 30px;"></div>
                                </div>
                                <div class="border bg-light p-2 rounded text-center" style="width: 100px;">
                                    <span class="fw-bold text-success" style="font-size: 10px;">Analytics UI</span>
                                    <div class="bg-success bg-opacity-10 rounded mt-1" style="height: 30px;"></div>
                                </div>
                                <div class="border bg-light p-2 rounded text-center" style="width: 100px;">
                                    <span class="fw-bold text-info" style="font-size: 10px;">CLI Terminal</span>
                                    <div class="bg-info bg-opacity-10 rounded mt-1" style="height: 30px;"></div>
                                </div>
                            </div>
                        </div>
                    `;
                }

                card.innerHTML = `
                    <div class="d-flex flex-column flex-md-row gap-3 align-items-md-center justify-content-between mb-3 border-bottom pb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="bg-navy-900 text-white fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 26px; height: 26px; font-size: 12px;">
                                ${index + 1}
                            </span>
                            <span class="text-uppercase text-muted fw-extrabold" style="font-size: 11px;">Question Item</span>
                        </div>
                        <div class="d-flex gap-2 justify-content-end align-items-center">
                            <!-- Required Toggle -->
                            <div class="form-check form-switch bg-white border rounded-3 px-4 py-1.5 m-0 d-inline-flex align-items-center gap-2" style="font-size: 13px;">
                                <input class="form-check-input m-0 cursor-pointer" type="checkbox" ${q.required ? 'checked' : ''} onchange="updateQuestionField('${q.id}', 'required', this.checked)" id="req-${q.id}">
                                <label class="form-check-label text-muted fw-semibold cursor-pointer m-0" for="req-${q.id}">Required</label>
                            </div>
                            <!-- Delete button -->
                            <button onclick="removeQuestionCard('${q.id}')" class="btn btn-outline-danger p-2 rounded-3" title="Remove Question">
                                <i class="bi bi-trash-fill fs-6"></i>
                            </button>
                             <!-- Save button -->
                            <button onclick="saveQuestionCard('${q.id}')" class="btn btn-outline-primary p-2 rounded-3" title="Save Question">
                                <i class="bi bi-save fs-6"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Inner Card Details Input -->
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label text-uppercase text-muted fw-bold mb-1" style="font-size: 10px;">Question Title <span class="text-danger">*</span></label>
                            <input type="text" value="${q.title}" oninput="updateQuestionField('${q.id}', 'title', this.value)" class="form-control bg-white shadow-none" >
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label text-uppercase text-muted fw-bold mb-1" style="font-size: 10px;">Answer Selection Input Type</label>
                            <select onchange="updateQuestionField('${q.id}', 'type', this.value)" class="form-select bg-white shadow-none text-muted">
                                ${questionTypes.map(t => `
                                                                                                                                            <option value="${t.value}" ${q.type === t.value ? 'selected' : ''}>${t.label}</option>
                                                                                                                                        `).join('')}
                            </select>
                        </div>
                    </div>

                    <!-- Dynamic Options Sub-panel -->
                    ${optionsHtml}
                `;
                container.appendChild(card);
            });
        }

        // Live Field synchronization in memory
        function updateQuestionField(qId, field, value) {
            const q = activeQuestions.find(item => item.id === qId);
            if (q) {
                q[field] = value;

                // Re-render only if changing Type as options grids depend on it
                if (field === 'type') {
                    // Reset defaults for clean switches
                    if (['radio', 'dropdown'].includes(value)) {
                        q.options = [{
                                optionCode: null,
                                option: 'Yes'
                            },
                            {
                                optionCode: null,
                                option: 'No'
                            }
                        ];
                    } else if (value === 'multiple') {
                        q.options = [{
                                optionCode: null,
                                option: 'Option A'
                            },
                            {
                                optionCode: null,
                                option: 'Option B'
                            },
                            {
                                optionCode: null,
                                option: 'Option C'
                            }
                        ];
                    }
                    renderQuestionCards();
                }
            }
        }

        function updateQuestionOption(qId, oIdx, value) {
            const q = activeQuestions.find(item => item.id === qId);
            if (q && q.options) {
                q.options[oIdx] = value;
            }
        }

        function addQuestionOption(qId) {
            const q = activeQuestions.find(item => item.id === qId);
            if (q && q.options) {
                const addItem = {
                    optionCode: null,
                    option: `New Option ${q.options.length + 1}`
                }
                q.options.push(addItem);
                renderQuestionCards();
            }
        }

        function removeQuestionOption(qId, oIdx) {
            const q = activeQuestions.find(item => item.id === qId);
            if (q && q.options && q.options.length > 1) {
                q.options.splice(oIdx, 1);
                renderQuestionCards();
            }
        }


        // Form Publishing Submission Verification
        function handleSaveQuiz() {
            const title = document.getElementById('quiz-title').value.trim();
            const desc = document.getElementById('quiz-desc').value.trim();
            const groupTrack = document.getElementById('quiz-group-track').value;
            const focal = document.getElementById('quiz-focal').value.trim();
            const actionee = document.getElementById('quiz-actionee').value.trim();
            const targetDate = document.getElementById('quiz-date').value;

            // Alert control handles
            const alertBox = document.getElementById('builder-validation-alert');
            const alertText = document.getElementById('builder-validation-text');

            if (!title) {
                alertBox.classList.remove('d-none');
                alertText.innerText = "Please input a Workshop Quiz Title. This is a required field.";
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                return;
            }

            if (activeQuestions.length === 0) {
                alertBox.classList.remove('d-none');
                alertText.innerText = "Please add at least one survey question configuration inside your canvas.";
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                return;
            }

            // Verify nested option structures are not empty
            let optionError = false;
            activeQuestions.forEach(q => {
                if (['radio', 'multiple', 'dropdown'].includes(q.type)) {
                    if (q.options.some(opt => !opt.trim())) {
                        optionError = true;
                    }
                }
            });

            if (optionError) {
                alertBox.classList.remove('d-none');
                alertText.innerText = "Option selections fields must not contain empty strings.";
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                return;
            }

            // Hide warnings and commit compilation to database arrays
            alertBox.classList.add('d-none');

            const newId = 'QZ-' + Date.now().toString(36).toUpperCase();
            const newQuiz = {
                id: newId,
                title,
                groupTrack,
                desc: desc || "No description provided.",
                focal: focal || "N/A",
                actionee: actionee || "N/A",
                targetDate: targetDate || "N/A",
                questionsCount: activeQuestions.length,
                submissions: 0 // Deployed as new
            };

            // quizzes.unshift(newQuiz);

            showNotification(
                "success",
                "Quiz Successfully Published",
                `
                    <div class="vstack gap-2 text-start">
                        <p class="mb-1">The evaluation form <strong>"${title}"</strong> has been successfully registered to sector track: <span class="badge bg-navy-50 text-navy-900 border border-light font-sans">${groupTrack}</span>.</p>
                        <div class="bg-light border p-3 rounded-3 d-flex align-items-center justify-content-between gap-3 mt-2">
                            <div class="text-truncate">
                                <span class="text-muted d-block" style="font-size: 10px;">LIVE SURVEY DIRECT LINK</span>
                                <code class="small text-navy-900 select-all font-monospace text-truncate d-block">https://spread.com/eval?quiz=${newId}</code>
                            </div>
                            <button onclick="copyToClipboard('https://spread.com/eval?quiz=${newId}')" class="btn btn-navy-900 btn-sm text-nowrap px-3">
                                Copy Link
                            </button>
                        </div>
                    </div>
                `,
                () => switchView('dashboard')
            );


        }

        function saveQuestionCard(data) {
            const question = activeQuestions.find(q => q.id === data);

            $.ajax({
                url: "{{ route('admin.builder.store-question', $quiz->id) }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: JSON.stringify(question),
                contentType: "application/json",
                success: function(res) {
                    console.log(res);
                },
                error: function(err) {
                    console.log(err);
                }
            });

            console.log(question);
        }
    </script>
@endsection
