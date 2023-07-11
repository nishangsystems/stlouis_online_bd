<?php

namespace App\Http\Controllers\Student;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\TransactionController;
use App\Http\Services\ApiService;
use App\Http\Services\SMSHelpers;
use App\Models\ApplicationForm;
use App\Models\Batch;
use App\Models\Campus;
use App\Models\CampusProgram;
use App\Models\CampusSemesterConfig;
use App\Models\Charge;
use App\Models\ClassSubject;
use App\Models\Config;
use App\Models\CourseNotification;
use App\Models\Income;
use App\Models\Material;
use App\Models\NonGPACourse;
use App\Models\Notification;
use App\Models\PayIncome;
use App\Models\Payments;
use App\Models\PlatformCharge;
use App\Models\ProgramLevel;
use App\Models\Resit;
use App\Models\Result;
use App\Models\SchoolUnits;
use App\Models\Semester;
use App\Models\Sequence;
use App\Models\StudentClass;
use App\Models\Students;
use App\Models\StudentStock;
use App\Models\StudentSubject;
use App\Models\SubjectNotes;
use App\Models\Subjects;
use App\Models\Topic;
use App\Models\Transaction;
use App\Models\Transcript;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Exception;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    private $years;
    private $batch_id;
    private $select = [
        'students.id as student_id',
        'collect_boarding_fees.id',
        'students.name',
        'students.matric',
        'collect_boarding_fees.amount_payable',
        'collect_boarding_fees.status',
        'school_units.name as class_name'
    ];

    private $select_boarding = [
        'students.id as student_id',
        'students.name',
        'students.matric',
        'collect_boarding_fees.id',
        'boarding_amounts.created_at',
        'boarding_amounts.amount_payable',
        'boarding_amounts.total_amount',
        'boarding_amounts.status',
        'boarding_amounts.balance'
    ];

    public function index()
    {
        return view('student.dashboard');
    }

    public function fee()
    {
        $data['title'] = "Tution Report";
        return view('student.fee')->with($data);
    }

    public function other_incomes()
    {
        $data['title'] = "Other Payments Report";
        return view('student.other_incomes', $data);
    }

    public function result(Request $request)
    {
        # code...
        $data['title'] = "My Result";
        return view('student.result')->with($data);
    }

    public function subject()
    {
        $data['title'] = "My Subjects";
        //     dd($data);
        return view('student.subject')->with($data);
    }

    public function profile()
    {
        return view('student.edit_profile');
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|min:8',
            'phone' => 'required|min:9|max:15',
        ]);


        if ($validator->fails()) {
            return redirect()->back()->with(['e' => $validator->errors()->first()]);
        }

        $data['success'] = 200;
        $user = auth('student')->user();
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();
        $data['user'] = auth('student')->user();
        return redirect()->back()->with(['s' => 'Phone Number and Email Updated Successfully']);
    }


    public function __construct( ApiService $service)
    {
        // $this->middleware('isStudent');
        // $this->boarding_fee =  BoardingFee::first();
        //  $this->year = Batch::find(Helpers::instance()->getCurrentAccademicYear())->name;
        $this->batch_id = Batch::find(Helpers::instance()->getCurrentAccademicYear())->id;
        $this->years = Batch::all();
        $this->api_service = $service;
    }


    
    public function edit_profile()
    {
        # code...
        $data['title'] = "Edit Profile";
        return view('student.edit_profile', $data);
    }
    public function update_profile(Request $request)
    {
        # code...
        if(
            Students::where([
                'email' => $request->email, 'phone' => $request->phone
            ])->count() > 0 && (auth('student')->user()->phone != $request->phone || auth('student')->user()->email != $request->email)
        ){
            return back()->with('error', __('text.validation_phrase1'));
        }
        
        $data = $request->all();
        Students::find(auth('student')->id())->update($data);
        return redirect(route('student.home'))->with('success', __('text.word_Done'));
    }
 

    /* ______________________________________________________________________________________
    ONLINE APPLICATION SPECIFIC ACTIONS
    _______________________________________________________________________________________ */
    public function all_programs (Request $request)
    {
        # code...
        $data['title'] = "Our programs";
        $data['campuses'] = json_decode($this->api_service->campuses())->data;
        foreach ($data['campuses'] as $key => $value) {
            # code...
            $data['campuses'][$key]->programs = collect(json_decode($this->api_service->campusPrograms($value->id))->data)->unique();
        }
        // return $data;
        return view('student.online.programs', $data);
    }

    public function start_application (Request $request, $step, $application_id = null)
    {
        try {

            // check if application is open now
            if(!(Helpers::instance()->application_open())){
                return redirect(route('student.home'))->with('error', 'Application closed for '.Batch::find(Config::all()->last()->year_id)->name);
            }
            # code...
            $data['title'] = "HND APPLICATION FOR DOUALA-BONABERI";
            $data['step'] = $step;
            // return $this->api_service->campuses();
            $data['campuses'] = json_decode($this->api_service->campuses())->data;
            $application = ApplicationForm::where(['student_id'=>auth('student')->id(), 'year_id'=>Helpers::instance()->getCurrentAccademicYear()])->first();
            if($application == null){
                $application = new ApplicationForm();
                $application->student_id = auth('student')->id();
                $application->year_id = Helpers::instance()->getCurrentAccademicYear();
                $application->save();
            }
            $data['application'] = $application;
    
            if($data['application']->degree_id != null){
                $data['degree'] = collect(json_decode($this->api_service->degrees())->data)->where('id', $data['application']->degree_id)->first();
            }
            if($data['application']->campus_id != null){
                $data['campus'] = collect($data['campuses'])->where('id', $data['application']->campus_id)->first();
            }
            if($data['application']->degree_id != null){
                $data['certs'] = json_decode($this->api_service->certificates())->data;
            }
            if($data['application']->entry_qualification != null){
                $data['programs'] = json_decode($this->api_service->campusDegreeCertificatePrograms($data['application']->campus_id, $data['application']->degree_id, $data['application']->entry_qualification))->data;
                $data['cert'] = collect($data['certs'])->where('id', $data['application']->entry_qualification)->first();
            }
            if($data['application']->program_first_choice != null){
                $data['program1'] = collect($data['programs'])->where('id', $data['application']->program_first_choice)->first();
                $data['program2'] = collect($data['programs'])->where('id', $data['application']->program_second_choice)->first();
                // return $data;
            }
            return view('student.online.fill_form', $data);
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', $th->getMessage());
        }
    }

    public function persist_application(Request $request, $step, $application_id)
    {
        # code...
        // return $request->all();
        
        // check if application is open now
        if(!(Helpers::instance()->application_open())){
            return redirect(route('student.home'))->with('error', 'Application closed for '.Batch::find(Config::all()->last()->year_id)->name);
        }
        switch ($step) {
            case 1:
                # code...
                $validity = Validator::make($request->all(), [
                    'campus_id'=>'required', 'degree_id'=>'required'
                ]);
                break;
            
            case 2:
                # code...
                // return $request->all();
                $validity = Validator::make($request->all(), [
                    "name"=>'required',"gender"=> "required","dob"=> "required", "pob"=> "required", "nationality"=> "required",
                    "region"=> "required", "division"=> "required", "residence"=> "required", "phone"=> "required", "email"=> "required|email",
                    "referer"=> "required", "high_school"=> "required", "campus_id"=> "required", "entry_qualification"=> "required"
                ]);
                break;
            
            case 3:
                # code...
                $validity = Validator::make($request->all(), [
                    'program_first_choice'=>'required', 'program_second_choice'=>'required',
                ]);
                break;
            
            case 4:
                # code...
                
                $validity = Validator::make($request->all(), [
                    // 'first_spoken_language'=>'required', 'first_written_language'=>'required',
                    'employments'=>'array', 'previous_training'=>'array'
                ]);
                break;
                
            case 5:
                # code...
                // return $request->all();
                // $validity = Validator::make($request->all(), [
                //     'has_health_problem'=>'required|', 'has_health_allergy'=>'required', 'has_disability'=>'required',
                //     'health_problem'=>'required_if:has_health_problem,yes', 'health_allergy'=>'required_if:has_health_allergy,yes', 
                //     'disability'=>'required_if:has_disability,yes',
                // ]);
                $validity = Validator::make($request->all(), [
                    'fee_payer'=>'required', 'fee_payer_name'=>'required', 'fee_payer_residence'=>'required',
                    'fee_payer_tel'=>'required', 'fee_payer_occupation'=>'required'
                ]);
                break;
                
            case 6:
                $validity = Validator::make($request->all(), [
                    
                ]);
                # code...
                break;
            
            case 7:
                # code...
                // return $request->all();
                // momo-number validated with country code for cameroon: 237
                $validity = Validator::make($request->all(), [
                    "momo_number"=> "required|size:9", "amount"=> "required|numeric|min:1",
                    // "momo_screenshot"=> "file"
                ]);
                break;
            
        }

        if($validity->fails()){
            return back()->with('error', $validity->errors()->first());
        }
        // return $request->all();

        // persist data
        $data = [];
        if($step == 4){
            $data_p1=[];
            $_data = $request->previous_training;
            // return $_data;
            if($_data != null){
                foreach ($_data['school'] as $key => $value) {
                    $data_p1[] = ['school'=>$value, 'year'=>$_data['year'][$key], 'course'=>$_data['course'][$key], 'certificate'=>$_data['certificate'][$key]];
                }
                $data['previous_training'] = json_encode($data_p1);
                // return $data;
            }
            $data_p2 = [];
            $e_data = $request->employments;
            if($e_data != null){
                foreach ($e_data['employer'] as $key => $value) {
                    $data_p2[] = ['employer'=>$value, 'post'=>$e_data['post'][$key], 'start'=>$e_data['start'][$key], 'end'=>$e_data['end'][$key], 'type'=>$e_data['type'][$key]];
                }
                $data['employments'] = json_encode($data_p2);
                // return $data;
            }
            $data = collect($data)->filter(function($value, $key){return $key != '_token';})->toArray();
            $application = ApplicationForm::updateOrInsert(['id'=> $application_id, 'student_id'=>auth('student')->id()], $data);
        }
        elseif($step ==7){
            
            // MAKE API CALL TO PERFORM PAYMENT OF APPLICATION FEE
            // check if token exist and hasn't expired or get new token otherwise
            if(cache('tranzak_api_token') == null or Carbon::parse(cache('tranzak_api_token_expire'))->isAfter(now())){
                // get and cache different token
                $response = Http::post(config('tranzak.base').config('tranzak.token'), ['appId'=>config('tranzak.app_id'), 'appKey'=>config('tranzak.api_key')]);
                if($response->status() == 200){
                    // return json_decode($response->body())->data;
                    // return Carbon::createFromTimestamp(time() + json_decode($response->body())->data->expiresIn);
                    // cache token and token expirationtot session
                    cache(['tranzak_api_token'=> json_decode($response->body())->data->token]);
                    cache(['tranzak_api_token_expire'=>Carbon::createFromTimestamp(time() + json_decode($response->body())->data->expiresIn)]);
                }
            }
            // Assumed there is a valid api token
            // Moving the performing the payment request proper
            $headers = ['Authorization'=>'Bearer '.cache('tranzak_api_token')];
            $request_data = ['mobileWalletNumber'=>'237'.$request->momo_number, 'mchTransactionRef'=>'_apl_fee_'.time().'_'.random_int(1, 9999), "amount"=> $request->amount, "currencyCode"=> "XAF", "description"=>"Payment for application fee into ST LOUIS UNIVERSITY INSTITUTE"];
            $_response = Http::withHeaders($headers)->post(config('tranzak.base').config('tranzak.direct_payment_request'), $request_data);
            if($_response->status() == 200){
                // return json_decode($_response->body())->data;
                // save transaction and track it status

                session()->put('processing_tranzak_transaction_details', json_encode(json_decode($_response->body())->data));
                // return $this->pending_payment(array_push((), ['application_id']));
                return redirect()->to(route('student.application.payment.processing', $application_id))->with(['data'=>json_decode($_response->body())->data]);
            }

            // END OF PAYMENT PROCESS
            // $data = collect($data)->filter(function($value, $key){return $key != '_token';})->toArray();
            // $application = ApplicationForm::updateOrInsert(['id'=> $application_id, 'student_id'=>auth('student')->id()], $data);
        }else{
            $data = $request->all();
            $data = collect($data)->filter(function($value, $key){return $key != '_token';})->toArray();
            $application = ApplicationForm::updateOrInsert(['id'=> $application_id, 'student_id'=>auth('student')->id()], $data);
        }

        // $application->update($data);
        if($step == 7){
            // Form fully filled
            return redirect(route('student.home'))->with('success', 'Application form completely filled.');
        }
        // $application->update($data);
        if($step == 3){
            // Form fully filled
            $appl = ApplicationForm::find($application_id);
            // return 'xyz';
                $degs = json_decode($this->api_service->campusDegrees($appl->campus_id))->data;
                if (($degree = collect($degs)->where('id', $appl->degree_id)->first()) != null) {
                    if($degree->deg_name != 'MASTER DEGREE PROGRAMS'){
                        return redirect(route('student.application.start', [$step+1, $application_id]));
                    }
                }
        }
        $step = $request->step;
        return redirect(route('student.application.start', [$step, $application_id]));
    }

    public function pending_payment(Request $request, $application_id)
    {
        # code...
        
        // check if application is open now
        if(!(Helpers::instance()->application_open())){
            return redirect(route('student.home'))->with('error', 'Application closed for '.Helpers::instance()->getYear()->name);
        }
        $data['title'] = "Processing Transaction";
        $data['form_id'] = $application_id;
        $data['transaction'] = json_decode(session()->get('processing_tranzak_transaction_details'));
        // return $data;
        return view('student.online.processing_payment', $data);
        
    }

    public function pending_complete(Request $request, $appl_id)
    {
        # code...
        try {
            
            // check if application is open now
            if(!(Helpers::instance()->application_open())){
                return redirect(route('student.home'))->with('error', 'Application closed for '.Helpers::instance()->getYear()->name);
            }
            //code...
            $transaction_status = json_decode($request->qstring);
            // return $transaction_status;
            switch ($transaction_status->status) {
                case 'SUCCESSFUL':
                    # code...
                    // save transaction and update application_form
                    $transaction = ['request_id'=>$transaction_status->requestId, 'amount'=>$transaction_status->amount, 'currency_code'=>$transaction_status->currencyCode, 'purpose'=>"application fee", 'mobile_wallet_number'=>$transaction_status->mobileWalletNumber, 'transaction_ref'=>$transaction_status->mchTransactionRef, 'app_id'=>$transaction_status->appId, 'transaction_id'=>$transaction_status->transactionId, 'transaction_time'=>$transaction_status->transactionTime, 'payment_method'=>$transaction_status->payer->paymentMethod, 'payer_user_id'=>$transaction_status->payer->userId, 'payer_name'=>$transaction_status->payer->name, 'payer_account_id'=>$transaction_status->payer->accountId, 'merchant_fee'=>$transaction_status->merchant->fee, 'merchant_account_id'=>$transaction_status->merchant->accountId, 'net_amount_recieved'=>$transaction_status->merchant->netAmountReceived];
                    $transaction_instance = new Transaction($transaction);
                    $transaction_instance->save();
    
                    $appl = ApplicationForm::find($appl_id);
                    $appl->transaction_id = $transaction_instance->id;
                    $appl->save();
    
                    // SEND SMS
                    $phone_number = auth('student')->user()->phone;
                    if(str_starts_with($phone_number, '+')){
                        $phone_number = substr($phone_number, '4');
                    }
                    if(strlen($phone_number) > 9){
                        $phone_number = substr($phone_number, '3');
                    }
                    $tempArray = ['237699131895'];
                    
                    $formatContactArray = SMSHelpers::formatPhoneNumbers($tempArray);
                    //send sms to formatted array of numbers
                    $message="Application form for ST. LOUIS UNIVERSITY INSTITUTE submitted successfully.";
                    $this->sendSMS('237699131895', $message);
    
                    return redirect(route('student.home'))->with('success', "Payment successful.");
                    break;
                
                case 'CANCELLED':
                    # code...
                    // notify user
                    return redirect(route('student.home'))->with('message', 'Payment Not Made. The request was cancelled.');
                    break;
                
                case 'FAILED':
                    # code...
                    return redirect(route('student.home'))->with('error', 'Payment failed.');
                    break;
                
                case 'REVERSED':
                    # code...
                    return redirect(route('student.home'))->with('message', 'Payment failed. The request was reversed.');
                    break;
                
                default:
                    # code...
                    break;
            }
            return redirect(route('student.home'))->with('error', 'Payment failed. Unrecognised transaction status.');
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', $th->getMessage());
        }
    }

    public function submit_application(Request $request){
        
        // check if application is open now
        if(!(Helpers::instance()->application_open())){
            return redirect(route('student.home'))->with('error', 'Application closed for '.Helpers::instance()->getYear()->name);
        }
        $applications = auth('student')->user()->currentApplicationForms()->whereNull('transaction_id')->get();
        $data['title'] = "Submit Application";
        $data['applications'] = $applications;
        return view('student.online.submit_form', $data);
    }

    public function submit_application_save(Request $request, $appl_id)
    {
        # code...
        $application = ApplicationForm::find($appl_id);
        if($application != null){
            $application->submitted = 1;
            $application->save();
            return back()->with('success', 'Application submitted.');
        }
        return back()->with('error', 'Application could not be found.');
    }

    public function download_application_form()
    {
        # code...
        $data['title'] = "Download Application Form";
        $data['applications'] = auth('student')->user()->applicationForms->whereNotNull('transaction_id');
        return view('student.online.download_form', $data);
    }

    public function download_form(Request $request, $application_id)
    {
        # code...
        try{
            $application = ApplicationForm::find($application_id);
            $data['campuses'] = json_decode($this->api_service->campuses())->data;
            $data['application'] = ApplicationForm::find($application_id);
            $data['degree'] = collect(json_decode($this->api_service->degrees())->data??[])->where('id', $data['application']->degree_id)->first();
            $data['campus'] = collect($data['campuses'])->where('id', $data['application']->campus_id)->first();
            $data['certs'] = json_decode($this->api_service->certificates())->data;
            
            $data['programs'] = json_decode($this->api_service->campusDegreeCertificatePrograms($data['application']->campus_id, $data['application']->degree_id, $data['application']->entry_qualification))->data;
            $data['cert'] = collect($data['certs'])->where('id', $data['application']->entry_qualification)->first();
            $data['program1'] = collect($data['programs'])->where('id', $data['application']->program_first_choice)->first();
            $data['program2'] = collect($data['programs'])->where('id', $data['application']->program_second_choice)->first();
            
            // $title = $application->degree??''.' APPLICATION FOR '.$application->campus->name??' --- '.' CAMPUS';
            $title = ($application->degree->name??'').' APPLICATION FOR '.($application->campus->name??'').' CAMPUS';
            $data['title'] = $title;

            if(in_array(null, array_values($data))){ return redirect(route('student.application.start', [0, $application_id]))->with('message', "Make sure your form is correctly filled and try again.");}
            // return view('student.online.form_dawnloadable', $data);
            $pdf = PDF::loadView('student.online.form_dawnloadable', $data);
            $filename = $title.' - '.$application->name.'.pdf';
            return $pdf->download($filename);
        }catch(Throwable $th){
            if(in_array(null, array_values($data))){ return redirect(route('student.application.start', [0, $application_id]))->with('message', "Make sure your form is correctly filled and try again.");}
        }
    }

    public function payment_data ()
    {
        # code...
        $data['title'] = "Payment Data";
        $data['payments'] = ApplicationForm::where('student_id', auth('student')->id())->whereNotNull('transaction_id')->get();
        if(request('appl') != null){
            $data['appl'] = ApplicationForm::find(request('appl'));
        }
        foreach ($data['payments'] as $key => $value) {
            # code...
            $data['payments'][$key]->campus = collect(json_decode($this->api_service->campuses())->data)->where('id', $value->campus_id)->first();
            $data['payments'][$key]->degree = collect(json_decode($this->api_service->degrees())->data)->where('id', $value->degree_id)->first();
        }
        return view('student.online.payment_data', $data);
    }
}
