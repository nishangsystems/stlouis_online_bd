@extends('student.layout')
@section('section')
    <div class="py-4">
        @switch($step)
            @case(0)
                <form enctype="multipart/form-data" id="application_form" method="post" action="{{ route('student.application.start', [1, $application->id]) }}">
                    @csrf
                    <div class="px-5 py-5 border-top shadow bg-light">
                        <div class="row w-100">
                            <div class="col-sm-12 col-md-6">
                                <label class="text-capitalize"><span style="font-weight: 700;">{{ __('text.word_campus') }}</span></label>
                                <select name="campus_id" class="form-control text-primary"  oninput="setDegreeTypes(event)">
                                    <option>{{ __('text.select_campus') }}</option>
                                    @foreach ($campuses as $campus)
                                        <option value="{{ $campus->id }}" {{ $application->campus_id == $campus->id ? 'selected' : '' }}>{{ $campus->name }}</option>  
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-12 col-md-6">
                                <label class="text-capitalize"><span style="font-weight: 700;">{{ __('text.word_degree') }}</span></label>
                                <select name="degree_id" class="form-control text-primary"  id="degree_types">  
                                    @if($application->degree_id != null)
                                                                                    
                                    @endif                                  
                                </select>
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
                        <h4 class="py-3 border-bottom border-top bg-white text-primary my-4 text-uppercase col-sm-12 col-md-12 col-lg-12" style="font-weight:800;">{{ __('text.word_stage') }} 1: {{ __('text.personal_details_bilang') }} : <span class="text-danger">APPLYING FOR A(AN) {{ $degree->deg_name }} PROGRAM</span></h4>
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
                                <input type="tel" class="form-control text-primary"  name="phone" value="{{ auth('student')->user()->phone }}" readonly required>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-4">
                            <label class="text-secondary  text-capitalize">{{ __('text.word_email_bilang') }}</label>
                            <div class="">
                                <input type="email" class="form-control text-primary"  name="email" value="{{ auth('student')->user()->email }}" required readonly>
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
                        <div class="col-sm-12 col-md-12 col-lg-12 py-4 d-flex justify-content-center">
                            <a href="{{ route('student.application.start', [$step-1, $application->id]) }}" class="px-4 py-1 btn btn-lg btn-danger">{{ __('text.word_back') }}</a>
                            <input type="submit" class="px-4 py-1 btn btn-lg btn-primary" value="{{ __('text.save_and_continue') }}">
                        </div>
                    </div>
                </form>
                @break
        
            @case(2)
                <form enctype="multipart/form-data" id="application_form" method="post" action="{{ route('student.application.start', [ 3, $application->id]) }}">
                    @csrf
                    <div class="py-2 row bg-light border-top shadow">
                        <h4 class="py-3 border-bottom border-top bg-white text-primary my-4 text-uppercase col-sm-12 col-md-12 col-lg-12" style="font-weight:800;">{{ __('text.word_stage') }} 2: {{ __('text.course_envisaged_bilang') }} : <span class="text-danger">APPLYING FOR A(AN) {{ $degree->deg_name }} PROGRAM</span></h4>
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

                        <div class="col-sm-12 col-md-12 col-lg-12 py-4 d-flex justify-content-center">
                            <a href="{{ route('student.application.start', [$step-1, $application->id]) }}" class="px-4 py-1 btn btn-lg btn-danger">{{ __('text.word_back') }}</a>
                            <input type="submit" class="px-4 py-1 btn btn-lg btn-primary" value="{{ __('text.save_and_continue') }}">
                        </div>
                    </div>
                </form>
                @break

            @case(3)
                <form enctype="multipart/form-data" id="application_form" method="post" action="{{ route('student.application.start', [4, $application->id]) }}">
                    @csrf
                    <div class="py-2 row bg-light border-top shadow">
                        <h4 class="py-3 border-bottom border-top bg-white text-primary my-4 text-uppercase col-sm-12 col-md-12 col-lg-12" style="font-weight:800;">{{ __('text.word_stage') }} 3: {{ __('text.previous_higher_education_training_bilang') }} : <span class="text-danger">APPLYING FOR A(AN) {{ $degree->deg_name }} PROGRAM</span></h4>
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
                        <div class="col-sm-12 col-md-12 col-lg-12 py-4 d-flex justify-content-center">
                            <a href="{{ route('student.application.start', [$step-1, $application->id]) }}" class="px-4 py-1 btn btn-lg btn-danger">{{ __('text.word_back') }}</a>
                            <input type="submit" class="px-4 py-1 btn btn-lg btn-primary" value="{{ __('text.save_and_continue') }}">
                        </div>
                    </div>
                </form>
                @break
        
            @case(4)
                <form enctype="multipart/form-data" id="application_form" method="post" action="{{ route('student.application.start', [5, $application->id]) }}">
                    @csrf
                    <div class="py-2 row bg-light border-top shadow">
                        <h4 class="py-3 border-bottom border-top bg-white text-primary my-4 text-uppercase col-sm-12 col-md-12 col-lg-12" style="font-weight:800;">{{ __('text.word_stage') }} 4: {{ __('text.financial_obligation_bilang') }} : <span class="text-danger">APPLYING FOR A(AN) {{ $degree->deg_name }} PROGRAM</span></h4>
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
                            <a href="{{ route('student.application.start', [$step-1, $application->id]) }}" class="px-4 py-1 btn btn-lg btn-danger">{{ __('text.word_back') }}</a>
                            <input type="submit" class="px-4 py-1 btn btn-lg btn-primary" value="{{ __('text.save_and_continue') }}">
                        </div>
                    </div>
                </form>
                @break
        
            @case(5)
                <form enctype="multipart/form-data" id="application_form" method="post" action="{{ route('student.application.start', [6, $application->id]) }}">
                    @csrf
                    <div class="py-2 row text-capitalize bg-light">
                        <!-- hidden field for submiting application form -->
                        <h4 class="py-3 border-bottom border-top bg-white text-primary my-4 text-uppercase col-sm-12 col-md-12 col-lg-12" style="font-weight:800;">{{ __('text.word_stage') }} 5: {{ __('text.preview_and_submit_form_bilang') }} : <span class="text-danger">APPLYING FOR A(AN) {{ $degree->deg_name }} PROGRAM</span></h4>
                        
                        <!-- STAGE 1 PREVIEW -->
                        <h4 class="py-1 border-bottom border-top border-warning bg-white text-danger my-4 text-uppercase col-sm-12 col-md-12 col-lg-12" style="font-weight:500;">{{ __('text.word_stage') }} 1: <a href="{{ route('student.application.start', [1, $application->id]) }}" class="text-white btn py-1 px-2 btn-sm">{{ __('text.view_and_or_edit_stage') }} 1</a></h4>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-5">
                            <label class="text-secondary  text-capitalize">{{ __('text.word_name') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0 ">{{ $application->name ?? '' }}</label>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.word_gender_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0 ">{{ $application->gender ?? '' }}</select>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-4">
                            <label class="text-secondary  text-capitalize">{{ __('text.date_of_birth_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0 ">{{ $application->dob ?? '' }}</label>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.place_of_birth_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0 ">{{ $application->pob ?? '' }}</label>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.word_region_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0 ">{{ $application->nationality ?? '' }}</label>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.region_of_origin') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0 ">{{ $application->_region->region ?? '' }}</label>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.word_division_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0 "  name="division" required>{{ $application->_division->name ?? '' }}</label>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.word_residence_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0 ">{{ $application->residence ?? '' }}<label>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-5">
                            <label class="text-secondary  text-capitalize">{{ __('text.telephone_number_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0 ">{{ $application->phone ?? '' }}</label>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-4">
                            <label class="text-secondary  text-capitalize">{{ __('text.word_email_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0 ">{{ $application->email ?? '' }}</label>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-4">
                            <label class="text-secondary  text-capitalize">{{ __('text.where_did_you_hear_about_us') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0 ">{{ $application->referer ?? '' }}</label>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-4">
                            <label class="text-secondary  text-capitalize">{{ __('text.word_campus') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0 ">{{ $campus->name ?? '' }}</label>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-4">
                            <label class="text-secondary  text-capitalize">{{ __('text.entry_qualification') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0 ">{{ $cert->certi ?? '' }}</label>
                            </div>
                        </div>


                        <!-- STAGE 2 -->
                        <h4 class="py-1 border-bottom border-top border-warning bg-white text-danger my-4 text-uppercase col-sm-12 col-md-12 col-lg-12" style="font-weight:500;">{{ __('text.word_stage') }} 2: <a href="{{ route('student.application.start', [2, $application->id]) }}" class="text-white btn py-1 px-2 btn-sm">{{ __('text.view_and_or_edit_stage') }} 2</a></h4>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.first_choice_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0">{{ $program1->name ?? '' }}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <label class=" text-secondary text-capitalize">{{ __('text.second_choice_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0">{{ $program2->name ?? '' }}</label>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.word_level') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0">{{ $application->level ?? null }}</label>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.1st_language_spoken_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0">{{ $application->first_spoken_language ?? '' }}</label>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.1st_language_written_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0">{{ $application->first_written_language ?? '' }}</label>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.2nd_language_spoken_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0">{{ $application->second_spoken_language ?? '' }}</label>
                            </div>
                        </div>
                        <div class="py-2 col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.2nd_language_written_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0">{{ $application->second_written_language ?? '' }}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.any_known_health_problem_bilang') }}</label>
                            <div>
                                <label class="form-control text-primary border-0">{{ $application->has_health_problem ?? '' }}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary text-capitalize">{{ __('text.if_yes_mention_bilang') }}</label>
                            <div>
                                <label class="form-control text-primary border-0">{{ $application->health_problem ?? '' }}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.any_known_health_allergy_bilang') }}</label>
                            <div>
                                <label class="form-control text-primary border-0">{{ $application->has_health_allergy ?? '' }}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary text-capitalize">{{ __('text.if_yes_mention_bilang') }}</label>
                            <div>
                                <label class="form-control text-primary border-0">{{ $application->health_allergy ?? '' }}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.any_disabilities_bilang') }}</label>
                            <div>
                                <label class="form-control text-primary border-0">{{ $application->has_disability ?? '' }}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary text-capitalize">{{ __('text.if_yes_mention_bilang') }}</label>
                            <div>
                                <label class="form-control text-primary border-0">{{ $application->disability ?? '' }}</label>
                            </div>
                        </div>
                        
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary  text-capitalize">{{ __('text.awaiting_results_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0">{{ $application->awaiting_results ?? '' }}</label>
                            </div>
                        </div>

                        @if($degree->deg_name == 'MASTER DEGREE PROGRAMS')
                            <!-- STAGE 3 -->
                            <h4 class="py-1 border-bottom border-top border-warning bg-white text-danger my-4 text-uppercase col-sm-12 col-md-12 col-lg-12" style="font-weight:500;">{{ __('text.word_stage') }} 3: <a href="{{ route('student.application.start', [3, $application->id]) }}" class="text-white btn py-1 px-2 btn-sm">{{ __('text.view_and_or_edit_stage') }} 3</a></h4>
                            <h4 class="py-3 border-bottom border-top bg-white text-dark my-4 text-uppercase col-sm-12 col-md-12 col-lg-12" style="font-weight:500;"> {{ __('text.previous_higher_education_training_bilang') }}</h4>
                            <div class="col-sm-12 col-md-12 col-lg-12 py-2">
                                <table class="border">
                                    <thead>
                                        <tr class="text-capitalize">
                                            <th class="text-center border">{{ __('text.word_school_bilang') }}</th>
                                            <th class="text-center border">{{ __('text.word_year_bilang') }}</th>
                                            <th class="text-center border">{{ __('text.word_course_bilang') }}</th>
                                            <th class="text-center border">{{ __('text.word_certificate_bilang') }}</th>
                                        <tr>
                                    </thead>
                                    <tbody id="previous_trainings">
                                        @foreach (json_decode($application->previous_training)??[] as $key=>$training)
                                            <tr class="text-capitalize">
                                                <td class="border"><label class="form-control text-primary border-0">{{ $training->school ?? '' }}</label></td>
                                                <td class="border"><label class="form-control text-primary border-0">{{ $training->year ?? '' }}</label></td>
                                                <td class="border"><label class="form-control text-primary border-0">{{ $training->course ?? '' }}</label></td>
                                                <td class="border"><label class="form-control text-primary border-0">{{ $training->certificate ?? '' }}</label></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <h4 class="py-3 border-bottom border-top bg-white text-dark my-4 text-uppercase col-sm-12 col-md-12 col-lg-12" style="font-weight:500;"> {{ __('text.employment_history_bilang') }}</h4>
                            <div class="col-sm-12 col-md-12 col-lg-12 py-2">
                                <table class="border">
                                    <thead>
                                        <tr class="text-capitalize">
                                            <th class="text-center border">{{ __('text.employer_name_and_address_bilang') }}</th>
                                            <th class="text-center border">{{ __('text.post_held_bilang') }}</th>
                                            <th class="text-center border">{{ __('text.word_from_bilang') }}</th>
                                            <th class="text-center border">{{ __('text.word_to_bilang') }}</th>
                                            <th class="text-center border">{{ __('text.full_or_parttime_bilang') }}</th>
                                        <tr>
                                    </thead>
                                    <tbody id="employments">
                                        @foreach (json_decode($application->employments)??[] as $key=>$emp)
                                            <tr class="text-capitalize">
                                                <td class="border"><label class="form-control text-primary border-0">{{ $emp->employer ?? '' }}</label></td>
                                                <td class="border"><label class="form-control text-primary border-0">{{ $emp->post ?? '' }}</label></td>
                                                <td class="border"><label class="form-control text-primary border-0">{{ $emp->start ?? '' }}</label></td>
                                                <td class="border"><label class="form-control text-primary border-0">{{ $emp->end ?? '' }}</label></td>
                                                <td class="border"><label class="form-control text-primary border-0">{{ $emp->type ?? '' }}</label></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        <!-- STAGE 4 -->
                        <h4 class="py-1 border-bottom border-top border-warning bg-white text-danger my-4 text-uppercase col-sm-12 col-md-12 col-lg-12" style="font-weight:500;">{{ __('text.word_stage') }} 4: <a href="{{ route('student.application.start', [4, $application->id]) }}" class="text-white btn py-1 px-2 btn-sm">{{ __('text.view_and_or_edit_stage') }} 4</a></h4>
                        <div class="col-sm-12 col-md-8 col-lg-8">
                            <label class="text-secondary  text-capitalize">{{ __('text.who_is_responsible_for_your_fee_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0">{{ $application->fee_payer ?? '' }}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-4">
                            <label class="text-secondary text-capitalize">{{ __('text.word_name_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0">{{ $application->fee_payer_name ?? '' }}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3 col-lg-3">
                            <label class="text-secondary text-capitalize">{{ __('text.word_residence') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0">{{ $application->fee_payer_residence ?? '' }}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary text-capitalize">{{ __('text.word_tel') }} (<span class="text-dabger">{{ __('text.with_country_code') }}</span>)</label>
                            <div class="">
                                <label class="form-control text-primary border-0">{{ $application->fee_payer_tel ?? '' }}</label>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4 col-lg-3">
                            <label class="text-secondary text-capitalize">{{ __('text.word_occupation_bilang') }}</label>
                            <div class="">
                                <label class="form-control text-primary border-0">{{ $application->fee_payer_occupation ?? '' }}</label>
                            </div>
                        </div>

                        
                        <div class="col-sm-12 col-md-12 col-lg-12 py-4 mt-5 d-flex justify-content-center text-uppercase">
                            <a href="{{ route('student.application.start', [$step-1, $application->id]) }}" class="px-4 py-1 btn btn-lg btn-danger">{{ __('text.word_back') }}</a>
                            <a href="{{ route('student.home') }}" class="px-4 py-1 btn btn-lg btn-success">{{ __('text.pay_later') }}</a>
                            @if($application->fee_payer != null)<button type="submit" class="px-4 py-1 btn btn-lg btn-primary text-uppercase">{{ __('text.word_submit') }}</button>@endif
                        </div>
                    </div>
                </form>
                @break

            @case(6)
                <form enctype="multipart/form-data" id="application_form" method="post" action="{{ route('student.application.start', [7, $application->id]) }}">
                    @csrf
                    <div class="py-2 row bg-light border-top shadow">
                        
                        <div class="col-sm-12 col-md-12 col-lg-12 d-flex">
                            <div class="col-sm-10 col-md-8 col-lg-6 rounded bg-white py-5 my-3 shadow mx-auto">
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
                                    <a href="{{ route('student.application.start', [$step-1, $application->id]) }}" class="px-4 py-1 btn btn-xs btn-danger">{{ __('text.word_back') }}</a>
                                    <input type="submit" class="px-4 py-1 btn btn-xs btn-primary" value="{{ __('text.save_and_continue') }}">
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                </form>
                @break
        @endswitch
        {{-- <!-- <div class="py-2">
            <h4 class="text-dark my-4 text-uppercase">{{ __('text.admission_information') }}</h4>
            <div class=" py-2 text-dark" style="font-size: 1.5rem;">
                <div class="row"><b class="text-primary d-block py-2 col-sm-12">B. BONAMOUSSADI CAMPUS</b></div>
                <div class="row border-bottom"><span class="col-sm-12 col-md-4">BANK:</span> <b class="col-sm-12 col-md-8">AZIRE COOPERATIVE CREDIT UNION</b>.</div>
                <div class="row border-bottom"><span class="col-sm-12 col-md-4">ACCOUNT NAME/ NOM DE COMPTE:</span> <b class="col-sm-12 col-md-8">ST LOUIS HIGHER INSTITUTE OF AGRICULTURE</b></div>
                <div class="row border-bottom"><span class="col-sm-12 col-md-4">ACCOUNT NO/ DE COMPTE:</span> <b class="col-sm-12 col-md-8">PN08_1252</b></div>
                <div class="row"><b class="text-primary d-block py-2 col-sm-12">B. BONAMOUSSADI CAMPUS</b></div>
                <div class="row border-bottom"><span class="col-sm-12 col-md-4">BANK:</span> <b class="col-sm-12 col-md-8">OPUS SECURITATIS SOLIDARITY LIMITED (OPSEC) (CATHOLIC CREDIT UNION)</b></div>
                <div class="row border-bottom"><span class="col-sm-12 col-md-4">ACCOUNT NO:</span> <b class="col-sm-12 col-md-8">300737</b></div>
                <div class="row border-bottom"><span class="col-sm-12 col-md-4">ACCOUNT NAME:</span> <b class="col-sm-12 col-md-8">ST LOUIS HIGHER INSTITUTE OF MEDICAL STUDIES</b></div>
                <div class="border-bottom py-2">
                    <p class=" py-3">Request a receipt for every amount paid at the bank and present it in school alongside a photocopy for cross
                    checking. <b>Yourapplication shan only be processed upon payment of the Application fee</b>. Toujours demander
                    un reçu pour chaque montant paye a la banque et le presenter a l'ecoomat de l'institute avec deux (02) photocopies
                    pour verification. <b>Votre demande ne sera traitée qu'apres paiement (a la banque) des frais de dossier
                    Admission Criteria /Criteres</b></p>
                    <p class=" py-3">We admit science students With <b>2-25 points</b> in any of the departments of the St Louis of Medical Studies and the
                    Institute of Engineering and Technology. study. Art students are usually admitted With <b>4-25 points</b> and can onlv
                    study Nursing or Midwifery.</p>
                    <p class=" py-3">We shall exceptionally admit arts students With <b>2 points</b> especially earned in the social sciences like <b>Economics</b>
                    and <b>Geography</b>. This admission is <b>conditional and they must prove their worth</b> and stay along With the rest of
                    the class otherwise they will be discontinued at the end of the year.</p>
                    <p class=" py-3"><b>Les candidats avec un BACC scientifique peuvent être admis dans toutes les filières de l'Institut Médicales
                    et de Technologie. Nous admettons ceux qui ont le BACC GI, G3 et A, uniquement dans les programmes
                    suivants ; Soins Infirmier, Sage-Femme ou Agriculture. Cette admission est conditionnelle et ces candidats
                    devront prouver leur valeur en avançant avec le reste de la promotion sinon a la fin de l'année ils seront
                    conseiller de se retirer.</b></p>
                </div>
                <div class="border-bottom py-2">
                    <p class="py-2">ST LOUIS HIGHER INSTITUTE OF MEDICÄL STUDIES</p>
                    <p class="py-2">ST LOUIS HIGHER INSTITUTION OF ENGIENEERING AND TECHONOLOGY DOUALA - CAMEROON</p>
                </div>
            </div>
            <form action="{{ route('student.application.start', [$step+1, 'new']) }}" method="post">
                @csrf
                <div class="py-4 d-flex justify-content-end">
                    <button type="submit" class="px-4 py-1 rounded btn btn-sm btn-primary" >{{ __('text.new_application') }}</button>
                </div>
            </form>
            <table class="table border">
                <thead>
                    <tr class="text-capitalize">
                        <th>{{ __('text.word_name') }}</th>
                        <th>{{ __('text.created_at') }}</th>
                        <th>{{ __('text.updated_at') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (auth('student')->user()->currentApplicationForms as $application)
                        <tr class="border-bottom">
                            <td>{{ $application->surname }} {{ $application->first_name }}</td>
                            <td>{{ $application->created_at }}</td>
                            <td>{{ $application->updated_at }}</td>
                            <td><a href="{{ route('student.application.start', [$step+1, $application->id]) }}" class="px-4 py-1 rounded btn btn-sm btn-primary" >{{ __('text.word_proceed') }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div> --> --}}
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