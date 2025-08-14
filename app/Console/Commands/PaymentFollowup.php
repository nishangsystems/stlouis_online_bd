<?php

namespace App\Console\Commands;

use App\Models\ApplicationForm;
use App\Models\Charge;
use App\Models\TranzakTransaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentFollowup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payment_followup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // read through all pending transactions
        // for each transaction, check the status using the requestID
        // if failed/cancelled, delete pending transaction
        // if successful, effect the expected payment operation
        Log::channel('payment_follower')->info('Runing a follow-up now');
        foreach (\App\Models\PendingTranzakTransaction::all() as $key => $pending_transaction) {
            # code...
            $trans = json_decode($pending_transaction->transaction);
            $counter = 0;
            $tranzak_credentials = \App\Models\TranzakCredential::where('campus_id', $pending_transaction->campus_id)->first();
            if($tranzak_credentials == null){
                continue;
            }
            if(cache($tranzak_credentials->cache_token_key) == null or Carbon::parse(cache($tranzak_credentials->cache_token_expiry_key))->isAfter(now())){
                GEN_TOKEN:
                $response = Http::post(config('tranzak.tranzak.base').config('tranzak.tranzak.token'), ['appId'=>$tranzak_credentials->app_id, 'appKey'=>$tranzak_credentials->api_key]);
                if($response->status() == 200){
                    cache([$tranzak_credentials->cache_token_key => json_decode($response->body())->data->token]);
                    cache([$tranzak_credentials->cache_token_expiry_key=>Carbon::createFromTimestamp(time() + json_decode($response->body())->data->expiresIn)]);
                }
                $counter++;
            }
            $url = config('tranzak.tranzak.base').config('tranzak.tranzak.transaction_details').$pending_transaction->requestId;
            $headers = ['Access-Control-Allow-Origin'=> '*',  'Authorization' => "Bearer ".cache($tranzak_credentials->cache_token_key)];
            $response = Http::withHeaders($headers)->get($url);
            Log::Channel('payment_follower')->info(json_encode($response->collect('data')));
            if($response->status() == 200){
                $response_data = $response->collect('data');
                switch($response_data['status']){
                    case "SUCCESSFUL":
                        // save the transaction to tranzack transactions
                        $_data = ['request_id'=>$pending_transaction->requestId, 'amount'=>$trans->amount, 'currency_code'=>$trans->currencyCode, 
                            'purpose'=>$pending_transaction->purpose, 'mobile_wallet_number'=>$trans->mobileWalletNumber, 
                            'transaction_ref'=>$trans->mchTransactionRef, 'app_id'=>$trans->appId, 'transaction_id'=>$response_data['partnerTransactionId'].'/'.$response_data['transactionId'],
                             'transaction_time'=>$response_data['transactionTime'], 'payment_method'=>'TRANZAK_MOMO'];
                        $new_instance = new TranzakTransaction($_data);
                        $new_instance->save();
                        
                        // update the corresponding payment target
                        if($pending_transaction->purpose == "APPLICATION"){
                            if(($form = ApplicationForm::find($pending_transaction->form_id)) != null){
                                $form->update(['transaction_id'=>$new_instance->id]);
                            }
                        }
                        elseif($pending_transaction->purpose == "PLATFORM"){
                            $data = ['year_id'=>$pending_transaction->year_id, 'student_id'=>$pending_transaction->student_id, 'amount'=>$trans->amount, 'item_id'=>$pending_transaction->payment_id, 'transaction_id'=>$new_instance->id, 'parent'=>false, 'type'=>'PLATFORM', 'used'=>true];
                            $charge = new Charge($data);
                            $charge->save();
                        }
                        $pending_transaction->delete();
                        $this->line('__________');
                        break;

                    case "FAILED":
                        $pending_transaction->delete();
                        $this->line('__________');
                        break;
                }
            }else{
                // Generate token
                if($counter <= 3)
                    goto GEN_TOKEN;
            }
        }
    }
}
