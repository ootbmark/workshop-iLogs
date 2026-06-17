<?php

use App\Http\Controllers\Forms\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'home'])->name('home');

Route::group(['middleware' => ['auth', 'verified']], function () {

    Route::get('/send-test-email', function () {
        Mail::raw('This is a test email from SparkPost!', function ($message) {
            $message->to('macsamsonx@gmail.com')
                ->subject('Test SparkPost Email');
        });

        return 'Test email sent successfully!';
    });
    Route::get('/website-view', function () {
        return 'Hello Website';
    });
    Route::get('/discussions/create', [App\Http\Controllers\DiscussionController::class, 'create'])->name('discussions.create');
    Route::post('/discussions', [App\Http\Controllers\DiscussionController::class, 'store'])->name('discussions.store');
    Route::get('/discussions-data', [App\Http\Controllers\DiscussionController::class, 'getData'])->name('discussions.data');
    Route::post('/discussions/{id}/reply', [App\Http\Controllers\DiscussionController::class, 'reply'])->name('discussions.reply');
    Route::post('/discussions/{id}/share', [App\Http\Controllers\DiscussionController::class, 'share'])->name('discussions.share');
    Route::get('/discussions/{id}/preview', [App\Http\Controllers\DiscussionController::class, 'preview'])->name('discussions.preview');
    Route::get('/reply/{id}/preview', [App\Http\Controllers\DiscussionController::class, 'replyPreview'])->name('reply.preview');
    Route::post('/discussions/{id}/preview', [App\Http\Controllers\DiscussionController::class, 'previewSave'])->name('discussions.preview.save');
    Route::post('/reply/{id}/preview', [App\Http\Controllers\DiscussionController::class, 'replyPreviewSave'])->name('reply.preview.save');
    Route::get('/discussions/{id}/edit/preview', [App\Http\Controllers\DiscussionController::class, 'editPreview'])->name('discussions.edit.preview');
    Route::get('/reply/{id}/edit/preview', [App\Http\Controllers\DiscussionController::class, 'editReplyPreview'])->name('reply.edit.preview');
    Route::post('/discussions/{id}/update/preview', [App\Http\Controllers\DiscussionController::class, 'editPreviewUpdate'])->name('discussions.preview.update');
    Route::post('/reply/{id}/update/preview', [App\Http\Controllers\DiscussionController::class, 'editReplyPreviewUpdate'])->name('reply.preview.update');
    Route::get('/discussions/check', [App\Http\Controllers\DiscussionController::class, 'check'])->name('discussions.check');
    Route::get('/organisations', [App\Http\Controllers\OrganisationController::class, 'index'])->name('organisations.index');
    Route::get('/organisations-data', [App\Http\Controllers\OrganisationController::class, 'getData'])->name('organisations.data');
    Route::get('/universities-data', [App\Http\Controllers\OrganisationController::class, 'getUniversitiesData'])->name('universities.data');
    Route::get('/organisations/{id}', [App\Http\Controllers\OrganisationController::class, 'show'])->name('organisations.show');
    Route::post('/contact', [App\Http\Controllers\PageController::class, 'contactSubmit'])->name('contact.submit');
    Route::post('/threads/{id}/favorite', [App\Http\Controllers\DiscussionController::class, 'favorite'])->name('threads.favorite');
    Route::get('/users/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('users.show');
    Route::get('/users-data', [App\Http\Controllers\UserController::class, 'getData'])->name('users.data');
    Route::get('/users-data-paginated', [App\Http\Controllers\UserController::class, 'getDataPaginated'])->name('users.data.paginated');
    Route::get('/users-data-by-ids', [App\Http\Controllers\UserController::class, 'getDataByIds'])->name('users.data.by_ids');

    Route::get('/forms', 'QuizController@index')->name('forms.index.main');
    Route::get('/form/{slug}', 'QuizController@show')->name('quiz')->middleware('verified');
    Route::get('/form/{slug}/preview', 'QuizController@preview')->name('quiz.preview');
    Route::post('/form/{user_quiz_id}', 'QuizController@createQuizAnswer')->name('quiz-answer');
    Route::delete('/form/{user_quiz_id}', 'QuizController@deleteQuizAnswer')->name('delete-quiz-answer');
    Route::patch('/form/{quiz_id}', 'QuizController@completeQuiz')->name('quiz-complete');
    Route::get('/scribes/{scribe_id}/edit', [\App\Http\Controllers\QuizController::class, 'editQuizAnswer'])->name('quiz-answer.edit');
    Route::patch('/scribes/{scribe_id}', [\App\Http\Controllers\QuizController::class, 'updateQuizAnswer'])->name('quiz-answer.update');


    Route::group(['prefix' => 'profile'], function () {
        Route::get('/', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
        Route::post('/', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
        Route::get('/info', [App\Http\Controllers\ProfileController::class, 'info'])->name('profile.info');
        Route::get('/discussions', [App\Http\Controllers\ProfileController::class, 'discussions'])->name('profile.discussions');
        Route::get('/replies', [App\Http\Controllers\ProfileController::class, 'replies'])->name('profile.replies');
        Route::get('/password', [App\Http\Controllers\ProfileController::class, 'password'])->name('profile.password');
        Route::post('/password', [App\Http\Controllers\ProfileController::class, 'passwordUpdate'])->name('profile.password.update');
        Route::get('/notifications', [App\Http\Controllers\ProfileController::class, 'notifications'])->name('profile.notifications');
        Route::post('/notifications', [App\Http\Controllers\ProfileController::class, 'notificationsUpdate'])->name('profile.notifications.update');
        Route::get('/invite', [App\Http\Controllers\ProfileController::class, 'invite'])->name('profile.invite');
        Route::post('/invite', [App\Http\Controllers\ProfileController::class, 'inviteSubmit'])->name('profile.invite.submit');
        Route::get('/find', [App\Http\Controllers\ProfileController::class, 'find'])->name('profile.find');
        Route::post('/send-contact-email/{user_id}', [App\Http\Controllers\ProfileController::class, 'sendContactEmail'])->name('profile.send');
        Route::post('/image', [App\Http\Controllers\ProfileController::class, 'imageUpdate'])->name('profile.image.update');
        Route::post('/image/delete', [App\Http\Controllers\ProfileController::class, 'imageDelete'])->name('profile.image.delete');
    });

    // Spam Discussion Routes
    Route::get('/discussion/spam', [App\Http\Controllers\SpamDiscussionsController::class, 'index'])->name('discussion-spam.view');
    Route::get('/discussion/spam/{id}', [App\Http\Controllers\SpamDiscussionsController::class, 'view'])->name('discussion-spam.spam-view');
    Route::post('/discussion/spam/invalid', [App\Http\Controllers\SpamDiscussionsController::class, 'store_thread'])->name('discussion-spam.invalid-spam');
});

Route::get('/discussions', [App\Http\Controllers\DiscussionController::class, 'index'])->name('discussions.index');
Route::get('/discussions/{id}', [App\Http\Controllers\DiscussionController::class, 'show'])->name('discussions.show');

Route::get('/group-discussions/{id}', [App\Http\Controllers\GroupController::class, 'discussions'])->name('groups.discussions');

Route::get('/groups', [App\Http\Controllers\GroupController::class, 'index'])->name('groups.index');
Route::get('/groups-data', [App\Http\Controllers\GroupController::class, 'getData'])->name('groups.data');

Route::get('/contact', [App\Http\Controllers\PageController::class, 'contact'])->name('contact');
Route::get('/help', [App\Http\Controllers\PageController::class, 'help'])->name('help');
Route::get('/about', [App\Http\Controllers\PageController::class, 'about'])->name('about');
Route::get('/useful-links', [App\Http\Controllers\PageController::class, 'useful'])->name('useful');
Route::get('/user-guide', [App\Http\Controllers\PageController::class, 'guide'])->name('guide');
Route::get('/faq', [App\Http\Controllers\PageController::class, 'faq'])->name('faq');
Route::get('/tos-privacy-cookie', [App\Http\Controllers\PageController::class, 'privacy'])->name('privacy');
Route::get('/logout-account', [App\Http\Controllers\PageController::class, 'logout'])->name('page.logout');

Route::get('/email-footer', function () {
    return view('emails.footer_alert');
});
Route::prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])
        ->name('dashboard');
    Route::prefix('/forms')->group(function () {
        // Route::get('/', [GroupsController::class, 'index'])->name('forms.view');

        /*
        GET    /admin/groups
        GET    /admin/groups/create
        POST   /admin/groups
        GET    /admin/groups/{group}
        GET    /admin/groups/{group}/edit
        PUT    /admin/groups/{group}
        DELETE /admin/groups/{group}
        */
    });
    Route::resource('groups', Forms\GroupsController::class);
});
Route::prefix('forms/v2/')->group(function () {
    Route::controller(\Forms\FormsController::class)->group(function () {
        Route::get('dashboard/{data}', 'index')->name('forms.dashboard');
        Route::get('/view', 'loginCode')->name('forms.view');
        Route::post('/view', 'verifyCode')->name('form.verifyCode');
        Route::get('/view/{data}', 'show')->name('forms.view-code');
        Route::get('/generate', 'generate');
        Route::post('/store-answer/{data}', 'storeParticipantAnswer')->name('forms.store-answer');
        Route::patch('/complete-quiz/{data}', 'completeQuiz')->name('forms.quiz-complete');

        // Scribes
        Route::get('list-of-scribes/{data}', 'scribesDataTable')->name('forms.scribes-data');
        Route::get('/{data}/scribes/{scribe}', 'viewQuizReport')->name('forms.scribes-view');
        Route::get('/{data}/questioner/{scribe}', 'editQuizReport')->name('forms.scribes-edit');

        Route::get('/share-workshop-link', 'shareLink')->name('forms.show-qrcode');
        Route::get('/workshop-dashboard/{data}', 'workshopDashboard')->name('forms.workshop-dashboard');
    });
});
