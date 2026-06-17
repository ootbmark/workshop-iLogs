@extends('layouts.app')
@section('add-css')
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"
        integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"
        integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ=="
        crossorigin="anonymous"></script>



    <link href="/css/crop.css" rel="stylesheet">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link href="/metronic/css/plugins/jquery.dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="/metronic/css/style.bundle.css" rel="stylesheet" type="text/css">
    <link href="/metronic/css/custom.css" rel="stylesheet" type="text/css">
    <link href="/metronic/css/plugins/plugins.bundle.css" rel="stylesheet" type="text/css">
    <link href="{{ mix('css/profile.css') }}" rel="stylesheet">
    <style>
        .select2 {
            display: block;
        }

        #import_modal .select2-selection__arrow:before,
        #select--company_id .select2-selection .select2-selection__arrow:before {
            position: relative;
            top: 12px;
        }

        #import_modal .select2-selection__rendered,
        #select--company_id .select2-selection .select2-selection__rendered {
            padding-top: 0;
            padding-bottom: 0;
        }

        .modal .modal-content .modal-header .close:before {
            display: none;
        }

        body,
        html {
            font-size: 18px !important;
            line-height: 1.25 !important;
            font-family: WebFont, sans-serif, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji !important;
        }

        html {
            font-size: 16px !important;
        }

        .btn-import {
            background-color: #30b741;
            border-color: #30b741;
            color: #fff;
        }

        .btn-import:hover {
            background-color: #279b36;
            border-color: #279b36;
            color: #fff;
        }

        table.dataTable {
            width: 100% !important;
        }

        table.dataTable th,
        table.dataTable td {
            vertical-align: middle;
            white-space: nowrap;
        }

        /* Actions column */
        table.dataTable td:last-child {
            min-width: 120px;
        }

        /* Long title/company text */
        .text-truncate-column {
            max-width: 250px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        /* Mobile */
        @media (max-width: 768px) {

            table.dataTable th,
            table.dataTable td {
                font-size: 14px;
            }
        }
    </style>
@endsection

@section('content')
    @include('profile._sidebar')

    <div class="profile-content">
        @include('dashboard._forms_navbar')

        <div class="discussions-container ml-0 mt-4 p-0">
            <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">

                <!-- Modal -->
                <div class="modal fade" tabindex="-1" id="import_modal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Import</h5>
                            </div>
                            <form class="modal-body p-4" id="import_form" action="{{ route('quiz.import_for_form') }}"
                                method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="select_form">Select form:</label>
                                    <select name="quiz_id" id="select_form" class="form-control validatable">
                                        <option disabled selected value=""></option>
                                        @foreach ($forms as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="select_file">Select file:</label>
                                    <input type="file" name="import_file" id="select_file"
                                        class="form-control-file validatable">
                                </div>
                            </form>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="submit_import_modal">
                                    Submit
                                    <span class="spinner-border spinner-border-sm d-none" role="status"
                                        aria-hidden="true"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" tabindex="-1" id="imported_modal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-body text-center p-4">
                                <p id="imported_modal_message"></p>
                                <button type="button" class="btn my-btn mt-3" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="quizClone" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Clone</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    {!! Form::label('title', 'Title') !!}
                                    {!! Form::text('title', null, ['class' => 'form-control']) !!}
                                    <span class="form-text text-danger" id="title_error"></span>
                                    {!! $errors->first('title', '<span class="form-text text-danger">:message</span>') !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('description', 'Description') !!}
                                    {!! Form::textarea('description', null, ['class' => 'form-control', 'style="height: 58px"']) !!}
                                    <span class="form-text text-danger" id="description_error"></span>
                                    {!! $errors->first('description', '<span class="form-text text-danger">:message</span>') !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('time_limit', 'Workshop Date') !!}
                                    {{--                            {!! Form::date('time_limit', null, ['class' => 'form-control']) !!} --}}
                                    {{--                            <input type="date" class="form-control " pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"> --}}
                                    <input class="datepicker-forms quiz-ipt-each form-control" name="time_limit"
                                        type="text" autocomplete="off" placeholder="And by when:">
                                    <span class="form-text text-danger" id="time_limit_error"></span>
                                    {!! $errors->first('time_limit', '<span class="form-text text-danger">:message</span>') !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('groups_ids', 'Groups') !!}
                                    {!! Form::select('groups_ids[]', $groups, null, ['class' => 'form-control', 'id' => 'groups_ids']) !!}
                                    {!! $errors->first('teacher_ids', '<p style="color:red" class="help-block">:message</p>') !!}
                                </div>
                                <div class="form-group" id="select--company_id">
                                    {!! Form::label('company_id', 'Company') !!}
                                    {!! Form::select('company_id', [null => 'none'] + $companies, null, [
                                        'class' => 'form-control',
                                        'id' => 'company_id',
                                    ]) !!}
                                    {!! $errors->first('company_id', '<p style="color:red" class="help-block">:message</p>') !!}
                                </div>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="quizCloneSaveBtn">Save Clone</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="kt-subheader  kt-grid__item" id="kt_subheader">
                    <div class="kt-container  kt-container--fluid align-items-center ">
                        <div class="kt-subheader__main">
                            <h3 class="kt-subheader__title">Forms</h3>
                            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                            <div class="kt-subheader__breadcrumbs">
                                {{-- <a href="{{route('dashboard')}}" class="kt-subheader__breadcrumbs-home"><i
                                        class="flaticon2-shelter"></i></a> --}}
                                <span class="kt-subheader__breadcrumbs-separator"></span>
                                <a href="{{ route('forms.index') }}" class="kt-subheader__breadcrumbs-link">
                                    Forms</a>
                            </div>
                        </div>
                        <div>
                            <span id="show_import_modal" class="btn btn-import">{{ __('Import Form') }}</span>
                            <a href="{{ route('quiz.exportPdf') }}"
                                class="btn btn-primary">{{ __('Export all forms to pdf') }}</a>
                            <a href="{{ route('quiz.export') }}"
                                class="btn btn-primary">{{ __('Export all forms to excel') }}</a>
                            <a href="{{ route('reports_export') }}"
                                class="btn btn-primary">{{ __('Export all reports to excel') }}</a>
                            {{-- <a href="{{ route('reports_answers') }}"
                               class="btn btn-primary">{{ __('Export all answers to excel') }}</a> --}}
                        </div>
                    </div>
                </div>

                <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="kt-portlet kt-portlet--mobile">
                                <div class="kt-portlet__head kt-portlet__head--lg">
                                    <div class="kt-portlet__head-label">
                                        <h3 class="kt-portlet__head-title">
                                            Forms
                                        </h3>
                                    </div>
                                    <div class="kt-portlet__head-toolbar">
                                        <div class="kt-portlet__head-wrapper">
                                            <div class="kt-portlet__head-actions">
                                                <a href="{{ route('forms.create') }}"
                                                    class="btn btn-brand btn-elevate btn-icon-sm">
                                                    <i class="flaticon2-plus"></i>
                                                    Create New Form
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="kt-portlet__body">
                                    @include('flash::message')
                                    <div
                                        class="kt-datatable kt-datatable--default kt-datatable--brand kt-datatable--loaded table-responsive">
                                        <table class="table table-striped table-bordered dataTable w-100">
                                            <thead>
                                                <tr>
                                                    <th>Type</th>
                                                    <th>Company</th>
                                                    <th>Facilitator</th>
                                                    <th>Number of Scribes</th>
                                                    <th>Workshop Date</th>
                                                    {{--  <th>Form Link</th> --}}
                                                    <th>Created On</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js"></script>
    <script src="/metronic/js/plugins/jquery.dataTables.min.js" type="text/javascript"></script>
    <script>
        $('#groups_ids').select2({
            multiple: true
        });
        $('#company_id').select2();

        $('#select_form').select2();

        $('.datepicker-forms').datepicker({
            format: 'dd MM yyyy',
        });

        var dataTable = $('.dataTable').DataTable({
            language: {
                search: '{{ __('Search') }}',
                processing: '{{ __('Processing') }}',
                lengthMenu: '{{ __('Show') }}' + " _MENU_",
                info: '{{ __('Showing') }}' + " _START_ " + '{{ __('to') }}' + " _END_ " +
                    '{{ __('of') }}' + " _TOTAL_ " + '{{ __('entries') }}',
                zeroRecords: '{{ __('No data available in table') }}'

            },
            serverSide: true,
            processing: true,
            responsive: true,
            aaSorting: [
                [5, "desc"]
            ],
            scrollX: true,
            ajax: "{{ route('quiz.dataTable') }}",
            columns: [
                // {name: 'id'},
                {
                    name: 'title'
                },
                {
                    name: 'company.name',
                    orderable: false,
                },
                // { name: 'description'},
                {
                    name: 'quiz_user.name',
                    orderable: false,
                    searchable: false,
                    render: function(name, type, row) {
                        if (name !== "N/A") {
                            return name
                        }
                        return ""
                    }
                },

                {
                    name: 'quiz_reports',
                    orderable: false,
                    searchable: false,
                    render: function(name, type, row) {
                        return name.length
                    }
                },
                {
                    name: 'time_limit',
                    searchable: false,
                    render: function(name, type, row) {
                        if (name) {
                            return moment(name).format('DD MMM YYYY')
                        }
                        return '';
                    }
                },
                /*  {
                     data: 'quiz_code',
                     orderable: false,
                     searchable: false
                 }, */
                {
                    name: 'created_at',
                    render: function(name, type, row) {
                        if (name) {
                            return moment(name).format('DD MMM YYYY')
                        }
                        return '';
                    }
                },
                {
                    name: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
            "columnDefs": [{
                    className: "white-space",
                    "targets": [5]
                },
                {
                    className: "white-space",
                    "targets": [4]
                },
            ]
        });

        $('.dataTable').on('draw.dt', function() {
            $('.quiz-clone').click(function() {
                quizInfo($(this).data('id'));
            });
        });

        function quizInfo(quiz_id) {
            $.ajax({
                type: 'get',
                url: `/dashboard/api-quiz/${quiz_id}`,
                success: function(data) {
                    let resp = data.data;
                    $('#quizClone input[name=title]').val(resp.title);
                    $('#quizClone textarea[name=description]').val(resp.description);
                    $('#quizClone input[name=time_limit]').val(moment(resp.time_limit).format('DD MMM YYYY'));
                    $('#groups_ids').val(resp.groups).trigger('change');
                    $('#company_id').val(resp.company_id).trigger('change');
                    $('#quizCloneSaveBtn').attr('data-id', resp.id);
                    $('#quizClone').modal('show')
                }
            })
        }

        $('#quizCloneSaveBtn').click(function() {
            let title = $('#quizClone input[name=title]').val();
            let description = $('#quizClone textarea[name=description]').val();
            let time_limit = $('#quizClone input[name=time_limit]').val();
            let groups_ids = $('#groups_ids').val();
            let company_id = $('#company_id').val();

            $.ajax({
                type: 'post',
                url: `/dashboard/quiz-clone/${$(this).data('id')}`,
                data: {
                    title: title,
                    description: description,
                    time_limit: time_limit,
                    groups_ids: groups_ids,
                    company_id: company_id,
                },
                success: function(data) {
                    $('#quizClone').modal('hide');
                    dataTable.ajax.reload();
                },
                error: function(error) {
                    let errors = error.responseJSON.errors;

                    if (errors.title) {
                        $('#title_error').html(errors.title[0]);
                        $('#title_error').show()
                    } else $('#title_error').hide();

                    if (errors.description) {
                        $('#description_error').html(errors.description[0]);
                        $('#description_error').show()
                    } else $('#description_error').hide();

                    if (errors.time_limit) {
                        $('#time_limit_error').html(errors.time_limit[0]);
                        $('#time_limit_error').show();
                    } else $('#time_limit_error').hide();
                }
            })

        })

        $('#show_import_modal').on('click', function() {
            $('#import_modal').modal('show');
        });

        $('#submit_import_modal').on('click', function() {
            const form = $('#import_form');
            const submitBtn = $(this);
            const spinner = submitBtn.find('.spinner-border');
            submitBtn.attr('disabled', true);
            spinner.removeClass('d-none');
            clearFormErrors(form);

            const data = new FormData(form[0]);

            $.ajax({
                url: form.attr('action'),
                method: form.attr('method'),
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'ACCEPT': 'application/json'
                },
                data: data,
                processData: false,
                contentType: false,
                success: data => {
                    spinner.addClass('d-none');
                    submitBtn.attr('disabled', false);
                    $('#import_modal').modal('hide');
                    $('#imported_modal #imported_modal_message').text(data.message);
                    $('#imported_modal').modal('show');
                },
                error: err => {
                    if (err?.responseJSON.errors) {
                        const errors = err.responseJSON.errors;

                        for (let key in errors) {
                            form.find(`.validatable[name=${key}]`).parent().append(`
                                <span class="invalid-feedback d-block" style="color:red" role="alert">
                                    <strong> ${errors[key][0]}</strong>
                                </span>
                            `)
                        }
                    }
                    spinner.addClass('d-none');
                    submitBtn.attr('disabled', false);
                }
            })
        });

        function clearFormErrors(parent) {
            parent.find('.invalid-feedback').remove();
        }
        $(document).on('click', '.btn-copy', function() {
            const code = $(this).data('link');
            navigator.clipboard.writeText(code)
                .then(() => {
                    alert('Copied: ' + code);
                })
                .catch(err => {
                    console.error('Failed to copy', err);
                });
        });
    </script>
    <style>
        .white-space {
            white-space: nowrap;
        }
    </style>
    @include('profile._avatar_scripts')
@endsection
