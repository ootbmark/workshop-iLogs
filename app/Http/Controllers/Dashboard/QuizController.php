<?php

namespace App\Http\Controllers\Dashboard;

use App\Exports\QuizAnswerExport;
use App\Exports\QuizExport;
use App\Exports\QuizReportExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Quiz\CreateQuizRequest;
use App\Http\Requests\Quiz\ImportFormRequest;
use App\Http\Requests\Quiz\UpdateQuizRequest;
use App\Http\Resources\QuizResource;
use App\Imports\Mapping\QuizMapping;
use App\Imports\QuizReportsImport;
use App\Models\Answer;
use App\Models\Company;
use App\Models\GroupForQuiz;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAnswer;
use App\Models\QuizReport;
use App\Models\User;
use App\Services\ReportAnswersCreatorService;
use Carbon\Carbon;
use Freshbitsweb\Laratables\Laratables;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class QuizController extends Controller
{
    /** @var ReportAnswersCreatorService */
    private $answerCreator;

    public function __construct(ReportAnswersCreatorService $answerCreator)
    {
        $this->answerCreator = $answerCreator;
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {

        $groups = GroupForQuiz::pluck('name', 'id')->toArray();
        $companies = Company::pluck('name', 'id')->toArray();
        $forms = Quiz::pluck('title', 'id')->toArray();

        return view('dashboard.quiz.index', [
            'groups' => $groups,
            'companies' => $companies,
            'forms' => $forms,
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('dashboard.quiz.create');
    }

    /**
     * @return JsonResource
     */
    public function retrieveAllQuizes(): JsonResource
    {
        $quizes = Quiz::orderBy('id', 'desc')->get();

        return QuizResource::collection($quizes);
    }

    /**
     * @param CreateQuizRequest $request
     * @return JsonResource
     */
    public function createQuiz(CreateQuizRequest $request): JsonResource
    {

        $data = $request->only([
            'title',
            'description',
            'time_limit',
            'groups_ids',
            'answer_by_one',
            'company_id',
        ]);

        $data['user_id'] = $request->user()->id;

        $data['is_active'] = false;

        $data['slug'] = Quiz::getSlug($request->title);

        $quiz = Quiz::create($data);

        if ($data['groups_ids']) {

            $data['groups_ids'] = explode(',', $data['groups_ids']);

            $quiz->groups()->sync($data['groups_ids']);
        }

        return QuizResource::make($quiz->refresh());
    }

    /**
     * @param UpdateQuizRequest $request
     * @param int $quiz_id
     * @return JsonResource
     */
    public function updateQuiz(UpdateQuizRequest $request, int $quiz_id): JsonResource
    {
        $quiz = $this->checkIfExistsQuiz($quiz_id);

        $data = $request->only([
            'title',
            'description',
            'time_limit',
            'answer_by_one',
            'groups_ids',
            'company_id',
        ]);

        if ($data['groups_ids']) {

            $data['groups_ids'] = explode(',', $data['groups_ids']);

            $quiz->groups()->sync($data['groups_ids']);
        }

        $quiz->update($data);

        return QuizResource::make($quiz->refresh());
    }

    /**
     * @param int $quiz_id
     * @return JsonResource
     */
    public function retrieveOneByID(int $quiz_id = null): JsonResource
    {
        $quiz = $this->checkIfExistsQuiz($quiz_id);

        return QuizResource::make($quiz);
    }

    /**
     * @param int $quiz_id
     * @return JsonResource
     */
    public function deleteQuiz(int $quiz_id): JsonResource
    {
        $quiz = $this->checkIfExistsQuiz($quiz_id);

        $quiz->delete();

        return JsonResource::make([
            'deleted' => true,
        ]);
    }

    /**
     * @param int $quiz_id
     * @return mixed
     */
    public function checkIfExistsQuiz(int $quiz_id)
    {
        $quiz = Quiz::find($quiz_id);

        if (!$quiz) {
            throw new NotFoundException();
        }

        return $quiz;
    }

    /**
     * @return array
     */
    public function dataTable()
    {
        return Laratables::recordsOf(Quiz::class, function ($query) {
            return $query->select('*');
        });
    }

    /**
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $quiz = Quiz::find($id);
        return view('dashboard.quiz.edit', [
            'quiz' => $quiz
        ]);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        Quiz::find($id)->delete();
        flash()->success(__('Form deleted!'));
        return redirect()->back();
    }

    /**
     * @param int $quiz_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function reports(int $quiz_id)
    {
        return view('dashboard.quiz.reports', [
            'id' => $quiz_id,
        ]);
    }


    /**
     * @param int $quiz_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function scribes()
    {
        $users = User::query()
            ->with('allowed_quizes:title')
            ->whereHas('quiz_reports')
            ->latest()
            ->paginate(m_per_page());

        $companies = Company::pluck('name')->toArray();

        return view('dashboard.quiz.scribes', compact('users', 'companies'));
    }


    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     */
    public function scribesDataTable(Request $request)
    {
        return DataTables::of(QuizReport::with('quiz_answers.answers', 'quiz_answers.answer', 'quiz', 'quiz.company')
            ->where('user_id', $request->get('user_id'))
            ->whereHas('quiz')
            ->latest())
            ->addColumn('group_number', function ($import) use (&$count, &$group, &$num) {
                if ($import->group_id) {
                    if ($group != $import->group_id) {
                        $count++;

                        $group = $import->group_id;

                        $num = 0;
                    }

                    $num++;

                    return $count . '.' . $num;
                } else return '';
            })
            ->addColumn('group_name', function ($import) {
                if ($import->group_id) return GroupForQuiz::find($import->group_id)->name;
                else return '';
            })
            ->addColumn('first_answer', function ($import) {
                if ($import->quiz_answers()->first()) {
                    return $answer = $import->quiz_answers()->first()->text ?? '';
                } else {
                    return $answer = $import->quiz_answers()->first()->answer->text ?? '';
                }
            })
            ->addColumn('quiz_title', function ($import) {

                return $import->quiz->title;
            })
            ->addColumn('company', function ($import) {
                return $import->quiz->company->name ?? null;
            })
            ->addColumn('actions', function ($import) {

                return view('dashboard.quiz.custom._report_actions', [
                    'quiz_report' => $import
                ])->render();
            })
            ->addColumn('quiz_code', function ($import) {

                return view('dashboard.quiz.custom._quiz_code_link', [
                    'quiz_report' => $import
                ])->render();
            })
            ->rawColumns([

                'group_number',
                'group_name',
                'first_answer',
                'quiz_title',
                'company',
                'quiz_code',
                'actions',

            ])
            ->make(true);
    }


    public function reportsDataTable(int $id, Request $request)
    {
        return DataTables::of(QuizReport::where('quiz_id', $id)->with('quiz_answers.answers', 'quiz_answers.answer', 'quiz')->orderBy('group_id'))
            ->addColumn('group_number', function ($import) use (&$count, &$group, &$num) {
                if (!isset($count)) {
                    $count = 1;
                }
                if ($group != $import->group_id) {
                    $count++;

                    $group = $import->group_id;

                    $num = 0;
                }

                $num++;

                return $count . '.' . $num;
            })
            ->addColumn('group_name', function ($import) {
                if ($import->group_id) return GroupForQuiz::find($import->group_id)->name;
                else return '';
            })
            ->addColumn('first_answer', function ($import) {
                if ($import->quiz_answers()->first()) {
                    return $answer = $import->quiz_answers()->first()->text ?? '';
                } else {
                    return $answer = $import->quiz_answers()->first()->answer->text ?? '';
                }
            })
            ->addColumn('quiz_title', function ($import) {

                return $import->quiz->title;
            })
            ->addColumn('actions', function ($import) {

                return view('dashboard.quiz.custom._report_actions', [
                    'quiz_report' => $import
                ])->render();
            })
            ->rawColumns([

                'group_number',
                'group_name',
                'first_answer',
                'quiz_title',
                'actions',

            ])
            ->make(true);
    }

    /**
     * @param int $quiz_report_id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function retrieveReportByID(int $quiz_report_id)
    {
        $report = QuizReport::find($quiz_report_id);
        if ($report === null) {
            $archivedReport = QuizReport::withoutGlobalScope(QuizReport::SCOPE_ONLY_FRESH)->find($quiz_report_id);
            if ($archivedReport !== null) {
                return redirect()->route('quiz.report', $archivedReport->parent_id);
            } else {
                abort(404);
            }
        }

        $quiz_id = $report->quiz->id;

        $quiz_answers = QuizAnswer::whereHas('quiz_report', function ($query) use ($quiz_report_id) {
            return $query->where('quiz_report_id', $quiz_report_id);
        })->get();

        return view('dashboard.quiz.report', [
            'quiz_answers' => $quiz_answers,
            'quiz_reports' => $report,
            'quiz_id' => $quiz_id
        ]);
    }

    public function retrieveReportByIdDestroy(int $quiz_report_id)
    {

        $quiz_reports = QuizReport::find($quiz_report_id);

        $quiz_reports->delete();

        flash()->success('Delete Report');

        return back();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function reportsExport()
    {
        return (new QuizReportExport())->download('reports.xlsx');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function answersExport()
    {
        set_time_limit(1000000);
        return (new QuizAnswerExport())->download('answers.xlsx');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function quizIsActive(Request $request, $id)
    {
        $request->validate([
            'is_active' => 'boolean'
        ]);
        Quiz::find($id)->update($request->all());
        return response()->json('success', 200);
    }

    public function export()
    {
        return Excel::download(new QuizExport(), 'All Forms.xlsx');
    }

    public function exportPdf()
    {
        $quizzes = Quiz::with(['quiz_reports' => function ($q) {
            $q->orderBy('group_id', 'desc');
        }])->get();

        $pdf = \PDF::loadView('dashboard.quiz.export_pdf', [
            'quizzes' => $quizzes,
        ])->setPaper('a2', 'landscape');

        return $pdf->download('All Forms.pdf');
    }

    public function onlyExport(int $id)
    {
        $quiz = Quiz::find($id);

        return Excel::download(new QuizExport($id), str_replace('/', '-', $quiz->title) . ".xlsx");
    }

    public function onlyExportPdf(int $id)
    {

        $quizzes = Quiz::where('id', $id)->with(['quiz_reports' => function ($q) {
            $q->orderBy('group_id', 'desc');
        }])->get();

        $pdf = \PDF::loadView('dashboard.quiz.export_pdf', [
            'quizzes' => $quizzes,
        ])->setPaper('a2', 'landscape');

        if (count($quizzes)) {
            return $pdf->download(str_replace('/', '', $quizzes[0]->title) . ".pdf");
        } else {
            flash()->warning('There are no report yet');
            return back();
        }
    }

    public function importForForm(ImportFormRequest $request)
    {
        $quiz = Quiz::findOrFail($request->get('quiz_id'));
        $mapping = new QuizMapping($quiz);

        $import = new QuizReportsImport($quiz, $mapping, $this->getAnswerCreator());

        Excel::import(
            $import,
            $request->file('import_file')
        );

        $message = sprintf(
            '%d reports from %d have been imported.',
            $import->getPerformedReportCount(),
            $import->getReportCount()
        );

        return response()->json(['message' => $message]);
    }

    public function groups(int $id = null)
    {
        $groups = GroupForQuiz::get(['name', 'id'])->toArray();

        $selected_group = [];

        if ($id) {

            $quiz = Quiz::find($id);

            if (count($quiz->groups)) $selected_group = $quiz->groups()->get(['name', 'groups_for_quiz.id'])->toArray();
        }

        return response()->json([
            'status' => 'success',
            'groups' => $groups,
            'selected_group' => $selected_group
        ], 200);
    }

    public function companies(int $id = null)
    {
        $companies = Company::get(['name', 'id'])->toArray();

        $selectedCompany = null;

        if ($id !== null) {

            $quiz = Quiz::findOrFail($id);

            $company = $quiz->company()->first(['id', 'name']);
            $selectedCompany = $company !== null ? $company->toArray() : null;
        }

        return response()->json([
            'status' => 'success',
            'companies' => $companies,
            'selected_company' => $selectedCompany
        ], 200);
    }

    public function quizStatusChange(int $id, Request $request)
    {
        $status = $request->key;

        $report = QuizReport::find($id);

        $report->report_status = $status;

        $report->save();

        return response()->json('success', 200);
    }


    public function quizClone(CreateQuizRequest $request, int $id)
    {
        $quiz = Quiz::find($id);

        $groups_ids = $quiz->groups->pluck('id')->toArray();

        $quiz_data = Arr::except($quiz->toArray(), ['id', 'created_at', 'updated_at', 'deleted_at']);

        $quiz_data['title'] = $request['title'];

        $quiz_data['slug'] = self::checkQuizSlug($request['title']);

        $quiz_data['description'] = $request->description;

        $quiz_data['time_limit'] = Carbon::parse($request->time_limit);
        $quiz_data['company_id'] = $request->get('company_id');

        $cloned_quiz = Quiz::create($quiz_data);

        if ($request->groups_ids) {
            $cloned_quiz->groups()->sync($request->groups_ids);
        } else {
            $cloned_quiz->groups()->sync($groups_ids);
        }

        $cloned_quiz_id = $cloned_quiz->id;

        $quiz->questions->each(function ($question) use ($cloned_quiz_id, $quiz) {

            $question_data = Arr::except($question->toArray(), ['id', 'quiz_id', 'created_at', 'updated_at', 'deleted_at']);

            $newQuestion = Question::create($question_data + ['quiz_id' => $cloned_quiz_id]);

            $newQuestionId = $newQuestion->id;

            $question->answers->each(function ($answer) use ($newQuestionId) {

                $answer_data = Arr::except($answer->toArray(), ['id', 'question_id', 'created_at', 'updated_at', 'deleted_at']);

                Answer::create($answer_data + ['question_id' => $newQuestionId]);
            });
        });

        return response('success', 200);
    }

    public function checkQuizSlug($row, $i = null)
    {
        $j = $i + 1;

        if ($i) {
            $quizFind = Quiz::whereSlug(Str::slug($row, '-') . '-' . $i)->first();

            $quizTrashed = Quiz::onlyTrashed()->whereSlug(Str::slug($row, '-') . '-' . $i)->first();
        } else {
            $quizFind = Quiz::whereSlug(Str::slug($row, '-'))->first();

            $quizTrashed = Quiz::onlyTrashed()->whereSlug(Str::slug($row, '-'))->first();
        }

        if ($quizFind || $quizTrashed) {
            return self::checkQuizSlug($row, $j);
        } else {
            if ($i) return Str::slug($row, '-') . '-' . $i;
            else return Str::slug($row, '-');
        }
    }

    public function quizQuestionFieldsIsRequired(int $id, Request $request)
    {
        $quiz = Quiz::find($id);

        $quiz->update([
            'is_required_fields' => $request->is_required
        ]);

        return response()->json('success', 200);
    }


    public function getSelfVerification(int $quizId)
    {
        $quiz = Quiz::query()->findOrFail($quizId);

        $data = $quiz->only([
            'verification_text_1',
            'verification_text_2',
            'verification_text_3',
            'verification_text_4',
            'verification_text_5',
        ]);

        return response()->json($data, 200);
    }

    public function updateSelfVerification(Request $request, int $quizId)
    {
        $data = $request->only([
            'verification_text_1',
            'verification_text_2',
            'verification_text_3',
            'verification_text_4',
            'verification_text_5'
        ]);

        $quiz = Quiz::findOrFail($quizId);

        $quiz->update($data);

        return response()->json('updated', 200);
    }

    public function reportChangeList(int $id)
    {
        $report = QuizReport::query()
            ->withoutGlobalScope(QuizReport::SCOPE_ONLY_FRESH)
            ->findOrFail($id);

        $mapping = new QuizMapping($report->quiz);

        $result = $report->listChanges($mapping);

        if ($result === null) {
            return response()->json('Older Revision not found', 404);
        }

        return response()->json($result);
    }

    private function getAnswerCreator(): ReportAnswersCreatorService
    {
        return $this->answerCreator;
    }

    public function archive(Request $request)
    {
        return view('dashboard.quiz.archive', [
            'formId' => null,
        ]);
    }

    public function archiveByForm(Request $request, int $formId)
    {
        return view('dashboard.quiz.archive', [
            'formId' => $formId,
        ]);
    }

    public function archiveDatatable()
    {
        return $this->createArchiveDatatable();
    }

    public function archiveByFormDatatable(int $formId)
    {
        return $this->createArchiveDatatable($formId);
    }

    private function createArchiveDatatable(?int $formId = null)
    {
        $query = QuizReport::query()
            ->withoutGlobalScope(QuizReport::SCOPE_ONLY_FRESH)
            ->with('quiz')
            ->with(['older' => function ($q) {
                $q->select('id', 'parent_id');
            }]);

        if ($formId !== null) {
            $query->where('quiz_id', $formId);
        }
        $query->latest();

        return DataTables::of($query)
            ->addColumn('quiz_title', function ($report) {

                return $report->quiz->title ?? null;
            })
            ->addColumn('company', function ($report) {
                return $report->quiz->company->name ?? null;
            })
            ->addColumn('actions', function ($report) {

                return view('dashboard.quiz.custom._archive_actions', [
                    'report' => $report
                ])->render();
            })
            ->rawColumns([

                'group_number',
                'group_name',
                'first_answer',
                'quiz_title',
                'actions',

            ])
            ->make(true);
    }
}
