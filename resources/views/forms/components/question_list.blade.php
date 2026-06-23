<section>
    <div id="survey-progress" class="mb-4" style="display: none;">
        <div class="progress-bar-container">
            <div id="progress-bar-fill" class="progress-bar-fill" style="width: {{ $percent }}%;"></div>
            <span id="progress-bar-text" class="progress-bar-text">{{ $percent }}% Complete</span>
        </div>
    </div>
    <div class="assessment-bar">
        <div class="row align-items-center">
            <!-- Value -->
            <div class="col-md-4 mb-3 mb-md-0 border-end border-md-end-none">
                <div class="assessment-col">
                    <span class="assessment-title">Value</span>
                    <div class="custom-radio-group">
                        <label class="custom-radio-label">
                            <input type="radio" name="numbers" value="High" class="d-none">
                            <span class="custom-radio-circle"></span> High
                        </label>
                        <label class="custom-radio-label">
                            <input type="radio" name="numbers" value="Medium" class="d-none">
                            <span class="custom-radio-circle"></span> Medium
                        </label>
                        <label class="custom-radio-label">
                            <input type="radio" name="numbers" value="Low" class="d-none">
                            <span class="custom-radio-circle"></span> Low
                        </label>
                    </div>
                </div>
            </div>
            <!-- Effort -->
            <div class="col-md-4 mb-3 mb-md-0 border-end border-md-end-none">
                <div class="assessment-col">
                    <span class="assessment-title">Effort</span>
                    <div class="custom-radio-group">
                        <label class="custom-radio-label">
                            <input type="radio" name="effort" value="High" class="d-none">
                            <span class="custom-radio-circle"></span> High
                        </label>
                        <label class="custom-radio-label">
                            <input type="radio" name="effort" value="Medium" class="d-none">
                            <span class="custom-radio-circle"></span> Medium
                        </label>
                        <label class="custom-radio-label">
                            <input type="radio" name="effort" value="Low" class="d-none">
                            <span class="custom-radio-circle"></span> Low
                        </label>
                    </div>
                </div>
            </div>
            <!-- Priority -->
            <div class="col-md-4">
                <div class="assessment-col">
                    <span class="assessment-title">Priority</span>
                    <div class="custom-radio-group">
                        <label class="custom-radio-label">
                            <input type="radio" name="priority" value="High" class="d-none">
                            <span class="custom-radio-circle"></span> High
                        </label>
                        <label class="custom-radio-label">
                            <input type="radio" name="priority" value="Medium" class="d-none">
                            <span class="custom-radio-circle"></span> Medium
                        </label>
                        <label class="custom-radio-label">
                            <input type="radio" name="priority" value="Low" class="d-none">
                            <span class="custom-radio-circle"></span> Low
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{--  Question List --}}
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
                        allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen
                        {{ $question->question_required ? 'data-required="1"' : '' }}>
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
                        <select class="form-select border border-primary custom-select"
                            name="answers[{{ $question->id }}]" data-id="{{ $question->id }}" aria-label="select">
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
                    <input type="text" class="form-control border border-primary text"
                        name="answers[{{ $question->id }}][text]"
                        value="{{ Arr::exists($answers, $question->id) ? $answers[$question->id][0]['text'] : '' }}"
                        data-id="{{ $question->id }}" aria-label="input">
                    <small class="text-danger error d-none">This field is required</small>
                </div>
            @elseif($question->type === 'textarea')
                <div class="form-group" {{ $question->question_required ? 'data-required=1' : '' }}>
                    <textarea class="form-control border border-primary textarea" name="answers[{{ $question->id }}][text]"
                        rows="3" data-id="{{ $question->id }}" aria-label="textarea">{{ Arr::exists($answers, $question->id) ? $answers[$question->id][0]['text'] : '' }}</textarea>
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
                        <textarea class="form-control border border-primary textarea" name="answers[{{ $question->id }}][comment]"
                            rows="3" data-id="{{ $question->id }}" data-type="circling_text"
                            placeholder="Explain your selections..."></textarea>
                        <small class="text-danger error d-none">This field is required</small>
                    </div>
                </div>
            @endif
        </div>
    @endforeach

    <!-- SPREAD Interactive Completed Laravel Form -->
    <form class="quiz" action="{{ route('forms.quiz-complete', $participant['participantID']) }}" method="post"
        id="dane_form">
        @csrf
        @method('PATCH')

        <div class="d-flex flex-column flex-md-row quiz-ipt">
            <!-- Left Form Section -->
            <div class="quiz-ipt-left">
                <div class="form-group mb-3">
                    <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Oil Co Focal Point</label>
                    <input class="quiz-ipt-each form-control border border-primary" type="text" name="focal_point"
                        placeholder="Oil Co Focal Point (e.g. Drilling Engr.)"
                        value="{{ $quizReport ? $quizReport->focal_point : '' }}">
                </div>

                <div class="form-group mb-3">
                    <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Actual Actionee</label>
                    <input class="quiz-ipt-each form-control border border-primary" type="text"
                        name="action_party" value="{{ $quizReport ? $quizReport->action_party : '' }}"
                        placeholder="Actual Actionee (e.g. Service Co rep.)">
                </div>

                <!-- Interactive Self Verification Checks -->
                <div class="self-verification d-flex flex-column">
                    <div class="py-2 fw-bold text-dark" style="font-size: 0.9rem;">Self Verification Checks</div>
                    <div class="d-flex flex-column flex-sm-row gap-3">
                        <div class="d-flex flex-column w-100 w-sm-50">
                            <div class="self-verification-option">
                                <input class="custom-control-input" type="checkbox" name="is_verification_1"
                                    id="is_verification_1">
                                <label class="form-check-label" for="is_verification_1">
                                    {{ $quiz->verification_text_1 ?? 'Verification Item 1: Complete and Checked' }}
                                </label>
                            </div>
                            <div class="self-verification-option">
                                <input class="custom-control-input" type="checkbox" name="is_verification_2"
                                    id="is_verification_2">
                                <label class="form-check-label" for="is_verification_2">
                                    {{ $quiz->verification_text_2 ?? 'Verification Item 2: QA Approved' }}
                                </label>
                            </div>
                            <div class="self-verification-option">
                                <input class="custom-control-input" type="checkbox" name="is_verification_3"
                                    id="is_verification_3">
                                <label class="form-check-label" for="is_verification_3">
                                    {{ $quiz->verification_text_3 ?? 'Verification Item 3: Stakeholders Informed' }}
                                </label>
                            </div>
                        </div>
                        <div class="d-flex flex-column w-100 w-sm-50">
                            <div class="self-verification-option">
                                <input class="custom-control-input" type="checkbox" name="is_verification_4"
                                    id="is_verification_4">
                                <label class="form-check-label" for="is_verification_4">
                                    {{ $quiz->verification_text_4 ?? 'Verification Item 4: Compliance Verified' }}
                                </label>
                            </div>
                            <div class="self-verification-option">
                                <input class="custom-control-input" type="checkbox" name="is_verification_5"
                                    id="is_verification_5">
                                <label class="form-check-label" for="is_verification_5">
                                    {{ $quiz->verification_text_5 ?? 'Verification Item 5: Final Submission Ready' }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Form Section -->
            <div class="quiz-ipt-right">
                <div class="form-group mb-3">
                    <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">And by when (Target
                        Date):</label>
                    <input class=" quiz-ipt-each form-control border border-primary" name="target_date"
                        type="date" autocomplete="off" placeholder="And by when:"
                        value="{{ $quizReport ? $quizReport->target_date : '' }}">
                </div>

                <div class="form-group mb-3">
                    <label class="form-label text-muted mb-1" style="font-size: 0.75rem;">Lead Business
                        Partner</label>
                    <input class="quiz-ipt-each form-control border border-primary" type="text"
                        name="business_partner" value="{{ $quizReport ? $quizReport->business_partner : '' }}"
                        placeholder="Lead Business Partner">
                </div>
            </div>
        </div>

        <!-- Dynamic Laravel Parameter Fields -->
        <div class="d-none" id="hidden_inp"></div>
        <div class="d-none" id="hidden_inp_effort"></div>
        <div class="d-none" id="hidden_inp_priority"></div>
        <div class="d-none" id="group"></div>

        <!-- Error Banner -->
        <div class="text-center mt-3 form-error" style="display: none">
            <small class="text-danger fw-bold" style="font-size: 16px;">Please complete all required fields and verify
                checks to continue.</small>
        </div>

        <!-- Action Submissions Bar -->
        <div class="text-center mt-4">
            <button type="submit" id="form_submit_button" class="btn btn-spread-submit px-5 py-3 done">
                Submit Survey
            </button>
        </div>
    </form>
</section>


{{-- <section class="questioner mt-5 mb-5">

    <div class="time-progress w-100">
        <div class="time-progress-container">
            <div class="w3-light-grey w3-round-xlarge parent-progress">
                <div class="w3-container w3-blue w3-round-xlarge progress-bar"
                    style="width:{{ $percent }}%; background-color: #524a90 !important;">{{ $percent }}%
                </div>
            </div>
        </div>
    </div>
    <div class="mt-2 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between ">
                    <div class="text-center mr-md-5 d-flex flex-column align-items-center">
                        <div class="text-center quiz-checkbox-name">
                            {{ __('Value') }}
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="custom-radio">
                                <input id="hard" type="radio" name="numbers" class="status_radio radio-high"
                                    value="high">
                                <label class="hard-color" for="hard">High</label>
                                <input id="medium" type="radio" name="numbers" class="status_radio radio-medium"
                                    value="medium">
                                <label class="medium-color" for="medium">Medium</label>
                                <input id="low" type="radio" name="numbers" class="status_radio radio-low"
                                    value="low">
                                <label class="low-color" for="low">Low</label>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mr-md-5 d-flex flex-column align-items-center">
                        <div class="text-center quiz-checkbox-name">
                            {{ __('Effort') }}
                        </div>
                        <div class="d-flex align-items-center d-flex flex-column align-items-center">
                            <div class="custom-radio">
                                <input id="hard2" type="radio" name="effort" value="high"
                                    class="status_radio_effort radio-high">
                                <label class="hard-color" for="hard2">High</label>
                                <input id="medium2" type="radio" name="effort" value="medium"
                                    class="status_radio_effort radio-medium">
                                <label class="medium-color" for="medium2">Medium</label>
                                <input id="low2" type="radio" name="effort" value="low"
                                    class="status_radio_effort radio-low">
                                <label class="low-color" for="low2">Low</label>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mr-0">
                        <div class="text-center quiz-checkbox-name">
                            {{ __('Priority') }}
                        </div>
                        <div class="d-flex align-items-center d-flex flex-column align-items-center">
                            <div class="custom-radio">
                                <input id="hard3" type="radio" name="priority" value="high"
                                    class="radio_priority radio-high">
                                <label class="hard-color" for="hard3">High</label>
                                <input id="medium3" type="radio" name="priority" value="medium"
                                    class="radio_priority radio-medium">
                                <label class="medium-color" for="medium3">Medium</label>
                                <input id="low3" type="radio" name="priority" value="low"
                                    class="radio_priority radio-low">
                                <label class="low-color" for="low3">Low</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="quiz-container">
        @foreach ($quiz->questions as $question)
            <div class="quiz-block">
                <div class="d-flex justify-content-between align-items-start">
                    <h4 class="quiz-title mt-0">{{ $loop->index + 1 }}. {{ $question->title }}</h4>
                    @if ($question->question_info)
                        <div class="position-relative question_info">
                            <svg width="25" version="1.1" class="" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                viewBox="0 0 496.158 496.158"
                                style="enable-background:new 0 0 496.158 496.158; cursor: pointer"
                                xml:space="preserve">
                                <path style="fill:#25B7D3;" d="M496.158,248.085c0-137.022-111.069-248.082-248.075-248.082C111.07,0.003,0,111.063,0,248.085
         c0,137.001,111.07,248.07,248.083,248.07C385.089,496.155,496.158,385.086,496.158,248.085z" />
                                <path style="fill:#FFFFFF;" d="M138.216,173.592c0-13.915,4.467-28.015,13.403-42.297c8.933-14.282,21.973-26.11,39.111-35.486
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
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                                <g>
                                </g>
                            </svg>
                            <div class="question_popup p-4">
                                {!! $question->question_info !!}
                            </div>
                        </div>
                    @endif
                </div>


                <div style="margin-bottom: 1.5em">
                    @if ($question->file_type === 'image')
                        <div style="text-align: left" {{ $question->question_required ? 'data-required=1' : '' }}>
                            <img style="max-width:100%; max-height: 300px;" src="{{ $question->file_url }}"
                                alt="img">
                        </div>
                    @elseif($question->file_type === 'image_url')
                        <div style="text-align: left" {{ $question->question_required ? 'data-required=1' : '' }}>
                            <img style="max-width:100%; max-height: 300px;" src="{{ $question->url }}"
                                alt="img">
                        </div>
                    @elseif($question->file_type === 'youtube')
                        <iframe style="width:100%; height: 540px;"
                            src="https://www.youtube.com/embed/{{ $question->url }}" frameborder="0"
                            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen {{ $question->question_required ? 'data-required="1"' : '' }}>
                        </iframe>
                    @elseif($question->file_type === 'video')
                        <div style="margin: auto" {{ $question->question_required ? 'data-required=1' : '' }}>
                            <video style="width: 100%;max-height: 67vh;" controls>
                                <source src="{{ $question->file_url }}" type="video/mp4">
                            </video>
                        </div>
                    @endif
                </div>

                @if ($question->type === 'file')
                    <div class="d-flex flex-wrap align-items-start checkbox-image-container"
                        {{ $question->question_required ? 'data-required=1' : '' }}>
                        @foreach ($question->answers as $key => $answer)
                            <div class=" custom-control custom-checkbox checkbox-image">
                                <input type="checkbox" class="custom-control-input" value="{{ $answer->id }}"
                                    {{ isset($answers[$question->id]) && in_array_field($answer->id, 'answer_id', $answers[$question->id]) ? 'checked' : '' }}
                                    data-id="{{ $question->id }}" id="customCheck{{ $answer->id }}">
                                <label class="custom-control-label" for="customCheck{{ $answer->id }}">
                                    <a href="{{ $answer->file_url ?? $answer->url }}" class="imageZoom"
                                        data-target="#imageZoomModal" data-toggle="modal">
                                        <img src="{{ $answer->file_url ?? $answer->url }}" alt="Random" />
                                    </a>
                                </label>
                            </div>
                        @endforeach
                        <small class="text-danger error" style="display: none;">The field is required</small>
                    </div>
                @elseif($question->type === 'multiple')
                    <div {{ $question->question_required ? 'data-required=1' : '' }}>
                        @foreach ($question->answers as $key => $answer)
                            <div class=" custom-control custom-checkbox">
                                <input class="custom-control-input custom-checkbox" type="checkbox" name=""
                                    {{ isset($answers[$question->id]) && in_array_field($answer->id, 'answer_id', $answers[$question->id]) ? 'checked' : '' }}
                                    id="exampleRadios{{ $answer->id }}" data-id="{{ $question->id }}"
                                    value="{{ $answer->id }}">
                                <label class="custom-control-label" for="exampleRadios{{ $answer->id }}">
                                    {{ $answer->title }}
                                </label>

                            </div>
                        @endforeach
                        <small class="text-danger error" style="display: none">The fields is
                            required</small>
                    </div>
                @elseif($question->type === 'dropdown')
                    <div {{ $question->question_required ? 'data-required=1' : '' }}>
                        <div class="form-group">
                            <select class="custom-select" data-id="{{ $question->id }}" aria-label="select">
                                <option value="">{{ __('Option') }}...</option>
                                @foreach ($question->answers as $answer)
                                    <option value="{{ $answer->id }}"
                                        {{ Arr::exists($answers, $question->id) && $answers[$question->id][0]['answer_id'] == $answer->id ? 'selected' : '' }}>
                                        {{ $answer->title }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                        <small class="text-danger error" style="display: none">The fields is required</small>
                    </div>
                @elseif($question->type === 'radio')
                    <div {{ $question->question_required ? 'data-required=1' : '' }}>
                        @foreach ($question->answers as $answer)
                            <div class=" custom-control custom-radio">
                                <input class="custom-control-input" type="radio" name="radio"
                                    {{ Arr::exists($answers, $question->id) && $answers[$question->id][0]['answer_id'] == $answer->id ? 'checked' : '' }}
                                    id="exampleRadios{{ $answer->id }}" data-id="{{ $question->id }}"
                                    value="{{ $answer->id }}">
                                <label class="custom-control-label" for="exampleRadios{{ $answer->id }}">
                                    {{ __('quiz.' . $answer->title) }}
                                </label>
                            </div>
                        @endforeach
                        <small class="text-danger error" style="display: none">The field is required</small>
                    </div>
                @elseif($question->type === 'text')
                    <div class="form-group" {{ $question->question_required ? 'data-required=1' : '' }}>
                        <input type="text" class="form-control border border-primary text"
                            value="{{ Arr::exists($answers, $question->id) ? $answers[$question->id][0]['text'] : '' }}"
                            data-id="{{ $question->id }}" aria-describedby="" aria-label="input">
                        <small class="text-danger error" style="display: none">The field is required</small>
                    </div>
                @elseif($question->type === 'textarea')
                    <div class="form-group" {{ $question->question_required ? 'data-required=1' : '' }}>
                        <textarea class="form-control border border-primary textarea" rows="3" data-id="{{ $question->id }}" aria-label="textarea">{{ Arr::exists($answers, $question->id) ? $answers[$question->id][0]['text'] : '' }}</textarea>
                        <small class="text-danger error" style="display: none">The field is required</small>
                    </div>
                @elseif($question->type === 'circling')
                    <div class="circling" {{ $question->question_required ? 'data-required=1' : '' }}>
                        <div class="d-flex">
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
                                <div style="margin-right: 40px" class=" custom-control custom-checkbox">
                                    <input class="custom-control-input custom-checkbox" type="checkbox"
                                        name="dd{{ $question->id }}" data-type="circling"
                                        {{ $checked ? 'checked' : '' }} id="exampleRadios{{ $answer->id }}"
                                        data-id="{{ $question->id }}" value="{{ $answer->id }}"
                                        data-is-not-empty="{{ isset($answers[$question->id]) && in_array_field($answer->id, 'answer_id', $answers[$question->id]) ? '1' : '' }}">
                                    <label class="custom-control-label" for="exampleRadios{{ $answer->id }}">
                                        {{ $answer->title }}
                                    </label>
                                </div>
                            @endforeach

                        </div>
                        <div class="form-group textarea">
                            <textarea class="form-control border border-primary textarea" rows="3" data-id="{{ $question->id }}" data-type="circling_text">{{ Arr::exists($answers, $question->id) ? $answers[$question->id][0]['text'] : '' }}</textarea>
                            <small class="text-danger error" style="display: none">The fields is
                                required</small>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach

        <form class="quiz" action="{{ route('forms.quiz-complete', $participant['participantID']) }}"
            method="post" id="dane_form">
            @csrf
            @method('PATCH')
            <div class="d-flex quiz-ipt">
                <div class="quiz-ipt-left">
                    <input class="quiz-ipt-each form-control border border-primary" type="text" name="focal_point"
                        placeholder="Oil Co Focal Point (e.g. Drilling Engr.)">
                    <input class="quiz-ipt-each form-control border border-primary" type="text" name="action_party"
                        placeholder="Actual Actionee (e.g. Service Co rep.)">
                    <div class="self-verification d-flex flex-column">
                        <div class="py-2">Self Verification</div>
                        <div class="d-flex flex-row">
                            <div class="d-flex flex-column w-50">
                                <div class="self-verification-option">
                                    <input class="custom-checkbox" type="checkbox" name="is_verification_1"
                                        id="is_verification_1">
                                    <label class="" for="is_verification_1">
                                        {{ $quiz->verification_text_1 }}
                                    </label>
                                </div>
                                <div class="self-verification-option">
                                    <input class="custom-checkbox" type="checkbox" name="is_verification_2"
                                        id="is_verification_2">
                                    <label class="" for="is_verification_2">
                                        {{ $quiz->verification_text_2 }}
                                    </label>
                                </div>
                                <div class="self-verification-option">
                                    <input class="custom-checkbox" type="checkbox" name="is_verification_3"
                                        id="is_verification_3">
                                    <label class="" for="is_verification_3">
                                        {{ $quiz->verification_text_3 }}
                                    </label>
                                </div>
                            </div>
                            <div class="d-flex flex-column w-50">
                                <div class="self-verification-option">
                                    <input class="custom-checkbox" type="checkbox" name="is_verification_4"
                                        id="is_verification_4">
                                    <label class="" for="is_verification_4">
                                        {{ $quiz->verification_text_4 }}
                                    </label>
                                </div>
                                <div class="self-verification-option">
                                    <input class="custom-checkbox" type="checkbox" name="is_verification_5"
                                        id="is_verification_5">
                                    <label class="" for="is_verification_5">
                                        {{ $quiz->verification_text_5 }}
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="quiz-ipt-right">
                    <input class="datepicker quiz-ipt-each form-control border border-primary" name="target_date" type="text"
                        autocomplete="off" placeholder="And by when:">
                    <input class="quiz-ipt-each form-control border border-primary" type="text" name="business_partner"
                        placeholder="Lead Business Partner">
                </div>
            </div>
            <div class="d-none" id="hidden_inp"></div>
            <div class="d-none" id="hidden_inp_effort"></div>
            <div class="d-none" id="hidden_inp_priority"></div>
            <div class="d-none" id="group"></div>
            <div class="text-center mt-3 form-error" style="display: none">
                <small class="text-danger " style="font-size: 20px">Please answer all questions</small>
            </div>

            <div class="text-center mt-4">
                <button type="submit" id="form_submit_button"
                    class="btn my-btn text-uppercase done">{{ __('Done') }}</button>
            </div>
        </form>
    </div>

</section> --}}
