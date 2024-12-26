<?php

use App\Http\Controllers\ExecuteController;
use App\Models\Execute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "API";
});

// Route::apiResource('execute', ExecuteController::class);
Route::get('/subjects', [ExecuteController::class, 'index']);
Route::post('/registerAdmin', [ExecuteController::class, 'registerAdmin']);
Route::post(uri: '/registerLearner', action: [ExecuteController::class, 'registerLearner']);
Route::post('/loginAdmin', [ExecuteController::class, 'loginAdmin']);


//Learner Routes
Route::post('/loginLearner', [ExecuteController::class, 'loginLearner']);
Route::get('/getSubjects/{lrn}', [ExecuteController::class, 'getSubjects']);
Route::get('/getSubjectsToday', [ExecuteController::class, 'getSubjectsToday']);
Route::get('/getModules', [ExecuteController::class, 'getModules']);
Route::get('/getLessonID', [ExecuteController::class, 'getLessonID']);
Route::get('/getLessons', [ExecuteController::class, 'getLessons']);
Route::get('/getQuestions', [ExecuteController::class, 'getQuestions']);
Route::get('/getAssessments', [ExecuteController::class, 'getAssessments']);
Route::post('/saveAnswers', [ExecuteController::class, 'saveAnswers']);
Route::get('/getAssessmentProgress', [ExecuteController::class, 'getAssessmentProgress']);
Route::post('/logoutLearner', [ExecuteController::class, 'logoutLearner'])->middleware('auth:sanctum');
Route::middleware('auth:sanctum')->get('/getLearnerByToken', [ExecuteController::class, 'getLearnerByToken']);
Route::get('/getLearner/{lrn}', [ExecuteController::class, 'getLearner']);
Route::get('/getAnswerFile', [ExecuteController::class, 'getAnswerFile']);
Route::post('/saveAssessmentsAnswer', [ExecuteController::class, 'saveAssessmentsAnswer']);
Route::post('/updateLearnerPassword/{lrn}', [ExecuteController::class, 'updateLearnerPassword']);
Route::get('/getPendingAssessments', [ExecuteController::class, 'getPendingAssessments']);
Route::get('/getDiscussions', [ExecuteController::class, 'getDiscussions']);
Route::post('/updateProfilePicture', [ExecuteController::class, 'uploadProfilePicture']);
Route::post('/updateFile', [ExecuteController::class, 'uploadFile']);
Route::get('/discussionReplies/{discussionid}', [ExecuteController::class, 'viewDiscussionReplies']);
Route::post('/discussionReply', [ExecuteController::class, 'sendDiscussionReplies']);
Route::get('/checkProgress', [ExecuteController::class, 'checkProgress']);
Route::get('/getScore', [ExecuteController::class, 'getScore']);
Route::get('/getFile', [ExecuteController::class, 'getFile']);
Route::post('/uploadFile', [ExecuteController::class, 'uploadFile']);
Route::get('/getAnnouncements', [ExecuteController::class, 'getAnnouncements']);
Route::get('/getResultAnalysis', [ExecuteController::class, 'getResultAnalysis']);
Route::get('/getmoduleID', [ExecuteController::class, 'getmoduleID']);

//Message Component
Route::get('/messages/{id}', [ExecuteController::class, 'showMessages']);
Route::get('/admins/{id}', [ExecuteController::class, 'getAdmin']);
Route::post('/messages/reply', [ExecuteController::class, 'sendReply']);
Route::post('/messages/compose', [ExecuteController::class, 'sendMessage']);
Route::get('/messages/unread/{lrn}', [ExecuteController::class, 'getUnreadMessages']);
Route::post('/messages/clear', [ExecuteController::class, 'clearUnreadMessages']);
Route::get('/messages/getAdminDetails/{lrn}', [ExecuteController::class, 'getAdminDetails']);



Route::post('/subjects/create', [ExecuteController::class, 'createAssessment']);
Route::get('/subjects/showAll', [ExecuteController::class, 'showAll']);
Route::get('/subjects/assessment', [ExecuteController::class, 'showAssessment']);
Route::get('/subjects/{id}', [ExecuteController::class, 'show']);

// Change Password Component
Route::post('/request-change-password', [ExecuteController::class, 'requestChangePassword']);
Route::post('/get-password-change-status', [ExecuteController::class, 'getPasswordChangeRequestStatus']);
Route::post('/change-password/{email}', [ExecuteController::class, 'changePassword']);

// Route::get('/subjects', 'ExecuteController@index');
// Route::post('/users', 'ExecuteController@store');

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum'); 