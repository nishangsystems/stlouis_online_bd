<?php

use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\ProgramProvisionController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\CustomApplicationController;
use App\Http\Controllers\Auth\CustomForgotPasswordController;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\Student\HomeController as StudentHomeController;
use App\Http\Controllers\TransactionController;
use App\Http\Resources\SubjectResource;
use App\Models\Resit;
use App\Models\StudentSubject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

Route::get('/clear', function () {
    echo Session::get('applocale');
    $clearcache = Artisan::call('cache:clear');
    echo "Cache cleared<br>";

    $clearview = Artisan::call('view:clear');
    echo "View cleared<br>";

    $clearconfig = Artisan::call('config:cache');
    echo "Config cleared<br>";

});

// test mail sender
Route::get('send_sms', [Controller::class, 'sendSMS']/*function(){
    // $mailer = new MailService();
    // $subject = "Form Submission Notification";
    // $text = "Your application form has been submitted successfully";
    // $data = ['name'=>"GERMANUS K", 'email'=>"germanuskeming@gmail.com"];
    // if(@mail($data['email'], $subject, $text)){
    //     return "success";
    // }else{return "failed";}
    // $mailer->sendPlainMail($subject, $text, $data);

    // $basic  = new \Vonage\Client\Credentials\Basic("8d8bbcf8", "04MLvso1he1b8ANc");
    // $client = new \Vonage\Client($basic);

    // $response = $client->sms()->send(
    //     new \Vonage\SMS\Message\SMS("237699131895", '+237672908239', 'A text message sent using the Nexmo SMS API')
    // );
    
    // $message = $response->current();
    
    // if ($message->getStatus() == 0) {
    //     echo "The message was sent successfully\n";
    // } else {
    //     echo "The message failed with status: " . $message->getStatus() . "\n";
    // }
}*/);

Route::get('set_local/{lang}', [Controller::class, 'set_local'])->name('lang.switch');

Route::get('payment-form',[TransactionController::class,'paymentForm'])->name('payment_form');
Route::post('make-payments',[TransactionController::class,'makePayments'])->name('make_payments');
Route::get('complete-transaction/{transaction_id}',[StudentHomeController::class,'complete_transaction'])->name('complete_transaction');
Route::get('failed-transaction/{transaction_id}',[StudentHomeController::class,'failed_transaction'])->name('failed_transaction');
// Route::get('get-transaction-status/{transaction_id}',[TransactionController::class,'getTransactionStatus'])->name('get_transaction_status');
Route::post('mtn-momo',[TransactionController::class,'mtnCallBack'])->name('mtn_callback');

Route::post('login', [CustomLoginController::class, 'login'])->name('login.submit');
Route::get('login', [CustomLoginController::class, 'showLoginForm'])->name('login');
Route::get('registration', [Controller::class, 'registration'])->name('registration');
Route::post('check_matricule', [Controller::class, 'check_matricule'])->name('check_matricule');
Route::post('createAccount', [Controller::class, 'createAccount'])->name('createAccount');
Route::post('logout', [CustomLoginController::class, 'logout'])->name('logout');

Route::post('reset_password_with_token/password/reset', [CustomForgotPasswordController::class, 'validatePasswordRequest'])->name('reset_password_without_token');
Route::get('reset_password_with_token/{token}/{email}', [CustomForgotPasswordController::class, 'resetForm'])->name('reset');
Route::post('reset_password_with_token', [CustomForgotPasswordController::class, 'resetPassword'])->name('reset_password_with_token');
Route::post('recover_username', [CustomForgotPasswordController::class, 'recover_username'])->name('recover_username');

Route::get('', 'WelcomeController@home');
Route::get('home', 'WelcomeController@home');
// Route::middleware('password_reset')->group(function(){
    // });


Route::get('_api/root/create', [Controller::class, 'create_api_root'])->name('api.create_root');
Route::post('_api/root/create', [Controller::class, 'save_api_root']);


Route::prefix('admin')->name('admin.')->middleware('isAdmin')->group(function () {

    Route::get('', 'Admin\HomeController@index')->name('home');
    Route::get('home', 'Admin\HomeController@index')->name('home');
    Route::get('set_watermark', 'Admin\HomeController@set_watermark')->name('set_watermark');
    Route::post('set_watermark', 'Admin\HomeController@save_watermark');
    Route::get('setayear', 'Admin\HomeController@setayear')->name('setayear');
    Route::post('setayear/{id}', 'Admin\HomeController@setAcademicYear')->name('createacademicyear');
    Route::get('units/{parent_id}/student', 'Admin\ProgramController@students')->name('students.index');

    Route::get('student_list/select/{filter?}', 'Admin\ProgramController@program_levels_list_index')->name('student.bulk.index');
    Route::get('student_list/bulk/{filter}/{item_id}/{year_id?}', 'Admin\ProgramController@bulk_program_levels_list')->name('student.bulk.list');
    Route::get('messages/bulk/{filter}/{item_id}/{recipients}/{year_id?}', 'Admin\ProgramController@bulk_message_notifications')->name('messages.bulk');
    Route::post('messages/bulk/{filter}/{item_id}/{recipients}/{year_id?}', 'Admin\ProgramController@bulk_message_notifications_save')->name('messages.bulk');
    Route::get('programs/{id}/levels', 'Admin\ProgramController@program_levels')->name('programs.levels');
    Route::get('programs/index', 'Admin\ProgramController@program_index')->name('programs.index');
    
    Route::get('search/students/{name}', 'Admin\PayIncomeController@searchStudent')->name('searchStudent');
    Route::get('search/students/', 'Admin\PayIncomeController@get_searchStudent')->name('get_searchStudent');
    Route::get('search/users/', 'Admin\PayIncomeController@get_searchUser')->name('get_searchUser');

    Route::get('users/search', [Controller::class, 'search_user'])->name('users.search');
    
    Route::resource('users', 'Admin\UserController');
    Route::get('student/matricule', 'Admin\StudentController@matric')->name('students.matricule');
    Route::post('student/matricule', 'Admin\StudentController@matricPost')->name('students.matricule');
    Route::post('student/{id}/password/reset', 'Admin\StudentController@reset_password')->name('student.password.reset');
    Route::resource('student', 'Admin\StudentController');
    Route::post('students', 'Admin\StudentController@getStudentsPerClass')->name('getStudent.perClassYear');
    

    Route::get('sub-units/{parent_id}','Admin\ProgramController@getSubUnits')->name('getSubUnits');

    Route::resource('roles','Admin\RolesController');
    Route::get('permissions', 'Admin\RolesController@permissions')->name('roles.permissions');
    Route::get('assign_role', 'Admin\RolesController@rolesView')->name('roles.assign');
    Route::post('assign_role', 'Admin\RolesController@rolesStore')->name('roles.assign.post');
    Route::post('roles/destroy/{id}', 'Admin\RolesController@destroy')->name('roles.destroy');

    Route::prefix('statistics')->name('stats.')->group(function(){
        Route::get('sudents', 'Admin\StatisticsController@students')->name('students');
        Route::get('fees', 'Admin\StatisticsController@fees')->name('fees');
        Route::get('results', 'Admin\StatisticsController@results')->name('results');
        Route::get('income', 'Admin\StatisticsController@income')->name('income');
        Route::get('expenditure', 'Admin\StatisticsController@expenditure')->name('expenditure');
        Route::get('fees/{class_id}', 'Admin\StatisticsController@unitFees')->name('unit-fees');
        Route::get('ie_report', 'Admin\StatisticsController@ie_report')->name('ie_report');
        Route::get('ie_report/monthly', 'Admin\StatisticsController@ie_monthly_report')->name('ie.report');
    });
    
    Route::get('set_letter_head', [AdminHomeController::class, 'set_letter_head'])->name('set_letter_head');
    Route::post('set_letter_head/save', [AdminHomeController::class, 'save_letter_head'])->name('save_letter_head');

    Route::get('students', [StudentController::class, 'index'])->name('students.index');

    Route::get('reset_password', 'Controller@reset_password')->name('reset_password');
    Route::post('reset_password', 'Controller@reset_password_save')->name('reset_password');
    
    Route::get('charges/set', 'Admin\HomeController@set_charges')->name('charges.set');
    Route::post('charges/set', 'Admin\HomeController@save_charges')->name('charges.save');

    // APPLICATION PLATFORM ROUTES ONLY
    Route::get('admission/campus/degrees/{cid?}', [ProgramController::class, 'config_degrees'])->name('admission.campus.degrees');
    Route::post('admission/campus/degrees/{cid?}', [ProgramController::class, 'set_config_degrees']);
    Route::get('admission/degree/certificates/{degree_id?}', [ProgramController::class, 'degree_certificates'])->name('admission.degree.certificates');
    Route::post('admission/degree/certificates/{degree_id?}', [ProgramController::class, 'set_degree_certificates']);
    Route::get('admission/open/{id?}', [ProgramController::class, 'open_admission'])->name('admission.open');
    Route::post('admission/open/{id?}', [ProgramController::class, 'set_open_admission']);
    Route::get('admission/programs/{cid?}', [ProgramController::class, 'config_programs'])->name('admission.programs.config');
    Route::post('admission/programs/{cid?}', [ProgramController::class, 'set_config_programs']);
    Route::get('admission/admit/{id}', [ProgramController::class, 'admit_student'])->name('admission.admit');
    Route::get('admission/show/{id}', [ProgramController::class, 'application_details'])->name('admission.show');
    Route::get('applications', [ProgramController::class, 'applications'])->name('applications.all');
    Route::name('applications.')->prefix('applications')->group(function(){
        Route::get('print_form/{id?}', [ProgramController::class, 'print_application_form'])->name('print_form');
        Route::get('edit/{id?}', [ProgramController::class, 'edit_application_form'])->name('update');
        Route::post('edit/{id?}', [ProgramController::class, 'update_application_form']);
        Route::get('admit/{id?}', [ProgramController::class, 'admit_application_form'])->name('admit');
        Route::post('admit/{id?}', [ProgramController::class, 'admit_student']);
        Route::get('uncompleted/{id?}', [ProgramController::class, 'uncompleted_application_form'])->name('uncompleted');
        Route::get('uncompleted/{id}/bypass', [ProgramController::class, 'bypass_application_form'])->name('bypass');
        Route::get('distant/{id?}', [ProgramController::class, 'distant_application_form'])->name('distant');
        Route::get('admission_letter/{id?}', [ProgramController::class, 'admission_letter'])->name('admission_letter');
        Route::get('program/change/{id?}', [ProgramController::class, 'application_form_change_program'])->name('change_program');
        Route::post('program/change/{id?}', [ProgramController::class, 'change_program']);
        Route::post('change_program/{id?}', [ProgramController::class, 'change_program_save'])->name('_change.program');
        Route::get('by_program/{id?}', [ProgramController::class, 'applications_per_program'])->name('by_program');
        Route::get('by_degree/{id?}', [ProgramController::class, 'applications_per_degree'])->name('by_degree');
        Route::get('by_campus/{id?}', [ProgramController::class, 'applications_per_campus'])->name('by_campus');
        Route::get('finance/general', [ProgramController::class, 'finance_general_report'])->name('finance.general');
        Route::get('degree_certificates', [ProgramController::class, 'get_degree_certificates'])->name('degree_certificates');

    });

    Route::prefix('custom/applications')->name('custom_applications.')->group(function(){
        Route::get('', [CustomApplicationController::class, 'index'])->name('index');
        Route::get('local/create', [CustomApplicationController::class, 'create_local'])->name('local.create');
        Route::post('local/create', [CustomApplicationController::class, 'store_local']);
        Route::get('create', [CustomApplicationController::class, 'create'])->name('create');
        Route::post('create', [CustomApplicationController::class, 'store']);
        Route::get('switch', [CustomApplicationController::class, 'switch_program'])->name('switch');
        Route::post('switch', [CustomApplicationController::class, 'switch_generate_matricule']);
        Route::post('switch_confirmed', [CustomApplicationController::class, 'switch_confirmed'])->name('switch_confirmed');
        Route::get('importadmit', [CustomApplicationController::class, 'import_admit_students'])->name('import');
        Route::post('importadmit', [CustomApplicationController::class, 'import_admit_students_save']);
        Route::get('import', [CustomApplicationController::class, 'import'])->name('mass_import');
        Route::post('import', [CustomApplicationController::class, 'import_save']);
    });


    Route::prefix('reports')->name('reports.')->group(function(){
        Route::get('degree/{degree?}', [ProgramController::class, 'applicants_report_by_degree'])->name('applicants.by_degree');
        Route::get('program/{program?}', [ProgramController::class, 'applicants_report_by_program'])->name('applicants.by_program');
        Route::get('finance/general', [ProgramController::class, 'finance_report_general'])->name('applicants.by_program');
    });

    Route::get('platform/bypass/{student_id?}', [AdminHomeController::class, 'bypass_platform_charges'])->name('platform.bypass');
    Route::post('platform/bypass/{student_id?}', [AdminHomeController::class, 'bypass_save_platform_charges']);
    Route::get('application/bypass/{form_id?}', [AdminHomeController::class, 'bypass_application_fee'])->name('application.bypass');
    Route::post('application/bypass/{form_id?}', [AdminHomeController::class, 'bypass_save_application_fee']);

    
    Route::prefix('program_provisions')->name('program_provisions.')->group(function(){
        Route::get('index', [ProgramProvisionController::class, 'index'])->name('index');
        Route::get('config/{campus_id?}', [ProgramProvisionController::class, 'configure'])->name('config');
        Route::post('config/{campus_id?}', [ProgramProvisionController::class, 'save_configuration']);
    });
});

Route::get('degree{degree_id}programs', [Controller::class, 'degree_programs'])->name('degree_programs');
Route::get('program{program_id}levels', [Controller::class, 'program_levels'])->name('_program_levels');


Route::prefix('student')->name('student.')->middleware(['isStudent', 'plcharge'])->group(function () {
    Route::get('', 'Student\HomeController@index')->name('home');
    Route::get('edit_profile', 'Student\HomeController@edit_profile')->name('edit_profile');
    Route::post('update_profile', 'Student\HomeController@update_profile')->name('update_profile');
    Route::get('subject', 'Student\HomeController@subject')->name('subject');
    Route::get('result/ca', 'Student\HomeController@result')->name('result.ca');
    Route::post('result/ca', 'Student\HomeController@ca_result');
    Route::get('result/ca/download', 'Student\HomeController@ca_result_download');
    Route::get('result/exam', 'Student\HomeController@result')->name('result.exam');
    Route::post('result/exam', 'Student\HomeController@exam_result');
    Route::get('result/exam/download', 'Student\HomeController@exam_result_download');
    Route::get('fee/tution', 'Student\HomeController@fee')->name('fee.tution');
    Route::get('fee/others', 'Student\HomeController@other_incomes')->name('fee.other_incomes');
    Route::get('fee/pay', 'Student\HomeController@pay_fee')->name('pay_fee');
    Route::post('fee/pay', 'Student\HomeController@pay_fee_momo')->name('pay_fee');
    Route::get('others/pay/{id?}', 'Student\HomeController@pay_other_incomes')->name('pay_others');
    Route::post('others/pay/{id?}', 'Student\HomeController@pay_other_incomes_momo')->name('pay_others');
    Route::get('subjects/{id}/notes', 'Student\HomeController@subjectNotes')->name('subject.notes');
    Route::get('boarding_fees/details', 'Student\HomeController@boarding')->name('boarding');
    Route::post('boarding_fees/details/', 'Student\HomeController@getBoardingFeesYear')->name('boarding_fees_details');
    Route::prefix('courses')->name('courses.')->group(function(){
        Route::get('registration', 'Student\HomeController@course_registration')->name('registration');
        Route::post('registration', 'Student\HomeController@register_courses');
        Route::get('registered', 'Student\HomeController@registered_courses')->name('registered');
        Route::get('form_b', 'Student\HomeController@form_b')->name('form_b');
        Route::get('drop', 'Student\HomeController@drop_course')->name('drop');
        Route::get('add', 'Student\HomeController@add_course')->name('add');
        Route::get('content/{subject_id}', 'Student\HomeController@course_content_index')->name('content');
    });
    Route::get('note/index/{course_id}', 'Student\HomeController@course_notes')->name('note.index');
    Route::get('assignment/index/{course_id}', 'Student\HomeController@assignment')->name('assignment.index');
    Route::get('notification/index/{course_id}', 'Student\HomeController@notification')->name('notification.index');
    Route::get('notification/show/{course_id}', 'Student\HomeController@show_notification')->name('notification.show');
    Route::prefix('notification')->name('notification.')->group(function(){
        Route::get('/', 'Student\HomeController@_notifications_index')->name('home');
        Route::get('/class/{class_id}/{campus_id}', 'Student\HomeController@_class_notifications')->name('class');
        Route::get('/department/{department_id}/{campus_id}', 'Student\HomeController@_department_notifications')->name('department');
        Route::get('/program/{program_id}/{campus_id}', 'Student\HomeController@_program_notifications')->name('program');
        Route::get('/school/{campus_id?}', 'Student\HomeController@_school_notifications')->name('school');
        Route::get('/view/{id}', 'Student\HomeController@_program_notifications_show')->name('view');
    });
    Route::prefix('material')->name('material.')->group(function(){
        Route::get('/', 'Student\HomeController@_notifications_index')->name('home');
        Route::get('/class/{class_id}/{campus_id}', 'Student\HomeController@_class_material')->name('class');
        Route::get('/department/{department_id}/{campus_id}', 'Student\HomeController@_department_material')->name('department');
        Route::get('/program/{program_id}/{campus_id}', 'Student\HomeController@_program_material')->name('program');
        Route::get('/school/{campus_id?}', 'Student\HomeController@_school_material')->name('school');
    });
    Route::get('resit/registration', 'Student\HomeController@resit_registration')->name('resit.registration');
    Route::post('resit/registration', 'Student\HomeController@register_resit');
    Route::post('resit/registration/payment', 'Student\HomeController@resit_payment')->name('resit.registration.payment');
    Route::get('resit/registered_courses', 'Student\HomeController@registered_resit_courses')->name('resit.registered_courses');
    Route::get('resit/index', 'Student\HomeController@resit_index')->name('resit.index');
    Route::get('resit/download/{resit_id}', 'Student\HomeController@resit_download')->name('resit.download_courses');
    Route::get('registered_courses/{year?}/{semester?}/{student?}', 'Student\HomeController@registerd_courses')->name('registered_courses');
    Route::get('class-subjects/{level}', 'Student\HomeController@class_subjects')->name('class-subjects');
    Route::get('search_course', 'Student\HomeController@search_course')->name('search_course');
    Route::get('courses/download/{year}/{semester}', 'Student\HomeController@download_courses')->name('courses.download');
    Route::get('stock/report/{year}', 'Student\HomeController@stock_report')->name('stock.report');
    Route::name('transcript.')->prefix('transcripts')->group(function () {
        Route::get('apply/{config_id?}', 'Student\HomeController@apply_transcript')->name('apply');
        Route::post('apply/{config_id?}', 'Student\HomeController@apply_save_transcript');
        Route::get('hostory', 'Student\HomeController@transcript_history')->name('history');
    });
    Route::get('reset_password', 'Controller@reset_password')->name('reset_password');
    Route::post('reset_password', 'Controller@reset_password_save')->name('reset_password');

    Route::get('platform/pay', 'Student\HomeController@pay_platform_charges')->name('platform_charge.pay');
    Route::post('charges/pay', 'Student\HomeController@pay_charges_save')->name('charge.pay')->withoutMiddleware('isStudent');
    Route::get('result/pay', 'Student\HomeController@pay_semester_results')->name('result.pay');
    Route::get('transcript/pay', 'Student\HomeController@pay_transcript_charges')->name('transcript.pay');

    Route::get('online_payments/history', 'Student\HomeController@online_payment_history')->name('online.payments.history');



    // ONLINE APPLICATION PORTAL ROUTES
    Route::get('campus/degree/certs/programs/{campus_id}/{degree_id}/{cert_id}', [Controller::class, 'campusDegreeCertPrograms'])->name('campus.degree.cert.programs');
    Route::get('campus/program/levels/{campus_id}/{program_id}', [Controller::class, 'campusProgramLevels'])->name('campus.program.levels');
    Route::get('campus/programs/{campus_id}', [Controller::class, 'campusPrograms'])->name('campus.programs');
    Route::get('campus/degrees/{campus_id}', [Controller::class, 'campusDegrees'])->name('campus.degrees');
    Route::get('region/divisions/{region_id}', [Controller::class, 'regionDivisions'])->name('region.divisions');
    Route::get('programs/all', [StudentHomeController::class, 'all_programs'])->name('programs.index');
    Route::get('payment/data', [StudentHomeController::class, 'payment_data'])->name('payment.data');
    Route::get('application/basic/{step}/{id?}', [StudentHomeController::class, 'start_basic_application'])->name('application.basic.start');
    Route::post('application/basic/{step}/{id?}', [StudentHomeController::class, 'persist_basic_application']);
    Route::get('application/start/{step}/{id?}', [StudentHomeController::class, 'start_application'])->name('application.start');
    Route::post('application/start/{step}/{id?}', [StudentHomeController::class, 'persist_application']);
    Route::get('application/submit/{id?}', [StudentHomeController::class, 'submit_application'])->name('application.submit');
    Route::post('application/submit/{id?}', [StudentHomeController::class, 'submit_application_save']);
    Route::get('application/form/download/{id?}', [StudentHomeController::class, 'download_application_form'])->name('application.form.download');
    Route::post('application/form/download/{id?}', [StudentHomeController::class, 'download_form']);
    Route::get('application/admission_letter/download/{id?}', [StudentHomeController::class, 'download_admission_letter'])->name('application.admission_letter.download');
    Route::post('application/admission_letter/download/{id?}', [StudentHomeController::class, 'download_admission_letter_save']);
    Route::get('application/payment/processing/{form_id}', [StudentHomeController::class, 'pending_payment'])->name('application.payment.processing');
    Route::get('application/payment/complete/{form_id}', [StudentHomeController::class, 'pending_complete'])->name('application.payment.complete');
    Route::post('application/payment/complete/{form_id}', [StudentHomeController::class, 'pending_complete']);
});

Route::post('student/charges/pay', 'Student\HomeController@pay_charges_save')->name('student.charge.pay');
Route::get('student/platform/pay', 'Student\HomeController@pay_platform_charges')->name('student.platform_charge.pay');
Route::get('student/charges/complete_transaction/{ts_id}', 'Student\HomeController@complete_charges_transaction')->name('student.charges.complete');
Route::get('student/charges/failed_transaction/{ts_id}', 'Student\HomeController@failed_charges_transaction')->name('student.charges.failed');
Route::get('student/tranzak/processing', 'Student\HomeController@tranzak_payment_processing')->name('student.tranzak.processing');
Route::post('student/tranzak/complete', 'Student\HomeController@tranzak_complete')->name('student.tranzak.complete');


Route::get('section-children/{parent}', 'HomeController@children')->name('section-children');
Route::get('section-subjects/{parent}', 'HomeController@subjects')->name('section-subjects');
Route::get('student-search/{name}', 'HomeController@student')->name('student-search');
Route::get('student-search', 'HomeController@student_get')->name('student-search-get');
Route::get('search-all-students/{name}', 'HomeController@searchStudents')->name('search-all-students');
Route::get('search-all-students', 'HomeController@searchStudents_get')->name('get-search-all-students');
Route::get('search-students', 'HomeController@search_students')->name('search_students');
Route::get('search-forms', 'HomeController@search_forms')->name('search_forms');
Route::get('student-fee-search', 'HomeController@fee')->name('student-fee-search');
Route::get('student_rank', 'HomeController@rank')->name('student_rank');
Route::post('student_rank', 'HomeController@rankPost')->name('student_rank');

Route::prefix('course/notification')->name('course.notification.')->group(function(){
    Route::get('{course_id}', 'Teacher\SubjectController@notifications')->name('index');
    Route::get('{course_id}/create', 'Teacher\SubjectController@create_notification')->name('create');
    Route::post('{course_id}/save', 'Teacher\SubjectController@save_notification')->name('save');
    Route::get('{course_id}/edit/{id}', 'Teacher\SubjectController@edit_notification')->name('edit');
    Route::post('{course_id}/update/{id}', 'Teacher\SubjectController@update_notification')->name('update');
    Route::get('{course_id}/delete/{id}', 'Teacher\SubjectController@drop_notification')->name('drop');
    Route::get('{course_id}/show/{id}', 'Teacher\SubjectController@show_notification')->name('show');
});

Route::name('faqs.')->prefix('faqs')->group(function(){
    Route::get('', 'FAQsController@index')->name('index');
    Route::get('create', 'FAQsController@create')->name('create');
    Route::post('create', 'FAQsController@save')->name('save');
    Route::get('edit/{id}', 'FAQsController@edit')->name('edit');
    Route::get('publish/{id}', 'FAQsController@publish')->name('publish');
    Route::get('download/{id}', 'FAQsController@download')->name('download');
    Route::post('update/{id}', 'FAQsController@update')->name('update');
    Route::get('show/{id}', 'FAQsController@show')->name('show');
    Route::get('delete/{id}', 'FAQsController@drop')->name('drop');
});

Route::name('material.')->prefix('{layer}/{layer_id}/material/{campus_id?}')->group(function(){
    Route::get('', 'MaterialController@index')->name('index');
    Route::get('create', 'MaterialController@create')->name('create');
    Route::post('create', 'MaterialController@save')->name('save');
    Route::get('edit/{id}', 'MaterialController@edit')->name('edit');
    Route::get('download/{id}', 'MaterialController@download')->name('download');
    Route::post('update/{id}', 'MaterialController@update')->name('update');
    Route::get('show/{id}', 'MaterialController@show')->name('show');
    Route::get('delete/{id}', 'MaterialController@drop')->name('drop');
});

// ALTERNATIVE NOTIFICATIONS AND MATERIAL APPRAOCH
Route::name('notifications.')->prefix('{layer}/{layer_id}/notifications/{campus_id?}')->group(function(){
    Route::get('/', 'NotificationsController@index')->name('index');
    Route::get('/create', 'NotificationsController@create')->name('create');
    Route::post('/create', 'NotificationsController@save')->name('save');
    Route::get('/delete/{id}', 'NotificationsController@drop')->name('drop');
    Route::get('/edit/{id}', 'NotificationsController@edit')->name('edit');
    Route::post('/update/{id}', 'NotificationsController@update')->name('update');
    Route::get('/show/{id}', 'NotificationsController@show')->name('show');
});

// Messages
Route::name('messages.')->prefix('messages')->group(function(){
    Route::get('create', [NotificationsController::class, 'create_message'])->name('create');
    Route::post('create', [NotificationsController::class, 'create_message_save']);
    Route::get('sent', [NotificationsController::class, 'sent_messages'])->name('sent');
});

Route::get('search/students/boarders/{name}', 'HomeController@getStudentBoarders')->name('getStudentBoarder');

Route::get('student/set_location', function(Request $request){
    $student_id = $request->student_id;
    $location = $request->location;
    if($student_id != null){
        \App\Models\Students::where('id', $student_id)->update(['location'=>$location]);
        return back()->with('success', "User location updated successfully");
    }else{
        return back()->with('error', "Failed to update user location");
    }
})->name('student.set_location');

Route::get('getColor/{label}', [HomeController::class, 'getColor'])->name('getColor');

Route::get('mode/{locale}', function ($batch) {
    session()->put('mode', $batch);

    return redirect()->back();
})->name('mode');

