<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helpers;
use App\Http\Controllers\Controller;
use App\Mail\AdmissionMail;
use App\Models\ApplicationForm;
use App\Models\Batch;
use App\Models\CampusBank;
use App\Models\ClassSubject;
use App\Models\Config;
use App\Models\EntryQualification;
use App\Models\Level;
use App\Models\ProgramLevel;
use App\Models\School;
use App\Models\SchoolUnits;
use App\Models\StudentClass;
use App\Models\Students;
use App\Models\Subjects;
use App\Models\Transaction;
use App\Session;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{

    public function open_admission(Request $request)
    {
        # code...
        $data['title'] = "Configure Admission Session.";
        $data['sessions'] = Config::all();
        $data['current_session'] = Config::where('year_id', Helpers::instance()->getCurrentAccademicYear())->first();
        // return $data;
        return view('admin.setting.config_admission', $data);
    }

    public function set_open_admission(Request $request)
    {
        # code...
        $validity = Validator::make($request->all(), ['start_date'=>'required|date', 'end_date'=>'required|date', 'fee1_latest_date'=>'required|date', 'fee2_latest_date'=>'required|date', 'director'=>'required', 'dean'=>'required', 'help_email'=>'email|required']);
        if($validity->fails()){return back()->with('error', $validity->errors()->first());}

        // return $request->all();
        $config = ['start_date'=>$request->start_date, 'end_date'=>$request->end_date, 'fee1_latest_date'=>$request->fee1_latest_date, 'fee2_latest_date'=>$request->fee2_latest_date, 'director'=>$request->director, 'dean'=>$request->dean, 'help_email'=>$request->help_email];
        Config::updateOrInsert(['year_id'=>Helpers::instance()->getCurrentAccademicYear()], $config);
        return back()->with('success', __('text.word_done'));
    }

    public function applicants_report_by_degree(Request $request)
    {
        # code...
    }

    public function applicants_report_by_program(Request $request)
    {
        # code...
    }

    public function finance_report_general()
    {
        # code...
    }

    public function config_programs(Request $request, $cid = null)
    {
        # code...
        $data['title'] = "Configure Programs Per Entry Qualification";
        // return $data;

        
        $qlf = json_decode($this->api_service->certificates());
        if($qlf != null){
            $data['certs'] = $qlf->data;
            $data['cert'] = collect($qlf->data)->where('id', $cid)->first();
            if($data['cert'] != null){
                $data['cert_programs'] = collect(json_decode($this->api_service->certificatePrograms($cid))->data)->pluck('id')->toArray();
                $progs = json_decode($this->api_service->programs());
                // return $progs;
                if($progs != null){
                    $data['programs'] = $progs->data;
                }
            }
        }
        // return $data;
        return view('admin.setting.config_program', $data);
    }

    public function set_config_programs(Request $request, $entry_id)
    {
        # code...
        $validity = Validator::make($request->all(), ['programs'=>'required|array']);
        if($validity->fails()){return back()->with('error', $validity->errors()->first());}

        // save program configuration
        $programs = $request->programs;
        $response = $this->api_service->setCertificatePrograms($entry_id, $programs);
        return back()->with('message', $response);
    }

    public function config_degrees(Request $request, $campus_id = null)
    {
        # code...
        $data['title'] = "Configure Campus Degrees";
        $data['campuses'] = json_decode($this->api_service->campuses())->data;
        $data['degrees'] = json_decode($this->api_service->degrees())->data;
        if($campus_id != null){
            $degs = $this->api_service->campusDegrees($campus_id);
            if($degs != null){
                $data['campus_degrees'] = collect(json_decode($degs)->data)->pluck('id')->toArray();
            }
        }
           
        return view('admin.setting.configure_campus_degrees', $data);
    }

    public function set_config_degrees(Request $request, $cid)
    {
        # code...
        $validity = Validator::make($request->all(), ['campus_degrees'=>'array']);
        if($validity->fails()){return back()->with('error', $validity->errors()->first());}
        // return $request->all();
        if(($resp = json_decode($this->api_service->setCampusDegrees($cid, $request->campus_degrees??[]))->data) == '1'){
            return back()->with('success', 'Updated successfully');
        }else{
            return back()->with('error', $resp);
        };
    }


    public function applications()
    {
        # code...
        $data['title'] = "All Application Forms";
        $data['_this'] = $this;
        $data['applications'] = ApplicationForm::whereNotNull('transaction_id')
            ->where(function($builder){
                if(($campus_id = auth()->user()->campus_id) != null){
                    $builder->where('campus_id', $campus_id);
                }
            })->get();
        return view('admin.student.applications', $data);
    }

    public function application_details(Request $request, $id)
    {
        # code...
        $data['application'] = ApplicationForm::find($id);
        $data['title'] = "Application Details For ".$data['application']->name;
        
    }



    
    public function print_application_form(Request $request, $id = null)
    {
        # code...
        // dd(123);
        if($id == null){
            $data['title'] = "Print Student Application Form";
            $data['_this'] = $this;
            $data['action'] = __('text.word_print');
            $data['download'] = __('text.word_download');
            $data['applications'] = ApplicationForm::whereNotNull('transaction_id')->get();
            return view('admin.student.applications', $data);
        }

        $application = ApplicationForm::find($id);
        $data['campuses'] = json_decode($this->api_service->campuses())->data;
        $data['application'] = ApplicationForm::find($id);
        $data['degree'] = collect(json_decode($this->api_service->degrees())->data??[])->where('id', $data['application']->degree_id)->first();
        $data['campus'] = collect($data['campuses'])->where('id', $data['application']->campus_id)->first();
        $data['certs'] = json_decode($this->api_service->certificates())->data;
        
        $data['programs'] = json_decode($this->api_service->campusDegreeCertificatePrograms($data['application']->campus_id, $data['application']->degree_id, $data['application']->entry_qualification))->data;
        $data['cert'] = collect($data['certs'])->where('id', $data['application']->entry_qualification)->first();
        $data['program1'] = collect($data['programs'])->where('id', $data['application']->program_first_choice)->first();
        $data['program2'] = collect($data['programs'])->where('id', $data['application']->program_second_choice)->first();
        
        // $title = $application->degree??''.' APPLICATION FOR '.$application->campus->name??' --- '.' CAMPUS';
        $status = $application->program_status;
        $title = "APPLICATION FORM FOR ".($data['degree']->deg_name??'')."(".$status.")";
        $data['title'] = $title;

        // if(in_array(null, array_values($data))){ return redirect(route('student.application.start', [0, $id]))->with('message', "Make sure your form is correctly filled and try again.");}
        // return view('student.online.form_dawnloadable', $data);
        $pdf = PDF::loadView('student.online.form_dawnloadable', $data);
        $filename = $title.' - '.$application->name.'.pdf';
        return $pdf->download($filename);
    }

    public function edit_application_form(Request $request, $id = null)
    {
        # code...
        if($id == null){
            $data['title'] = "Edit Student Information";
            $data['_this'] = $this;
            $data['action'] = __('text.word_edit');
            $data['applications'] = ApplicationForm::whereNotNull('transaction_id')->where('admitted', 0)
                ->where(function($builder){
                    if(($campus_id = auth()->user()->campus_id) != null){
                        $builder->where('campus_id', $campus_id);
                    }
                })->where('year_id', Helpers::instance()->getCurrentAccademicYear())->get();
            return view('admin.student.applications', $data);
        }

        # code...
        // return $this->api_service->campuses();
        $data['status_set'] = $this->api_service->program_provisioning_status_set()['data'];
        $data['campuses'] = json_decode($this->api_service->campuses())->data;
        $data['application'] = ApplicationForm::find($id);
        $data['levels'] = collect(json_decode($this->api_service->levels())->data);
        $data['degrees'] = collect(json_decode($this->api_service->degrees())->data);
        $data['divisions'] = $data['application']->_region?->divisions;
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
        
        $data['title'] = "APPLICATION FORM FOR ".$data['degree']->deg_name;
        return view('admin.student.edit_form', $data);
        
    }
    public function update_application_form(Request $request, $id)
    {
        # code...
        $data = $data = $request->all();
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
        $application = ApplicationForm::updateOrInsert(['id'=> $id], $data);
        // $application->update($data);
        
        ApplicationForm::find($id)->update($data);
        return back()->with('success', __('text.word_done'));
    }

    public function uncompleted_application_form(Request $request, $id=null)
    {
        # code...
        if($id == null){
            $data['title'] = "Uncompleted Application Forms";
            $data['_this'] = $this;
            $data['action'] = __('text.word_show');
            $data['applications'] = ApplicationForm::whereNull('transaction_id')
                ->where(function($builder){
                    if(($campus_id = auth()->user()->campus_id) != null){
                        $builder->where('campus_id', $campus_id);
                    }
                })->where('year_id', Helpers::instance()->getCurrentAccademicYear())->get();
            // return $data;
            return view('admin.student.applications', $data);
        }

        // return $this->api_service->campuses();
        $data['campuses'] = json_decode($this->api_service->campuses())->data;
        $data['application'] = ApplicationForm::find($id);

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
        
        $data['title'] = "INCOMPLETE APPLICATION FORM ".( array_key_exists('degree', $data) ? "FOR ".$data['degree']->deg_name : null);
        return view('admin.student.show_form', $data);
    }

    public function distant_application_form(Request $request, $id)
    {
        # code...
    }

    public function admission_letter(Request $request, $id = null)
    {

        // return back();
        # code...
        $year_id = request('year_id', Helpers::instance()->getCurrentAccademicYear());
        if($id == null){
            $data['title'] = "Send Student Admission Letter";
            $data['_this'] = $this;
            $data['action'] = __('text.word_send');
            $data['download'] = __('text.word_download');
            $data['applications'] = ApplicationForm::whereNotNull('transaction_id')->where('admitted', 1)
                ->where(function($builder){
                    if(($campus_id = auth()->user()->campus_id) != null){
                        $builder->where('campus_id', $campus_id);
                    }
                })->where('year_id', $year_id)->get();
            return view('admin.student.applications', $data);
        }
        if($request->has('_atn')){
            return $this->send_admission_letter($id, $request->_atn);
        }
        if($this->send_admission_letter($id)){
            return back()->with('success', __('text.word_done'));
        }
        return back()->with('error', __('text.operation_failed'));
    }

    public function send_admission_letter($id, $action = null)
    {
        // TEMPORARILY HALTING SENDING OF ADMISSION LETTERS
        // return true;

        $current_year = Helpers::instance()->getCurrentAccademicYear();
        
        $appl = ApplicationForm::find($id);
        if($appl != null){
            $campus = collect(json_decode($this->api_service->campuses())->data)->where('id', $appl->campus_id)->first()??null;
            $program = collect(json_decode($this->api_service->programs())->data)->where('id', $appl->program_first_choice)->first()??null;
            $degree = collect(json_decode($this->api_service->degrees())->data)->where('id', $appl->degree_id)->first()??null;
            $config = Config::where('year_id', Helpers::instance()->getCurrentAccademicYear())->first();

            $data['platform_links'] = [
                'BONABERI'=>'https://bnb.stlouissystems.org',
                'BONAMOUSSADI'=>'https://bms.stlouissystems.org',
                'YAOUNDE'=>'https://yde.stlouissystems.org',
            ];

            $data['title'] = "ADMISSION LETTER";
            $data['name'] = $appl->name;
            $data['matric'] =  $appl->matric;
            $data['registrar'] = "Mandi Derick Ediange";
            $data['dean_name'] = $config->dean??null;
            $data['fee1_dateline'] = $config->fee1_latest_date;
            $data['fee2_dateline'] = $config->fee2_latest_date;
            $data['help_email'] =  $config->help_email;
            $data['campus'] = $campus->name??null;
            $data['degree'] = $degree->deg_name??null;
            $data['program'] = str_replace($data['degree'], ' ', $program->name??"");
    
            $pdf = Pdf::loadView('admin.student.admission_letter', $data);
            if($action == '_dld'){
                return $pdf->download($appl->matric.'_ADMISSION_LETTER.pdf');
            }
            // $this->sendAdmissionEmails($appl->name, $appl->email, $appl->matric, $program->name??null, $campus->name??null, $config->fee1_latest_date, $config->fee2_latest_date, $config->director, $config->dean, $config->help_email, $pdf, $degree->deg_name??null);
            return true;
        }
        return false;
    }

    public function admit_application_form(Request $request, $id=null)
    {
        # code...
        if($id == null){
            $data['title'] = "Admit Student";
            $data['_this'] = $this;
            $data['action'] = __('text.word_admit');
            $data['applications'] = ApplicationForm::whereNotNull('transaction_id')->where('admitted', 0)
                ->where(function($builder){
                    if(($campus_id = auth()->user()->campus_id) != null){
                        $builder->where('campus_id', $campus_id);
                    }
                })->where('year_id', Helpers::instance()->getCurrentAccademicYear())->get();
            return view('admin.student.applications', $data);
        }
        if(!$request->has('matric') or ($request->matric == null)){
            // 
            // GENERATE MATRICULE
            $application = ApplicationForm::find($id);
            if(($programs = json_decode($this->api_service->programs())->data) != null){
                $program = collect($programs)->where('id', $application->program_first_choice)->first()??null;
                if($program != null){
                    // dd($program);
                    $year = substr(Batch::find(Helpers::instance()->getCurrentAccademicYear())->name, 2, 2);
                    $prefix = $program->prefix??null;//3 char length
                    $max_count = '';
                    if($prefix == null){
                        return back()->with('error', 'Matricule generation prefix not set.');
                    }
                    $max_matric = json_decode(json: $this->api_service->max_matric($prefix, $year, ['type' => 'degree', 'degree_id' => $application->degree_id]))->data; //matrics starting with '$prefix' sort
                    // dd($max_matric);
                    if($max_matric == null){
                        $max_count = 0;
                    }else{
                        $max_count = intval(substr($max_matric, strlen($prefix)+4));
                    }

                    NEXT_MATRIC:
                    $next_count = substr('0000'.(++$max_count), -4);
                    $student_matric = $prefix.'/'.$year.'/'.$next_count;
                    // dd($student_matric);
                    if(ApplicationForm::where('matric', $student_matric)->where('id', '!=', $id)->count() == 0){
                        $matric_exist = json_decode($this->api_service->matric_exist($student_matric))->data??0;
                        if($matric_exist == 1){
                            goto NEXT_MATRIC;
                        }
                        $data['title'] = "Student Admission";
                        $data['application'] = $application;
                        $data['program'] = $program;
                        $data['matricule'] = $student_matric;
                        $data['campus'] = collect(json_decode($this->api_service->campuses())->data)->where('id', $application->campus_id)->first();
                        return view('admin.student.confirm_admission', $data);
                    }else{
                        # code...
                        goto NEXT_MATRIC;
                    }
                    return back()->with('error', 'Failed to generate matricule');
                }
            }
        }
    }


    public function admit_student(Request $request, $id)
    {

        
        $validity = Validator::make($request->all(), ['matric'=>'required']);
        if($validity->fails()){
            return back()->with('error', 'Missing matricule');
        }
        $application = ApplicationForm::find($id);

        // dd($application);
        // POST STUDENT TO SCHOOL SYSTEM
        $application->matric = $request->matric;

        $resp = json_decode($this->api_service->store_student($application->toArray()))->data??null;
        // dd($resp);
        if($resp != null and !is_string($resp)){
           if($resp->status == 1){
                $application->update(['matric'=>$request->matric, 'admitted'=>1]);

                // Send sms/email notification
                $phone_number = $application->phone;
                if(str_starts_with($phone_number, '+')){
                    $phone_number = substr($phone_number, '1');
                }
                if(strlen($phone_number) <= 9){
                    $phone_number = '237'.$phone_number;
                }
                // dd($phone_number);
                $message="Congratulations {$application->name}. You have been admitted into ST LOUIS UNIVERSITY INSTITUTE for {$application->year->name} . Access your admission portal to download your admission letter";
                // $sent = $this->sendSMS($phone_number, $message);
                $this->tranzak_sms_service->send([$phone_number], $message);

                // Send student admission letter to email
                $this->send_admission_letter($application->id);

                return redirect(route('admin.applications.admit'))->with('success', "Student admitted successfully.");
           }else
           return back()->with('error', $resp);
       }else{
           return back()->with('error', $resp);
       }



    }


    public function application_form_change_program(Request $request, $id = null)
    {
        # code...
        if($id == null){
            $data['title'] = "Change Student Program";
            $data['_this'] = $this;
            $data['action'] = __('text.change_program');
            $data['applications'] = ApplicationForm::where('admitted', true)
                ->where(function($builder){
                    if(($campus_id = auth()->user()->campus_id) != null){
                        $builder->where('campus_id', $campus_id);
                    }
                })->where('year_id', Helpers::instance()->getCurrentAccademicYear())->get();
            return view('admin.student.applications', $data);
        }

        // return $this->api_service->campuses();
        $data['campuses'] = json_decode($this->api_service->campuses())->data;
        $data['application'] = ApplicationForm::find($id);
        
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
            $data['programs'] = json_decode($this->api_service->campusPrograms($data['application']->campus_id))->data;
            $data['cert'] = collect($data['certs'])->where('id', $data['application']->entry_qualification)->first();
        }
        if($data['application']->program_first_choice != null){
            $data['program1'] = collect($data['programs'])->where('id', $data['application']->program_first_choice)->first();
            $data['program2'] = collect($data['programs'])->where('id', $data['application']->program_second_choice)->first();
            // return $data;
        }
        if($data['application']->level != null){
            $data['levels'] = json_decode($this->api_service->levels())->data;
        }
        // dd($data);
        
        $data['title'] = "CHANGE PROGRAM FOR ".$data['degree']->deg_name;
        return view('admin.student.change_program', $data);
    }

    public function change_program(Request $request, $id)
    {
        # code...
        $validity = Validator::make($request->all(), ['current_program'=>'required', 'new_program'=>'required', 'level'=>'required']);
        if($validity->fails()){
            return back()->with('error', $validity->errors()->first());
        }
        $data = ['program_first_choice'=>$request->new_program, 'level'=>$request->level];
        session()->put('program_change_update', $data);
        // ApplicationForm::find($id)->update($data);

        // UPDATE STUDENT IN SCHOOL SYSTEM.
        // 
        // GENERATE MATRICULE
        $application = ApplicationForm::find($id);
        if(($programs = json_decode($this->api_service->programs())->data) != null){
            $program = collect($programs)->where('id', $request->new_program)->first()??null;
            if($program != null){
                
                $year = substr(Batch::find(Helpers::instance()->getCurrentAccademicYear())->name, 2, 2);
                $prefix = $program->prefix;//3 char length
                $max_count = '';
                if($prefix == null){
                    return back()->with('error', 'Matricule generation prefix not set.');
                }
                $max_matric = json_decode($this->api_service->max_matric($prefix, $year, ['type' => 'degree', 'degree_id' => $application->degree_id]))->data; //matrics starting with '$prefix' sort
                if($max_matric == null){
                    $max_count = 0;
                }else{
                    $max_count = intval(substr($max_matric, strlen($prefix)+4));
                }
                
                
                NEXT_ATTEMPT:
                $next_count = substr('0000'.(++$max_count), -4);
                $student_matric = $prefix.'/'.$year.'/'.$next_count;
                
                if(ApplicationForm::where('matric', $student_matric)->count() == 0){

                    // dd($student_matric);
                    $matric_exist = json_decode($this->api_service->matric_exist($student_matric))->data??0;
                    if($matric_exist == 1){
                        goto NEXT_ATTEMPT;
                    }
                    $data['title'] = "Change Student Program";
                    $data['application'] = $application;
                    $data['program'] = $program;
                    $data['matricule'] = $student_matric;
                    $data['campus'] = collect(json_decode($this->api_service->campuses())->data)->where('id', $application->campus_id)->first();
                    return view('admin.student.confirm_change_program', $data);
                }else{
                    goto NEXT_ATTEMPT;
                }
                
                return back()->with('error', "Failed to generate matricule. {$student_matric}");
            }
        }
        return back()->with('success', 'Done');
    }

    public function change_program_save(Request $request, $id)
    {
        # code...
        $validity = Validator::make($request->all(), ['matric'=>'required']);
        if($validity->fails()){return back()->with('error', 'Missing matricule');}
        $application = ApplicationForm::find($id);
        // dd($application->toJson());
        // (new ApplicationForm())-
        
        
        // POST STUDENT TO SCHOOL SYSTEM
        $update_data = session()->get('program_change_update');
        $program = collect(json_decode($this->api_service->programs())->data)->where('id', $update_data['program_first_choice'])->first()??null;
        $resp = json_decode($this->api_service->update_student($application->matric, ['program'=>$update_data['program_first_choice'], 'level'=>$update_data['level'], 'matric'=>$request->matric]))->data??null;
        // dd($resp);
        if($resp != null){
            if($resp->status ==1){
                // $application->matric = $request->matric;
                $update_data['degree_id'] = $program->degree_id??$application->degree_id;
                $update_data['matric'] = $request->matric;
                $update_data['admitted'] = 1;
                $application->update($update_data);

                // Send sms/email notification
                return redirect(route('admin.applications.admit'))->with('success', "Program changed successfully.");
            }else
            return back()->with('error', $resp);
        }
    }

    public function bypass_application_form(Request $request, $id)
    {
        # code...
        // create a relatively null transaction for the student
        $data = ['request_id'=>auth()->id(), 'amount'=>0, 'currency_code'=>'_____', 'purpose'=>'_____', 'mobile_wallet_number'=>'_______', 'transaction_ref'=>'_______', 'app_id'=>'_______', 'transaction_id'=>'_________', 'transaction_time'=>now()->toDateTimeString(), 'payment_method'=>'______', 'payer_user_id'=>'_________', 'payer_name'=>'_________', 'payer_account_id'=>'________', 'merchant_fee'=>0, 'merchant_account_id'=>'___________', 'net_amount_recieved'=>0];
        $transaction = new Transaction($data);
        $transaction->save();

        $application = ApplicationForm::find($id);
        $application->update(['transaction_id'=>$transaction->id]);
        return redirect(route('admin.applications.uncompleted'))->with('success', __('text.word_done'));
    }

    public function applications_per_program(Request $request, $program_id = null)
    {
        # code...
        $campus_id = auth()->user()->campus_id;
        if($program_id == null){
            // select program
            $data['title'] = "Select Program";
            $data['campus_id'] = $campus_id;
            $data['programs'] = json_decode($this->api_service->programs())->data??[];
            return view('admin.student.program_applications', $data);
        }else{
            $progs = collect(json_decode($this->api_service->programs())->data);
            $data['title'] = $progs->where('id', $program_id)->first()->name." Applications";
            $data['progs'] = $progs;
            if($campus_id != null){
                $data['appls'] = ApplicationForm::where('program_first_choice', $program_id)->whereNotNull('transaction_id')->where('year_id', \App\helpers\Helpers::instance()->getCurrentAccademicYear())->where('campus_id', $campus_id)->get();
            }else{
                $data['appls'] = ApplicationForm::where('program_first_choice', $program_id)->whereNotNull('transaction_id')
                    ->where(function($builder){
                        if(($campus_id = auth()->user()->campus_id) != null){
                            $builder->where('campus_id', $campus_id);
                        }
                    })->where('year_id', \App\helpers\Helpers::instance()->getCurrentAccademicYear())->get();
            }
            return view('admin.student.program_applications', $data);
        }
    }

    public function applications_per_degree(Request $request, $degree_id = null)
    {
        # code...
        $campus_id = auth()->user()->campus_id;
        if($degree_id == null){
            $data['title'] = "Select Degree type";
            $data['campus_id'] = $campus_id;
            $data['degrees'] = json_decode($this->api_service->degrees())->data??[];
            return view('admin.student.degree_applications', $data);
        }else{
            $progs = collect(json_decode($this->api_service->programs())->data);
            $degs = collect(json_decode($this->api_service->degrees())->data);
            // dd($degs->where('id', $degree_id)->first());
            $data['title'] = $degs->where('id', $degree_id)->first()->deg_name.' Applications';
            $data['progs'] = $progs;
            if($campus_id != null){
                $data['appls'] = ApplicationForm::where('degree_id', $degree_id)->whereNotNull('transaction_id')->where('year_id', \App\helpers\Helpers::instance()->getCurrentAccademicYear())->where('campus_id', $campus_id)->get();
            }else{
                $data['appls'] = ApplicationForm::where('degree_id', $degree_id)->whereNotNull('transaction_id')
                    ->where(function($builder){
                        if(($campus_id = auth()->user()->campus_id) != null){
                            $builder->where('campus_id', $campus_id);
                        }
                    })->where('year_id', \App\helpers\Helpers::instance()->getCurrentAccademicYear())->get();
            }
            return view('admin.student.degree_applications', $data);
        }
    }

    public function applications_per_campus($campus_id = null)
    {
        # code...
        $campuses = collect(json_decode($this->api_service->campuses())->data);
        // dd($campuses);
        if($campus_id == null){
            $campus = auth()->user()->campus_id;
            if($campus == null){
                $data['campuses'] = ApplicationForm::where('year_id', \App\helpers\Helpers::instance()->getCurrentAccademicYear())
                    ->where(function($builder){
                        if(($campus_id = auth()->user()->campus_id) != null){
                            $builder->where('campus_id', $campus_id);
                        }
                    })->select(['campus_id', DB::raw('COUNT(id) as applicants')])->whereNotNull('transaction_id')->groupBy('campus_id')->get()->map(function($row)use($campuses){
                    $row->campus_name = $campuses->where('id', $row->campus_id)->first()->name??'';
                    return $row;
                });
            }else{
                $data['campuses'] = ApplicationForm::where('year_id', \App\helpers\Helpers::instance()->getCurrentAccademicYear())->select(['campus_id', DB::raw('COUNT(id) as applicants')])->whereNotNull('transaction_id')->where('campus_id', $campus)->groupBy('campus_id')->get()->map(function($row)use($campuses){
                    $row->campus_name = $campuses->where('id', $row->campus_id)->first()->name??'';
                    return $row;
                });
            }
            $data['title'] = "Applications per Campus";
        }else{
            $data['title'] = 'Applications for '.$campuses->where('id', $campus_id)->first()->name??null;
            $data['appls'] = ApplicationForm::where('campus_id', $campus_id)->whereNotNull('transaction_id')->where('year_id', \App\helpers\Helpers::instance()->getCurrentAccademicYear())->orderBy('name')->get();
            $data['progs'] = collect(json_decode($this->api_service->programs())->data);
        }
        // dd($data);
        return view('admin.student.campus_applications', $data);
    }

    public function finance_general_report(Request $request)
    {
        # code...
        $data['title'] = "General Financial Reports";
        $data['appls'] = ApplicationForm::where(['year_id'=>\App\helpers\Helpers::instance()->getCurrentAccademicYear()])
            ->where(function($builder){
                if(($campus_id = auth()->user()->campus_id) != null){
                    $builder->where('campus_id', $campus_id);
                }
            })->get();
        return view('admin.student.finance_general', $data);
    }

    private function sendAdmissionEmails($name, $email, $matric, $program, $campus, $fee1_dateline, $fee2_dateline, $director_name, $dean_name, $help_email, $file, $degree){
        Mail::to($email)->send(new AdmissionMail($name, $campus, $program, $matric,  $fee1_dateline, $fee2_dateline, $help_email, $director_name, $dean_name, $degree,  $file, config('platform_links')[$campus]));
    }

    public function get_($degree_id = null)
    {
        # code...
        $data['title'] = __('text.configure_degree_certificates');
        $data['degrees'] = json_decode($this->api_service->degrees())->data;
        $data['certificates'] = json_decode($this->api_service->certificates())->data;
        if($degree_id != null){
            $data['degree_certificates'] = collect(json_decode($this->api_service->degree_certificates($degree_id))->data)->pluck('id')->toArray();
        }
        // dd($data);
        return view('admin.setting.degree_certs', $data);
    }

    public function set_degree_certificates(Request $request, $degree_id)
    {
        # code...
        $validator = Validator::make($request->all(), ['certificates'=>'required|array']);
        if($validator->fails()){
            return back()->with('error', $validator->errors()->first());
        }
        $certificate_ids = $request->certificates;
        $response = json_decode($this->api_service->set_degree_certificates($degree_id, $certificate_ids));
        if($response->status == 'success'){return back()->with('success', __('text.word_done'));}else{
            return back()->with('error', $response->message);
        }
    }

    public function get_degree_certificates(Request $request){
        $degree_id = $request->degree_id;
        $degree_certificates = collect(json_decode($this->api_service->degree_certificates($degree_id))->data);
        return $degree_certificates->all();
    }


    public function application_referal_report(Request $request){
        try{
            $data['title'] = "Applications Referal Report";
            $year = $request->year_id ?: Helpers::instance()->getCurrentAccademicYear();
            $data['years'] = Batch::where('id', '<=', Helpers::instance()->getCurrentAccademicYear())->orderByDesc('id')->get();
            $data['records'] = ApplicationForm::where('year_id', $year)->whereNotNull('transaction_id')->get(['id', 'referer'])
                ->map(function($rec){
                    $ref = $rec->referer;
                    $rec->referer = explode(':', $ref)[0];
                    return $rec;
                })->groupBy('referer')->map(function($grp, $key){
                    $rec = $grp->first();
                    $rec->count = $grp->count();
                    return $rec;
                });

            return view('admin.applications.reports.referal_reports', $data);
        }catch(\Throwable $th){
            logger()->error($th);
            session()->flash('error'. $th->getMessage());
            return back();
        }
    }


    public function application_referal_report_details(Request $request){
        try{
            $data['title'] = "Applications Referal Report Details &Rang; ";
            $item = $request->item;
            if(empty($item)){
                return redirect()->route('admin.reports.application.referal_report');
            }
            $data['title'] .= $item;
            $year = $request->year_id ?: Helpers::instance()->getCurrentAccademicYear();
            $programs = collect(json_decode($this->api_service->programs())->data??[]);
            $data['records'] = ApplicationForm::where('year_id', $year)->where('referer', 'LIKE', $item.'%')->whereNotNull('transaction_id')->get(['id', 'name', 'program_first_choice', 'program_second_choice', 'referer'])
                ->map(function($rec)use($programs){
                    $rec->program = $programs->where('id', $rec->program_first_choice)->first()?->name;
                    return $rec;
                });

            return view('admin.applications.reports.referal_report_details', $data);
        }catch(\Throwable $th){
            logger()->error($th);
            session()->flash('error'. $th->getMessage());
            return back();
        }
    }

}
