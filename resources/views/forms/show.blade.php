@extends('forms.app')

@section('content')
    <style>
        /* Survey Question Card Wrapper (Dynamic Quiz Block style) */
        .quiz-block {
            background-color: #ffffff;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 2.25rem;
            margin-bottom: 1.75rem;
        }

        .quiz-title {
            font-size: 1.15rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        /* Info popups and tooltips styling */
        .question_info {
            position: relative;
        }

        .question_popup {
            display: none;
            position: absolute;
            right: 0;
            top: 32px;
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            width: 320px;
            z-index: 100;
            color: var(--text-dark);
            font-size: 0.85rem;
        }

        .question_info:hover .question_popup {
            display: block;
        }

        /* Interactive Image checkbox grids */
        .checkbox-image-container {
            gap: 15px;
        }

        .checkbox-image {
            position: relative;
            cursor: pointer;
        }

        .checkbox-image input[type="checkbox"] {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
            width: 18px;
            height: 18px;
        }

        .checkbox-image label {
            border: 2px solid #cbd5e1;
            border-radius: 6px;
            overflow: hidden;
            display: block;
            transition: border-color 0.2s;
            background-color: #f8fafc;
        }

        .checkbox-image input[type="checkbox"]:checked+label {
            border-color: var(--accent-blue);
            box-shadow: 0 0 0 3px rgba(63, 81, 181, 0.15);
        }

        .checkbox-image img {
            width: 140px;
            height: 100px;
            object-fit: cover;
            display: block;
        }

        /* Quiz footer fields layout */
        .quiz-ipt {
            border-top: 1px solid var(--border-color);
            padding-top: 2rem;
            margin-top: 2rem;
            gap: 2rem;
        }

        .quiz-ipt-left,
        .quiz-ipt-right {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        .quiz-ipt-each {
            padding: 12px 16px;
        }

        .self-verification {
            background-color: #f8fafc;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 1.5rem;
        }

        .self-verification-option {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-bottom: 0.75rem;
        }

        .self-verification-option input[type="checkbox"] {
            margin-top: 4px;
        }

        .self-verification-option label {
            font-size: 0.85rem;
            color: #475569;
            cursor: pointer;
            line-height: 1.4;
        }

        /* SPREAD custom style radio options */
        .custom-survey-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 0;
            cursor: pointer;
        }

        .custom-survey-radio {
            width: 22px;
            height: 22px;
            border: 2px solid #cbd5e1;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        input[type="radio"]:checked+.custom-survey-radio {
            border-color: #101a36;
            border-width: 6px;
        }

        /* Alert notifications */
        .alert-spread {
            background-color: #fef2f2;
            border: 1px solid #fee2e2;
            color: #ef4444;
            border-radius: 6px;
            padding: 12px 16px;
            display: none;
            margin-bottom: 1.5rem;
        }

        /* Transition animations */
        .fade-step {
            display: none;
        }

        .fade-step.active {
            display: block;
            animation: fadeIn 0.4s ease-out forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
    <div class="survey-header-section">
        <h1 class="survey-title"><b>{{ $quiz->title }}</b></h1>
        <p class="survey-subtitle">
            {{ $quiz->description }}
        </p>
    </div>
    <div class="survey-card">
        @if (!$participant)
            <div class="mb-4 border-bottom pb-3">
                <h3 class="fw-bold text-dark">Participant Registration</h3>
                <p class="text-muted small">Input your workspace profile details and segment team grouping.</p>
            </div>

            <form id="form-step-2" action="{{ route('forms.view-code', base64_encode($quiz->quiz_code)) }}" method="GET">
                <div class="mb-4">
                    <label for="name-input" class="form-label">Participant's Name <span
                            class="required-asterisk">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Carlos Cruz"
                        value="{{ old('name') }}" required>
                </div>

                <div class="mb-4">
                    <label for="group-select" class="form-label">Select Groups <span
                            class="required-asterisk">*</span></label>
                    <div class="d-flex gap-2">
                        <select class="form-select" name="groups" required value="{{ old('groups') }}">
                            <option value="" disabled selected>-- Select your active group track --</option>
                            @foreach ($groups as $key => $group)
                                <option value="{{ base64_encode($key) }}">{{ $group }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="d-flex gap-3 pt-3">
                    <button type="button" class="btn btn-spread-secondary">
                        Back
                    </button>
                    <button type="submit" class="btn btn-spread-submit">
                        Next Stage <i class="bi bi-chevron-right ms-1"></i>
                    </button>
                </div>
            </form>
        @else
            @include('forms.components.question_list')
        @endif
    </div>

    {{-- <div class="my-container pt-5">
        <h1 class="text-center"><b>{{ $quiz->title }}</b></h1>
        <small class="text-center">{{ $quiz->description }}</small>
        @if (!$participant)
            <div class="login-container bg-white mt-3">
                <form class="mt-4 login-form" action="{{ route('forms.view-code', base64_encode($quiz->quiz_code)) }}"
                    method="GET">
                    <div class="form-group row">
                        <label for="email" class="col-md-3 col-form-label text-uppercase">PARTICIPANT'S NAME<span
                                class="text-red ml-1">*</span></label>
                        <div class="col-md-9 col-lg-7">
                            <input type="text" name="name" class="form-control" id="formCode"
                                placeholder="Workshop Code" required>
                            @error('name')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-md-3 col-form-label text-uppercase">SELECT GROUPS<span
                                class="text-red ml-1">*</span></label>
                        <div class="col-md-9 col-lg-7">
                            <select name="groups" id="groups" class="form-control border border-primary" required>
                                @foreach ($groups as $key => $group)
                                    <option value="{{ base64_encode($key) }}">{{ $group }}</option>
                                @endforeach
                            </select>

                            @error('groups')
                                <span class="text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-9 col-lg-7 offset-md-3 pl-lg-2 pl-0 pr-0 form-group d-flex">
                        <button type="submit" class="btn my-btn text-uppercase">Submit</button>
                    </div>
                </form>

            </div>
        @else
            @include('forms.components.question_list')
        @endif

    </div> --}}
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.countdown/2.2.0/jquery.countdown.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
    <script>
        $('.datepicker').datepicker({
            format: 'dd MM yyyy',
        });
        $('.status_radio').change(function() {
            $('#dane_form #hidden_inp').html(`<input type="hidden" value="${$(this).val()}" name="is_priority">`);
        });

        $('.status_radio_effort').change(function() {
            $('#dane_form #hidden_inp_effort').html(
                `<input type="hidden" value="${$(this).val()}" name="is_priority_effort">`);
        });
        $('.radio_priority').change(function() {
            $('#dane_form #hidden_inp_priority').html(
                `<input type="hidden" value="${$(this).val()}" name="is_priority_priority">`);
        });
        $('#user_group').change(function() {
            $('#dane_form #group').html(`<input type="hidden" value="${$(this).val()}" name="group_id">`);
        });

        $('.question_info').hover(function() {
            $(this).find('.question_popup').fadeIn(100)
        }, function() {
            $(this).find('.question_popup').fadeOut(100)
        });


        $('.imageZoom').click(function(e) {
            e.preventDefault();
            if ($(this).attr('href')) {
                $(this).attr('data-toggle', 'modal');
                $('#imageZoomModal').find('.modal-body').html(`<img src='${$(this).attr('href')}'>`)
            } else {
                $(this).attr('data-toggle', '')
            }

        })
    </script>

    <script>
        $('#form_submit_button').on('click', function(e) {
            e.preventDefault();
            $(this).attr('disabled', true);
            $('#dane_form').submit();
        });

        var percent = 0;

        @if ($quiz->is_required_fields)
            $('#dane_form').submit(function() {

                if (percent != 100) {
                    $('.form-error').show();
                    setTimeout(function() {
                        $('.form-error').fadeOut('slow');
                    }, 5000);
                    $('#form_submit_button').attr('disabled', false);
                    return false;
                } else {
                    this.is_check = true;
                    $('.form-error').hide()
                }
            });
        @endif

        $('#dane_form').submit(function(e) {
            var checkAllI = true;
            var requiredFields = $('div[data-required=1]');
            var firstError = null;
            let i = 0;

            while (i < requiredFields.length) {
                let fieldBlock = $(requiredFields[i]);

                var inputs = fieldBlock.find('input, textarea, select');

                inputs.map(function(index, itemIn) {
                    if (!$(itemIn).val() && !firstError) {
                        firstError = $(itemIn);
                        checkAllI = false;
                        fieldBlock.find('small.error').show();
                        setTimeout(() => {
                            fieldBlock.find('small.error').fadeOut('slow');
                        }, 10000);
                        $('body, html').animate({
                            scrollTop: fieldBlock.offset().top - 130
                        }, 700);
                    }
                });

                if (fieldBlock.find('input[type=checkbox], input[type=radio]').length && !firstError) {
                    if (fieldBlock.find('input[type=checkbox]:checked, input[type=radio]:checked').length) {
                        checkAllI = true;
                    } else {
                        fieldBlock.find('small.error').show();
                        setTimeout(function() {
                            fieldBlock.find('small.error').fadeOut('slow');
                        }, 10000);
                        $('body, html').animate({
                            scrollTop: fieldBlock.offset().top - 130
                        }, 700);
                        checkAllI = false;
                        break;
                    }
                }

                i++;
            }

            if (!checkAllI) {
                $('#form_submit_button').attr('disabled', false);
            }

            return checkAllI;

        });

        if (window.performance && window.performance.navigation.type === window.performance.navigation.TYPE_BACK_FORWARD) {
            window.location.reload();
        }

        $(document).ready(function() {
            // 1. Setup global AJAX configuration for CSRF tokens
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // 2. Define reusable Route URLs from Laravel
            const answerRoute = "{{ $routeList[0] }}";
            const deleteRoute = "{{ $routeList[1] }}";

            // 3. Centralized Answer Submission Function
            const submitAnswer = (route, method, question_id, answer, type = null, text = null) => {
                $.ajax({
                    url: route,
                    method: method,
                    data: {
                        question_id,
                        answer,
                        type,
                        text
                    },
                    success: function(result) {
                        // Update progress bar efficiently
                        $('.progress-bar')
                            .css('width', result.percent + '%')
                            .text(result.percent + '%');
                    }
                });
            };

            // --- EVENT HANDLERS ---

            // Select2 Elements
            $(".js-example-responsive").select2({
                width: 'resolve'
            }).on('select2:select', function(e) {
                submitAnswer(answerRoute, 'POST', $(this).attr('data-id'), e.params.data.id);
            }).on('select2:unselect', function(e) {
                submitAnswer(deleteRoute, 'DELETE', $(this).attr('data-id'), e.params.data.id);
            });

            // Custom Checkboxes
            $('.custom-control-input').on('click', function() {
                const $this = $(this);
                const isChecked = $this.prop("checked");
                const route = isChecked ? answerRoute : deleteRoute;
                const method = isChecked ? 'POST' : 'DELETE';
                const textVal = $this.parents('div.circling').find('textarea').val();

                submitAnswer(route, method, $this.attr('data-id'), $this.val(), $this.data('type'),
                    textVal);
            });

            // Custom Dropdown Selects
            $('.custom-select').on('change', function() {
                const $this = $(this);
                const val = $this.val();
                console.log($(this).data('id'), val)
                if (val === "") {
                    submitAnswer(deleteRoute, 'DELETE', $this.attr('data-id'));
                } else {
                    submitAnswer(answerRoute, 'POST', $this.attr('data-id'), val, 'dropdown');
                }
            });

            // Radio Buttons
            $('.form-check-input').on('change', function() {
                const $this = $(this);
                console.log($this.val(), $this.data('id'))
                submitAnswer(answerRoute, 'POST', $this.attr('data-id'), $this.val(), 'radio');
            });

            // Text Inputs
            $('.text').on('blur', function() {
                const $this = $(this);
                const val = $this.val();

                if (val === '') {
                    submitAnswer(deleteRoute, 'DELETE', $this.attr('data-id'));
                } else {
                    submitAnswer(answerRoute, 'POST', $this.attr('data-id'), val, 'text');
                }
            });

            // Textarea Inputs
            $('.textarea').on('blur', function() {
                const $this = $(this);
                const val = $this.val();
                const type = $this.data('type');
                const isBlank = val === '';

                submitAnswer(
                    isBlank ? deleteRoute : answerRoute,
                    isBlank ? 'DELETE' : 'POST',
                    $this.attr('data-id'),
                    val,
                    type
                );
            });
        });
    </script>
@endsection
