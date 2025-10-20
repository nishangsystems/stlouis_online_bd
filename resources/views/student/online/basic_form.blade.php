@extends('student.layout')
@section('section')
    <div class="py-4">
        @switch($step)
            @case(0)
                <form enctype="multipart/form-data" id="application_form" method="post" action="{{ route('student.application.start', [1, $application->id]) }}">
                    @csrf
                    <div class="px-5 py-5 border-top shadow bg-light">
                        <div class="row w-100">
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <label class="text-capitalize"><span style="font-weight: 700;">{{ __('text.program_enrolment_status') }}</span></label>
                                <select name="program_status" class="form-control text-primary" required>
                                    <option>{{ __('text.word_status') }}</option>
                                    @foreach ($status_set as $pset)
                                        <option value="{{ $pset['name'] }}" {{ $application->program_status == $pset['name'] ? 'selected' : '' }}>{{ $pset['name'] }}</option>  
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <label class="text-capitalize"><span style="font-weight: 700;">{{ __('text.word_campus') }}</span></label>
                                <select name="campus_id" class="form-control text-primary" required oninput="setDegreeTypes(event)">
                                    <option>{{ __('text.select_campus') }}</option>
                                    @foreach ($campuses as $campus)
                                        <option value="{{ $campus->id }}" {{ $application->campus_id == $campus->id ? 'selected' : '' }}>{{ $campus->name }}</option>  
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6 col-lg-4">
                                <label class="text-capitalize"><span style="font-weight: 700;">{{ __('text.applying_for_phrase') }}</span></label>
                                <select name="degree_id" class="form-control text-primary"  id="degree_types" required>  
                                    @if($application->degree_id != null)
                                                                                    
                                    @endif                                  
                                </select>
                            </div>
                            <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                                <label class="text-secondary  text-capitalize">{{ __('text.entry_qualification') }}</label>
                                <div class="">
                                    <select class="form-control text-primary"  name="entry_qualification" required>
                                        <option value=""></option>
                                        @foreach ($certs as $cert)
                                            <option value="{{ $cert->id }}" {{ $application->entry_qualification== $cert->id ? 'selected' : '' }}>{{ $cert->certi }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="pt-5 d-flex justify-content-center">
                            <button type="submit" class="px-5 py-1 btn btn-lg btn-primary" onclick="event.preventDefault(); confirm('Are you sure the selected degree type is OK?') ? ($('#application_form').submit()) : null">{{ __('text.new_application') }}</button>
                        </div>
                    </div>
                </form>
                @break
            @case('18')
                <form enctype="multipart/form-data" id="application_form" method="post" action="{{ route('student.application.start', [1, $application->id]) }}">
                    @csrf
                    <div class="px-5 py-5 border-top shadow bg-light" style="font-size: 2rem; font-weight: 700;">
                        <a class="text-uppercase d-block w-100 alert-primary text-center py-5 border">
                            Applying for {{ $application->type }} in {{ $application->campus }} campus
                        </a>
                        <div class="pt-5 d-flex justify-content-center text-uppercase">
                            <a href="" class="px-5 py-2 btn btn-lg btn-danger mx-3" >{{ __('text.word_back') }}</a>
                            <a href="" class="px-5 py-2 btn btn-lg btn-primary mx-3" onclick="confirm('Are you sure you are applying for  BACHELOR  Program?') ? (window.location=`{{ route('student.application.start', [1, $application->id]) }}`) : null">{{ __('text.word_continue') }}</a>
                        </div>
                    </div>
                </form>
                @break

            @case(1)
                <form enctype="multipart/form-data" id="application_form" method="post" action="{{ route('student.application.start', [2, $application->id]) }}">
                    @csrf
                    <div class="py-2 row bg-light border-top shadow">
                        <h4 class="py-3 border-bottom border-top bg-white text-primary my-4 text-uppercase col-sm-12 col-md-12 col-lg-12" style="font-weight:800;"> BASIC APPLICATION FORM <span class="text-danger">APPLYING FOR A(AN) {{ $degree->deg_name }} PROGRAM</span></h4>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.first_choice_bilang') }}</label>
                            <div class="">
                                <select class="form-control text-primary"  name="program_first_choice" required oninput="loadCplevels(event)">
                                    <option>{{ __('text.select_program') }}</option>
                                    @forelse ($programs as $program)
                                        <option value="{{ $program->id }}" {{ $application->program_first_choice == $program->id ? 'selected' : '' }}>{{ $program->name }}</option>
                                    @empty
                                        <option>{{ __('text.no_data_available') }}</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <label class=" text-secondary text-capitalize">{{ __('text.second_choice_bilang') }}</label>
                            <div class="">
                                <select class="form-control text-primary"  name="program_second_choice" required>
                                    <option>{{ __('text.select_program') }}</option>
                                    @forelse ($programs as $program)
                                        <option value="{{ $program->id }}" {{ $application->program_second_choice == $program->id ? 'selected' : '' }}>{{ $program->name }}</option>
                                    @empty
                                        <option>{{ __('text.no_data_available') }}</option>
                                    @endforelse
                                </select>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.word_level') }}</label>
                            <div class="">
                                <select class="form-control text-primary"  name="level" required id="cplevels">
                                    
                                </select>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-5">
                            <label class="text-secondary  text-capitalize">{{ __('text.word_name_bilang') }}</label>
                            <div class="">
                                <input type="text" class="form-control text-primary"  name="name" value="{{ auth('student')->user()->name }}" readonly required>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.word_gender_bilang') }}</label>
                            <div class="">
                                <select class="form-control text-primary"  name="gender" required>
                                    <option value="male" {{ $application->gender == 'male' ? 'selected' : '' }}>{{ __('text.word_male') }}</option>
                                    <option value="female" {{ $application->gender == 'female' ? 'selected' : '' }}>{{ __('text.word_female') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-4">
                            <label class="text-secondary  text-capitalize">{{ __('text.date_of_birth_bilang') }}</label>
                            <div class="">
                                <input type="date" class="form-control text-primary"  name="dob" value="{{ $application->dob }}" required>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.place_of_birth_bilang') }}</label>
                            <div class="">
                                <input type="text" class="form-control text-primary"  name="pob" value="{{ $application->pob }}" required>
                            </div>
                        </div>
                        
                        <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.word_residence_bilang') }}</label>
                            <div class="">
                                <input type="text" class="form-control text-primary"  name="residence" value="{{ $application->residence }}" required>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-5">
                            <label class="text-secondary  text-capitalize">{{ __('text.telephone_number_bilang') }}</label>
                            <div class="">
                                <input type="tel" class="form-control text-primary"  name="phone" value="{{ auth('student')->user()->phone }}" readonly required>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-4">
                            <label class="text-secondary  text-capitalize">{{ __('text.word_email_bilang') }}</label>
                            <div class="">
                                <input type="email" class="form-control text-primary"  name="email" value="{{ auth('student')->user()->email }}" required readonly>
                            </div>
                        </div>
                        
                        <div class="col-sm-12 col-md-12 col-lg-12 py-4 d-flex justify-content-center">
                            <a href="{{ route('student.application.start', [$step-1, $application->id]) }}" class="px-4 py-1 btn btn-lg btn-danger">{{ __('text.word_back') }}</a>
                            <input type="submit" class="px-4 py-1 btn btn-lg btn-primary" value="{{ __('text.save_and_continue') }}">
                        </div>
                    </div>
                </form>
                @break

            @case(6)
                <div class="py-2 row bg-light border-top shadow">
                    <div class="col-sm-12 col-md-12 col-lg-12 d-flex">
                        <div class="col-sm-10 col-md-8 col-lg-6 rounded bg-white py-5 my-3 shadow mx-auto">
                            <form enctype="multipart/form-data" id="application_form" method="post" action="{{ route('student.application.start', [7, $application->id]) }}">
                                @csrf
                                <input type="hidden" name="channel" value="bank">
                                <input type="hidden" name="amount" value="{{ $degree->amount }}">
                                <div class="py-5 d-flex justify-content-end">
                                    <input type="submit" class="px-4 py-1 btn btn-lg rounded btn-primary text-capitalize" value="{{ __('text.bank_payment') }}">
                                </div>
                            </form>
                            <form enctype="multipart/form-data" target="_blank" id="application_form" method="post" action="{{ route('student.application.start', [7, $application->id]) }}">
                                @csrf
                                <div class="py-4 text-info text-center ">You are about to make a payment of {{ $degree->amount }} CFA for application fee
                                </div>
                                <div class="py-3">
                                    <label class="text-secondary text-capitalize">{{ __('text.momo_number_used_in_payment') }} (<span class="text-danger">{{ __('text.without_country_code') }}</span>)</label>
                                    <div class="">
                                        <input type="tel" class="form-control text-primary"  name="momo_number" value="{{ $application->momo_number }}">
                                    </div>
                                </div>
                                <div class="py-3">
                                    <label class="text-secondary text-capitalize">{{ __('text.word_amount') }} </label>
                                    <div class="">
                                        <input readonly type="text" class="form-control text-primary"  name="amount" value="{{ $degree->amount }}">
                                    </div>
                                </div>
                                <div class="py-5 d-flex justify-content-center">
                                    <a href="{{ route('student.application.start', [$step-1, $application->id]) }}" class="px-4 py-1 btn btn-sm rounded-md btn-danger">{{ __('text.word_back') }}</a>
                                    <input type="submit" class="px-4 py-1 btn btn-sm rounded-md btn-primary" value="{{ __('text.save_and_continue') }}">
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @break
        @endswitch
    </div>
@endsection
@section('script')
    <script>

        $(document).ready(function(){
            if("{{ $application->degree_id }}" != null){
                loadCampusDegrees('{{ $application->campus_id }}');
            }
            if("{{ $application->division }}" != null){
                setDivisions('{{ $application->region }}');
            }
            if("{{ $application->level }}" != null){
                setLevels("{{ $application->program_first_choice }}");
            }
        });
        // momo preview generator
        let momoPreview = function(event){
            let file = event.target.files[0];
            if(file != null){
                let url = URL.createObjectURL(file);
                $('#momo_image_preview').attr('src', url);
            }
        }

        // Add and drop previous trainings form table rows
        let addTraining = function(){
            let key = `_key_${ Date.now() }_${ Math.random()*1000000 }`;
            let html = `<tr class="text-capitalize">
                            <td class="border"><input class="form-control text-primary"  name="previous_training[school][${key}]" required value="" placeholder="SCHOOL"></td>
                            <td class="border"><select class="form-control text-primary"  name="previous_training[year][${key}]" required>
                                                    <option></option>
                                                    @for($i = 1980; $i <= 2500; $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select></td>
                            <td class="border"><input class="form-control text-primary"  name="previous_training[course][${key}]" required value="" placeholder="COURSE"></td>
                            <td class="border"><input class="form-control text-primary"  name="previous_training[certificate][${key}]" required value="" placeholder="CERTIFICATE"></td>
                            <td class="border"><span class="btn btn-sm px-4 py-1 btn-danger rounded" onclick="dropTraining(event)">{{ __('text.word_drop') }}</span></td>
                        </tr>`;
            $('#previous_trainings').append(html);
        } 

        let dropTraining = function(event){
            let training = $(event.target).parent().parent();
            // let training = $('#previous_trainings').children().last();
            if(training != null){
                training.remove();
            }
        }
        // Add and drop employment form table rows
        let addEmployment = function(){
            let key = `_key_${ Date.now() }_${ Math.random()*1000000 }`;
            let html = `<tr class="text-capitalize">
                            <td class="border"><input class="form-control text-primary"  name="employments[employer][${key}]" required value="" placeholder="EMPLOYER"></td>
                            <td class="border"><input class="form-control text-primary"  name="employments[post][${key}]" required value="" placeholder="POST"></td>
                            <td class="border"><select class="form-control text-primary"  name="employments[start][${key}]">
                                                    <option></option>
                                                    @for($i = 1980; $i <= 2500; $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select></td>
                            <td class="border"><select class="form-control text-primary"  name="employments[end][${key}]">
                                                    <option></option>
                                                    @for($i = 1980; $i <= 2500; $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select></td>
                            <td class="border">
                                <select class="form-control text-capitalize" name="employments[type][${key}]" required>
                                    <option selected></option>
                                    <option value="full-time">{{ __('text.full_time') }}</option>
                                    <option value="full-time">{{ __('text.part_time') }}</option>
                                </select>
                            </td>
                            <td class="border"><span class="btn btn-sm px-4 py-1 btn-danger rounded" onclick="dropEmployment(event)">{{ __('text.word_drop') }}</span></td>
                        </tr>`;
            $('#employments').append(html);
        } 

        let dropEmployment = function(event){
            let training = $(event.target).parent().parent();
            // let training = $('#previous_trainings').children().last();
            if(training != null){
                training.remove();
            }
        }

        let completeForm = function(){
            let confirmed = confirm('By clicking this button, you are confirming that every information supplied is correct.');
            if(confirmed){
                $('#application_form').submit();
            }
        }

        let setDegreeTypes = function(event){
            let campus = event.target.value;
            loadCampusDegrees(campus);
        }

        let loadCampusDegrees = function(campus){
            url = `{{ route('student.campus.degrees', '__CID__') }}`.replace('__CID__', campus);
            $.ajax({
                method: 'get', url: url,
                success: function(data){
                    console.log(data);
                    let html = `<option></option>`;
                    data.forEach(element => {
                        html+=`<option value="${element.id}" ${ '{{ $application->degree_id }}' == element.id ? 'selected' : '' } >${element.deg_name}</option>`;
                    });
                    $('#degree_types').html(html);
                }
            })
        }

        let loadDivisions = function(event){
            let region = event.target.value;
            setDivisions(region);
        }

        let setDivisions = function(region){
            url = "{{ route('student.region.divisions', '__RID__') }}".replace('__RID__', region);
            $.ajax({
                method: 'get', url: url, 
                success: function(data){
                    let html = `<option></option>`
                    data.forEach(element => {
                        html+=`<option value="${element.id}" ${'{{ $application->division}}' == element.id ? 'selected' : '' }>${element.name}</option>`.replace('region_id', element.id)
                    });
                    $('#divisions').html(html);
                }
            })
        }

        let campusDegreeCertPorgrams = function(event){
            cert_id = event.target.value;
            campus_id = "{{ $application->campus_id }}";
            degree_id = "{{ $application->degree_id }}";

            url = "{{ route('student.campus.degree.cert.programs', ['__CmpID__', '__DegID__', '__CertID__']) }}".replace('__CmpID__', camus_id).replace('__DegID__').replace('__CertID__');
            $.ajax({
                method: 'get', url: url,
                success: function(data){
                    console.log(data);
                    let html = `<option></option>`;
                    data.forEach(element=>{
                        html += `<option value="${element.id}">${element.certi}</option>`;
                    })

                }
            })
        }

        let loadCplevels = function(event){
            campus_id = "{{ $application->campus_id }}";
            program_id = event.target.value;

            setLevels(program_id);
        }

        let setLevels = function(program_id){

            campus_id = "{{ $application->campus_id }}";

            url = "{{ route('student.campus.program.levels', ['__CmpID__', '__PrgID__']) }}".replace('__CmpID__', campus_id).replace('__PrgID__', program_id);
            $.ajax({
                method : 'get', url : url, 
                success : function(data){
                    console.log(data);
                    let html = `<option></option>`;
                    data.forEach(element=>{
                        html += `<option value="${element.level}" ${ "{{ $application->level }}" == element.level ? 'selected' : ''}>${element.level}</option>`;
                    });
                    $('#cplevels').html(html);
                }
            });
        }

    </script>
@endsection