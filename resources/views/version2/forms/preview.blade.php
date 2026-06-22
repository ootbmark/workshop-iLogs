@extends('layouts.appV2')

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

        /* Core Card Layout recreation */
        .report-card {
            background-color: var(--card-bg);
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
            max-width: 1000px;
            width: 100%;
            margin: 0 auto;
            overflow: hidden;
            padding: 2.5rem;
        }

        .report-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
        }

        .report-header h2 {
            font-size: 1.15rem;
            font-weight: 600;
            color: #475569;
            margin: 0;
        }

        .edit-icon-btn {
            background: none;
            border: none;
            color: #6366f1;
            font-size: 1.1rem;
            padding: 0.35rem;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .edit-icon-btn:hover {
            background-color: #f1f5f9;
            color: var(--accent-blue);
            transform: scale(1.05);
        }

        /* Question & Answer Grid styling to match image_499fa5.png */
        .report-table-header {
            display: flex;
            justify-content: space-between;
            padding: 1.25rem 0;
            font-weight: 700;
            color: #1e293b;
            font-size: 0.95rem;
        }

        .report-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 1.25rem 0;
            font-size: 0.95rem;
            color: #64748b;
        }

        .report-question {
            flex: 1;
            padding-right: 2rem;
            line-height: 1.5;
        }

        .report-answer {
            width: 180px;
            text-align: left;
            font-weight: 500;
            color: #475569;
        }

        /* Three-Column Footer matching image_499fa5.png spacing */
        .report-footer {
            border-top: 1px solid var(--border-color);
            margin-top: 1.5rem;
            padding-top: 2rem;
        }

        .footer-col {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .footer-label {
            font-size: 0.85rem;
            color: #94a3b8;
            font-weight: 500;
        }

        .footer-value {
            font-size: 0.95rem;
            color: #475569;
            font-weight: 500;
        }
    </style>
    <div class="container-xl py-5">
        <div class="survey-header-section text-center">
            <h1 class="survey-title"><b>{{ $quiz->title }}</b></h1>
            <p class="survey-subtitle">
                {{ $quiz->description }}
            </p>
        </div>
        <div class="survey-card">
            @foreach ($quiz->questions as $question)
                <div class="quiz-block">
                    <div class="d-flex justify-content-between align-items-start">
                        <h4 class="quiz-title mt-0">{{ $loop->index + 1 }}. {{ $question->title }}</h4>
                        {{--  {{ json_encode($question) }} --}}
                        @if ($question->question_info)
                            <div class="position-relative question_info">
                                <svg width="25" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 496.158 496.158"
                                    style="enable-background:new 0 0 496.158 496.158; cursor: pointer">
                                    <path style="fill:#25B7D3;"
                                        d="M496.158,248.085c0-137.022-111.069-248.082-248.075-248.082C111.07,0.003,0,111.063,0,248.085
                                                                    c0,137.001,111.07,248.07,248.083,248.07C385.089,496.155,496.158,385.086,496.158,248.085z" />
                                    <path style="fill:#FFFFFF;"
                                        d="M138.216,173.592c0-13.915,4.467-28.015,13.403-42.297c8.933-14.282,21.973-26.11,39.111-35.486
                                                                    c17.139-9.373,37.134-14.062,59.985-14.062c21.238,0,39.99,3.921,56.25,11.755c16.26,7.838,28.818,18.495,37.683,31.97
                                                                    c8.861,13.479,13.293,28.125,13.293,43.945c0,12.452-2.527,23.367-7.581,32.739c-5.054,9.376-11.062,17.469-18.018,24.279
                                                                    c-6.959,6.812-19.446,18.275-37.463,34.388c-4.981,4.542-8.975,8.535-11.975,11.976c-3.004,3.443-5.239,6.592-6.702,9.447
                                                                    c-1.466,2.857-2.603,5.713-3.406,8.57c-0.807,2.855-2.015,7.875-3.625,15.051c-2.784,15.236-11.501,22.852-26.147,22.852
                                                                    c-7.618,0-14.028-2.489-19.226-7.471c-5.201-4.979-7.8-12.377-7.8-22.192c0-12.305,1.902-22.962,5.713-31.97
                                                                    c3.808-9.01,8.861-16.92,15.161-23.73c6.296-6.812,14.794-14.904,25.488-24.28c9.373-8.202,16.15-14.392,20.325-18.567
                                                                    c4.175-4.175,7.69-8.823,10.547-13.953c2.856-5.126,4.285-10.691,4.285-16.699c0-11.718-4.36-21.605-13.074-29.663
                                                                    c-8.717-8.054-19.961-12.085-33.728-12.085c-16.116,0-27.981,4.065-35.596,12.195c-7.618,8.13-14.062,20.105-19.336,35.925
                                                                    c-4.981,16.555-14.43,24.829-28.345,24.829c-8.206,0-15.127-2.891-20.764-8.679C141.035,186.593,138.216,180.331,138.216,173.592z
                                                                    M245.442,414.412c-8.937,0-16.737-2.895-23.401-8.68c-6.667-5.784-9.998-13.877-9.998-24.279c0-9.229,3.22-16.991,9.668-23.291
                                                                    c6.444-6.297,14.354-9.448,23.73-9.448c9.229,0,16.991,3.151,23.291,9.448c6.296,6.3,9.448,14.062,9.448,23.291
                                                                    c0,10.255-3.296,18.312-9.888,24.17C261.7,411.481,254.084,414.412,245.442,414.412z" />
                                </svg>
                                <div class="question_popup p-4">
                                    {!! $question->question_info !!}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Attachments Section -->
                    <div class="question-media-frame mb-3">
                        @if ($question->file_type === 'image')
                            <div style="text-align: left" {{ $question->question_required ? 'data-required=1' : '' }}>
                                <img src="{{ $question->file_url }}" alt="Attachment Image">
                            </div>
                        @elseif($question->file_type === 'image_url')
                            <div style="text-align: left" {{ $question->question_required ? 'data-required=1' : '' }}>
                                <img src="{{ $question->url }}" alt="External Attachment Image">
                            </div>
                        @elseif($question->file_type === 'youtube')
                            <iframe src="https://www.youtube.com/embed/{{ $question->url }}" frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen {{ $question->question_required ? 'data-required="1"' : '' }}>
                            </iframe>
                        @elseif($question->file_type === 'video')
                            <div style="margin: auto" {{ $question->question_required ? 'data-required=1' : '' }}>
                                <video controls>
                                    <source src="{{ $question->file_url }}" type="video/mp4">
                                </video>
                            </div>
                        @endif
                    </div>

                    <!-- Dynamic Answers Input Fields -->
                    @if ($question->type === 'file')
                        <div class="d-flex flex-wrap align-items-start checkbox-image-container"
                            {{ $question->question_required ? 'data-required=1' : '' }}>
                            @foreach ($question->answers as $key => $answer)
                                <div class="custom-control custom-checkbox checkbox-image">
                                    <input type="checkbox" class="form-check-input" value="{{ $answer->id }}"
                                        name="answers[{{ $question->id }}][]"
                                        {{ isset($answers[$question->id]) && in_array_field($answer->id, 'answer_id', $answers[$question->id]) ? 'checked' : '' }}
                                        data-id="{{ $question->id }}" id="customCheck{{ $answer->id }}">
                                    <label class="custom-control-label" for="customCheck{{ $answer->id }}">
                                        <a href="{{ $answer->file_url ?? $answer->url }}" class="imageZoom"
                                            data-target="#imageZoomModal" data-toggle="modal">
                                            <img src="{{ $answer->file_url ?? $answer->url }}" alt="Preview" />
                                        </a>
                                    </label>
                                </div>
                            @endforeach
                            <small class="text-danger error d-none">This field is required</small>
                        </div>
                    @elseif($question->type === 'multiple')
                        <div {{ $question->question_required ? 'data-required=1' : '' }}>
                            @foreach ($question->answers as $key => $answer)
                                <div class="form-check custom-control">
                                    <input class="form-check-input" type="checkbox" name="answers[{{ $question->id }}][]"
                                        {{ isset($answers[$question->id]) && in_array_field($answer->id, 'answer_id', $answers[$question->id]) ? 'checked' : '' }}
                                        id="exampleRadios{{ $answer->id }}" data-id="{{ $question->id }}"
                                        value="{{ $answer->id }}">
                                    <label class="form-check-label custom-control-label"
                                        for="exampleRadios{{ $answer->id }}">
                                        {{ $answer->title }}
                                    </label>
                                </div>
                            @endforeach
                            <small class="text-danger error d-none">This field is required</small>
                        </div>
                    @elseif($question->type === 'dropdown')
                        <div {{ $question->question_required ? 'data-required=1' : '' }}>
                            <div class="form-group">
                                <select class="form-select custom-select" name="answers[{{ $question->id }}]"
                                    data-id="{{ $question->id }}" aria-label="select">
                                    <option value="">Option...</option>
                                    @foreach ($question->answers as $answer)
                                        <option value="{{ $answer->id }}"
                                            {{ Arr::exists($answers, $question->id) && $answers[$question->id][0]['answer_id'] == $answer->id ? 'selected' : '' }}>
                                            {{ $answer->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-danger error d-none">This field is required</small>
                        </div>
                    @elseif($question->type === 'radio')
                        <div {{ $question->question_required ? 'data-required=1' : '' }}>
                            @foreach ($question->answers as $answer)
                                <div class="form-check custom-control">
                                    <input class="form-check-input" type="radio" name="answers[{{ $question->id }}]"
                                        {{ Arr::exists($answers, $question->id) && $answers[$question->id][0]['answer_id'] == $answer->id ? 'checked' : '' }}
                                        id="exampleRadios{{ $answer->id }}" data-id="{{ $question->id }}"
                                        value="{{ $answer->id }}">
                                    <label class="form-check-label custom-control-label"
                                        for="exampleRadios{{ $answer->id }}">
                                        {{ __('quiz.' . $answer->title) }}
                                    </label>
                                </div>
                            @endforeach
                            <small class="text-danger error d-none">This field is required</small>
                        </div>
                    @elseif($question->type === 'text')
                        <div class="form-group" {{ $question->question_required ? 'data-required=1' : '' }}>
                            <input type="text" class="form-control text" name="answers[{{ $question->id }}][text]"
                                value="{{ Arr::exists($answers, $question->id) ? $answers[$question->id][0]['text'] : '' }}"
                                data-id="{{ $question->id }}" aria-label="input">
                            <small class="text-danger error d-none">This field is required</small>
                        </div>
                    @elseif($question->type === 'textarea')
                        <div class="form-group" {{ $question->question_required ? 'data-required=1' : '' }}>
                            <textarea class="form-control textarea" name="answers[{{ $question->id }}][text]" rows="3"
                                data-id="{{ $question->id }}" aria-label="textarea">{{ Arr::exists($answers, $question->id) ? $answers[$question->id][0]['text'] : '' }}</textarea>
                            <small class="text-danger error d-none">This field is required</small>
                        </div>
                    @elseif($question->type === 'circling')
                        <div class="circling" {{ $question->question_required ? 'data-required=1' : '' }}>
                            <div class="d-flex flex-wrap mb-3">
                                @foreach ($question->answers as $key => $answer)
                                    @php
                                        $checked = false;
                                    @endphp
                                    @foreach ($answer->quiz_answer()->where('user_quiz_id', $user_quiz_id)->get() as $item)
                                        @php
                                            if ($item) {
                                                $checked = true;
                                            }
                                        @endphp
                                    @endforeach
                                    <div style="margin-right: 40px" class="form-check custom-control">
                                        <input class="form-check-input" type="checkbox"
                                            name="answers[{{ $question->id }}][options][]" data-type="circling"
                                            {{ $checked ? 'checked' : '' }} id="exampleRadios{{ $answer->id }}"
                                            data-id="{{ $question->id }}" value="{{ $answer->id }}"
                                            data-is-not-empty="{{ isset($answers[$question->id]) && in_array_field($answer->id, 'answer_id', $answers[$question->id]) ? '1' : '' }}">
                                        <label class="form-check-label custom-control-label"
                                            for="exampleRadios{{ $answer->id }}">
                                            {{ $answer->title }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <div class="form-group textarea">
                                <textarea class="form-control textarea" name="answers[{{ $question->id }}][comment]" rows="3"
                                    data-id="{{ $question->id }}" data-type="circling_text" placeholder="Explain your selections..."></textarea>
                                <small class="text-danger error d-none">This field is required</small>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection
