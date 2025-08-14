<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Http\Services\ApiService;
use Illuminate\Http\Request;

class ProgramProvisionController extends Controller
{

    //
    public function index(request $request){
        $data['title'] = "Program Provision Status";
        $data['status_set'] = $this->api_service->program_provisioning_status_set()['data'];
        $status_collection = $this->api_service->program_provision_status_settings();
        if($status_collection->has('message')){
            session()->flash('error', $status_collection['message']);
            $data['data'] = [];
        }
        if($status_collection->has(['data'])){
            $data['data'] = $status_collection['data'];
        }
        // dd($data);
        return view('admin.program_provisions.index', $data);
    }

    //
    public function configure(request $request, $campus_id = null){
        $data['title'] = "Configure Program Provision Status";
        $data['campuses'] = collect(json_decode($this->api_service->campuses())->data);
        $data['status_set'] = $this->api_service->program_provisioning_status_set()['data'];
        if($campus_id != null){
            $data['title'] = "Configure Program Provision Status For ".optional($data['campuses']->where('id', $campus_id)->first())->name??'';
            $data['status_collection'] = collect($this->api_service->program_provision_status_settings($campus_id = $campus_id)['data']);
            $data['programs'] = collect(json_decode($this->api_service->programs())->data)
                ->each(function($rec)use($data){
                    $rec->_stats = collect($data['status_collection']->first())->where('program_id', $rec->id)->pluck('status')->first();
                });
        }
        // dd($data);
        return view('admin.program_provisions.configure', $data);
    }
        
    //
    public function save_configuration(request $request, $campus_id){
        try {
            $data = ['campus_id'=>$campus_id, 'program_status'=>$request->input('program_status')];
            $res = $this->api_service->campus_program_provision_update_status($data);
            if($res->has('data')){
                return back()->with('success', "Done"); 
            }
            return back()->with('error', $res->get('message', ''));
        } catch (\Throwable $th) {
            //throw $th;
            return back()->with('error', $th->getMessage());
        }   
    }

}
