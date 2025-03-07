<?php

use App\Http\Controllers\AdminUserRegistrationController;
use App\Http\Controllers\AdvertController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ApplicationResponseController;
use App\Http\Controllers\AttacheeBiodataController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\ContactPageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GeneratePDFController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecommendationLetterController;
use App\Http\Controllers\ReportsGenerationController;
use App\Http\Controllers\StudyAreaController;
use App\Http\Controllers\WelcomePageController;
use App\Http\Livewire\Applicant\Home;
use App\Http\Livewire\Attachee\Apply;
use App\Http\Livewire\Attachee\AttacheeNotifications;
use App\Http\Livewire\Attachee\AttacheeNotificationView;
use App\Http\Livewire\Attachee\EvaluationForm;
use App\Http\Livewire\Attachee\Notifications;
use App\Http\Livewire\Attachee\NotificationView;
use App\Http\Livewire\Attachee\ViewApplication;
use App\Http\Livewire\CentralServices\AdvertView;
use App\Http\Livewire\CentralServices\ApplicantBiodata;
use App\Http\Livewire\CentralServices\ApplicantProfile;
use App\Http\Livewire\CentralServices\ApplicantUsers;
use App\Http\Livewire\CentralServices\ApplicationDetails;
use App\Http\Livewire\CentralServices\ApplicationDocuments;
use App\Http\Livewire\CentralServices\Applications;
use App\Http\Livewire\CentralServices\ApplicationsData;
use App\Http\Livewire\CentralServices\AttacheeUsers;
use App\Http\Livewire\CentralServices\Departments;
use App\Http\Livewire\CentralServices\DepartmentsUsers;
use App\Http\Livewire\CentralServices\DepartmentView;
use App\Http\Livewire\CentralServices\DipcaUsers;
use App\Http\Livewire\CentralServices\EditAdvert;
use App\Http\Livewire\CentralServices\GenerateEvaluations;
use App\Http\Livewire\CentralServices\GenerateReports;
use App\Http\Livewire\CentralServices\Users;
use App\Http\Livewire\CentralServices\ViewUserProfile;
use App\Http\Livewire\Departments\AdvertApplications;
use App\Http\Livewire\Departments\AttacheeDismissal;
use App\Http\Livewire\Departments\AttacheeReporting;
use App\Http\Livewire\Departments\DepartmentAdvertView;
use App\Http\Livewire\Departments\DepartmentEditAdvert;
use App\Http\Livewire\Departments\DepartmentNotifications;
use App\Http\Livewire\Departments\DepartmentNotificationView;
use App\Http\Livewire\Departments\EditStudyArea;
use App\Http\Livewire\Departments\RecommendationLetters;
use App\Http\Livewire\Departments\StudyAreaView;
use App\Http\Livewire\Departments\UploadRecommendationLetters;
use App\Http\Livewire\Departments\ViewApplicantBiodata;
use App\Http\Livewire\Departments\ViewApplicantProfile;
use App\Http\Livewire\Departments\ViewApplicationDetails;
use App\Http\Livewire\Dipca\GenerateReports as DipcaGenerateReports;
use App\Http\Livewire\Dipca\Notifications as DipcaNotifications;
use App\Http\Livewire\HomePage;
use App\Models\Advert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/test', function () {
    return (view('test'));
});
// Route::get('/pdf', [GeneratePDFController::class, 'generatePdf']);
Route::get('/', HomePage::class)->name('welcome.page');
Route::get('/home-page', function () {
    return view('home');
})->name('home');
Route::get('/adverts/{id}', [WelcomePageController::class, 'show'])->name('guest.view_advert');
Route::get('/registration-successful', function () {
    return view('notifications.registration-success');
})->name('registration.success');
Route::get('/account-activation-notice', function () {
    return view('notifications.account-activation');
})->name('account_activation_notice');

Route::get(
    '/dashboard',
    [DashboardController::class, 'index']
)->middleware(['auth', 'is_active'])->name('dashboard');

Route::get(
    '/contacts',
    [ContactPageController::class, 'index']
)->name('contacts');

Route::middleware(['auth', 'is_active'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::put('change-password', [PasswordController::class, 'changePassword'])->name('password.change_password');

});
// Route::post(
//     '/broadcasting/custom-auth',
//     function () {
//         return auth()->user();
//     }
// );

require __DIR__ . '/auth.php';

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/*------------------------------------------
--------------------------------------------
All Attachee Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:attachee', 'prevent-back-history', 'verified', 'is_active'])->group(function () {

    Route::get('/attachee/home', Home::class)->name('attachee.home');
    Route::get('/adverts/{id}/apply', [ApplicationController::class, 'create']);
    Route::get('/attachee/biodata', [AttacheeBiodataController::class, 'create'])->name('attachee.biodata');
    Route::get('/attachee/profile', \App\Http\Livewire\Attachee\Profile::class)->name('attachee.profile');
    Route::get('/attachee/my-applications', [ApplicationController::class, 'getAttacheeApplications'])->name('attachee.applications');
    Route::get('/attachee/my-applications/{id}/view-application', ViewApplication::class);
    Route::get('/attachee/my-reviewed-applications', [ApplicationController::class, 'getAttacheeReviewedApplications'])->name('attachee.reviewed_applications');
    Route::get('/attachee/my-reviewed-applications/{id}/links', [ApplicationController::class, 'getAttacheeReviewedApplicationDownloads'])->name('attachee.reviewed_application_downloads');
    Route::get('/attachee/notifications/', AttacheeNotifications::class)->name('attachee.notifications');
    Route::get('/attachee/notifications/{id}', AttacheeNotificationView::class)->name('attachee.notification');
    Route::get('/attachee/application-response-letter/{id}', [ApplicationResponseController::class, 'generateApplicationResponseLetter']);
    //Route::get('/attachee/offer-acceptance-form/{id}', [ApplicationResponseController::class, 'generateOfferAcceptanceForm']);
    //Route::get('/attachee/offer-acceptance-form-upload-page/{id}', [ApplicationResponseController::class, 'showOfferAcceptanceFormUploadPage']);
    //Route::post('/attachee/offer-acceptance-form-upload/{id}', [ApplicationResponseController::class, 'uploadOfferAcceptanceForm']);
    Route::get('/attachee/offer-acceptance/{id}', [ApplicationResponseController::class, 'acceptOffer']);
    Route::get('/attachee/evaluation-form/{id}', EvaluationForm::class)->name('attachee.evaluation_form');
    Route::get('/attachee/evaluation-done', function () {
        return view('attachee.notify-evaluation-done');
    })->name('attachee.evaluation_done');
    Route::get('/attachee/my-recommendation-letters', [RecommendationLetterController::class, 'show'])->name('attachee.recommendation-letters');



});

/*------------------------------------------
--------------------------------------------
All Dipca Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:dipca_admin', 'prevent-back-history', 'is_active'])->group(function () {

    Route::get('/dipca/home', [HomeController::class, 'dipcaHome'])->name('dipca.home');
    Route::get('/dipca/reports', DipcaGenerateReports::class)->name('dipca.reports');
    Route::post('/dipca/download-reports', [ReportsGenerationController::class, 'downloadReport'])->name('dipca.reports_download');
    Route::get('/dipca/notifications/', DipcaNotifications::class)->name('dipca.notifications');


});

/*------------------------------------------
--------------------------------------------
All Departments Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:department_admin', 'prevent-back-history'])->group(function () {

    Route::get('/departments/home', [HomeController::class, 'departmentHome'])->name('departments.home');

    Route::get('/departments/create-study-areas-notification', [StudyAreaController::class, 'createStudyAreasNotification'])->name('departments.create_study_areas_notification');
    Route::get('/departments/create-new-study-area', [StudyAreaController::class, 'create'])->name('departments.new_study_area_form');
    Route::get('/departments/study-areas', [StudyAreaController::class, 'showDepartmentStudyAreas'])->name('departments.study_areas');
    Route::get('/departments/study-areas/{id}', StudyAreaView::class)->name('departments.view_study_area');
    Route::get('/departments/edit-study-area/{id}', EditStudyArea::class)->name('departments.edit_study_area');

    Route::get('/departments/create-new-advert', [AdvertController::class, 'create'])->name('departments.new_advert_form');
    // Route::post('/departments/create-new-advert', [AdvertController::class, 'store'])->name('departments.new_advert_create');
    Route::get('/departments/view-adverts', [AdvertController::class, 'showDepartmentAdverts'])->name('departments.view_adverts');
    Route::get('/departments/applicable-adverts', [AdvertController::class, 'getDepartmentApplicableAdverts'])->name('departments.applicable_adverts');
    Route::get('/departments/view-advert/{id}', DepartmentAdvertView::class)->name('departments.view_advert');
    Route::get('/departments/edit-advert/{id}', DepartmentEditAdvert::class)->name('departments.edit_advert');
    Route::get('/departments/applicable-adverts/{id}/applications', AdvertApplications::class)->name('departments.advert_applications');
    Route::get('/departments/view-application-details/{id}', ViewApplicationDetails::class)->name('departments.view_application_details');
    Route::get('/departments/view-applicant-biodata/{id}', ViewApplicantBiodata::class)->name('departments.view_applicant_biodata');
    Route::get('/departments/view-applicant-profile/{id}', ViewApplicantProfile::class)->name('departments.view_applicant_profile');
    Route::get('/departments/attachee-reporting', AttacheeReporting::class)->name('departments.attachee_reporting');
    Route::get('/departments/attachee-dismissal', AttacheeDismissal::class)->name('departments.attachee_dismissal');
    Route::get('/departments/notifications/', DepartmentNotifications::class)->name('departments.notifications');
    Route::get('/departments/notifications/{id}', DepartmentNotificationView::class)->name('departments.notification');
    Route::get('/departments/recommendation-letters/upload', UploadRecommendationLetters::class)->name('departments.upload_recommendation_letter');
    Route::get('/departments/recommendation-letters', RecommendationLetters::class)->name('departments.recommendation_letters');


    // Route::get('/departments/applicable-adverts/{id}/applications', DepartmentEditAdvert::class)->name('departments.edit_advert');

});

/*------------------------------------------
--------------------------------------------
All Central services Routes List
--------------------------------------------
--------------------------------------------*/
Route::middleware(['auth', 'user-access:central_services_admin', 'prevent-back-history'])->group(function () {

    Route::get('/central-services/home', [HomeController::class, 'centralServicesHome'])->name('central_services.home');
    Route::get('/central-services/view-adverts', [AdvertController::class, 'showCentralServicesAdvertsView'])->name('central_services.view_adverts');
    Route::get('/central-services/view-advert/{id}', AdvertView::class)->name('central_services.view_advert');
    Route::get('/central-services/edit-advert/{id}', EditAdvert::class)->name('central_services.edit_advert');
    Route::get('/central-services/departments/', Departments::class)->name('central_services.departments');
    Route::get('/central-services/departments/{id}', DepartmentView::class)->name('central_services.department_view');
    Route::get('/central-services/aplicant-users', ApplicantUsers::class)->name('central_services.users');
    //Route::get('/central-services/attachee-users', AttacheeUsers::class)->name('central_services.attachee_users');
    Route::get('/central-services/departments-users', DepartmentsUsers::class)->name('central_services.departments_users');
    Route::get('/central-services/dipca-users', DipcaUsers::class)->name('central_services.dipca_users');
    Route::get('/central-services/user-profile-view/{id}', ViewUserProfile::class)->name('central_services.user_profile_view');
    Route::get('/central-services/reset-user-password/{id}', [PasswordController::class, 'showAdminPasswordResetForm'])->name('central_services.reset_user_password');
    Route::post('/central-services/reset-user-password/', [PasswordController::class, 'adminPasswordReset'])->name('central_services.reset_user_password.submit');
    Route::get('/central-services/notifications/', \App\Http\Livewire\CentralServices\Notifications::class)->name('central_services.notifications');
    Route::get('/central-services/department-admin-registration', [AdminUserRegistrationController::class, 'getDepartmentAdminCreationForm'])->name('central_services.department-admin-registration-form');
    Route::get('/central-services/dipca-admin-registration', [AdminUserRegistrationController::class, 'getDipcaAdminCreationForm'])->name('central_services.dipca-admin-registration-form');
    Route::post('/central-services/department-admin-registration', [AdminUserRegistrationController::class, 'storeDepartmentAdmin'])->name('central_services.department-admin-registration');
    Route::post('/central-services/dipca-admin-registration', [AdminUserRegistrationController::class, 'storeDipcaAdmin'])->name('central_services.dipca-admin-registration');
    Route::get('/central-services/applications-data', ApplicationsData::class)->name('central_services.applications_summary');
    Route::get('/central-services/applications', Applications::class)->name('central_services.applications');
    Route::get('/central-services/application-details/{id}', ApplicationDetails::class)->name('central_services.application_details');
    Route::get('/central-services/application-details/{id}/applicant-biodata', ApplicantBiodata::class)->name('central_services.applicant_biodata');
    Route::get('/central-services/application-details/{id}/applicant-profile', ApplicantProfile::class)->name('central_services.applicant_profile');
    Route::get('/central-services/evaluations', GenerateEvaluations::class)->name('central_services.evaluations');
    Route::get('/central-services/reports', GenerateReports::class)->name('central_services.reports');
    Route::post('/central-services/download-reports', [ReportsGenerationController::class, 'downloadReport'])->name('central_services.reports_download');


});