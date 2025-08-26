<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyJobRequest;
use App\Models\JobApplication;
use App\Models\JobVacany;
use App\Models\Resume;
use App\Services\ResumeAnalysisServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenAI\Laravel\Facades\OpenAI;

class JobVacancyController extends Controller
{
    private $resumeAnalysisServices;

    public function __construct(ResumeAnalysisServices $resumeAnalysisServices)
    {
        $this->resumeAnalysisServices = $resumeAnalysisServices;
    }

    public function show($id)
    {
        $jobVacancies = JobVacany::findOrFail($id);
        return view('job-vacancies.show', compact('jobVacancies'));
    }

    public function apply($id)
    {
        $jobVacancies = JobVacany::findOrFail($id);
        $resumes = Auth::user()->resumes;
        return view('job-vacancies.apply', compact('jobVacancies', 'resumes'));
    }

    public function processApply(ApplyJobRequest $request, String $id)
    {
        $jobVacancies = JobVacany::findOrFail($id);
        $extractedInfo = null;
        
        if ($request->resume_option == 'new_resume') {
            $file = $request->file('resume_file');
            $extension = $file->getClientOriginalExtension();
            $fileName = 'resume_' . time() . '.' . $extension;
            // store in laravel storage
            $path = $file->storeAs('resumes', $fileName, 'cloud');

            $fileUrl = config('filesystems.cloud.url') . '/' . $path;

            // extract resume info
            $extractedInfo = $this->resumeAnalysisServices->extractResumeInfo($path);

            $resume = Resume::create([
                'user_id' => Auth::id(),
                'file_url' => $path,
                'file_name' => $fileName,
                'contact_details' => json_encode([
                    'name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                    'phone' => Auth::user()->phone,
                    'address' => Auth::user()->address,
                ]),
                'summary' => $extractedInfo['summary'],
                'skills' => $extractedInfo['skills'],
                'experience' => is_array($extractedInfo['experience']) ? json_encode($extractedInfo['experience']) : $extractedInfo['experience'],
                'education' => is_array($extractedInfo['education']) || is_object($extractedInfo['education']) ? json_encode($extractedInfo['education']) : $extractedInfo['education'],
            ]);
        } else {
            $resume = $request->input('resume_option');
            $resume = Resume::findOrFail($resume);

            $extractedInfo = [
                'summary' => $resume->summary,
                'skills' => $resume->skills,
                'experience' => $resume->experience,
                'education' => $resume->education,
            ];
        }

        // Evalute Job Application
        $aiEvaluation = $this->resumeAnalysisServices->analyzeResume($jobVacancies, $extractedInfo);

        // Create Job Application
        JobApplication::create([
            'status' => 'pending',
            'ai_score' => $aiEvaluation['ai_score'],
            'ai_feedback' => $aiEvaluation['ai_feedback'],
            'user_id' => Auth::id(),
            'resume_id' => $resume->id,
            'job_vacancy_id' => $id,
        ]);
        return redirect()->route('job-applications.index', $id)->with('success', 'Application submitted successfully.');
    }
}
