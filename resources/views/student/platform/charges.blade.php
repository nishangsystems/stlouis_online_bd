@extends('student.layout')
@section('section')
@php
    $year = $year_id;
@endphp
    <div class="py-3">
        @if ($purpose == 'RESULTS' && (\App\Models\Charge::where(['year_id'=>$year_id, 'semester_id'=>$semester->id, 'student_id'=>auth('student')->id(), 'type'=>'RESULTS'])->count() > 0))
            <div class="text-center py-3 h4 text-info">{{__('text.dublicate_charges_attempt')}}</div>
        @elseif ($purpose == 'PLATFORM' && (\App\Models\Charge::where(['year_id'=>$year_id, 'student_id'=>auth('student')->id(), 'type'=>'PLATFORM'])->count() > 0))
            <div class="text-center py-3 h4 text-info">{{__('text.dublicate_charges_attempt')}}</div>
        @else
            <!-- check if student has already paid the request  -->
            <div class="container">

                <div class="text-center text-danger h3">
                    @switch($purpose)
                        @case('PLATFORM')
                            {{__('text.platform_payments_template_text', ['purpose'=>__('text.PLATFORM_CHARGES'), 'amount'=>$amount, 'year'=>\App\Models\Batch::find($year_id)->name, 'semester'=>''])}}
                            @break
                        @case('RESULTS')
                            {{__('text.platform_payments_template_text', ['purpose'=>__('text.SEMESTER_RESULT_CHARGES'), 'amount'=>$amount, 'year'=>\App\Models\Batch::find($year_id)->name, 'semester'=>$semester->name])}}
                            @break
                        @case('TRANSCRIPT')
                            {{__('text.platform_payments_template_text', ['purpose'=>__('text.TRANSCRIPT_APPLICATION_CHARGES'), 'amount'=>$amount,  'year'=>\App\Models\Batch::find($year_id)->name, 'semester'=>''])}}
                            @break
                    
                        @default
                            
                    @endswitch
                </div>
                
                <form method="post" action="{{route('student.charge.pay')}}" id="poster-form" class="mt-5 py-4 px-3 bg-light" style="border-radius: 1rem;">
                    <!-- SET REQUIRED HIDDEN INPUT FIELDS HERE -->
                    @csrf
                    <input type="hidden" name="payment_purpose" value="{{$purpose}}">
                    <input type="hidden" name="student_id" value="{{auth('student')->id()}}">
                    <input type="hidden" name="payment_id" value="{{$payment_id}}">
                    <input type="hidden" name="amount" value="{{$amount}}">
                    <input type="hidden" name="year_id" value="{{$year_id}}">
                    <input type="hidden" name="semester_id" value="{{$semester_id ?? ''}}">
                    <input type="hidden" name="channel" id="p-channel" value="{{$semester_id ?? ''}}"><!-- holds the payment channel to be used for payment -->
                    <div class="row my-4 py-3">
                        <label class="col-sm-3 text-capitalize">{{__('text.word_amount')}}</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="tel" name="tel" value="{{$amount}}" readonly>
                        </div>
                    </div>
                    <div class="row my-4 py-3">
                        <label class="col-sm-3 text-capitalize">{{__('text.payment_number')}}</label>
                        <div class="col-sm-9">
                            <input class="form-control" type="tel" name="tel" value="{{auth('student')->user()->phone}}">
                        </div>
                    </div>
                    <div class="row my-4 py-3">
                        <label class="col-sm-3 text-capitalize"></label>
                        <div class="col-sm-9">
                            <h3 class="text-dark text-capitalize">{{__('text.pay_with')}} : </h3>
                            <div class="flex justify-content-center text-center my-4 py-2">
                                {{-- <span class="mx-3 text-center d-inline-block">
                                    <button type="submit" class="d-block border-0 btn-white rounded-md mb-4" onclick="event.preventDefault(); $('#p-channel').val('mtnmomo'); $('#poster-form').submit()">
                                        <img class="img img-responsive rounded d-block" src="{{url('public/assets/images/mtn_momo.jpg')}}" style="height: 8rem; width: 12rem">
                                    </button>
                                    <span class="h4 fw-bolder">{{__('text.mtn_mobile_money')}}</span>
                                </span> --}}
                                <span class="mx-3 text-center d-inline-block">
                                    <button type="submit" class="d-block border-0 btn-white rounded-md mb-4" style="border: 1px solid steelblue; border-radius: 0.5rem; padding: 0.7rem 1.4rem; font-size: larger; font-weight: 700; color: steelblue;">
                                        @lang('text.make_payment')
                                    </button>
                                    <span class="h4 fw-bolder">{{__('text.orange_money')}}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                
            </form>
            </div>
        @endif
    </div>
@endsection