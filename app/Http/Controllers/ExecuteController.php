<?php

namespace App\Http\Controllers;

use App\Models\Execute;
use App\Models\Subject;
use App\Models\Assessment;
use App\Models\AssessmentAnswer;
use App\Http\Requests\StoreExecuteRequest;
use App\Http\Requests\UpdateExecuteRequest;
use App\Models\Admin;
use App\Models\Learner;
use App\Models\Discussion;
use App\Models\Discussion_Reply;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use View;
use Illuminate\Support\Facades\Validator;


class ExecuteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $dayOfWeek = date('N'); // Get the day of the week (1 = Monday, 7 = Sunday)

        $program = '';

        // Determine the program based on the current day
        if ($dayOfWeek == 1) {
            $program = 'blp';
        } elseif (in_array($dayOfWeek, [2, 3])) {
            $program = 'alsElem';
        } elseif (in_array($dayOfWeek, [4, 5])) {
            $program = 'aleJhs';
        }

        // Retrieve the subjects based on the program
        $subject = DB::table('classes')
            ->rightJoin('subjects', 'classes.subjectID', '=', 'subjects.subjectID')
            ->select('subjects.subjectID', 'subjects.image', 'subjects.subject_name', 'classes.Schedule')
            ->where('subjects.Program', '=', $program)
            ->get();

        return $subject;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function createAssessment(Request $request)
    {
        //
        $validatedData = $request->validate([
            'Lesson_ID' => 'required|integer',
            'Title' => 'required|string|max:255',
            'Instruction' => 'required|string|max:255',
            'Description' => 'required|string|max:255',
            'Due_date' => 'date',
        ]);

        $assess = Assessment::create($validatedData);
        return response()->json($assess, 201);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Execute $id)
    public function show($id)
    {
        //
        $subject = DB::table('classes')
            ->rightJoin('subjects', 'classes.subjectID', '=', 'subjects.subjectID')
            ->select('subjects.subjectID', 'subjects.image', 'subjects.subject_name', 'subjects.Program', 'classes.Schedule')
            ->where('subjects.subjectID', '=', $id)
            ->get();

        if ($subject) {
            return response()->json($subject);
            // return $subject;
        } else {
            return response()->json(['message' => 'Subject not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Execute $execute)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Execute $execute)
    {
        //
    }

    public function showAll()
    {
        //
        $subject = DB::table('classes')
            ->rightJoin('subjects', 'classes.subjectID', '=', 'subjects.subjectID')
            ->select('subjects.subjectID', 'subjects.image', 'subjects.subject_name', 'subjects.Program', 'classes.Schedule')
            ->get();
        return $subject;
    }

    public function showAssessment()
    {
        //
        $assess = DB::table('assessments')
            ->select(
                'assessments.assessmentID',
                'assessments.Title',
                'assessments.Instruction',
                'assessments.Description',
                DB::raw('DATE_FORMAT(assessments.Due_date, "%M %d, %Y") as formatted_due_date')
            )
            ->get();

        return $assess;
    }

    public function registerAdmin(Request $request)
    {
        $formField = $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'birthdate' => 'required|date',
            'address' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:255',
            'role' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed'
        ]);

        Admin::create($formField);

        return 'registered';
    }

    // public function registerLearner(Request $request)
    // {

    //         // $validatedData = $request->validate([
    //         //     'firstname' => 'required|string|max:255',
    //         //     'middlename' => 'required|string|max:255',
    //         //     'lastname' => 'required|string|max:255',
    //         //     'extension_name' => 'nullable|string|max:255',
    //         //     'birthdate' => 'required|date|before:2016-01-01',
    //         //     'placeofbirth' => 'required|string|max:255',
    //         //     'last_education' => 'required|string|max:255',
    //         //     'gender' => 'required|string',
    //         //     'civil_status' => 'required|string',
    //         //     'email' => 'required|string|unique:learners,email',
    //         //     'password' => 'required|string|min:6|confirmed',
    //         // ]);

    //         $validator = Validator::make($request->all(), [
    //             'firstname' => 'required|string|max:255',
    //             'middlename' => 'required|string|max:255',
    //             'lastname' => 'required|string|max:255',
    //             'extension_name' => 'nullable|string|max:255',
    //             'birthdate' => 'required|date|before:2016-01-01',
    //             'placeofbirth' => 'required|string|max:255',
    //             'last_education' => 'required|string|max:255',
    //             'gender' => 'required|string',
    //             'civil_status' => 'required|string',
    //             'email' => 'required|email|unique:users,email',
    //             'password' => 'required|string|min:8|confirmed',
    //         ]);
    
    //         // $validatedData['password'] = bcrypt($validatedData['password']);
    //         $validator['password'] = bcrypt($validator['password']);
    //         // $student = Learner::create($validatedData);
    //         $student = Learner::create($validator);
            
    //         if ($validator->fails()) {
    //             $errors = $validator->errors();
        
    //             $response = [];
    //             if ($errors->has('email')) {
    //                 $response['email'] = $errors->first('email'); // Get the first error for 'email'
    //             }
    //             if ($errors->has('password')) {
    //                 $response['password'] = $errors->first('password'); // Get the first error for 'password'
    //             }
        
    //             return response()->json([
    //                 'status' => 'Error',
    //                 'message' => 'Validation failed',
    //                 'errors' => $response,
    //             ], 400);
    //         }
    //         return response()->json(['message' => 'Student registered successfully', 'student' => $student], 201);
    //     // } catch (\Illuminate\Validation\ValidationException $e) {
    //     //     return response()->json([
    //     //         'data' => 'Email address exist',
    //     //         'status' => 'Error',
    //     //         'code' => 422
    //     //     ], 422);
    //     // }
    // }

    public function registerLearner(Request $request)
    {
        // Validate request data
        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'middlename' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'extension_name' => 'nullable|string|max:255',
            'birthdate' => 'required|date|before:2016-01-01',
            'placeofbirth' => 'required|string|max:255',
            'last_education' => 'required|string|max:255',
            'gender' => 'required|string',
            'civil_status' => 'required|string',
            'email' => 'required|email|unique:learners,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Check for validation errors
        // if ($validator->fails()) {
        //     return response()->json([
        //         'status' => 'Error',
        //         'message' => 'Validation failed',
        //         'errors' => $validator->errors(), // This will return all field errors
        //     ], 422);
        // }
        $user = Learner::where('email', $request->email)->first();
        if($user) {
            return response()->json([
                'message' => 'Email Already Taken'
            ], 400);
        }else {
            // Create a new learner after validation passes
            $validatedData = $validator->validated();
            $validatedData['password'] = bcrypt($validatedData['password']);
            $student = Learner::create($validatedData);
    
            return response()->json([
                'message' => 'Student registered successfully',
                'student' => $student,
            ], 201);
        }

    }

    

    public function loginAdmin(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins',
            'password' => 'required'
        ]);

        $user = Admin::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'message' => 'The Provided Credentials are incorrect'
            ];
        }
        ;
        $token = $user->createToken($user->lastname);

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];
    }

    public function loginLearner(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:learners',
            'password' => 'required'
        ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'message' => 'null'
        //     ], 400); // Return as a bad request
        // }

        $user = Learner::where('email', $request->email)->first();
        
        if(!$user) {
            return response()->json([
                'token' => null
            ], 401);
        }

        if (is_null($user->lrn)) {
            return response()->json([
                'token' => null
            ], 400); // return a specific message if LRN is missing
        } else {

            if(!Hash::check($request->password, $user->password)) 
            {
                return [
                    'token' => null
                ];
            } else {
                $token = $user->createToken($user->lastname);
                
                return [
                    'user' => $user,
                    'token' => $token->plainTextToken
                ];
            }
        } 
        
    }

    public function logoutLearner(Request $request)
    {
        $request->user()->tokens()->delete();
        return [
            'message' => 'Logged out successfully'
        ];
    }

    public function getLearnerByToken(Request $request)
    {
        //Retrieve the currently authenticated student
        $learner = $request->user();

        if ($learner) {
            //Return all the student's information
            return response()->json($learner);
        } else {
            return response()->json(['message' => 'Student not found'], 404);
        }
    }

    public function getSubjects($lrn)
    {

        $subjects = DB::table('rosters')
            ->join('learners', 'rosters.lrn', '=', 'learners.lrn')
            ->join('classes', 'rosters.classid', '=', 'classes.classid')
            ->join('rooms', 'classes.roomid', '=', 'rooms.roomid')
            ->join('admins', 'classes.adminid', '=', 'admins.adminID')
            ->join('subjects', 'classes.subjectid', '=', 'subjects.subjectid')
            ->where('rosters.lrn', $lrn)
            ->select(
                'classes.*',
                'subjects.*',
                'admins.*',
                'rooms.school',
                DB::raw("CONCAT(admins.firstname, ' ', admins.middlename, ' ', admins.lastname) AS admin_name")
            )
            ->distinct()
            ->get();


        return response()->json($subjects);
    }


    public function getSubjectsToday(Request $request)
    {
        $today = date('l');
        $lrn = $request->input('lrn');

        $subjects = DB::table('rosters')
            ->join('learners', 'rosters.lrn', '=', 'learners.lrn')
            ->join('classes', 'rosters.classid', '=', 'classes.classid')
            ->join('admins', 'classes.adminid', '=', 'admins.adminID')
            ->join('subjects', 'classes.subjectid', '=', 'subjects.subjectid')
            ->where('rosters.lrn', $lrn)
            ->where('classes.schedule', 'LIKE', '%' . $today . '%')
            ->select(
                'learners.*',       // Select all columns from the learners table
                'rosters.*',        // Select all columns from the rosters table
                'classes.*',        // Select all columns from the classes table
                'subjects.*',       // Select all columns from the subjects table
                DB::raw("CONCAT(admins.firstname, ' ', admins.middlename, ' ', admins.lastname) AS admin_name")
            )
            ->get();

        return response()->json($subjects);
    }

    public function getModules(Request $request)
    {
        $classid = $request->input('classid');

        $modules = DB::table('modules')
            ->select('modules.*', DB::raw("DATE_FORMAT(modules.date, '%M %d, %Y') as formatted_date"))
            ->where('modules.classid', $classid)
            // ->orderBy('modules.date', 'desc')
            ->get();


        return response()->json($modules);
    }
    public function getLessonID(Request $request)
    {
        $mid = $request->input('mid');

        $lessons = DB::table('lessons')
            ->select('lessons.*')
            ->where('module_id', $mid)
            ->get();


        return response()->json($lessons);
    }

    public function getLessons(Request $request)
    {
        $module_id = $request->input('moduleID');

        // Get all lessons for the specified module
        $lessons = DB::table('lessons')
        ->select('lessons.*', DB::raw('(SELECT COUNT(*) FROM assessments WHERE assessments.lesson_id = lessons.lesson_id) as total_assessments'))
        ->where('lessons.module_id', $module_id)
        ->get();

        // For each lesson, fetch related media data
        foreach ($lessons as $lesson) {
            $lesson->media = DB::table('media')
                ->where('lesson_id', $lesson->lesson_id)
                ->get();
        }

        return response()->json($lessons);
    }


    public function getAssessments(Request $request)
    {
        $lesson_id = $request->input('lessonID');
        $lrn = $request->input('lrn'); // Assuming the LRN is passed from the request
        $currentDate = now()->format('Y-m-d');

        $assessments = DB::table('assessments')
            ->select(
                'assessments.*',
                'assessment_answers.assessmentid as aid',  // Aliasing assessmentid to aid
                'assessment_answers.lrn as slrn',         // Aliasing lrn to slrn  
                'assessment_answers.score as scores',               // Fetching the score 
                DB::raw("DATE_FORMAT(assessment_answers.date_submission, '%M %d, %Y') as formatted_date"),
                DB::raw("DATE_FORMAT(assessments.due_date, '%M %d, %Y') as due_date"),
                DB::raw("IF(assessments.available, 1, 0) as isOpen") // Keep it open on due date or later
            )
            ->leftJoin('assessment_answers', function ($join) use ($lrn) {
                $join->on('assessments.assessmentid', '=', 'assessment_answers.assessmentid')
                    ->where('assessment_answers.lrn', '=', $lrn);
            })
            ->where('assessments.lesson_id', $lesson_id)
            ->get();


        return response()->json($assessments);
    }

    public function getQuestions(Request $request)
    {
        $assessmentID = $request->input('assessmentID');
        $lrn = $request->input('lrn');

        $questions = DB::table('questions')
            ->leftJoin('answers', function ($join) use ($lrn) {
                $join->on('answers.question_id', '=', 'questions.question_id')
                    ->where('answers.lrn', '=', $lrn);
            })
            ->select('questions.*', 'answers.answer as user_answer')
            ->where('questions.assessment_id', $assessmentID)
            ->get();

        foreach ($questions as $q) {
            if ($q->type == "multiple-choice") {
                $q->options = DB::table('options')
                    ->where('question_id', $q->question_id)
                    ->get();
            }
        }

        return response()->json($questions);
    }

    public function getPendingAssessments(Request $request)
    {
        $lrn = $request->input('lrn');
        $currentDate = now()->format('Y-m-d');

        $pendingAssessments = DB::table('assessments')
            ->leftJoin('assessment_answers', function ($join) use ($lrn) {
                $join->on('assessment_answers.assessmentid', '=', 'assessments.assessmentid')
                    ->where('assessment_answers.lrn', '=', $lrn);
            })
            ->leftJoin('lessons', 'assessments.lesson_id', '=', 'lessons.lesson_id')
            ->leftJoin('modules', 'lessons.module_id', '=', 'modules.modules_id')
            ->leftJoin('rosters', 'modules.classid', '=', 'rosters.classid')
            ->whereNull('assessment_answers.lrn')
            ->where('rosters.lrn', '=', $lrn)
            ->select(
                'assessments.*',
                'assessment_answers.lrn as slrn', 
                DB::raw("IF(assessments.due_date >= '$currentDate', 1, 0) as isDateDue"),
                DB::raw("IF(assessments.available, 1, 0) as isOpen")
            )
            ->get();

        return response()->json($pendingAssessments);
    }


    public function getAssessmentProgress(Request $request)
    {
        $lrn = $request->input('lrn');

        $progress = DB::table('assessment_answers')
            ->select('assessment_answers.*')
            ->where('assessment_answers.lrn', $lrn)
            ->get();

        return response()->json($progress);
    }

    public function saveAnswers(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'qid' => 'required',
            'slrn' => 'required',
            'answerValue' => 'required|string',
        ]);

        // Extract values
        $lrn = $validated['slrn'];
        $qid = $validated['qid'];
        $answer = $validated['answerValue'];

        // Upsert query
        DB::table('answers')->updateOrInsert(
            [
                'question_id' => $qid, // Unique constraint
                'lrn' => $lrn,
            ],
            [
                'answer' => $answer, // Values to insert or update
            ]
        );


        // Return a success response
        return response()->json('Answer saved successfully');
    }

    public function saveAssessmentsAnswer(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'assessmentID' => 'required',
            'lrn' => 'required'

        ]);
        // Extract values
        $lrn = $validated['lrn'];
        $assessmentID = $validated['assessmentID'];

        // Get the current date and time
        $today = now()->format('Y-m-d H:i:s');

        // Upsert query
        DB::table('assessment_answers')->updateOrInsert(
            [
                'assessmentid' => $assessmentID, // Unique constraint
                'lrn' => $lrn,
            ],
            [
                'date_submission' => $today, // Add the current date and time
            ]
        );


        // Return a success response
        return response()->json('Answer saved successfully');
    }

    public function updateLearnerPassword(Request $request, $lrn)
    {
        // Validate the incoming request
        $request->validate([
            'oldpassword' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Find the learner by LRN
        $learner = Learner::where('lrn', $lrn)->first();

        // Check if the learner exists
        if (!$learner) {
            return response()->json(['message' => 'Learner not found'], 404);
        }

        // Check if the old password matches the stored password
        if (!Hash::check($request->oldpassword, $learner->password)) {
            return response()->json(['message' => 'Old password does not match'], 400);
        }

        // Update to the new password
        $learner->password = Hash::make($request->password);
        $learner->save();

        return response()->json(['message' => 'Password updated successfully'], 200);
    }

    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'lrn' => 'required'
        ]);

        // Retrieve the learner using LRN
        $lrn = $request->input('lrn');

        // Check if a file was uploaded
        if ($request->hasFile('profile_picture')) {
            // Store the new image in the 'public/profile_pictures' directory
            $filePath = $request->file('profile_picture')->store('profile_pictures', 'public');

            // Get the file name to save in the database
            $fileName = basename($filePath); // Get just the file name

            // Save the image in the assets folder
            $destinationPath = public_path('assets/profile_pictures');
            $request->file('profile_picture')->move($destinationPath, $fileName);

            // Use query builder to check and update or insert profile picture for the student
            DB::table('learners')->updateOrInsert(
                ['lrn' => $lrn], // Condition to match LRN
                ['image' => $fileName] // Save just the image name in the database
            );

            return response()->json(['message' => 'Profile picture updated successfully', 'image_name' => $fileName], 200);
        } else {
            return response()->json(['error' => 'No file uploaded'], 400);
        }
    }

    // LearnerController.php
    public function getLearner($lrn)
    {
        $learner = DB::table('learners')->where('lrn', $lrn)->first();

        if (!$learner) {
            return response()->json(['message' => 'Learner not found'], 404);
        }

        // return response()->json($learner, 200);
        return [
            'learner' => $learner,
            'image' => $learner->image
        ];
    }

    public function getDiscussions(Request $request)
    {
        $lessonID = $request->input('lessonid');

        $discussionlist = DB::table('discussions')
            ->select(
                'discussions.*',
                DB::raw("DATE_FORMAT(discussions.created_at, '%M %d, %Y') as date_created")
            ) //fetching date submission
            ->where('discussions.lesson_id', $lessonID)
            ->get();

        return response()->json($discussionlist);
    }

    public function viewDiscussionReplies($discussionid)
    {
        $replies = Discussion_Reply::where('discussionid', $discussionid)
            ->leftJoin('admins', 'discussion_replies.adminID', '=', 'admins.adminID') // Join the Admins Table
            ->leftJoin('learners', 'discussion_replies.lrn', '=', 'learners.lrn') // Join the Learners Table
            ->select(
                'discussion_replies.*',
                'admins.firstname as teacher_firstname',
                'admins.lastname as teacher_lastname',
                'learners.firstname as student_firstname',
                'learners.lastname as student_lastname'
            )
            ->orderBy('discussion_replies.created_at', 'asc')
            ->get();

        return response()->json($replies);
    }

    public function sendDiscussionReplies(Request $request)
    {
        $validated = $request->validate([
            'discussionid' => 'required|integer',
            'lrn' => 'nullable|string', // Only for students
            'adminID' => 'nullable|integer', // Only for teachers
            'reply' => 'required|string'
        ]);

        $reply = Discussion_Reply::create([
            'discussionid' => $validated['discussionid'],
            'lrn' => $validated['lrn'] ?? null,
            'adminID' => $validated['adminID'] ?? null,
            'reply' => $validated['reply']
        ]);

        return response()->json(['message' => 'Reply sent successfully', 'reply' => $reply]);
    }

    //Check the progress
    public function checkProgress(Request $request)
    {
        $classID = $request->input('cid'); //Classes ID
        $lrn = $request->input('lrn'); //Learner's LRN
        $currentDate = now()->format('Y-m-d');

        $subjectprogress = DB::table('classes')
            ->join('modules', 'classes.classid', '=', 'modules.classid')
            ->join('lessons', 'modules.modules_id', '=', 'lessons.module_id')
            ->join('assessments', 'lessons.lesson_id', '=', 'assessments.lesson_id')
            ->leftJoin('assessment_answers', function ($join) use ($lrn) {
                $join->on('assessment_answers.assessmentid', '=', 'assessments.assessmentid')
                    ->where('assessment_answers.lrn', '=', $lrn);
            })
            // ->whereNull('assessment_answers.lrn')
            ->where('classes.classid', $classID) // Added condition for classID
            ->select(
                'assessments.*',
                'assessment_answers.lrn as slrn',
                DB::raw("IF(assessments.due_date >= '$currentDate', 1, 0) as isDateDue"), // Keep it open on due date or later
                DB::raw("IF(assessments.available, 1, 0) as isOpen")
            )
            ->get();

        return response()->json($subjectprogress); // Return the subject progress
    }
    //Learner Assessment File Upload
    // public function uploadFile(Request $request)
    // {
    //     //Validate Request
    //     $request->validate([
    //         'lrn' => 'required',
    //         'assessmentid' => 'required',
    //         'file' => 'required|file|mimes:pdf,doc,docx|max:2048'
    //     ]);
    //     //Store the File
    //     if($request->hasFile('file')){
    //         $file = $request->file('file');
    //         $fileName = time() . '_' . $file->getClientOriginalName();

    //         // $filePath = $request->file('file')->store('assessments', 'public');
    //         $filePath = $file->storeAs('uploads',$fileName, 'public');

    //         //Save File info to Database
    //         // $fileInfo = new Media();
    //         // $fileInfo->assessmentid = $request->input('assessmentid');
    //         // $fileInfo->uploader = null;
    //         // $fileInfo->type = $file->getClientOriginalExtension();
    //         // $fileInfo->fileName = $filePath;
    //         // $fileInfo->save();
    //         $dateSubmission = now();

    //         $assessmentAnswer = new AssessmentAnswer();
    //         $assessmentAnswer->lrn = $request->input('lrn');
    //         $assessmentAnswer->assessmentid = $request->input('assessmentid');
    //         $assessmentAnswer->date_submission = $dateSubmission;
    //         $assessmentAnswer->file = $filePath;
    //         $assessmentAnswer->save();

    //         return response()->json(['message' => 'File uploaded successfully', 'file' => $filePath], 200);

    //     }
    //     return response()->json(['message' => 'File not Uploaded'], 400);
    // }
    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,docx,png,jpeg|max:2048', // Accept specific file types
            'lrn' => 'required',
            'assessmentid' => 'required' // Validation for assessment ID
        ]);

        // Retrieve the learner and assessment details
        $lrn = $request->input('lrn');
        $assessmentId = $request->input('assessmentid');
        $dateSubmission = now();

        // Check if a file was uploaded
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $originalFileName = $file->getClientOriginalName(); // Get original file name
            $destinationPath = public_path('assets/files'); // Directory to store the file

            // Move the file to the destination path with its original name
            $file->move($destinationPath, $originalFileName);

            // Check if a record already exists
            $existingRecord = DB::table('assessment_answers')
                ->where('lrn', $lrn)
                ->where('assessmentid', $assessmentId)
                ->first();

            if ($existingRecord) {
                // Update the existing record
                DB::table('assessment_answers')
                    ->where('answerid', $existingRecord->answerid)
                    ->update(['file' => $originalFileName, 'date_submission' => $dateSubmission]);
            } else {
                // Insert a new record
                DB::table('assessment_answers')->insert([
                    'lrn' => $lrn,
                    'file' => $originalFileName,
                    'assessmentid' => $assessmentId,
                    'date_submission' => $dateSubmission
                ]);
            }

            return response()->json(['message' => 'File uploaded successfully', 'file' => $originalFileName], 200);
        } else {
            return response()->json(['error' => 'No file uploaded'], 400);
        }
    }

    public function getScore(Request $request)
    {
        $aid = $request->input('aid');
        $lrn = $request->input('lrn');
        // Query to get the score
        $score = DB::table('assessment_answers') // Replace 'your_table_name' with the actual table name
            ->where('lrn', $lrn)
            ->orderBy('updated_at', 'desc')
            ->get();
        return response()->json($score);
    }
    public function getFile(Request $request)
    {
        $aid = $request->input('aid');
        $lrn = $request->input('lrn');
        $score = DB::table('assessment_answers') // Replace 'your_table_name' with the actual table name
            ->where('lrn', $lrn)
            ->where('assessmentid', $aid)
            ->get();
        return response()->json($score);

    }

    public function getAnswerFile(Request $request)
    {
        $aid = $request->input('aid');
        $lrn = $request->input('lrn');
        $fileInfo = DB::table('assessment_answers')
            ->where('lrn', $lrn)
            ->where('assessmentid', $aid)
            ->first();

        if (!$fileInfo) {
            return response()->json(['message' => 'File not found'], 404);
        }

        // return response()->json($learner, 200);
        return [
            'assessment' => $fileInfo,
            'file' => $fileInfo->file
        ];
    }

    public function getAnnouncements(Request $request)
    {
        $classid= $request->input('cid');

        $announcements = DB::table('announcements')
        ->select('announcements.*', DB::raw("DATE_FORMAT(announcements.created_at, '%M %d, %Y') as formatted_date"))
        ->where('announcements.classid', $classid)
        ->get();
    

        return response()->json($announcements);
    }

    public function showMessages($id)
    {
        $messages = DB::table('messages')
                ->join('learners', 'learners.lrn', '=', 'messages.lrn')
                ->join('admins', 'messages.adminID', '=', 'admins.adminID')
                ->where('messages.lrn', $id)
                ->select('messages.*', 'admins.firstname', 'admins.lastname', 'admins.adminID')
                ->orderBy('messages.updated_at', 'asc')
                ->get();
        
        return response()->json($messages);
    }

    public function sendReply(Request $request)
    {
        $validatedData = $request->validate([
            'lrn' => 'required|exists:learners,lrn',
            'messages' => 'required|string',
            'adminID' => 'required|exists:admins,adminID',
            // 'mid' => 'required'
        ]);

        //Fetch learner details
        $learner = Learner::where('lrn', $validatedData['lrn'])->first();

        //Update or create the message
        // $message = Message::where('messageid', $validatedData['mid'])
            // ->orderBy('created_at', 'desc')
            // ->first();
        
        // if ($message) {
            $message = new Message();
            $message->messages = $validatedData['messages'];
            $message->lrn = $validatedData['lrn'];
            $message->adminID = $validatedData['adminID'];
            $message->sender_name = $learner->firstname . ' ' . $learner->lastname; //Add sender name
            $message->updated_at = now();
            $message->save();
        // }

        return response()->json(['message' => 'Reply sent successfully!'], 200);

    }

    public function sendMessage(Request $request)
    {
        $validatedData = $request->validate([
            'adminID' => 'required|exists:admins,adminID',
            'messages' => 'required|string',
            'lrn' => 'required|exists:learners,lrn',
        ]);

        //Fetch learner details
        $learner = Learner::find($validatedData['lrn']);

        $message = new Message();
        $message->lrn = $validatedData['lrn'];
        $message->adminID = $validatedData['adminID'];
        $message->messages = $validatedData['messages'];
        $message->sender_name = $learner->firstname . ' ' . $learner->lastname;
        $message->status = 0;
        $message->save();

        $adminID = $request->adminID;
        $lrn = $request->lrn;

        DB::table('messages')
            ->where('adminID', $adminID)
            ->where('lrn', $lrn)
            ->where('messageid', '!=', $message->messageid)
            ->update(['status' => 1]);

        return response()->json(['message' => 'Message sent successfully!'], 200);
    }

    public function getAdmin($id) 
    {
        $admin = DB::table('admins')
        ->distinct()
        ->join('classes', 'classes.adminid', '=', 'admins.adminID')
        ->join('rosters', 'rosters.classid', '=', 'classes.classid')
        ->where('rosters.lrn', $id)
        ->select('admins.*')
        ->get();    

        return response()->json($admin);
    }

    public function requestChangePassword(Request $request)
    {
        // Validate the incoming email
        $request->validate([
            'email' => 'required|email'
        ]);

        // Find the learner by email
        $learner = Learner::where('email', $request->email)->first();

        if ($learner) {
            // Update the password_change_request column to 2
            $learner->password_change_request = 1;
            $learner->save();

            return response()->json([
                'message' => 'Password change request submitted successfully.'
            ], 200);
        }

        return response()->json([
            'message' => 'Learner not found.'
        ], 404);
    }

    public function getPasswordChangeRequestStatus(Request $request)
    {
        // Validate the email input
        $request->validate([
            'email' => 'required|email'
        ]);

        // Find the learner by email
        $learner = Learner::where('email', $request->email)->first();

        if ($learner) {
            // Return the password_change_request value
            return response()->json([
                'password_change_request' => $learner->password_change_request,
            ], 200);
        }

        return response()->json([
            'message' => 'Learner not found.'
        ], 404);
    }

    public function changePassword(Request $request, $email) 
    {
                // Validate the incoming request
                $request->validate([
                    'password' => 'required|string|min:8|confirmed',
                ], [
                    'password.confirmed' => 'The password field confirmation does not match.'
                ]);

                
        
                // Find the learner by email
                $learner = Learner::where('email', $email)->first();
        
                // Check if the learner exists
                if (!$learner) {
                    return response()->json(['message' => 'Learner not found'], 404);
                }
        
                // Check if the old password matches the stored password
                // if (!Hash::check($request->oldpassword, $learner->password)) {
                //     return response()->json(['message' => 'Old password does not match'], 400);
                // }
        
                // Update to the new password
                $learner->password = Hash::make($request->password);
                $learner->save();
        
                return response()->json(['message' => 'Password updated successfully'], 200);
    }

    public function getResultAnalysis(Request $request)
    {
        $aid = $request->query('aid');
        $lrn = $request->query('lrn');

        // Use DB::table() for query builder
        $result = DB::table('answers')
        ->join('questions', 'answers.question_id', '=', 'questions.question_id')
        ->join('assessments', 'questions.assessment_id', '=', 'assessments.assessmentid')
        ->where('questions.assessment_id', $aid)
        ->where('answers.lrn', $lrn)
        ->select(
            'questions.question as question', 
            'questions.type as question_type', 
            'questions.key_answer as key_answer',
            'answers.answer as answer',
            'answers.score as score',
        )
        ->get();    


            // Remove <p> tags from essay answers
        $result = $result->map(function ($item) {
            if ($item->question_type === 'Essay') {
                $item->answer = strip_tags($item->answer);
            }
            return $item;
        });


        return response()->json($result);
    }

    public function getmoduleID(Request $request) {
        $aid = $request->query('aid');

        $result = DB::table('classes')
        ->join('admins', 'classes.adminid', '=', 'admins.adminID')
        ->join('subjects', 'classes.subjectid', '=', 'subjects.subjectid')
        ->join('modules', 'classes.classid', '=', 'modules.classid' )
        ->join('lessons', 'modules.modules_id', '=', 'lessons.module_id')
        ->join('assessments', 'lessons.lesson_id', '=', 'assessments.lesson_id')
        ->select('modules.modules_id as mid', 'modules.description as modesc', 'modules.title as title', DB::raw("CONCAT(admins.firstname, ' ', admins.middlename, ' ', admins.lastname) AS admin_name"), 'subjects.subject_name as subname', 'classes.classid',)
        ->where('assessments.assessmentid', $aid)
        ->get();

        return response()->json($result);
    }

    // Function to get unread messages
    public function getUnreadMessages($lrn)
    {

        // Fetch unread messages based on the sender_name and join with learners
        $unreadMessages = DB::table('messages')
        ->join('learners', 'messages.lrn', '=', 'learners.lrn')
        ->where('learners.lrn', $lrn)
        ->whereRaw('messages.sender_name != CONCAT(learners.firstname, " ", learners.lastname)')
        ->where('messages.status', 0)
        ->select('messages.*')
        ->get();
        $count = $unreadMessages->count(); // Get total unread messages

        return $count;
    }

    // Function to clear unread messages count
    public function clearUnreadMessages(Request $request)
    {
        $lrn = $request->input('lrn'); // Pass LRN dynamically (e.g., logged-in user)

        // Clear logic can vary depending on your implementation
        // Here, just an acknowledgment response
        return response()->json(['message' => 'Messages cleared']);
    }

    public function getAdminDetails($lrn)
    {
        $admin = DB::table('admins')
            ->join('classes', 'admins.adminID', '=', 'classes.adminid')
            ->join('rosters', 'classes.classid', '=', 'rosters.classid')
            ->where('rosters.lrn', $lrn)
            ->distinct()
            ->select('admins.adminID')
            ->first();
    
        // Check if an admin was found and return the adminID as a string
        if ($admin) {
            return (string) $admin->adminID; // Cast to string
        }
    
        return null; // Return null or handle the case where no admin is found
    }
}