<?php

defined('BASEPATH') OR exit('No direct script access allowed');



/*

| -------------------------------------------------------------------------

| URI ROUTING

| -------------------------------------------------------------------------

| This file lets you re-map URI requests to specific controller functions.

|

| Typically there is a one-to-one relationship between a URL string

| and its corresponding controller class/method. The segments in a

| URL normally follow this pattern:

|

|	example.com/class/method/id/

|

| In some instances, however, you may want to remap this relationship

| so that a different class/function is called than the one

| corresponding to the URL.

|

| Please see the user guide for complete details:

|

|	https://codeigniter.com/user_guide/general/routing.html

|

| -------------------------------------------------------------------------

| RESERVED ROUTES

| -------------------------------------------------------------------------

|

| There are three reserved routes:

|

|	$route['default_controller'] = 'welcome';

|

| This route indicates which controller class should be loaded if the

| URI contains no data. In the above example, the "welcome" class

| would be loaded.

|

|	$route['404_override'] = 'errors/page_missing';

|

| This route will tell the Router which controller/method to use if those

| provided in the URL cannot be matched to a valid route.

|

|	$route['translate_uri_dashes'] = FALSE;

|

| This is not exactly a route, but allows you to automatically route

| controller and method names that contain dashes. '-' isn't a valid

| class or method name character, so it requires translation.

| When you set this option to TRUE, it will replace ALL dashes in the

| controller and method URI segments.

|

| Examples:	my-controller/index	-> my_controller/index

|		my-controller/my-method	-> my_controller/my_method

*/

$route['default_controller'] = 'home';

$route['404_override'] = '';

$route['translate_uri_dashes'] = FALSE;


// Admin Routes

$route['admin'] = 'admin/login';

$route['admin/dashboard'] = 'admin/dashboard';



/* Master Data */

/* $route['admin/packages'] = 'admincontrl/Packages/index';
$route['admin/packages/updatepackages/(:any)'] = 'admincontrl/Packages/updatePackageData/$1';
$route['admin/location'] = 'admincontrl/Location/index';
$route['admin/weekdrop'] = 'admincontrl/Weekdrop/index';
$route['admin/batch'] = 'admincontrl/Weekdrop/viewBatch'; */
$route['admin/allergies'] = 'admincontrl/allergies/viewAllergies';
$route['admin/healthproblem'] = 'admincontrl/healthproblem/viewHealthProblem';

$route['admin/mealtype'] = 'admincontrl/meals/viewMealType';
$route['admin/foodtype'] = 'admincontrl/food/viewFoodType';
$route['admin/diettype'] = 'admincontrl/diet/viewDietType';
$route['admin/nutrition'] = 'admincontrl/nutrition/index';
$route['admin/meals'] = 'admincontrl/mealmaster/index';
$route['admin/meals/addNewItem'] = 'admincontrl/mealmaster/addNewMeals';
$route['admin/meals/updateMealItem/(:any)'] = 'admincontrl/mealmaster/updateMeals/$1';
$route['admin/meals/duplicateMeal/(:any)'] = 'admincontrl/mealmaster/duplicateMeal/$1';
$route['admin/meals/deletemeal/(:any)'] = 'admincontrl/meals/deletemeal/$1';

$route['admin/coupon'] = 'admincontrl/coupon/index';

$route['admin/competition'] = 'admincontrl/competition/index';

$route['admin/payments'] = 'admincontrl/users/viewpayments';
$route['admin/users'] = 'admincontrl/users/viewUser';
$route['admin/users/(:any)'] = 'admincontrl/users/viewUser/$1';
$route['admin/users/addmeal/(:any)/(:any)'] = 'admincontrl/users/addUserPackageMealByUserId/$1/$2';
$route['admin/users/updatemeal/(:any)/(:any)/(:any)'] = 'admincontrl/users/updateOnoetoOneMealByUserId/$1/$2/$3';
$route['admin/users/viewMeal/(:any)/(:any)'] = 'admincontrl/users/viewUserMeal/$1/$2';
$route['admin/users/viewMealOnetoOne/(:any)/(:any)'] = 'admincontrl/users/viewUserMealOne/$1/$2';


$route['admin/workout/workoutlevel'] = 'admincontrl/Workoutmaster/viewworkoutlevel';
$route['admin/workout/workoutcoach'] = 'admincontrl/Workoutmaster/viewworkoutcoach';
$route['admin/workout/addcoach'] = 'admincontrl/Workoutmaster/workoutAddUpdatecoach';
$route['admin/workout/updatecoach/(:any)'] = 'admincontrl/Workoutmaster/workoutAddUpdatecoach/$1';
$route['admin/workout/deletecoach/(:any)'] = 'admincontrl/Workoutmaster/deletecoach/$1';

$route['admin/workout/musclegroup'] = 'admincontrl/Workoutmaster/musclegroup';
$route['admin/workout/workouttypes'] = 'admincontrl/Workoutmaster/workouttypes';
$route['admin/workout/equipments'] = 'admincontrl/Workoutmaster/workoutequipments';
$route['admin/workout/routine'] = 'admincontrl/Workout/workoutroutine';
$route['admin/workout/addroutine'] = 'admincontrl/Workout/workoutAddUpdateRoutine';
$route['admin/workout/updateroutine/(:any)'] = 'admincontrl/Workout/workoutAddUpdateRoutine/$1';
$route['admin/workout'] = 'admincontrl/Workout/index';
$route['admin/workout/addnewexcercise'] = 'admincontrl/Workout/addUpdateExcercise';
$route['admin/workout/updateexcercise/(:any)'] = 'admincontrl/Workout/addUpdateExcercise/$1';
$route['admin/workout/duplicateexcercise/(:any)'] = 'admincontrl/Workout/duplicateExcercise/$1';

$route['admin/users/viewWorkout/(:any)/(:any)'] = 'admincontrl/users/viewUserWorkout/$1/$2';
$route['admin/users/addworkout/(:any)/(:any)'] = 'admincontrl/users/addUserPackageWorkoutByUserId/$1/$2';
$route['admin/users/viewWorkoutOnetoOne/(:any)/(:any)'] = 'admincontrl/users/viewUserWorkoutOne/$1/$2';
$route['admin/users/updateworkout/(:any)/(:any)/(:any)'] = 'admincontrl/users/updateOnoetoOneWorkoutByUserId/$1/$2/$3';
$route['admin/orders'] = 'admincontrl/users/orderHistory';
$route['admin/exportuser'] = 'admincontrl/users/exportUser';

/* PHYSIO */
$route['admin/physio/program'] = 'admincontrl/Physio/physioprogram';
$route['admin/physio/addprogram'] = 'admincontrl/Physio/physioAddUpdateProgram';
$route['admin/physio/updateprogram/(:any)'] = 'admincontrl/Physio/physioAddUpdateProgram/$1';
$route['admin/physio'] = 'admincontrl/Physio/index';
$route['admin/physio/addnewexcercise'] = 'admincontrl/Physio/addUpdateExcercise';
$route['admin/physio/updateexcercise/(:any)'] = 'admincontrl/Physio/addUpdateExcercise/$1';
$route['admin/physio/duplicateexcercise/(:any)'] = 'admincontrl/Physio/addDuplicateExcercise/$1';



/* $route['admin/meal_tags_master'] = 'admin/meal_tags_master';



$route['admin/ingredients'] = 'admin/ingredients';

$route['admin/meals'] = 'admin/meals';

$route['admin/exercises'] = 'admin/exercises';

$route['admin/workouts'] = 'admin/workouts';

$route['admin/plans'] = 'admin/plans'; */





/* signout */

$route['signout'] = 'admin/signout';



