<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\ApplyJobRequest;
use App\Models\JobApplication;
use App\Models\JobVacany;
use App\Models\Resume;
use App\Services\ResumeAnalysisServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ApplicationController extends BaseController
{
    private $resumeAnalysisServices;

    public function __construct(ResumeAnalysisServices $resumeAnalysisServices)
    {
        $this->resumeAnalysisServices = $resumeAnalysisServices;
    }

    /**
     * List user's job applications
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = JobApplication::with(['jobVacancy.company', 'resume'])
                ->where('user_id', Auth::id());

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->get('status'));
            }

            // Sort options
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            // Pagination
            $perPage = $request->get('per_page', 15);
            $applications = $query->paginate($perPage);

            $applicationData = $applications->map(function ($application) {
                return [
                    'id' => $application->id,
                    'status' => $application->status,
                    'ai_score' => $application->ai_score,
                    'ai_feedback' => $application->ai_feedback,
                    'created_at' => $application->created_at,
                    'updated_at' => $application->updated_at,
                    'job' => [
                        'id' => $application->jobVacancy->id,
                        'title' => $application->jobVacancy->title,
                        'location' => $application->jobVacancy->location,
                        'company_name' => $application->jobVacancy->company ? $application->jobVacancy->company->name : 'Unknown Company',
                    ],
                    'resume' => $application->resume ? [
                        'id' => $application->resume->id,
                        'file_name' => $application->resume->file_name,
                    ] : null
                ];
            });

            return $this->paginatedResponse(
                $applicationData,
                [
                    'current_page' => $applications->currentPage(),
                    'per_page' => $applications->perPage(),
                    'total' => $applications->total(),
                    'last_page' => $applications->lastPage(),
                    'from' => $applications->firstItem(),
                    'to' => $applications->lastItem(),
                    'has_more_pages' => $applications->hasMorePages()
                ]
            );
        } catch (\Exception $e) {
            return $this->errorResponse('Failed to retrieve applications', null, 500);
        }
    }

    /**
     * Get application details
     */
    public function show(string $id): JsonResponse
    {
        try {
            $application = JobApplication::with(['jobVacancy.company', 'resume'])
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            return $this->successResponse([
                'id' => $application->id,
                'status' => $application->status,
                'ai_score' => $application->ai_score,
                'ai_feedback' => $application->ai_feedback,
                'created_at' => $application->created_at,
                'updated_at' => $application->updated_at,
                'job' => [
                    'id' => $application->jobVacancy->id,
                    'title' => $application->jobVacancy->title,
                    'description' => $application->jobVacancy->description,
                    'location' => $application->jobVacancy->location,
                    'salary' => $application->jobVacancy->salary,
                    'type' => $application->jobVacancy->type,
                    'company' => $application->jobVacancy->company ? [
                        'id' => $application->jobVacancy->company->id,
                        'name' => $application->jobVacancy->company->name,
                        'address' => $application->jobVacancy->company->address,
                        'website' => $application->jobVacancy->company->website,
                    ] : null
                ],
                'resume' => $application->resume ? [
                    'id' => $application->resume->id,
                    'file_name' => $application->resume->file_name,
                    'summary' => $application->resume->summary,
                    'skills' => $application->resume->skills,
                ] : null
            ], 'Application details retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Application not found', null, 404);
        }
    }

    /**
     * Apply for a job
     */
    public function apply(Request $request, string $jobId): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'resume_id' => 'required|exists:resumes,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', $validator->errors(), 422);
        }

        try {
            $job = JobVacany::findOrFail($jobId);
            $resume = Resume::where('id', $request->resume_id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Check if user already applied for this job
            $existingApplication = JobApplication::where('user_id', Auth::id())
                ->where('job_vacancy_id', $jobId)
                ->first();

            if ($existingApplication) {
                return $this->errorResponse('You have already applied for this job', null, 409);
            }

            // Prepare resume data for AI analysis
            $extractedInfo = [
                'summary' => $resume->summary,
                'skills' => $resume->skills,
                'experience' => $resume->experience,
                'education' => $resume->education,
            ];

            // Evaluate Job Application with AI
            $aiEvaluation = $this->resumeAnalysisServices->analyzeResume($job, $extractedInfo);

            // Create Job Application
            $application = JobApplication::create([
                'status' => 'pending',
                'ai_score' => $aiEvaluation['ai_score'],
                'ai_feedback' => $aiEvaluation['ai_feedback'],
                'user_id' => Auth::id(),
                'resume_id' => $resume->id,
                'job_vacancy_id' => $jobId,
            ]);

            return $this->successResponse([
                'id' => $application->id,
                'status' => $application->status,
                'ai_score' => $application->ai_score,
                'ai_feedback' => $application->ai_feedback,
                'job_title' => $job->title,
                'company_name' => $job->company ? $job->company->name : 'Unknown Company',
            ], 'Application submitted successfully', 201);

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to submit application', null, 500);
        }
    }
}
