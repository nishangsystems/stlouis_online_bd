<?php
namespace App\Http\Services;
use App\Helpers\Helpers;
use Illuminate\Support\Facades\Http;

class ApiService{

    
    // get campuses
    public function campuses(){
        // dd(Helpers::instance()->getApiRoot().'/'.config('api_routes.campuses'));
        return Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.campuses'))->body();
    }

    // get campuses
    public function campusDegrees($campus_id){
        // dd([ Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.campus_degrees').'/'.$campus_id)->body(), Helpers::instance()->getApiRoot().'/'.config('api_routes.campus_degrees').'/'.$campus_id]);
        return Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.campus_degrees').'/'.$campus_id)->body();
    }

    // get campuses
    public function campusProgramLevels($campus_id, $program_id){
        // dd([ Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.campus_program_levels').'/'.$campus_id.'/'.$program_id)->body(), Helpers::instance()->getApiRoot().'/'.config('api_routes.campus_program_levels').'/'.$campus_id.'/'.$program_id]);
        return Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.campus_program_levels').'/'.$campus_id.'/'.$program_id)->body();
    }

    // get campuses
    public function setCertificatePrograms($certificate_id, array $program_ids){
        // dd([ Http::post(Helpers::instance()->getApiRoot().'/'.config('api_routes.certificate_programs').'/'.$certificate_id, ['certificate_id'=>$certificate_id, 'program_ids'=>$program_ids])->body(), Helpers::instance()->getApiRoot().'/'.config('api_routes.certificate_programs').'/'.$certificate_id]);
        return Http::post(Helpers::instance()->getApiRoot().'/'.config('api_routes.certificate_programs').'/'.$certificate_id, ['certificate_id'=>$certificate_id, 'program_ids'=>$program_ids])->body();
    }

    // Store/update admitted student
    public function store_student($student,  $id= null){
        return Http::contentType('application/json')->post(Helpers::instance()->getApiRoot().'/'.config('api_routes.store_student'), ['student'=>json_encode($student)])->body();
        // return Http::contentType('application/json')->get(Helpers::instance()->getApiRoot().'/'.config('api_routes.store_student') .'?student='.json_encode($student) )->body();
    }

    // Store/update admitted student
    public function update_student($matric, $update){
        // dd([ Http::post(Helpers::instance()->getApiRoot().'/'.config('api_routes.store_student').'/'.$id??null, ['student'=>$student])->body(), Helpers::instance()->getApiRoot().'/'.config('api_routes.store_student').'/'.$id??null]);
        // dd([Http::contentType('application/json')->get(Helpers::instance()->getApiRoot().'/'.config('api_routes.update_student') .'?matric='.json_encode($matric).'&student='.json_encode($update) )->body(), Helpers::instance()->getApiRoot().'/'.config('api_routes.update_student') .'?matric='.json_encode($matric).'&student='.json_encode($update)]);
        return Http::contentType('application/json')->get(Helpers::instance()->getApiRoot().'/'.config('api_routes.update_student') .'?matric='.json_encode($matric).'&student='.json_encode($update) )->body();
    }

    // get campuses
    public function certificatePrograms($certificate_id){
        // dd([ Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.certificate_programs').'/'.$certificate_id)->body(), Helpers::instance()->getApiRoot().'/'.config('api_routes.certificate_programs').'/'.$certificate_id]);
        return Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.certificate_programs').'/'.$certificate_id)->body();
    }

    // get campuses
    public function certificates(){
        // dd([ Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.certificates'))->body(), Helpers::instance()->getApiRoot().'/'.config('api_routes.certificates')]);
        return Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.certificates'))->body();
    }

    // get campuses
    public function degrees(){
        // dd([ Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.degrees'))->body(), Helpers::instance()->getApiRoot().'/'.config('api_routes.degrees')]);
        return Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.degrees'))->body();
    }

    // get campuses
    public function campusDegreeCertificatePrograms($campus_id, $degree_id, $certificate_id){
        // dd(Helpers::instance()->getApiRoot().'/'.config('api_routes.campus_degree_certificate_programs').'/'.$campus_id.'/'.$degree_id.'/'.$certificate_id);
        // dd(Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.campus_degree_certificate_programs').'/'.$campus_id.'/'.$degree_id.'/'.$certificate_id)->collect());
        return Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.campus_degree_certificate_programs').'/'.$campus_id.'/'.$degree_id.'/'.$certificate_id)->body();
    }

    public function programs()
    {
        # code...
        // dd([ Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.programs'))->body(), Helpers::instance()->getApiRoot().'/'.config('api_routes.programs')]);
        return Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.programs'))->body();
    }


    public function campusPrograms($campus_id){
        // dd( [Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.campus_programs').'/'.$campus_id)->body(), Helpers::instance()->getApiRoot().'/'.config('api_routes.campus_programs').'/'.$campus_id]);
        return Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.campus_programs').'/'.$campus_id)->body();
    }

    public function setCampusDegrees($campus_id, $degrees)
    {
        # code...
        return Http::post(Helpers::instance()->getApiRoot().'/'.config('api_routes.campus_degrees').'/'.$campus_id, ['degrees'=>$degrees])->body();
    }

    public function levels()
    {
        # code...
        return Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.levels'))->body();
    }

    public function max_matric($prefix, $year, $params = [])
    {
        # code...
        return Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.max_matric').'/'.$prefix.'/'.$year.'?'.http_build_query($params))->body();
        // return Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.max_matric').'/'.$prefix)->body();
    }

    public function matric_exist($matric)
    {
        # code...
        return Http::post(Helpers::instance()->getApiRoot().'/'.config('api_routes.matric_exist'), ['matric'=>$matric])->body();
        // return Http::get(Helpers::instance()->getApiRoot().'/'.config('api_routes.max_matric').'/'.$prefix)->body();
    }

    public function degree_certificates($degree_id){
        // dd(Helpers::instance()->getApiRoot().'/degree/certificates/'.$degree_id);
        return Http::get(Helpers::instance()->getApiRoot().'/degree/certificates/'.$degree_id)->body();
    }

    public function set_degree_certificates($degree_id, array $certificate_ids){
        return Http::post(Helpers::instance()->getApiRoot().'/degree/certificates/'.$degree_id, ['certificates'=>$certificate_ids])->body();
    }

    // ------------------------------------------------
    // PROGRAM PROVISION STATUS MANAGEMENT STARTS HERE
    // ------------------------------------------------

    public function program_provision_status_settings($campus_id = null, $status = null){
        return Http::get(Helpers::instance()->getApiRoot()."/program_provisioning/campus_propram_status?campus_id={$campus_id}&status={$status}")->collect();
    }

    public function program_provisioning_status_set(){
        return Http::get(Helpers::instance()->getApiRoot()."/program_provisioning/all_status")->collect();
    }

    public function program_provision_status($program_id, $campus_id = null){
        return Http::get(Helpers::instance()->getApiRoot()."/program_provisioning/program_status?program_id={$program_id}&campus_id={$campus_id}")->collect();
    }

    public function program_provision_student_statics($program_id=null, $campus_id=null, $year_id=null, $status=null){
        return Http::get(Helpers::instance()->getApiRoot()."/program_provisioning/admission_status_statics?program_id={$program_id}&campus_id={$campus_id}&year_id={$year_id}&status={$status}")->collect();
    }

    public function campus_program_provision_update_status($data){
        return Http::post(Helpers::instance()->getApiRoot()."/program_provisioning/update_campus_program_status", $data)->collect();
    }

}