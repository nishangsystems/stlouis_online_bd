<?php


namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Services\TranzakSMSService;
use App\Models\ApplicationForm;
use App\Models\Config;
use App\Models\File;
use App\Models\PlatformCharge;
use App\Models\Students;
use App\Models\TranzakTransaction;
use App\Http\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class HomeController  extends Controller
{


    public $tranzak_sms_service;
    public $api_service;

    public function __construct(TranzakSMSService $tranzakSMSService, ApiService $apiService){
        $this->tranzak_sms_service = $tranzakSMSService;
        $this->api_service = $apiService;
    }

    public function index()
    {
        return view('admin.dashboard');
    }

    public function set_letter_head()
    {
        # code...
        $data['title'] = __('text.upload_letter_head');
        return view('admin.setting.set-letter-head', $data);
    }

    public function save_letter_head(Request $request)
    {

        # code...
        $check = Validator::make($request->all(), ['file'=>'required|file|mimes:png,jpg,jpeg,gif,tif']);
        if ($check->fails()) {
            # code...
            return back()->with('error', $check->errors()->first());
        }
        
        $file = $request->file('file');
        // return $file->getClientOriginalName();
        if(!($file == null)){
            $ext = $file->getClientOriginalExtension();
            $filename = '_'.random_int(100000, 999999).'_'.time().'.'.$ext;
            $path = 'assets/images/avatars';
            if(!file_exists(url($path))){mkdir(url($path));}
            // $file->move(url($path), $filename);
            $file->move(public_path($path), $filename);
            if(File::where(['name'=>'letter-head'])->count() == 0){
                File::create(['name'=>'letter-head', 'path'=>$filename]);
            }else {
                File::where(['name'=>'letter-head'])->update(['path'=>$filename]);
            }
            return back()->with('success', __('text.word_done'));
        }
        return back()->with('error', __('text.error_reading_file'));
    }

    public function set_background_image()
    {
        # code...
        $data['title'] = __('text.set_background_image');
        return view('admin.setting.bg_image', $data);
    }

    public function save_background_image(Request $request)
    {
        # code...
        # code...
        $check = Validator::make($request->all(), ['file'=>'required|file|mimes:jpeg']);
        if ($check->fails()) {
            # code...
            return back()->with('error', $check->errors()->first());
        }
        $file = $request->file('file');
        // return $file->getClientOriginalName();
        if(!($file == null)){
            $ext = $file->getClientOriginalExtension();
            $filename = 'background_image.jpeg';
            // $path = $filename;
            if(!file_exists(url('/storage/app/bg_image'))){
                mkdir(url('/storage/app/bg_image'));
            }
            $file->move(url('/storage/app/bg_image'), $filename);
            return back()->with('success', __('text.word_done'));
        }
        return back()->with('error', __('text.error_reading_file'));
    }

    
    public function set_watermark()
    {
        # code...
        $data['title'] = __('text.set_watermark');
        return view('admin.setting.set_watermark', $data);
    }

    public function save_watermark(Request $request)
    {
        # code...
        # code...
        $check = Validator::make($request->all(), ['file'=>'required|file|mimes:jpeg']);
        if ($check->fails()) {
            # code...
            return back()->with('error', $check->errors()->first());
        }
        
        $file = $request->file('file');
        // return $file->getClientOriginalName();
        if(!($file == null)){
            $ext = $file->getClientOriginalExtension();
            $filename = 'logo.jpeg';
            $path = base_path('/assets/images');
            // $file->n('/bg_image', $filename);
            // \Storage::put($path, $file);
            $request->file('file')->move($path, $filename);
            return back()->with('success', __('text.word_done'));
        }
        return back()->with('error', __('text.error_reading_file'));
    }

    public function setayear()
    {
        $data['title'] = __('text.set_current_accademic_year');
        return view('admin.setting.setbatch')->with($data);
    }

    public function setsem()
    {
        return view('admin.setting.setsem');
    }

    public function deletebatch($id)
    {
        if (DB::table('batches')->count() == 1) {
            return redirect()->back()->with('error', __('text.can_not_delete_last_batch'));
        }
        DB::table('batches')->where('id', '=', $id)->delete();
        return redirect()->back()->with('success', __('text.word_done'));
    }



    public function setAcademicYear($id)
    {
        // dd($id);
        $year = Config::all()->last();
        $data = [
            'year_id' => $id
        ];
        $year->update($data);

        return redirect()->back()->with('success', __('text.word_done'));
    }

    public function set_charges()
    {
        # code...
        $data['title'] = __('text.set_charges');
        return view('admin.setting.charges', $data);
    }

    public function save_charges(Request $request)
    {
        # code...
        // return $request->all();
        $validity = Validator::make($request->all(), [
            'year_id'=>'required',
            'yearly_amount'=>'numeric',
            'transcript_amount'=>'numeric',
            'result_amount'=>'numeric'
        ]);
        if($validity->failed()){
            return back()->with('error', $validity->errors()->first());
        }
        PlatformCharge::updateOrInsert(['year_id'=>$request->year_id], ['yearly_amount'=>$request->yearly_amount, 'result_amount'=>$request->result_amount, 'transcript_amount'=>$request->transcript_amount]);
        return back()->with('success', __('text.word_done'));
    }



    public function bypass_platform_charges(Request $request, $student_id = null)
    {
        # code...
        $data['title'] = "Bypass Student Platform Charges";
        if($student_id != null){
            $data['student'] = Students::find($student_id);
            $data['title'] = "Bypass Student Platform Charges For ".$data['student']->name??'';
        }
        return view('admin.student.bypass_platform_charges', $data);
    }

    public function bypass_save_platform_charges(Request $request,  $student_id)
    {
        # code...
        $plcharge  = PlatformCharge::where('year_id', $this->current_accademic_year)->first();
        $check = ['year_id'=>$this->current_accademic_year, 'student_id'=>$student_id, 'type'=>'PLATFORM'];
        if(\App\Models\Charge::where($check)->count() > 0){
            return back()->with('message', "Student has already paid for platform charges");
        }
        $data = ['year_id'=>$this->current_accademic_year, 'student_id'=>$student_id, 'amount'=>$plcharge->amount??0, 'item_id'=>$plcharge->id??null, 'parent'=>0, 'type'=>'PLATFORM', 'used'=>1, 'financialTransactionId'=>(time().'_'.$student_id.str_replace(' ', '_', $request->reason??''))];
        $charge = new \App\Models\Charge($data);
        $charge->save();
        return back()->with('success', 'Done');
    }

    public function bypass_application_fee(Request $request, $form_id = null)
    {
        # code...
        $data['title'] = "Bypass Student Application Fee";
        if($form_id != null){
            $data['form'] = ApplicationForm::find($form_id);
            $data['title'] = "Bypass Student Application Fee For ".$data['form']->name;
        }
        return view('admin.student.bypass_application_fee', $data);
    }

    public function bypass_save_application_fee(Request $request, $form_id)
    {
        # code...
        // Create a fake transaction and update transaction-id for this application form
        // dd(123);
        $data = ['request_id'=>rand(1000000001, 9999990009), 'amount'=>0, 'currency_code'=>'XAF', 'purpose'=>"APPLICATION FEE BYPASS", 'mobile_wallet_number'=>'Bypassed By payer_account_id', 'transaction_ref'=>$request->reason??'', 'app_id'=>'------', 'transaction_id'=>'---------', 'transaction_time'=>now(), 'payment_method'=>'BYPASS', 'payer_user_id'=>0, 'payer_name'=>'--------', 'payer_account_id'=> auth()->id() , 'merchant_fee'=>0, 'merchant_account_id'=>'--------', 'net_amount_recieved'=>'---------'];
        $transaction = new TranzakTransaction($data);
        $transaction->save();
        ApplicationForm::findOrFail($form_id)->update(['transaction_id' => $transaction->id]);
        return back()->with('success', 'Operation complete');
    }


    public function notify_applicants_by_sms(Request $request){
        $data['title'] = "Notify All|Admitted Applicants of this Accademic Year";
        $data['options'] = ['all_applicants' => 'ALL APPLICANTS', 'admitted_students' => 'ADMITTED STUDENTS'];
        return view('admin.notification.send_bulk_sms', $data);
    }


    public function notify_applicants_by_sms_send(Request $request){
        try {
            //code...
            $request->validate(['option' => 'required', 'message' => 'required']);
            $year_id = \App\Helpers\Helpers::instance()->getCurrentAccademicYear();

            switch($request->option){
                case "all_applicants":
                    // get and process phone numbers for all applicants, then forward sms to numbers if any
                    $current_year_applicants = \App\Models\ApplicationForm::whereNotNull('transaction_id')->whereNotNull('phone')->where('year_id', $year_id)->pluck('phone')->unique();
                    if($current_year_applicants->count() > 0){
                        $phone_numbers = $current_year_applicants->toArray();
                        $message = $request->message;

                        // send message
                        $this->tranzak_sms_service->send($phone_numbers, $message);
                        session()->flash('success', "Message sent successfully");
                    }else
                        session()->flash('error', "No applicants were found");
                    break;
                case "admitted_students":
                    // get and process phone numbers for all admitted students, then forward sms to numbers if any
                    $current_year_admitted_students = \App\Models\ApplicationForm::where('admitted', '>', 0)->whereNotNull('transaction_id')->whereNotNull('phone')->where('year_id', $year_id)->pluck('phone')->unique();
                    if($current_year_admitted_students->count() > 0){
                        $phone_numbers = $current_year_admitted_students->toArray();
                        $message = $request->message;

                        // send the message
                        $this->tranzak_sms_service->send($phone_numbers, $message);
                        session()->flash('success', "Message sent successfully");
                    }else
                        session()->flash('error', "No applicants were found");
                    break;

                }
            return back();
        } catch (\Throwable $th) {
            //throw $th;
            logger()->error($th);
            session()->flash('error', $th->getMessage());
            return back();
        }
    }
}
