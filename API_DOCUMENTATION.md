# Trilogy Backend API Documentation

## Overview
This is a CodeIgniter PHP application that serves as both a backend API and admin portal for a health and fitness platform. The application handles user management, meal planning, workout routines, and lifestyle tracking.

## Authentication Endpoints

### User Registration
- **Endpoint**: `/api/auth/registration`
- **Method**: POST
- **Parameters**:
  - name (string)
  - email_id (string)
  - password (string)
- **Response**: User profile data including:
  - cust_id
  - user_code
  - name
  - email_id
  - age
  - height
  - weight
  - gender
  - activity_lavel
  - allergies
  - health_problem
  - profile_picture

### User Login
- **Endpoint**: `/api/auth/login`
- **Method**: POST
- **Parameters**:
  - email_id (string)
  - password (string)
- **Response**: User profile data or admin credentials

## Profile Management Endpoints

### Reset Password
- **Endpoint**: `/api/profile/resetPassword`
- **Method**: POST
- **Parameters**:
  - cust_id (int)
  - password (string)

### Forget Password - Send OTP
- **Endpoint**: `/api/profile/forgetPasswordOtp`
- **Method**: POST
- **Parameters**:
  - email_id (string)
- **Response**: OTP and customer ID

### Forget Password - Verify OTP
- **Endpoint**: `/api/profile/forgetPasswordVerify`
- **Method**: POST
- **Parameters**:
  - cust_id (int)
  - otp (string)
  - password (string)

### Update Personal Data
- **Endpoint**: `/api/profile/updatePersonalData`
- **Method**: POST
- **Parameters**:
  - cust_id (int)
  - name (string)
  - email_id (string)
  - age (int)
  - gender (string)
  - height (float)
  - weight (float)
  - country (string)

### Update Fitness Data
- **Endpoint**: `/api/profile/updateFitnessData`
- **Method**: POST
- **Parameters**:
  - cust_id (int)
  - activity_lavel (float)
  - allergies (string)
  - health_problem (string)

### Set Goal
- **Endpoint**: `/api/profile/setGoal`
- **Method**: POST
- **Parameters**:
  - cust_id (int)
  - goal_type (int)
  - start_date (date)
  - duration (int)

### Get User Goal
- **Endpoint**: `/api/profile/getUserGoal`
- **Method**: POST
- **Parameters**:
  - cust_id (int)

### Get Day Remaining Calorie
- **Endpoint**: `/api/profile/getDayRemainingCalorie`
- **Method**: POST
- **Parameters**:
  - cust_id (int)
  - date (date)

### Set Profile Picture
- **Endpoint**: `/api/profile/setProfilePicture`
- **Method**: POST
- **Parameters**:
  - cust_id (int)
  - profile_picture (file)

### Fetch Profile Data
- **Endpoint**: `/api/profile/fetchProfileData`
- **Method**: POST
- **Parameters**:
  - cust_id (int)

### Get Allergies List
- **Endpoint**: `/api/profile/getAllergiesList`
- **Method**: POST

### Get Health Problem List
- **Endpoint**: `/api/profile/getHealthProblem`
- **Method**: POST

### Get User Stats
- **Endpoint**: `/api/profile/getTotal`
- **Method**: POST
- **Parameters**:
  - cust_id (int)

### Delete Account
- **Endpoint**: `/api/profile/deleteAccount`
- **Method**: POST
- **Parameters**:
  - cust_id (int)

## Core Services Endpoints

### User Profile Management
- **Set Personal Profile**: `/api/services` (action: setUserPersonalProfile)
- **Get Personal Profile**: `/api/services` (action: getUserPersonalProfile)
- **Set Profile Picture**: `/api/services` (action: setProfilePicture)
- **Set Competition Picture**: `/api/services` (action: setCompetitionPicture)

### Package Management
- **Get Packages**: `/api/services` (action: getPackages)
- **Get Six Week Batch**: `/api/services` (action: getSixWeekBatch)
- **Validate Package**: `/api/services` (action: validatePackage)
- **Validate Coupon**: `/api/services` (action: validateCoupon)
- **Set User Package**: `/api/services` (action: setUserPackage)
- **Get User Package**: `/api/services` (action: getUserPackage)
- **Check Package Start**: `/api/services` (action: checkUserPackageStart)

### Meal Planning
- **Get Meal Types**: `/api/meal/getMealType`
- **Get Meal List**: `/api/meal/getMealList`
  - Parameters: meal_type_id, cust_id, date
- **Select Meal**: `/api/meal/selectMeal`
  - Parameters: meal_type_id, meal_id, cust_id, date
- **Remove Meal**: `/api/meal/removeMeal`
  - Parameters: meal_type_id, meal_id, cust_id, date
- **Get My Selected Meals**: `/api/meal/mySelectedMeal`
  - Parameters: meal_type_id, cust_id, date
- **Get Meal Details**: `/api/meal/mealDetails`
  - Parameters: meal_id, cust_id, meal_type_id
- **Get Dietary Info**: `/api/services` (action: getDietary)
- **Get Assign Meal (6 Week)**: `/api/services` (action: getAssignMealSixWeek)
- **Get Assign Meal (6 Week Filter)**: `/api/services` (action: getAssignMealSixWeekFilter)
- **Set Selected Meal**: `/api/services` (action: setSelectedMeal)
- **Get My Selected Meal (6 Week)**: `/api/services` (action: getMySelectedMealSixWeek)
- **Get Meal Recipe (6 Week)**: `/api/services` (action: getMealRecipeforSixWeekByUser)

### Workout Management
- **Get Workout Program**: `/api/workout/getWorkoutProgram`
  - Parameters: cust_id, workout_level_id
- **Get Workout Level**: `/api/workout/getWorkoutLevel`
- **Select Workout**: `/api/workout/selectWorkout`
  - Parameters: cust_id, program_id
- **Remove Workout Program**: `/api/workout/removeWorkoutProgram`
  - Parameters: cust_id, program_id
- **Get My Selected Workout Program**: `/api/workout/mySelectedWorkoutProgram`
  - Parameters: cust_id
- **Get Week List**: `/api/workout/weekList`
  - Parameters: cust_id, program_id
- **Get Exercise List**: `/api/workout/getExerciseList`
  - Parameters: program_id, week, day
- **Get Exercise Details**: `/api/workout/getExerciseDetails`
  - Parameters: exercise_id
- **Get Workout Group**: `/api/services` (action: getWorkOutGroup)
- **Get Workout (6 Week)**: `/api/services` (action: getWorkoutSixWeek)
- **Get Trainers**: `/api/services` (action: getTrainersSixWeek)
- **Get Trainer Workouts**: `/api/services` (action: getTrainersWorkoutSixWeek)
- **Manage Favorite Workouts**: `/api/services` (actions: setTrainersWorkoutSixWeekFavorite, removeTrainersWorkoutSixWeekFavorite)
- **Get Favorite Workout List**: `/api/services` (action: getTrainersWorkoutSixWeekFavoriteList)

### Physiotherapy Management
- **Get Physio Program**: `/api/physio/getPhysioProgram`
  - Parameters: cust_id
- **Select Physio Program**: `/api/physio/selectPhysioProgram`
  - Parameters: cust_id, program_id
- **Remove Physio Program**: `/api/physio/removePhysioProgram`
  - Parameters: cust_id, program_id
- **Get My Selected Physio Program**: `/api/physio/mySelectedPhysioProgram`
  - Parameters: cust_id
- **Get Week List**: `/api/physio/weekList`
  - Parameters: cust_id, program_id
- **Get Exercise List**: `/api/physio/getExerciseList`
  - Parameters: program_id, week, day
- **Get Exercise Details**: `/api/physio/getExerciseDetails`
  - Parameters: exercise_id

### Lifestyle Management
- **Set Goal**: `/api/lifestyle/setGoal`
  - Parameters: cust_id, start_date, weightloss, weekcount
- **Set Lifestyle Goal**: `/api/services` (action: setLifeStyleGoal)
- **End Lifestyle Goal**: `/api/services` (action: endLifeStyleGoal)
- **Get Lifestyle Goal**: `/api/services` (action: getLifeStyleGoal)
- **Get Lifestyle Week List**: `/api/services` (action: getLifeStyleWeekList)
- **Manage Lifestyle Meals**: `/api/services` (actions: setLifeStyleMealByType, setLifeStyleMultipleMealByType)
- **Get Lifestyle Meal Details**: `/api/services` (action: getLifeStyleMealDetailsByID)

### Communication
- **Get Callback Availability**: `/api/services` (action: getCallbackAvailability)
- **Get Callback Slots**: `/api/services` (action: getCallbackSlots)
- **Set Callback**: `/api/services` (action: setCallback)
- **Get My Callbacks**: `/api/services` (action: getMyCallback)
- **Chat Management**: `/api/services` (actions: getChatData, setChatData)

## Admin Portal Endpoints

### Authentication
- **Admin Login**: `/admin/login`
- **Admin Signout**: `/admin/signout`
- **Admin Dashboard**: `/admin/dashboard`

### User Management
- **Users**: `/admin/users`
  - User listing: `/admin/users`
  - User details: `/admin/users/[user_id]`
  - Add meal to user: `/admin/users/addmeal/[user_id]/[type]`
  - Update meal: `/admin/users/updatemeal/[user_id]/[type]/[id]`
  - View user meal: `/admin/users/viewMeal/[user_id]/[type]`
  - View one-to-one meal: `/admin/users/viewMealOnetoOne/[user_id]/[type]`
  - View user workout: `/admin/users/viewWorkout/[user_id]/[type]`
  - Add workout to user: `/admin/users/addworkout/[user_id]/[type]`
  - View one-to-one workout: `/admin/users/viewWorkoutOnetoOne/[user_id]/[type]`
  - Update workout: `/admin/users/updateworkout/[user_id]/[type]/[id]`
  - Export users: `/admin/exportuser`

### Payment Management
- **Orders**: `/admin/orders`
- **Payments**: `/admin/payments`

### Meal Management
- **Meal Types**: `/admin/mealtype`
- **Food Types**: `/admin/foodtype`
- **Diet Types**: `/admin/diettype`
- **Nutrition**: `/admin/nutrition`
- **Meals List**: `/admin/meals`
  - Add new meal: `/admin/meals/addNewItem`
  - Update meal: `/admin/meals/updateMealItem/[id]`
  - Duplicate meal: `/admin/meals/duplicateMeal/[id]`
  - Delete meal: `/admin/meals/deletemeal/[id]`

### Workout Management
- **Workout Level**: `/admin/workout/workoutlevel`
- **Workout Coach**: `/admin/workout/workoutcoach`
  - Add coach: `/admin/workout/addcoach`
  - Update coach: `/admin/workout/updatecoach/[id]`
  - Delete coach: `/admin/workout/deletecoach/[id]`
- **Muscle Group**: `/admin/workout/musclegroup`
- **Workout Types**: `/admin/workout/workouttypes`
- **Workout Equipment**: `/admin/workout/equipments`
- **Workout Routine**: `/admin/workout/routine`
  - Add routine: `/admin/workout/addroutine`
  - Update routine: `/admin/workout/updateroutine/[id]`
- **Exercises**: `/admin/workout`
  - Add exercise: `/admin/workout/addnewexcercise`
  - Update exercise: `/admin/workout/updateexcercise/[id]`
  - Duplicate exercise: `/admin/workout/duplicateexcercise/[id]`

### Physiotherapy Management
- **Physio Program**: `/admin/physio/program`
  - Add program: `/admin/physio/addprogram`
  - Update program: `/admin/physio/updateprogram/[id]`
- **Physio Exercises**: `/admin/physio`
  - Add exercise: `/admin/physio/addnewexcercise`
  - Update exercise: `/admin/physio/updateexcercise/[id]`
  - Duplicate exercise: `/admin/physio/duplicateexcercise/[id]`

### Health Management
- **Allergies**: `/admin/allergies`
- **Health Problems**: `/admin/healthproblem`

### Marketing Management
- **Coupon**: `/admin/coupon`
- **Competition**: `/admin/competition`

## Public Website Endpoints
- **Home**: `/`
- **Lifestyle**: `/lifestyle`
- **Privacy Policy**: `/privacypolicy`
- **Know More**: `/knowmore`
- **Refund Policy**: `/refundpolicy`
- **Terms & Conditions**: `/termsconditions`
- **Join Now**: `/joinnow`
- **Thank You Page**: `/thankyoupage`
- **Success Payment Page**: `/successpaymentpage`
- **Cancel Payment Page**: `/cancelpaymentpage`
- **Join Now Weeks**: `/joinnowweeks`
- **Six Weeks**: `/sixweeks`
- **Buy Now**: `/buyNow/[id]`
- **Buy Now Submit**: `/buynowsubmit`
- **Buy Now Process**: `/buynowprocess`
- **Buy Now Success**: `/buyNowSuccess`
- **Validate Coupon**: `/validateCouponode`
- **Download Our App**: `/downloadourapp`

## Data Models

### User Data
- Basic Information
  - Name
  - Email
  - Password
  - User Code
  - Profile Picture
- Health Information
  - Age
  - Height
  - Weight
  - Gender
  - Activity Level
  - Allergies
  - Health Problems

### Package Data
- Package Details
- Duration
- Price
- Features
- Coupon Codes

### Meal Data
- Meal Types
- Ingredients
- Recipes
- Nutritional Information
- Dietary Restrictions

### Workout Data
- Exercise Lists
- Workout Routines
- Trainer Information
- Difficulty Levels
- Equipment Requirements

### Lifestyle Data
- Goals
- Progress Tracking
- Weekly Plans
- Meal Assignments
- Workout Assignments

## Security Features
- API Key Authentication
- Password Hashing
- CORS Headers
- Session Management
- Input Validation

## Common Response Format
```json
{
    "success": 0|1,
    "message": "Response message",
    "data": {
        // Response data object
    }
}
```

## Error Handling
- Invalid API Key
- Missing Required Parameters
- Invalid Data Format
- User Authentication Failures
- Database Operation Failures

## Notes
1. All API endpoints require an API key for authentication
2. Most endpoints support both GET and POST methods
3. Response times are optimized with memory limit and timeout settings
4. The application uses Europe/London timezone
5. File uploads are supported for profile pictures and competition pictures
6. BMR (Basal Metabolic Rate) calculation is implemented for both male and female users
7. The system supports 6-week meal and workout programs
8. All endpoints include proper validation for required parameters
9. The application supports multiple user types (admin, regular users)
10. The system includes comprehensive health and fitness tracking features
11. Payment processing is integrated for package purchases
12. Email notifications are sent for password reset and other important actions 