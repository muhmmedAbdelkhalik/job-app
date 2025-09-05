# 🚀 Postman Setup Guide

## Quick Setup (5 minutes)

### Step 1: Import Collection
1. Open **Postman**
2. Click **Import** button (top left)
3. Select **`Mobile_Job_API.postman_collection.json`**
4. Click **Import**

### Step 2: Import Environment
1. Click **Import** button again
2. Select **`Mobile_Job_API_Environment.postman_environment.json`**
3. Click **Import**

### Step 3: Select Environment
1. Click the **environment dropdown** (top right)
2. Select **"Mobile Job API Environment"**

### Step 4: Start Testing
1. Make sure your Laravel server is running:
   ```bash
   php artisan serve
   ```
2. Go to **Authentication** folder
3. Click **"Register User"**
4. Click **Send** button
5. ✅ You should see a successful response with a token!

## 🎯 What You Get

### ✅ Complete API Coverage
- **Authentication** (Register, Login, Logout)
- **Profile Management** (Get, Update)
- **Job Management** (List, Details, Search)
- **Job Applications** (List, Apply, Track)
- **Resume Management** (Upload, Manage, Delete)

### ✅ Smart Features
- **Auto Token Management** - Login automatically saves your token
- **Dynamic Data** - Unique emails generated for each test
- **Response Validation** - Automatic success/error checking
- **Environment Variables** - Easy switching between environments

### ✅ Ready for Mobile Development
- **Complete Request Examples** - Copy-paste for mobile apps
- **Response Format** - Know exactly what to expect
- **Error Handling** - Test all error scenarios
- **Authentication Flow** - Complete login/logout process

## 🔧 Customization

### Change Server URL
1. Click **"Mobile Job API Environment"**
2. Edit **`base_url`** variable
3. Change from `http://localhost:8000` to your server URL

### Add Resume File
1. Edit **`resume_file_path`** variable
2. Set path to a valid PDF/DOC file
3. Use **"Upload Resume"** request to test

### Test Different Data
1. Edit **`user_email`**, **`user_name`** variables
2. Or let the collection auto-generate unique emails
3. All requests will use your custom data

## 📱 Mobile App Integration

### Copy Request Examples
1. Right-click any request
2. Select **"Generate Code"**
3. Choose your language (JavaScript, Swift, Kotlin, etc.)
4. Copy the generated code to your mobile app

### Understand Response Format
All responses follow this format:
```json
{
  "success": true/false,
  "message": "Description",
  "data": { ... },
  "meta": { ... },
  "errors": null
}
```

### Authentication Headers
Always include this header for protected endpoints:
```
Authorization: Bearer {your_token}
```

## 🧪 Testing Scenarios

### 1. Happy Path Testing
1. Register → Login → Get Profile → Browse Jobs → Upload Resume → Apply for Job

### 2. Error Testing
1. Try registering with existing email
2. Login with wrong password
3. Access protected endpoint without token
4. Apply for job without resume

### 3. Edge Cases
1. Test pagination (change page numbers)
2. Test search functionality
3. Test filtering by location/type
4. Test large file uploads

## 🚨 Troubleshooting

### Common Issues

**❌ 401 Unauthorized**
- Check if you're logged in
- Verify token is set in environment
- Try logging in again

**❌ 422 Validation Error**
- Check request body format
- Verify all required fields are provided
- Check data types

**❌ 404 Not Found**
- Verify server is running
- Check endpoint URL
- Ensure resource exists

**❌ 429 Too Many Requests**
- Rate limiting is active
- Wait a few seconds before trying again

### Debug Steps
1. **Check Console** - View → Show Postman Console
2. **Verify Environment** - Make sure correct environment is selected
3. **Test Individual Requests** - Start with simple GET requests
4. **Check Server Logs** - Look at Laravel logs for errors

## 📊 Success Indicators

### ✅ Registration Success
```json
{
  "success": true,
  "message": "Registration successful",
  "data": {
    "user": { ... },
    "token": "1|abc123...",
    "token_type": "Bearer"
  }
}
```

### ✅ Login Success
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": { ... },
    "token": "2|def456...",
    "token_type": "Bearer"
  }
}
```

### ✅ Job List Success
```json
{
  "success": true,
  "message": "Success",
  "data": [ ... ],
  "meta": {
    "pagination": {
      "current_page": 1,
      "per_page": 15,
      "total": 25,
      "last_page": 2
    }
  }
}
```

## 🎉 You're Ready!

Your Postman collection is now set up and ready for:
- ✅ **API Testing** - Test all endpoints
- ✅ **Mobile Development** - Use as reference
- ✅ **Team Collaboration** - Share with developers
- ✅ **Documentation** - Complete API reference

**Happy Testing! 🚀**
