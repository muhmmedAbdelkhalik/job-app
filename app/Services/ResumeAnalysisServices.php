<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OpenAI\Laravel\Facades\OpenAI;
use Barryvdh\DomPDF\Facade\Pdf;
use Smalot\PdfParser\Parser;

class ResumeAnalysisServices
{
    public function extractResumeInfo(String $path)
    {
        try {
            // extract raw text from resume pdf file (read and get text)
            $text = $this->extractTextFromPdf($path);
            Log::debug("Successfully extracted text from resume" . strlen($text) . 'characters');

            // Use openAi to organize text into structured data
            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a percise resume parser. Extract informaction exactly as it in the resume without adding any additional information or interpretation. the output should be in json format.'],
                    ['role' => 'user', 'content' => "Parse the following resume content and extract the information as a json Object the exact keys: summary, skills, experience, education. The resume content is: " . $text . 'Return an empty string for keys that are not present in the resume.'],
                ],
                'response_format' => [
                    'type' => 'json_object',
                ],
                'temperature' => 0.1,
            ]);

            $result = $response->choices[0]->message->content;
            Log::debug("Successfully extracted structured data from resume", ['result' => $result]);

            $structuredData = json_decode($result, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("Failed to parse structured data from resume", [
                    'result' => $result,
                    'error' => json_last_error_msg(),
                ]);
                throw new \Exception('Failed to parse structured data from resume');
            }
            $requiredFields = ['summary', 'skills', 'experience', 'education'];
            $missingFields = array_diff($requiredFields, array_keys($structuredData));
            if (count($missingFields) > 0) {
                Log::error("Missing required fields from resume", [
                    'missingFields' => $missingFields,
                ]);
                throw new \Exception('Missing required fields from resume');
            }

            // return structured data
            return [
                'summary' => $structuredData['summary'] ?? '',
                'skills' => $structuredData['skills'] ?? '',
                'experience' => $structuredData['experience'] ?? '',
                'education' => $structuredData['education'] ?? '',
            ];
        } catch (\Exception $e) {
            Log::error("Failed to extract text from resume", [
                'error' => $e->getMessage(),
            ]);
            return [
                'summary' => '',
                'skills' => '',
                'experience' => '',
                'education' => '',
            ];
        }
    }

    public function analyzeResume($jobVacancy, $resumeData)
    {
        try {
            $jobDetails = json_encode([
                'job_title' => $jobVacancy->title,
                'job_description' => $jobVacancy->description,
                'job_location' => $jobVacancy->location,
                'job_salary' => $jobVacancy->salary,
                'job_type' => $jobVacancy->type,
                'job_category' => $jobVacancy->category,
                'job_company' => $jobVacancy->company,
            ]);

            $resumeDetails = json_encode($resumeData);

            $response = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'You are an expert HR professional. You will be given a job description and a resume. You will need to analyze the resume and determine if the candidate is a good fit for the job.
                     the output should be in json format.
                     Provide a score between 0 and 100 for the candidates fit for the job and detailed feedback
                     Response should only be Json that has following keys: ai_score, ai_feedback'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Please evaluate the following resume and job description and provide a score between 0 and 100 for the candidates fit for the job and detailed feedback.
                        The job description is: " . $jobDetails . "The resume is: " . $resumeDetails
                    ],
                ],
                'response_format' => [
                    'type' => 'json_object',
                ],
                'temperature' => 0.1,
            ]);

            $result = $response->choices[0]->message->content;
            Log::debug("Successfully analyzed resume", ['result' => $result]);

            $structuredData = json_decode($result, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error("Failed to parse structured data from resume", [
                    'result' => $result,
                    'error' => json_last_error_msg(),
                ]);
                throw new \Exception('Failed to parse structured data from resume');
            }

            return $structuredData;
        } catch (\Exception $th) {
            Log::error("Failed to analyze resume", [
                'error' => $th->getMessage(),
            ]);
            return [
                'ai_score' => 0,
                'ai_feedback' => 'Failed to analyze resume',
            ];
        }
    }

    private function extractTextFromPdf(String $path): string
    {
        try {
            // get pdf file from storage
            $filePath = parse_url($path, PHP_URL_PATH);
            if (!$filePath) {
                throw new \Exception('Invalid resume file path');
            }

            $filename = basename($filePath);
            $storagePath = "resumes/{$filename}";

            if (!Storage::disk('cloud')->exists($storagePath)) {
                throw new \Exception('Resume file not found');
            }

            $pdfContent = Storage::disk('cloud')->get($storagePath);
            if (!$pdfContent) {
                throw new \Exception('Failed to read resume file');
            }

            // Create a temporary file for processing
            $tempFile = tempnam(sys_get_temp_dir(), 'resume_');
            file_put_contents($tempFile, $pdfContent);

            try {
                // Use smalot/pdfparser to extract text from the PDF
                $text = $this->extractTextFromPdfBasic($tempFile);
                
                // Clean up the temporary file
                unlink($tempFile);
                
                return $text;
                
            } catch (\Exception $extractionException) {
                // Clean up the temporary file
                unlink($tempFile);
                
                Log::error("Failed to extract text from PDF using smalot/pdfparser", [
                    'error' => $extractionException->getMessage(),
                    'path' => $path
                ]);
                
                throw new \Exception('Unable to extract text from PDF: ' . $extractionException->getMessage());
            }
            
        } catch (\Exception $e) {
            Log::error("Failed to extract text from PDF", [
                'error' => $e->getMessage(),
                'path' => $path
            ]);
            throw $e;
        }
    }

    /**
     * Basic PDF text extraction method using smalot/pdfparser
     * This library is specifically designed for extracting text from existing PDFs
     */
    private function extractTextFromPdfBasic(string $filePath): string
    {
        try {
            // Create a new PDF parser instance
            $parser = new Parser();
            
            // Parse the PDF file
            $pdf = $parser->parseFile($filePath);
            
            // Extract text from the PDF
            $text = $pdf->getText();
            
            // Clean up the extracted text
            $text = preg_replace('/\s+/', ' ', $text); // Replace multiple spaces with single space
            $text = trim($text);
            
            // Log success
            Log::debug("Successfully extracted text from PDF using smalot/pdfparser", [
                'textLength' => strlen($text),
                'filePath' => $filePath
            ]);
            
            return $text;
            
        } catch (\Exception $e) {
            Log::error("Failed to extract text from PDF using smalot/pdfparser", [
                'error' => $e->getMessage(),
                'filePath' => $filePath
            ]);
            throw new \Exception('Failed to extract text from PDF: ' . $e->getMessage());
        }
    }
}
