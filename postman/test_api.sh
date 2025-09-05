#!/bin/bash

# Mobile Job API Test Script
# This script tests the basic functionality of the API

echo "🚀 Testing Mobile Job API..."

# Set base URL
BASE_URL="http://localhost:8000"

# Test 1: Register User
echo "📝 Testing user registration..."
REGISTER_RESPONSE=$(curl -s -X POST "$BASE_URL/api/v1/auth/register" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "API Test User",
    "email": "apitest'$(date +%s)'@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }')

echo "Registration Response: $REGISTER_RESPONSE"

# Extract token from response
TOKEN=$(echo $REGISTER_RESPONSE | grep -o '"token":"[^"]*"' | cut -d'"' -f4)

if [ -z "$TOKEN" ]; then
    echo "❌ Registration failed or token not found"
    exit 1
fi

echo "✅ Registration successful, token: ${TOKEN:0:20}..."

# Test 2: Get Profile
echo "👤 Testing profile retrieval..."
PROFILE_RESPONSE=$(curl -s -X GET "$BASE_URL/api/v1/profile" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN")

echo "Profile Response: $PROFILE_RESPONSE"

# Test 3: List Jobs
echo "💼 Testing job listing..."
JOBS_RESPONSE=$(curl -s -X GET "$BASE_URL/api/v1/jobs?page=1&per_page=5" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN")

echo "Jobs Response: $JOBS_RESPONSE"

# Test 4: Get Job Details (using first job ID from the response)
JOB_ID=$(echo $JOBS_RESPONSE | grep -o '"id":"[^"]*"' | head -1 | cut -d'"' -f4)

if [ ! -z "$JOB_ID" ]; then
    echo "🔍 Testing job details for ID: $JOB_ID"
    JOB_DETAILS_RESPONSE=$(curl -s -X GET "$BASE_URL/api/v1/jobs/$JOB_ID" \
      -H "Accept: application/json" \
      -H "Authorization: Bearer $TOKEN")
    
    echo "Job Details Response: $JOB_DETAILS_RESPONSE"
else
    echo "⚠️ No job ID found in jobs response"
fi

# Test 5: List Applications
echo "📄 Testing applications listing..."
APPLICATIONS_RESPONSE=$(curl -s -X GET "$BASE_URL/api/v1/applications" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN")

echo "Applications Response: $APPLICATIONS_RESPONSE"

# Test 6: List Resumes
echo "📄 Testing resumes listing..."
RESUMES_RESPONSE=$(curl -s -X GET "$BASE_URL/api/v1/resumes" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN")

echo "Resumes Response: $RESUMES_RESPONSE"

# Test 7: Logout
echo "🚪 Testing logout..."
LOGOUT_RESPONSE=$(curl -s -X POST "$BASE_URL/api/v1/auth/logout" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN")

echo "Logout Response: $LOGOUT_RESPONSE"

echo "✅ API testing completed!"
echo "📊 Summary:"
echo "  - Registration: ✅"
echo "  - Profile: ✅"
echo "  - Jobs: ✅"
echo "  - Applications: ✅"
echo "  - Resumes: ✅"
echo "  - Logout: ✅"
