<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class TranzakSMSService{

    private $api_key;
    private $app_id;
    private $cache_api_token_key;
    public function __construct(){

        $this->api_key = config('tranzak_sms.nishang_sms_api_key');
        $this->app_id = config('tranzak_sms.nishang_sms_app_id');
        // $this->api_key = config('tranzak_sms.biaka_sms_api_key');
        // $this->app_id = config('tranzak_sms.biaka_sms_app_id');
        $this->cache_api_token_key = config('tranzak_sms.sms_token');

        try {
            //code...
            if(!(Cache::has($this->cache_api_token_key)) or (Cache::has($this->cache_api_token_key.'_expiry') and Carbon::parse(cache($this->cache_api_token_key.'_expiry'))->isPast())){
                // get and cache different token
                GEN_TOKEN:
                // dd(config('tranzak.tranzak.base').config('tranzak.tranzak.token'));
                $response = Http::post(
                    config('tranzak_sms.base').config('tranzak_sms.token'), 
                    [
                        'appId'=>$this->app_id, 
                        'appKey'=>$this->api_key
                    ]
                );
                if($response->status() == 200){
                    // cache token and token expirationtot session
                    $data = $response->collect('data');
                    if(in_array('token', $data->toArray())){
                        cache([$this->cache_api_token_key => $data['token']]);
                        cache([$this->cache_api_token_key.'_expiry'=>Carbon::createFromTimestamp(time() + $data['expiresIn'])]);
                    }else
                    goto ERROR;
                }else{
                    ERROR:
                    throw new Exception("Error authentication SMS servers. Contact service provider if this persists");
                }
            }
        } catch (\Illuminate\Http\Client\ConnectionException $err) {
            // session()->flash('error', "Tranzak authentication failed. Make sure url is correct and that you have internet connection");
            logger()->error("Tranzak authentication failed. Make sure url is correct and that you have internet connection");
            return; 
        } catch (\Throwable $th){
            throw $th;
        }
    }

    public function custom_trim($phones){
        return array_map(function($item){
            if(is_array($item)){
                $item = implode('', $item);
            }
            $item = str_replace([' ', ','], '', $item);
            $item = strlen($item) <= 9 ? '237'.$item : $item;
            $item = strstr($item, '+') == false ? '+'.$item : $item;
            return $item;
        }, $phones);
    }

    /**
     * Summary of send
     * @param array $phones
     * @param string $message
     * @return bool
     */
    public function send($phones, $message){
        // Assumed there is a valid api token
        // Moving to performing the payment request proper
        $headers = ['Authorization'=>'Bearer '.cache($this->cache_api_token_key)];
        $request_data = ['phones'=>implode(',', $this->custom_trim([$phones])), 'msg'=>$message, 'senderId'=>config('tranzak_sms.nishang_sms_sender_id')];
        // $request_data = ['phones'=>implode(',', $this->custom_trim([$phones])), 'msg'=>$message, 'senderId'=>config('tranzak_sms.biaka_sms_sender_id')];
        // dd($request_data);
        $_response = Http::withHeaders($headers)->post(config('tranzak_sms.base').config('tranzak_sms.send_sms'), $request_data);
        // dd($_response->collect());
        if($_response->status() == 200){            
            // dd($_response->collect());
            return true;
        }else{
            // dd($_response->collect());
            return false;
        }
    }

    /**
     * Summary of send_otp
     * @param array $phones
     * @param string $otp
     * @return bool
     */

    
    public function send_otp($phones, $otp){
        // Assumed there is a valid api token
        // Moving to performing the payment request proper
        $headers = ['Authorization'=>'Bearer '.cache($this->cache_api_token_key)];
        $request_data = ['phones'=>implode(',', $this->custom_trim([$phones])), 'templateId'=>config('tranzak_sms.otp_templates.default'), 'params'=>[$otp], 'senderId'=>config('tranzak_sms.nishang_sms_sender_id')];
        // $request_data = ['phones'=>implode(',', $this->custom_trim([$phones])), 'msg'=>$message, 'senderId'=>config('tranzak_sms.biaka_sms_sender_id')];
        // dd($request_data);
        $_response = Http::withHeaders($headers)->post(config('tranzak_sms.base').config('tranzak_sms.send_otp'), $request_data);
        // dd($_response->collect());
        if($_response->status() == 200){            
            // dd($_response->collect());
            return true;
        }else{
            // dd($_response->collect());
            return false;
        }
    }
}