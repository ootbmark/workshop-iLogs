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
    <div class="survey-header-section">
        <h1 class="survey-title"><b>{{ $quiz->title }}</b></h1>
        <p class="survey-subtitle">
            {{ $quiz->description }}
        </p>
    </div>
    <div class="survey-card">
        <div class="report-header">
            <div>
                <h2>{{ $quizReport->name }}</h2>
                <small class="text-muted font-mono">{{ $quizReport->group->name }}</small>
            </div>

            <a type="button" class="edit-icon-btn" id="toggle-edit-btn" title="Edit Report"
                href="{{ route('forms.scribes-edit', [base64_encode($quiz->quiz_code), base64_encode($quizReport->id)]) }}">
                <i class="bi bi-pencil-fill"></i>
            </a>
        </div>

        <div id="survey-qa-table">
            <!-- Column Labels Header -->
            <div class="report-table-header border-bottom">
                <span style="flex: 1;">Question</span>
                <span style="width: 180px;">Answer</span>
            </div>
            @foreach ($quizAnswerList as $item)
                <div class="report-row border-bottom">
                    <div class="report-question" id="q-1-text">{{ $item['question'] }}</div>
                    <div class="report-answer" id="a-1-container">
                        <span id="a-1-text" class="text-display">{{ $item['answer'] }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="report-footer">
            <div class="row g-4">
                <!-- Column 1: Oil Co Focal Point -->
                <div class="col-md-4 footer-col">
                    <span class="footer-label">Oil Co Focal Point:</span>
                    <div id="focal-container">
                        <span id="focal-value" class="footer-value">{{ $quizReport->focal_point }}</span>
                    </div>
                </div>

                <!-- Column 2: Actual Actionee -->
                <div class="col-md-4 footer-col border-md-start border-md-end">
                    <span class="footer-label">Actual Action:</span>
                    <div id="actionee-container">
                        <span id="actionee-value" class="footer-value">{{ $quizReport->action_party }}</span>
                    </div>
                </div>

                <!-- Column 3: Target Date -->
                <div class="col-md-4 footer-col">
                    <span class="footer-label">And by when:</span>
                    <div id="date-container">
                        <span id="date-value" class="footer-value">{{ $quizReport->target_date }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
