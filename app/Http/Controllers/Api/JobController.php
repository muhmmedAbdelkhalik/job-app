<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\JobVacany;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class JobController extends BaseController
{
    /**
     * List job vacancies with pagination
     */
    public function index(Request $request): JsonResponse
    {
        $query = JobVacany::with(['company', 'category'])
            ->whereNull('deleted_at');

        // Search functionality
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        // Filter by location
        if ($request->has('location')) {
            $query->where('location', 'like', "%{$request->get('location')}%");
        }

        // Filter by job type
        if ($request->has('type')) {
            $query->where('type', $request->get('type'));
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->get('category_id'));
        }

        // Sort options
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $jobs = $query->paginate($perPage);

        return $this->paginatedResponse(
            $jobs->items(),
            [
                'current_page' => $jobs->currentPage(),
                'per_page' => $jobs->perPage(),
                'total' => $jobs->total(),
                'last_page' => $jobs->lastPage(),
                'from' => $jobs->firstItem(),
                'to' => $jobs->lastItem(),
                'has_more_pages' => $jobs->hasMorePages()
            ]
        );
    }

    /**
     * Get job details
     */
    public function show(string $id): JsonResponse
    {
        try {
            $job = JobVacany::with(['company', 'category', 'applications'])
                ->whereNull('deleted_at')
                ->findOrFail($id);

            // Increment view count
            $job->increment('view_count');

            return $this->successResponse([
                'id' => $job->id,
                'title' => $job->title,
                'description' => $job->description,
                'location' => $job->location,
                'salary' => $job->salary,
                'type' => $job->type,
                'view_count' => $job->view_count,
                'created_at' => $job->created_at,
                'updated_at' => $job->updated_at,
                'company' => [
                    'id' => $job->company->id,
                    'name' => $job->company->name,
                    'address' => $job->company->address,
                    'website' => $job->company->website,
                ],
                'category' => [
                    'id' => $job->category->id,
                    'name' => $job->category->name,
                ],
                'applications_count' => $job->applications->count(),
            ], 'Job details retrieved successfully');

        } catch (\Exception $e) {
            return $this->errorResponse('Job not found', null, 404);
        }
    }
}
