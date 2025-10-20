<?php

namespace App\Http\Controllers;

use App\Helpers\Helpers;
use App\Http\Services\ApiService;
use App\Http\Controllers\SMS\Helpers as SMSHelpers;
use App\Http\Services\FocusTargetSms;
use App\Models\Campus;
use App\Models\CampusProgram;
use App\Models\ClassSubject;
use App\Models\File;
use App\Models\Region;
use App\Models\Students;
use App\Models\TeachersSubject;
use App\Models\User;
use App\Models\Wage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Twilio\Rest\Client;

/**
 * Summary of Controller
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    var $current_accademic_year;
    var $api_service;
    public function __construct(ApiService $apiService, Helpers $helpers)
    {
        # code...
        $this->api_service = $apiService;
        $this->current_accademic_year = $helpers->getCurrentAccademicYear();
        ini_set('max_execution_time', 360);
    }

    public function set_local(Request $request, $lang)
    {
        # code...
        // return $lang;
        if (array_key_exists($lang, Config::get('languages'))) {
            Session::put('appLocale', $lang);
            App::setLocale($lang);
        }
        return back();
    }

    public static function sorted_program_levels()
    {
        $pls = [];
        # code...
        foreach (\App\Models\ProgramLevel::all() as $key => $value) {
            # code...
            $pls[] = [
                'id' => $value->id,
                'level_id'=>$value->level_id,
                'program_id'=>$value->program_id,
                'name' => $value->program()->first()->name.': LEVEL '.$value->level()->first()->level,
                'department'=> $value->program->parent_id
            ];
        }
        $pls = collect($pls)->sortBy(['name', 'level_id']);
        // $pls->where('id')
        return $pls;
    }
    public static function sorted_campus_program_levels($campus)
    {
        $pls = [];
        # code...
        $program_level_ids = CampusProgram::where(['campus_id'=>$campus])->pluck('program_level_id');
        foreach (\App\Models\ProgramLevel::whereIn('id', $program_level_ids)->get() as $key => $value) {
            # code...
            $pls[] = [
                'id' => $value->id,
                'level_id'=>$value->level_id,
                'program_id'=>$value->program_id,
                'name' => $value->program()->first()->name.': LEVEL '.$value->level()->first()->level
            ];
        }
        $pls = collect($pls)->sortBy('si');
        return $pls;
    }


    public function registration(){
        return view('auth.registration_info');
     }

    public function createAccount(Request $request){
        
        $request->validate([
            'name'=>'required', 'email'=>'required|email', 'phone'=>'required',
            'cpassword'=>'required',
            'password'=>'required_with:cpassword|same:cpassword|',
        ]);
        if(Students::where('email', $request->email)->count() > 0){
            return back()->with('error', __('text.error_email_exist'));
        }
        if(Students::where('phone', $request->phone)->count() > 0){
            return back()->with('error', __('text.error_phone_exist'));
        }
        $account = new Students($request->all());
        $account->password = Hash::make($request->password);
        $account->save();
        return redirect(route('login'))->with('success', 'Account successfully created');
    }

    public function reset_password(Request $request, $id= null)
    {
        # code...
        $data['title'] = "Reset Password";
        if (auth()->guard('student')->check()) {
            return view('student.reset_password', $data);
        }
        else {
            if (auth()->user()->type == 'admin') {
                return view('admin.reset_password', $data);
            }else{
                return view('teacher.reset_password', $data);
            }
        }
    }

    public function reset_password_save(Request $request)
    {
        # code...
        $validator = Validator::make($request->all(), [
            'current_password'=>'required',
            'new_password_confirmation'=>'required_with:new_password|same:new_password|min:6',
            'new_password'=>'required|min:6',
        ]);
        if($validator->fails()){
            return back()->with('error', $validator->errors()->first());
        }
        if (auth()->guard('student')->check()) {
            if(Hash::check($request->current_password, auth('student')->user()->getAuthPassword())){
                $stud = Students::find(auth('student')->id());
                $stud->password = Hash::make($request->new_password);
                $stud->password_reset = true;
                $stud->save();
                return back()->with('success', 'Done');
            }else{
                return back()->with('error', 'Operation failed. Make sure you entered the correct password');
            }
        }
        else{
            if(Hash::check($request->current_password, auth()->user()->getAuthPassword())){
                $user = User::find(auth()->id());
                $user->password = Hash::make($request->new_password);
                $user->password_reset = true;
                $user->save();
                return back()->with('success', 'Done');
            }else{
                return back()->with('error', 'Operation failed. Make sure you entered the correct password');
            }
        }

    }

    /**
     * Summary of sendSmsNotificaition
     * @param string $message_text
     * @param array|Collection $contacts
     * @return bool
     */
    public static function sendSmsNotificaition(String $message_text, $contacts)
    {
        $sms_sender_address = env('SMS_SENDER_ADDRESS');
        // dd($contacts);
        $contacts_no_spaces = array_map(function($el){
            return str_replace([' ', '.', '-', '(', ')', '+'], '', $el);
        }, $contacts);
        // dd($contacts_no_spaces);
        $cleaned_contacts = array_map(function($el){
            return explode('/',explode(',', $el)[0])[0];
        }, $contacts_no_spaces);
        // dd($cleaned_contacts);
        // $basic  = new \Vonage\Client\Credentials\Basic('8d8bbcf8', '04MLvso1he1b8ANc');
        // $client = new \Vonage\Client($basic);


        // SEND SMS PROPER
        SMSHelpers::sendSMS($message_text, $cleaned_contacts);

        // foreach ($contacts as $key => $contact) {
        //     # code...
        //     $message = new \Vonage\SMS\Message\SMS($contact, $sms_sender_address, $message_text);
        //     $client->sms()->send($message);
        // }
        return true;
    }

    public function search_user(Request $request)
    {
        # code...
        $search_key = $request->key;
        if($search_key == null){
            return null;
        }
        $users = User::where('name', 'LIKE', '%'.$search_key.'%')
                ->orWhere('matric', 'LIKE', '%'.$search_key.'%')
                ->orWhere('email', 'LIKE', '%'.$search_key.'%')
                ->orWhere('username', 'LIKE', '%'.$search_key.'%')
                ->take(10)->get();
        return response()->json(['users'=>$users]);
    }

    public static function get_payment_rate($teacher_id, $level_id){
        if($teacher_id != null){
            $rate = Wage::where(['teacher_id'=>$teacher_id, 'level_id'=>$level_id])->first();
            return $rate->price??0;
        }
        return null;
    }


    // public static function campusPrograms($campus_id){
    //     return Campus::find($campus_id)->programs;
    // }

    public function campusDegrees($campus_id)
    {
        # code...
        return json_decode($this->api_service->campusDegrees($campus_id))->data;
    }

    public function regionDivisions($region_id)
    {
        # code...
        return Region::find($region_id)->divisions;
    }

    public function create_api_root()
    {
        # code...
        return view('api_root');
    }

    public function save_api_root (Request $request)
    {
        # code...
        $request->validate(['api_root'=>'required|url']);

        $instance = new File(['name'=>'api_root', 'path'=>$request->api_root]);
        $instance->save();
        return back()->with('success', 'Done');
    }

    public function campusDegreeCertPrograms(Request $request, $campus_id, $degree_id, $cert_id)
    {
        # code...
        return json_decode($this->api_service->campusDegreeCertificatePrograms($campus_id, $degree_id, $cert_id))->data;
    }


    public function campusProgramLevels($campus_id, $program_id)
    {
        # code...
        return json_decode($this->api_service->campusProgramLevels($campus_id, $program_id))->data;
    }


    public function sendSMS($phone_number, $message)
    {
        if($message == null){return "Message must not be empty";}
        if($phone_number == null){return "Reciever IDs must not be empty";}
        
        return (new FocusTargetSms($phone_number, $message))->send();

    }

        

    public function degree_programs($degree_id){
        return collect(json_decode($this->api_service->programs())->data)->where('degree_id', $degree_id)->toArray();
    }

    public function program_levels($program_id){
        return json_decode($this->api_service->levels())->data;
    }
}
