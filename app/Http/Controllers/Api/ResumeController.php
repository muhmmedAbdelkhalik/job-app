<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Resume;
use App\Services\ResumeAnalysisServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ResumeController extends BaseController
{
    private $resumeAnalysisServices;

    public function __construct(ResumeAnalysisServices $resumeAnalysisServices)
    {
        $this->resumeAnalysisServices = $resumeAnalysisServices;
    }

    /**
     * List user's resumes
     */
    public function index(Request $request): JsonResponse
    {
        $query = Resume::where('user_id', Auth::id());

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $resumes = $query->paginate($perPage);

        $resumeData = $resumes->map(function ($resume) {
            return [
                'id' => $resume->id,
                'file_name' => $resume->file_name,
                'summary' => $resume->summary,
                'skills' => $resume->skills,
                'created_at' => $resume->created_at,
                'updated_at' => $resume->updated_at,
                'applications_count' => $resume->applications()->count(),
            ];
        });

        return $this->paginatedResponse(
            $resumeData,
            [
                'current_page' => $resumes->currentPage(),
                'per_page' => $resumes->perPage(),
                'total' => $resumes->total(),
                'last_page' => $resumes->lastPage(),
                'from' => $resumes->firstItem(),
                'to' => $resumes->lastItem(),
                'has_more_pages' => $resumes->hasMorePages()
            ]
        );
    }

    /**
     * Get resume details
     */
    public function show(string $id): JsonResponse
    {
        try {
            $resume = Resume::where('user_id', Auth::id())
                ->findOrFail($id);

            return $this->successResponse([
                'id' => $resume->id,
                'file_name' => $resume->file_name,
                'file_url' => $resume->file_url,
                'contact_details' => $resume->contact_details,
                'summary' => $resume->summary,
                'skills' => $resume->skills,
                'experience' => $resume->experience,
                'education' => $resume->education,
                'created_at' => $resume->created_at,
                'updated_at' => $resume->updated_at,
                'applications_count' => $resume->applications()->count(),
            ], 'Resume details retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Resume not found', null, 404);
        }
    }

    /**
     * Upload new resume
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'resume_file' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', $validator->errors(), 422);
        }

        try {
            $file = $request->file('resume_file');
            $extension = $file->getClientOriginalExtension();
            $fileName = 'resume_' . time() . '.' . $extension;
            
            // Store in cloud storage
            $path = $file->storeAs('resumes', $fileName, 'cloud');
            $fileUrl = config('filesystems.cloud.url') . '/' . $path;

            // Extract resume info using AI
            $extractedInfo = $this->resumeAnalysisServices->extractResumeInfo($path);

            $resume = Resume::create([
                'user_id' => Auth::id(),
                'file_url' => $path,
                'file_name' => $fileName,
                'contact_details' => json_encode([
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ]),
                'summary' => $extractedInfo['summary'] ?? '',
                'skills' => $extractedInfo['skills'] ?? '',
                'experience' => is_array($extractedInfo['experience'] ?? '') ? 
                    json_encode($extractedInfo['experience']) : 
                    ($extractedInfo['experience'] ?? ''),
                'education' => is_array($extractedInfo['education'] ?? '') || 
                    is_object($extractedInfo['education'] ?? '') ? 
                    json_encode($extractedInfo['education']) : 
                    ($extractedInfo['education'] ?? ''),
            ]);

            return $this->successResponse([
                'id' => $resume->id,
                'file_name' => $resume->file_name,
                'file_url' => $resume->file_url,
                'summary' => $resume->summary,
                'skills' => $resume->skills,
                'created_at' => $resume->created_at,
            ], 'Resume uploaded and processed successfully', 201);

        } catch (\Exception $e) {
            return $this->errorResponse('Failed to upload resume', null, 500);
        }
    }

    /**
     * Update resume
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'summary' => 'sometimes|string',
            'skills' => 'sometimes|string',
            'experience' => 'sometimes|string',
            'education' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('Validation failed', $validator->errors(), 422);
        }

        try {
            $resume = Resume::where('user_id', Auth::id())
                ->findOrFail($id);

            $resume->update($request->only(['summary', 'skills', 'experience', 'education']));

            return $this->successResponse([
                'id' => $resume->id,
                'file_name' => $resume->file_name,
                'summary' => $resume->summary,
                'skills' => $resume->skills,
                'experience' => $resume->experience,
                'education' => $resume->education,
                'updated_at' => $resume->updated_at,
            ], 'Resume updated successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Resume not found or failed to update', null, 404);
        }
    }

    /**
     * Delete resume
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $resume = Resume::where('user_id', Auth::id())
                ->findOrFail($id);

            // Delete file from storage
            if ($resume->file_url && Storage::disk('cloud')->exists($resume->file_url)) {
                Storage::disk('cloud')->delete($resume->file_url);
            }

            $resume->delete();

            return $this->successResponse(null, 'Resume deleted successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Resume not found or failed to delete', null, 404);
        }
    }
}
