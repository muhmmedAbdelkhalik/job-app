# 📱 Mobile Job API - Postman Collection

This Postman collection provides comprehensive testing for the Mobile Job API system.

## 🚀 Quick Start

### 1. Import Collection & Environment
1. Open Postman
2. Click **Import** button
3. Import both files:
   - `Mobile_Job_API.postman_collection.json`
   - `Mobile_Job_API_Environment.postman_environment.json`

### 2. Set Up Environment
1. Select **"Mobile Job API Environment"** from the environment dropdown
2. Update the `base_url` if your server is running on a different port
3. Update `resume_file_path` to point to a valid resume file for testing

### 3. Start Testing
1. Make sure your Laravel server is running: `php artisan serve`
2. Start with **Authentication → Register User**
3. The collection will automatically save your auth token for subsequent requests

## 📋 Collection Structure

### 🔐 Authentication
- **Register User** - Create new user account
- **Login User** - Authenticate existing user
- **Logout User** - Revoke authentication token

### 👤 Profile Management
- **Get Profile** - Retrieve user profile information
- **Update Profile** - Modify user profile details

### 💼 Job Management
- **List Jobs** - Browse available job vacancies
- **Get Job Details** - View specific job information

### 📄 Job Applications
- **List Applications** - View user's job applications
- **Get Application Details** - View specific application
- **Apply for Job** - Submit job application

### 📄 Resume Management
- **List Resumes** - View user's resumes
- **Get Resume Details** - View specific resume
- **Upload Resume** - Add new resume file
- **Update Resume** - Modify resume information
- **Delete Resume** - Remove resume

## 🔧 Environment Variables

| Variable | Description | Example |
|----------|-------------|---------|
| `base_url` | API base URL | `http://localhost:8000` |
| `user_email` | Test user email | `testuser@example.com` |
| `user_password` | Test user password | `password123` |
| `auth_token` | Authentication token | Auto-generated |
| `job_id` | Sample job ID | `0198b354-2e1a-73f6-86b0-4547216b6c91` |
| `resume_file_path` | Path to resume file | `/path/to/resume.pdf` |

## 🧪 Testing Features

### ✅ Automatic Token Management
- Login/Register automatically saves auth token
- All protected endpoints use the saved token
- No manual token copying required

### ✅ Dynamic Data Generation
- Unique email addresses generated for each test run
- Prevents conflicts with existing data
- Ensures clean test environment

### ✅ Response Validation
- Automatic status code checking
- Token extraction and storage
- Error response validation

### ✅ Query Parameters
- Pagination support (`page`, `per_page`)
- Search and filtering (`search`, `location`, `type`)
- Status filtering for applications

## 📊 API Response Format

All endpoints return consistent JSON responses:

### Success Response
```json
{
  "success": true,
  "message": "Operation successful",
  "data": { ... },
  "meta": { ... },
  "errors": null
}
```

### Error Response
```json
{
  "success": false,
  "message": "Error message",
  "data": null,
  "meta": null,
  "errors": { ... }
}
```

## 🔍 Testing Scenarios

### 1. Complete User Flow
1. Register new user
2. Login with credentials
3. Get user profile
4. Browse available jobs
5. Upload resume
6. Apply for a job
7. Check application status
8. Logout

### 2. Error Handling
1. Try registering with existing email
2. Login with wrong credentials
3. Access protected endpoint without token
4. Apply for job without resume

### 3. Pagination Testing
1. List jobs with different page sizes
2. Test search functionality
3. Filter by location and job type
4. Test application status filtering

## 🛠️ Customization

### Adding New Endpoints
1. Right-click on collection
2. Select "Add Request"
3. Configure method, URL, headers, and body
4. Add to appropriate folder

### Modifying Environment Variables
1. Click on environment name
2. Add or modify variables
3. Save changes

### Creating Test Scripts
1. Select request
2. Go to "Tests" tab
3. Add JavaScript validation code
4. Use `pm.response.json()` to access response data

## 📱 Mobile App Integration

This collection serves as a reference for mobile app developers:

### Authentication Flow
```javascript
// Register
POST /api/v1/auth/register
{
  "name": "User Name",
  "email": "user@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}

// Login
POST /api/v1/auth/login
{
  "email": "user@example.com",
  "password": "password123"
}

// Use token in headers
Authorization: Bearer {token}
```

### Job Browsing
```javascript
// List jobs with filters
GET /api/v1/jobs?search=developer&location=remote&type=full-time&page=1&per_page=15

// Get job details
GET /api/v1/jobs/{job_id}
```

### Job Application
```javascript
// Apply for job
POST /api/v1/jobs/{job_id}/apply
{
  "resume_id": "resume_uuid"
}
```

## 🚨 Troubleshooting

### Common Issues

1. **401 Unauthorized**
   - Check if auth token is set
   - Verify token is valid and not expired
   - Ensure Authorization header format: `Bearer {token}`

2. **422 Validation Error**
   - Check request body format
   - Verify required fields are provided
   - Check data types and formats

3. **404 Not Found**
   - Verify endpoint URL is correct
   - Check if resource exists
   - Ensure proper HTTP method

4. **429 Too Many Requests**
   - Rate limiting is active
   - Wait before making more requests
   - Check rate limit headers

### Debug Tips

1. **Check Response Headers**
   - Look for rate limit information
   - Check content-type headers
   - Verify CORS settings

2. **Enable Console Logging**
   - Open Postman Console (View → Show Postman Console)
   - Check for JavaScript errors
   - Monitor request/response flow

3. **Test Individual Endpoints**
   - Start with simple GET requests
   - Test authentication separately
   - Verify each step before proceeding

## 📞 Support

For issues or questions:
1. Check the API documentation
2. Review error responses
3. Test with different data
4. Check server logs

---

**Happy Testing! 🎉**
