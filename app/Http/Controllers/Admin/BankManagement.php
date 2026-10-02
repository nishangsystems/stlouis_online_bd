<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BankManagement extends Controller
{

    public function index(){
        $data['title'] = "Configure Campus Banks";
        $campuses = collect(json_decode($this->api_service->campuses())->data??[]);
        $data['campuses'] = $campuses;
        $data['campus_banks'] = \App\Models\CampusBank::all()->map(function($item) use ($campuses){
            $item->campus = $campuses->where('id', $item->campus_id)->first();
            return $item;
        });

        return view('admin.banks.campus_banks.index', $data);
    }


    public function save_campus_bank (Request $request){
        try{
            $request->validate([
                'campus_id' => 'required',
                'bank_name' => 'required',
                'account_name' => 'required',
                'account_number' => 'required',
            ]);

            $data = $request->only(['campus_id', 'bank_name', 'account_name', 'account_number']);
            if(\App\Models\CampusBank::where($data)->count() > 0){
                session()->flash('error', 'An record already exist with the provided account info');
                return back();
            }

            $instance = \App\Models\CampusBank::create($data);
            session()->flash('success', "Configuration updated successfully");
            return back();

        }catch(\Throwable $th){
            logger()->error($th);
            session()->flash('error', $th->getMessage());
            return back();
        }
    }


    public function edit_campus_bank(Request $request, $id){
        try{

            $data['title'] = "Configure Campus Banks";
            $data['config'] = \App\Models\CampusBank::find($id);
            if($data['config'] == null){
                session()->flash('error', "No record was found with the given ID");
                return back();
            }

            $campuses = collect(json_decode($this->api_service->campuses())->data??[]);
            $data['campuses'] = $campuses;
            $data['campus_banks'] = \App\Models\CampusBank::all()->map(function($item) use ($campuses){
                $item->campus = $campuses->where('id', $item->campus_id)->first();
                return $item;
            });

            $data['config']->campus = $campuses->where('id', $data['config']->campus_id)->first();

            return view('admin.banks.campus_banks.edit', $data);

        }catch(\Throwable $th){
            logger()->error($th);
            session()->flash('error', $th->getMessage());
            return back();
        }
    }


    public function update_campus_bank(request $request, $id){
        try{
            $config = \App\Models\CampusBank::find($id);
            if($config == null){
                session()->flash('error', "No record was found with the given ID");
                return back();
            }

            $request->validate(['bank_name' => 'required', 'account_name' =>'required', 'campus_id' => 'required', 'account_number' => 'required']);
            $update = $request->only(['campus_id', 'bank_name', 'account_name', 'account_number']);
            $config->update($update);
            session()->flash('success', "Configuration updated successfully");
            return back();

        }catch(\Throwable $th){
            logger()->error($th);
            session()->flash('error', $th->getMessage());
            return back();
        }
    }


    public function delete_campus_bank(Request $request, $id){
        try {
            //code...
            $cbank = \App\Models\CampusBank::find($id);
            if($cbank == null){
                session()->flash('error', "No record was found with the provided ID");
                return back();
            }

            // check if this bank has been used in any application
            if(\App\Models\ApplicationForm::where('campus_bank_id', $id)->count() > 0){
                session()->flash('error', "Operation denied. This setting has been used. Consider editing it");
                return back();
            }

            $cbank->delete();
            session()->flash('success', "Record deleted successfully");
            return back();
            
        } catch (\Throwable $th) {
            //throw $th;
            logger()->error($th);
            session()->flash('error', $th->getMessage());
            return back();
        }
    }
}
