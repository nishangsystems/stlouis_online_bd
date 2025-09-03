@extends('admin.layout')
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
        let momoPreview = (event)=>{
            let file = event.target.files[0];
            if(file != null){
                let url = URL.createObjectURL(file);
                $('#momo_image_preview').attr('src', url);
            }
        }

        // Add and drop previous trainings form table rows
        let addTraining = ()=>{
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

        let dropTraining = (event) => {
            let training = $(event.target).parent().parent();
            // let training = $('#previous_trainings').children().last();
            if(training != null){
                training.remove();
            }
        }
        // Add and drop employment form table rows
        let addEmployment = () => {
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

        let dropEmployment = (event) => {
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

        /** let setDivisions = function(region){
            url = "{{ route('student.region.divisions', '__RID__') }}".replace('__RID__', region);
            $.ajax({
                method: 'get', url: url, 
                success: function(data){
                    console.log(data);
                    let html = `<option></option>`
                    data.forEach(element => {
                        html+=`<option value="${element.id}" ${'{{ $application->division}}' == element.id ? 'selected' : '' }>${element.name}</option>`.replace('region_id', element.id)
                    });
                    $('#divisions').html(html);
                }
            })
        } */

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


        /*let loadCplevels = function(event){
            campus_id = "{{ $application->campus_id }}";
            program_id = event.target.value;

            setLevels(program_id);
        }*/

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
        $(document).ready(function(){

            if("{{ $application->level }}" != null){
                setLevels("{{ $application->program_first_choice }}");
            }
        });

        {/* let campusDegreeCertPorgrams = function(event){
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
        } */}

        {/* let loadCplevels = function(event){
            campus_id = "{{ $application->campus_id }}";
            program_id = event.target.value;

            setLevels(program_id);
        } */}

        {/* let setLevels = function(program_id){

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
        } */}

    </script>
@endsection
@section('section')
    <div class="py-4">
        <form enctype="multipart/form-data" id="application_form" method="post">
            @csrf
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
                        @foreach($degrees as $degree)
                            <option value="{{ $degree->id }}" {{ old('degree_id', $application->degree_id) == $degree->id ? 'selected' : '' }} >{{ $degree->deg_name??'' }}</option>                           
                        @endforeach                                  
                    </select>
                </div>
            </div>
            <div class="py-2 row bg-light border-top shadow">
                <h4 class="py-3 border-bottom border-top bg-white text-primary my-4 text-uppercase col-sm-12 col-md-12 col-lg-12" style="font-weight:800;"> {{ __('text.personal_details_bilang') }} </h4>
                <div class="py-2 col-sm-6 col-md-4 col-lg-5">
                    <label class="text-secondary  text-capitalize">{{ __('text.word_name_bilang') }}</label>
                    <div class="">
                        <input type="text" class="form-control text-primary"  name="name" value="{{ $application->name??'' }}" readonly required>
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
                    <label class="text-secondary  text-capitalize">{{ __('text.word_country_bilang') }}</label>
                    <div class="">
                        <select class="form-control text-primary"  name="nationality" required>
                            <option></option>
                            @foreach(config('all_countries.list') as $key=>$value)
                                <option value="{{ $value['name'] }}" {{ $application->nationality== $value['name'] ? 'selected' : ($value['name'] == 'Cameroon' ? 'selected' : '') }}>{{ $value['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary  text-capitalize">{{ __('text.region_of_origin') }}</label>
                    <div class="">
                        <select class="form-control text-primary"  name="region" required oninput="loadDivisions(event)">
                            <option value=""></option>
                            @foreach(\App\Models\Region::all() as $value)
                                <option value="{{ $value->id }}" {{ $application->region == $value->id ? 'selected' : '' }}>{{ $value->region }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary  text-capitalize">{{ __('text.word_division') }}</label>
                    <div class="">
                        <select class="form-control text-primary"  name="division" required id="divisions">
                            <option value=""></option>
                            @foreach ($divisions as $division)
                                <option value="{{ $division->id }}" {{ $application->division == $division->id ? 'selected' : '' }}>{{ $division->name }}</option>
                            @endforeach
                        </select>
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
                        <input type="tel" class="form-control text-primary"  name="phone" value="{{ $application->phone??'' }}" readonly required>
                    </div>
                </div>
                <div class="py-2 col-sm-6 col-md-4 col-lg-4">
                    <label class="text-secondary  text-capitalize">{{ __('text.word_email_bilang') }}</label>
                    <div class="">
                        <input type="email" class="form-control text-primary"  name="email" value="{{ $application->email??'' }}" required readonly>
                    </div>
                </div>
                <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary  text-capitalize">{{ __('text.where_did_you_hear_about_us') }}</label>
                    <div class="">
                        <select class="form-control text-primary"  name="referer" required>
                            <option value=""></option>
                            <option value="OTHERS" {{ $application->referer== 'OTHERS' ? 'selected' : '' }}>OTHERS</option>
                            <option value="CHURCH" {{ $application->referer== 'CHURCH' ? 'selected' : '' }}>CHURCH</option>
                            <option value="CURRENT STUDENT OF THE SCHOOL" {{ $application->referer== 'CURRENT STUDENT OF THE SCHOOL' ? 'selected' : '' }}>CURRENT STUDENT OF THE SCHOOL</option>
                            <option value="FACEBOOK" {{ $application->referer== 'FACEBOOK' ? 'selected' : '' }}>FACEBOOK</option>
                            <option value="FLYERS" {{ $application->referer== 'FLYERS' ? 'selected' : '' }}>FLYERS</option>
                            <option value="FROM A FRIEND" {{ $application->referer== 'FROM A FRIEND' ? 'selected' : '' }}>FROM A FRIEND</option>
                            <option value="MOSQUE" {{ $application->referer== 'MOSQUE' ? 'selected' : '' }}>MOSQUE</option>
                            <option value="THE BRAINS" {{ $application->referer== 'THE BRAINS' ? 'selected' : '' }}>THE BRAINS</option>
                            <option value="THROUGH MIA" {{ $application->referer== 'THROUGH MIA' ? 'selected' : '' }}>THROUGH MIA</option>
                            <option value="TV" {{ $application->referer== 'TV' ? 'selected' : '' }}>TV</option>
                        </select>
                    </div>
                </div>
                <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary  text-capitalize">{{ __('text.last_attended_high_school') }}</label>
                    <div class="">
                        <input type="text" class="form-control text-primary"  name="high_school" value="{{ $application->high_school }}" required>
                    </div>
                </div>
                <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary  text-capitalize">{{ __('text.word_campus') }}</label>
                    <div class="">
                        <select class="form-control text-primary"  name="campus_id" required>
                            <option value=""></option>
                            @foreach($campuses as $campus)
                                <option value="{{ $campus->id }}" {{ $application->campus_id== $campus->id ? 'selected' : '' }}>{{ $campus->name }}</option>
                            @endforeach
                        </select>
                    </div>
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
            <div class="py-2 row bg-light border-top shadow">
                <h4 class="py-3 border-bottom border-top bg-white text-primary my-4 text-uppercase col-sm-12 col-md-12 col-lg-12" style="font-weight:800;"> {{ __('text.course_envisaged_bilang') }} </h4>
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
                            <option value=""></option>
                            @foreach ($levels as $level)
                                <option value="{{ $level->level }}" {{ $level->level == $application->level ? 'selected' : '' }}>{{ $level->level??'' }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary  text-capitalize">{{ __('text.1st_language_spoken_bilang') }}</label>
                    <div class="">
                        <select class="form-control text-primary"  name="first_spoken_language" required>
                            <option></option>
                            @foreach (config('languages') as $key => $lang)
                                <option value="{{ $lang }}" {{ $application->first_spoken_language == $lang ? 'selected' : '' }}>{{ $lang }}</option>   
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary  text-capitalize">{{ __('text.1st_language_written_bilang') }}</label>
                    <div class="">
                        <select class="form-control text-primary"  name="first_written_language" required>
                            <option></option>
                            @foreach (config('languages') as $key => $lang)
                                <option value="{{ $lang }}" {{ $application->first_spoken_language == $lang ? 'selected' : '' }}>{{ $lang }}</option>   
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary  text-capitalize">{{ __('text.2nd_language_spoken_bilang') }}</label>
                    <div class="">
                        <select class="form-control text-primary"  name="second_spoken_language">
                            <option></option>
                            @foreach (config('languages') as $key => $lang)
                                <option value="{{ $lang }}" {{ $application->first_spoken_language == $lang ? 'selected' : '' }}>{{ $lang }}</option>   
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary  text-capitalize">{{ __('text.2nd_language_written_bilang') }}</label>
                    <div class="">
                        <select class="form-control text-primary"  name="second_written_language">
                            <option></option>
                            @foreach (config('languages') as $key => $lang)
                                <option value="{{ $lang }}" {{ $application->first_spoken_language == $lang ? 'selected' : '' }}>{{ $lang }}</option>   
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary  text-capitalize">{{ __('text.any_known_health_problem_bilang') }}</label>
                    <div>
                        <select class="form-control" name="has_health_problem" required>
                            <option value="yes" {{ $application->has_health_problem == 'yes' ? 'selected' : '' }}>{{ __('text.word_yes') }}</option>
                            <option value="no" {{ $application->has_health_problem == 'no' ? 'selected' : '' }} selected>{{ __('text.word_no') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary text-capitalize">{{ __('text.if_yes_mention_bilang') }}</label>
                    <div>
                        <input type="text" class="form-control text-primary"  name="health_problem" value="{{ $application->health_problem }}">
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary  text-capitalize">{{ __('text.any_known_health_allergy_bilang') }}</label>
                    <div>
                        <select class="form-control" name="has_health_allergy" required>
                            <option value="yes" {{ $application->has_health_allergy == 'yes' ? 'selected' : '' }}>{{ __('text.word_yes') }}</option>
                            <option value="no" {{ $application->has_health_allergy == 'no' ? 'selected' : '' }} selected>{{ __('text.word_no') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary text-capitalize">{{ __('text.if_yes_mention_bilang') }}</label>
                    <div>
                        <input type="text" class="form-control text-primary"  name="health_allergy" value="{{ $application->health_allergy }}">
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary  text-capitalize">{{ __('text.any_disabilities_bilang') }}</label>
                    <div>
                        <select class="form-control" name="has_disability" required>
                            <option value="yes" {{ $application->has_disability == 'yes' ? 'selected' : '' }}>{{ __('text.word_yes') }}</option>
                            <option value="no" {{ $application->has_disability == 'no' ? 'selected' : ''}} selected>{{ __('text.word_no') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary text-capitalize">{{ __('text.if_yes_mention_bilang') }}</label>
                    <div>
                        <input type="text" class="form-control text-primary"  name="disability" value="{{ $application->disability }}">
                    </div>
                </div>
                
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary  text-capitalize">{{ __('text.awaiting_results_bilang') }}</label>
                    <div class="">
                        <select class="form-control text-primary"  name="awaiting_results" required>
                            <option selected></option>
                            <option value="yes" {{ $application->awaiting_results == 'yes' ? 'selected' : '' }}>{{ __('text.word_yes') }}</option>
                            <option value="no" {{ $application->awaiting_results == 'no' ? 'selected' : '' }}>{{ __('text.word_no') }}</option>
                        </select>
                    </div>
                </div>

            </div>
            <div class="py-2 row bg-light border-top shadow">
                <h4 class="py-3 border-bottom border-top bg-white text-primary my-4 text-uppercase col-sm-12 col-md-12 col-lg-12" style="font-weight:800;"> {{ __('text.previous_higher_education_training_bilang') }} </h4>
                <div class="col-sm-12 col-md-12 col-lg-12 py-2">
                    <table class="border">
                        <thead>
                            <tr class="text-capitalize">
                                <th class="text-center border-0" colspan="5">
                                    <div class="d-flex justify-content-end py-2 w-100">
                                        <span class="btn btn-sm px-4 py-1 btn-secondary rounded" onclick="addTraining()">{{ __('text.word_add') }}</span>
                                    </div>
                                </th>
                            </tr>
                            <tr class="text-capitalize">
                                <th class="text-center border">{{ __('text.word_school_bilang') }}</th>
                                <th class="text-center border">{{ __('text.word_year_bilang') }}</th>
                                <th class="text-center border">{{ __('text.word_course_bilang') }}</th>
                                <th class="text-center border">{{ __('text.word_certificate_bilang') }}</th>
                                <th class="text-center border"></th>
                            <tr>
                        </thead>
                        <tbody id="previous_trainings">
                            @foreach (json_decode($application->previous_training)??[] as $key=>$training)
                                <tr class="text-capitalize">
                                    <td class="border"><input class="form-control text-primary"  name="previous_training[school][$key]" required value="{{ $training->school }}"></td>
                                    <td class="border"><select class="form-control text-primary"  name="previous_training[year][$key]" required>
                                        <option value=""></option>
                                        @for($i = 1980; $i <= 2500; $i++)
                                            <option value="{{ $i }}" {{ $training->year == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select></td>
                                    <td class="border"><input class="form-control text-primary"  name="previous_training[course][$key]" required value="{{ $training->course }}"></td>
                                    <td class="border"><input class="form-control text-primary"  name="previous_training[certificate][$key]" required value="{{ $training->certificate }}"></td>
                                    <td class="border"><span class="btn btn-sm px-4 py-1 btn-danger rounded" onclick="dropTraining(event)">{{ __('text.word_drop') }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <h4 class="py-3 border-bottom border-top bg-white text-primary my-4 text-uppercase col-sm-12 col-md-12 col-lg-12" style="font-weight:800;">{{ __('text.employment_history_bilang') }}</h4>
                <div class="col-sm-12 col-md-12 col-lg-12 py-2">
                    <table class="border">
                        <thead>
                            <tr class="text-capitalize">
                                <th class="text-center border-0" colspan="6">
                                    <div class="d-flex justify-content-end py-2 w-100">
                                        <span class="btn btn-sm px-4 py-1 btn-secondary rounded" onclick="addEmployment()">{{ __('text.word_add') }}</span>
                                    </div>
                                </th>
                            </tr>
                            <tr class="text-capitalize">
                                <th class="text-center border">{{ __('text.employer_name_and_address_bilang') }}</th>
                                <th class="text-center border">{{ __('text.post_held_bilang') }}</th>
                                <th class="text-center border">{{ __('text.word_from_bilang') }}</th>
                                <th class="text-center border">{{ __('text.word_to_bilang') }}</th>
                                <th class="text-center border">{{ __('text.full_or_parttime_bilang') }}</th>
                                <th class="text-center border"></th>
                            <tr>
                        </thead>
                        <tbody id="employments">
                            @foreach (json_decode($application->employments)??[] as $key=>$emp)
                                <tr class="text-capitalize">
                                    <td class="border"><input class="form-control text-primary"  name="employments[employer][$key]" required value="{{ $emp->employer }}"></td>
                                    <td class="border"><input class="form-control text-primary"  name="employments[post][$key]" required value="{{ $emp->post }}"></td>
                                    <td class="border"><select class="form-control text-primary"  name="employments[start][$key]" required value="{{ $emp->start }}">
                                        <option value=""></option>
                                        @for($i = 1980; $i <= 2500; $i++)
                                            <option value="{{ $i }}" {{ $emp->start == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select></td>
                                    <td class="border"><select class="form-control text-primary"  name="employments[end][$key]">
                                        <option value=""></option>
                                        @for($i = 1980; $i <= 2500; $i++)
                                            <option value="{{ $i }}" {{ $emp->end == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select></td>
                                    <td class="border">
                                        <select class="form-control text-capitalize text-primary" name="employments[type][$key]" required>
                                            <option selected></option>
                                            <option value="full-time" {{ $emp->type =='full-time' ? 'selected' : '' }}>{{ __('text.full_time') }}</option>
                                            <option value="part-time" {{ $emp->type =='part-time' ? 'selected' : '' }}>{{ __('text.part_time') }}</option>
                                        </select>
                                    </td>
                                    <td class="border"><span class="btn btn-sm px-4 py-1 btn-danger rounded" onclick="dropEmployment(event)">{{ __('text.word_drop') }}</span></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="py-2 row bg-light border-top shadow">
                <h4 class="py-3 border-bottom border-top bg-white text-primary my-4 text-uppercase col-sm-12 col-md-12 col-lg-12" style="font-weight:800;"> {{ __('text.financial_obligation_bilang') }}</h4>
                <div class="col-sm-12 col-md-8 col-lg-8">
                    <label class="text-secondary  text-capitalize">{{ __('text.who_is_responsible_for_your_fee_bilang') }}</label>
                    <div class="">
                        <select class="form-control text-capitalize text-primary" name="fee_payer" required>
                            <option></option>
                            <option value="father" {{ $application->fee_payer =='father' ? 'selected' : '' }}>{{ __('text.word_father') }}</option>
                            <option value="mother" {{ $application->fee_payer =='mother' ? 'selected' : '' }}>{{ __('text.word_mother') }}</option>
                            <option value="ant" {{ $application->fee_payer =='ant' ? 'selected' : '' }}>{{ __('text.word_ant') }}</option>
                            <option value="uncle" {{ $application->fee_payer =='uncle' ? 'selected' : '' }}>{{ __('text.word_uncle') }}</option>
                            <option value="brother" {{ $application->fee_payer =='brother' ? 'selected' : '' }}>{{ __('text.word_brother') }}</option>
                            <option value="sister" {{ $application->fee_payer =='sister' ? 'selected' : '' }}>{{ __('text.word_sister') }}</option>
                            <option value="me" {{ $application->fee_payer =='me' ? 'selected' : '' }}>{{ __('text.word_me') }}</option>
                            <option value="other" {{ $application->fee_payer =='other' ? 'selected' : '' }}>{{ __('text.word_other') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-4">
                    <label class="text-secondary text-capitalize">{{ __('text.word_name_bilang') }}</label>
                    <div class="">
                        <input type="text" class="form-control text-primary"  name="fee_payer_name" value="{{ $application->fee_payer_name }}">
                    </div>
                </div>
                <div class="col-sm-6 col-md-3 col-lg-3">
                    <label class="text-secondary text-capitalize">{{ __('text.word_residence') }}</label>
                    <div class="">
                        <input type="text" class="form-control text-primary"  name="fee_payer_residence" value="{{ $application->fee_payer_residence }}">
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary text-capitalize">{{ __('text.word_tel') }} (<span class="text-danger">{{ __('text.with_country_code') }}</span>)</label>
                    <div class="">
                        <input type="tel" class="form-control text-primary"  name="fee_payer_tel" value="{{ $application->fee_payer_tel }}">
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <label class="text-secondary text-capitalize">{{ __('text.word_occupation_bilang') }}</label>
                    <div class="">
                        <input type="text" class="form-control text-primary"  name="fee_payer_occupation" value="{{ $application->fee_payer_occupation }}">
                    </div>
                </div>
                
                <div class="col-sm-12 col-md-12 col-lg-12 py-4 d-flex justify-content-center">
                    <a href="{{ url()->previous() }}" class="px-4 py-1 btn btn-lg btn-danger">{{ __('text.word_back') }}</a>
                    <input type="submit" class="px-4 py-1 btn btn-lg btn-primary" value="{{ __('text.word_update') }}">
                </div>
            </div>
        </form>
    </div>
@endsection
