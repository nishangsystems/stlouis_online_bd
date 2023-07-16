@extends('student.printable')
@section('section')
    <div class="py-1">
        
        <div class="py-2 mx-5">
            <h4 class="text-dark my-1 text-uppercase" style="font-weight: 700;"><span style="font-weight:700; font-size: 1.5rem; border-radius: 50%; background: black; color: white;" class="py-2 px-3 mr-5">1</span>{{ __('text.personal_details_bilang') }}</h4>
            <div class="row py-2">
                <span class="d-block mr-5 text-secondary col-md-4  text-capitalize">{{ __('text.word_gender_bilang') }}</span>
                <span class="d-block text-uppercase text-black col-md-8" style="font-weight: 600; font-style: italic;">{{ $application->gender }}</span>
            </div>
            <div class="row py-2">
                <span class="d-block mr-5 text-secondary col-md-4 text-capitalize">{{ __('text.word_surname_bilang') }}</span>
                <span class="d-block text-uppercase text-black col-md-8" style="font-weight: 600; font-style: italic;">{{ explode(" ", $application->name)[0] }}</span>
            </div>
            <div class="row py-2">
                <span class="d-block mr-5 text-secondary col-md-4 text-capitalize">{{ __('text.first_name_bilang') }}</span>
                <span class="d-block text-uppercase text-black col-md-8" style="font-weight: 600; font-style: italic;">{{ $application->name }}</span>
            </div>
            <div class="row py-2">
                <span class="d-block mr-5 text-secondary col-md-4 text-capitalize">{{ __('text.date_of_birth_bilang') }}</span>
                <span class="d-block text-uppercase text-black col-md-8" style="font-weight: 600; font-style: italic;">{{ $application->dob }}</span>
            </div>
            <div class="row py-2">
                <span class="d-block mr-5 text-secondary col-md-4 text-capitalize">{{ __('text.place_of_birth_bilang') }}</span>
                <span class="d-block text-uppercase text-black col-md-8" style="font-weight: 600; font-style: italic;">{{ $application->pob }}</span>
            </div>
            <div class="row py-2">
                <span class="d-block mr-5 text-secondary col-md-4 text-capitalize">{{ __('text.word_region_bilang') }}</span>
                <span class="d-block text-uppercase text-black col-md-8" style="font-weight: 600; font-style: italic;">{{ $application->_region->region }}</span>
            </div>
            <div class="row py-2">
                <span class="d-block mr-5 text-secondary col-md-4 text-capitalize">{{ __('text.word_division_bilang') }}</span>
                <span class="d-block text-uppercase text-black col-md-8" style="font-weight: 600; font-style: italic;">{{ $application->_division->name }}</span>
            </div>
            <div class="row py-2">
                <span class="d-block mr-5 text-secondary col-md-4 text-capitalize">{{ __('text.word_residence_bilang') }}</span>
                <span class="d-block text-uppercase text-black col-md-8" style="font-weight: 600; font-style: italic;">{{ $application->residence }}</span>
            </div>
            <div class="row py-2">
                <span class="d-block mr-5 text-secondary col-md-4 text-capitalize">{{ __('text.telephone_number_bilang') }}</span>
                <span class="d-block text-uppercase text-black col-md-8" style="font-weight: 600; font-style: italic;">{{ $application->phone }}</span>
            </div>
            <div class="row py-2">
                <span class="d-block mr-5 text-secondary col-md-4 text-capitalize">{{ __('text.word_email_bilang') }}</span>
                <span class="d-block text-uppercase text-black col-md-8" style="font-weight: 600; font-style: italic;">{{ $application->email }}</span>
            </div>
        </div>
                    
        <div class="py-2 mx-5">
            <h4 class="text-dark my-1 text-uppercase" style="font-weight: 700;"><span style="font-weight:700; font-size: 1.5rem; border-radius: 50%; background: black; color: white;" class="py-2 px-3 mr-5">2</span>{{ __('text.course_envisaged_bilang') }}</h4>
            <div class="row py-2">
                <span class="d-block mr-5 text-secondary  text-capitalize col-md-4">{{ __('text.first_choice_bilang') }}</span>
                <span class="d-block text-uppercase text-black col-md-8" style="font-weight: 600; font-style: italic;">{{ $program1->name }}</span>
            </div>
            <div class="row py-2">
                <span class="d-block mr-5 text-secondary  text-capitalize col-md-4">{{ __('text.second_choice_bilang') }}</span>
                <span class="d-block text-uppercase text-black col-md-8" style="font-weight: 600; font-style: italic;">{{ $program2->name }}</span>
            </div>
        </div>
                    
        <div class="py-2 mx-5">
            <h4 class="text-dark my-1 text-uppercase" style="font-weight: 700;"><span style="font-weight:700; font-size: 1.5rem; border-radius: 50%; background: black; color: white;" class="py-2 px-3 mr-5">3</span>{{ __('text.language_proficiency_bilang') }}</h4>
            <div class="row py-2">
                <table class="border border-dark">
                    <thead><tr>
                        <th colspan="2" class="text-center border border-dark">{{ __('text.1st_language_bilang') }}</th>
                        <th colspan="2" class="text-center border border-dark">{{ __('text.2nd_language_bilang') }}</th>
                    <tr></thead>
                    <tbody>
                        <tr class="text-capitalize">
                            <td class="border border-dark">{{ __('text.word_spoken_bilang') }}</td>
                            <td class="border border-dark">{{ $application->first_spoken_language }}</td>
                            <td class="border border-dark">{{ __('text.word_spoken_bilang') }}</td>
                            <td class="border border-dark">{{ $application->second_spoken_language }}</td>
                        </tr>
                        <tr class="text-capitalize">
                            <td class="border border-dark">{{ __('text.word_written_bilang') }}</td>
                            <td class="border border-dark">{{ $application->first_written_language }}</td>
                            <td class="border border-dark">{{ __('text.word_written_bilang') }}</td>
                            <td class="border border-dark">{{ $application->second_written_language }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
                    
        <div class="py-2 mx-5">
            <h4 class="text-dark my-1 text-uppercase" style="font-weight: 700;"><span style="font-weight:700; font-size: 1.5rem; border-radius: 50%; background: black; color: white;" class="py-2 px-3 mr-5">4</span>{{ __('text.medical_history_bilang') }}</h4>
            {{-- pair start --}}
            <div class="row py-2">
                <span class="text-secondary text-capitalize mr-5">{{ __('text.any_known_health_problem_bilang') }}</span>
                <span class="d-block text-black text-capitalize" style="font-weight:600; font-style: italic;">{{ $application->has_health_problem }}</span>
            </div>
            <div class="row py-2">
                <span class="text-secondary text-capitalize mr-5">{{ __('text.if_yes_mention_bilang') }}</span>
                <span class="d-block text-black text-capitalize" style="font-weight:600; font-style: italic;">{{ $application->health_problem }}</span>
            </div>
            {{-- pair end --}}
            {{-- pair start --}}
            <div class="row py-2">
                <span class="text-secondary  text-capitalize mr-5">{{ __('text.any_known_health_allergy_bilang') }}</span>
                <span class="d-block text-black text-capitalize" style="font-weight:600; font-style: italic;">{{ $application->has_health_allergy }}</span>
            </div>
            <div class="row py-2">
                <span class="text-secondary text-capitalize mr-5">{{ __('text.if_yes_mention_bilang') }}</span>
                <span class="d-block text-black text-capitalize" style="font-weight:600; font-style: italic;">{{ $application->health_allergy }}</span>
            </div>
            {{-- pair end --}}
            {{-- pair start --}}
            <div class="row py-2">
                <span class="text-secondary  text-capitalize mr-5">{{ __('text.any_disabilities_bilang') }}</span>
                <span class="d-block text-black text-capitalize" style="font-weight:600; font-style: italic;">{{ $application->has_disability }}</span>
            </div>
            <div class="row py-2">
                <span class="text-secondary text-capitalize mr-5">{{ __('text.if_yes_mention_bilang') }}</span>
                <span class="d-block text-black text-capitalize" style="font-weight:600; font-style: italic;">{{ $application->disability }}</span>
            </div>
            {{-- pair end --}}
            
        </div>
                    
        <div class="py-2 mx-5">
            <h4 class="text-dark my-1 text-uppercase" style="font-weight: 700;"><span style="font-weight:700; font-size: 1.5rem; border-radius: 50%; background: black; color: white;" class="py-2 px-3 mr-5">5</span>{{ __('text.entry_qualification_bilang') }}</h4>
            {{-- pair start --}}
            <div class="row py-2">
                <span class="text-secondary  text-capitalize mr-5">{{ __('text.awaiting_results_bilang') }}</span>
                <span class="d-block text-black text-capitalize" style="font-weight:600; font-style: italic;">{{ $application->awaiting_results }}</div>
            </div>
            {{-- pair end --}}
            
        </div>

        @if($degree->deg_name == 'MASTER DEGREE PROGRAMS')
            <div class="py-2 mx-5">
                <h4 class="text-dark my-1 text-uppercase" style="font-weight: 700;"><span style="font-weight:700; font-size: 1.5rem; border-radius: 50%; background: black; color: white;" class="py-2 px-3 mr-5">6</span>{{ __('text.previous_higher_education_training_bilang') }}</h4>
                <div class="row py-2">
                    <table class="border border-black text-black">
                        <thead>
                            <tr class="text-capitalize">
                                <th class="text-center border border-black" style="border: 1px solid black">{{ __('text.word_school_bilang') }}</th>
                                <th class="text-center border border-black" style="border: 1px solid black">{{ __('text.word_year_bilang') }}</th>
                                <th class="text-center border border-black" style="border: 1px solid black">{{ __('text.word_course_bilang') }}</th>
                                <th class="text-center border border-black" style="border: 1px solid black">{{ __('text.word_certificate_bilang') }}</th>
                            <tr>
                        </thead>
                        <tbody id="previous_trainings">
                            @foreach (json_decode($application->previous_training) as $key=>$training)
                                <tr class="text-capitalize" style="font-weight: 600; font-style: italic;">
                                    <td class="border border-black" style="border: 1px solid black">{{ $training->school }}</td>
                                    <td class="border border-black" style="border: 1px solid black">{{ $training->year }}</td>
                                    <td class="border border-black" style="border: 1px solid black">{{ $training->course }}</td>
                                    <td class="border border-black" style="border: 1px solid black">{{ $training->certificate }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
                        
            <div class="py-2 mx-5">
                <h4 class="text-dark my-1 text-uppercase" style="font-weight: 700;"><span style="font-weight:700; font-size: 1.5rem; border-radius: 50%; background: black; color: white;" class="py-2 px-3 mr-5">7</span>{{ __('text.employment_history_bilang') }}</h4>
                <div class="row py-2">
                    <table class="border">
                        <thead>
                            <tr class="text-capitalize">
                                <th class="text-center border" style="border: 1px solid black">{{ __('text.employer_name_and_address_bilang') }}</th>
                                <th class="text-center border" style="border: 1px solid black">{{ __('text.post_held_bilang') }}</th>
                                <th class="text-center border" style="border: 1px solid black">{{ __('text.word_from_bilang') }}</th>
                                <th class="text-center border" style="border: 1px solid black">{{ __('text.word_to_bilang') }}</th>
                                <th class="text-center border" style="border: 1px solid black">{{ __('text.full_or_parttime_bilang') }}</th>
                            <tr>
                        </thead>
                        <tbody id="employments">
                            @foreach (json_decode($application->employments) as $key=>$emp)
                                <tr class="text-capitalize">
                                    <td class="border" style="border: 1px solid black">{{ $emp->employer }}</td>
                                    <td class="border" style="border: 1px solid black">{{ $emp->post }}</td>
                                    <td class="border" style="border: 1px solid black">{{ $emp->start }}</td>
                                    <td class="border" style="border: 1px solid black">{{ $emp->end }}</td>
                                    <td class="border" style="border: 1px solid black">{{ $emp->type }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
            </div>      
        @endif       
                    
        <div class="py-2 mx-5">
            <h4 class="text-dark my-1 text-uppercase" style="font-weight: 700;"><span style="font-weight:700; font-size: 1.5rem; border-radius: 50%; background: black; color: white;" class="py-2 px-3 mr-5">8</span>{{ __('text.financial_obligation_bilang') }}</h4>
            {{-- pair start --}}
            <div class="row py-2">
                <span class="text-secondary  text-capitalize mr-5">{{ __('text.who_is_responsible_for_your_fee_bilang') }}</span>
                <span class="d-block text-black text-capitalize" style="font-weight:600; font-style: italic;">{{ $application->fee_payer }}</span>
            </div>
            <div class="row py-2">
                <span class="text-secondary text-capitalize mr-5">{{ __('text.word_name_bilang') }}</span>
                <span class="d-block text-black text-capitalize" style="font-weight:600; font-style: italic;">{{ $application->fee_payer_name }}</span>
            </div>
            <div class="row py-2">
                <span class="text-secondary text-capitalize mr-5">{{ __('text.word_residence') }}</span>
                <span class="d-block text-black text-capitalize" style="font-weight:600; font-style: italic;">{{ $application->fee_payer_residence }}</span>
            </div>
            <div class="row py-2">
                <span class="text-secondary text-capitalize mr-5">{{ __('text.word_tel') }}</span>
                <span class="d-block text-black text-capitalize" style="font-weight:600; font-style: italic;">{{ $application->fee_payer_tel }}</span>
            </div>
            <div class="row py-2 border-bottom">
                <span class="text-secondary text-capitalize mr-5">{{ __('text.word_occupation_bilang') }}</span>
                <span class="d-block text-black text-capitalize" style="font-weight:600; font-style: italic;">{{ $application->fee_payer_occupation }}</span>
            </div>
            {{-- pair end --}}
            
            
        </div>           
    
        <div class="py-2 mx-5">
            <h4 class="text-dark my-1 text-uppercase" style="font-weight: 700;"><span style="font-weight:700; font-size: 1.5rem; border-radius: 50%; background: black; color: white;" class="py-2 px-3 mr-5">9</span>{{ __('text.declaration_by_candidate_bilang') }}</h4>
            {{-- pair start --}}
            <div class="py-2" style="font-size: large;">
                <p class="py-2">{!! __('text.student_application_confirmation', ['name'=>$application->first_name .' '. $application->surname, 'school'=>'ST LOUIS UNIVERSITY INSTITUTE, DOUALA.']) !!}</p>
                <p class="py-2">{!! __('text.student_application_confirmation_french', ['name'=>$application->first_name .' '. $application->surname, 'school'=>'L’INSTITUT UNIERSITAIRE ST LOUIS, DOUALA.']) !!}</p>
            </div>
            {{-- pair end --}}
            
        </div>

        <div class="py-2 mx-5">
            <h4 class="text-dark my-1 text-uppercase" style="font-weight: 700;"><span style="font-weight:700; font-size: 1.5rem; border-radius: 50%; background: black; color: white;" class="py-2 px-3 mr-5">10</span>{{ __('text.declaration_by_parent_or_guardian_bilang') }}</h4>
            {{--  pair start --}}
            <div class="py-2" style="font-size: large;">
                <p class="py-2">{!! __('text.parent_application_confirmation', ['name'=>$application->fee_payer_name]) !!}</p>
                <p class="py-2">{!! __('text.parent_application_confirmation_french', ['name'=>$application->fee_payer_name]) !!}</p>
            </div>
            {{-- pair end --}}

            <div class="py-4 px-5 d-flex justify-content-between">
                <span class="d-block text-center text-capitalize">_________________<br>{{ __('text.word_signature') }}</span>
                <span class="d-block text-center text-capitalize">{{ date('l d-m-Y', time()) }}<br>{{ __('text.word_date') }}</span>
            </div>
            
        </div>

        <div class="py-2 mx-5">
            {{-- <h4 class="text-dark my-4 text-uppercase" style="font-weight: 700;">{{ __('text.admission_information') }}</h4> --}}
            <div class=" py-2 text-dark" style="font-size: 1.5rem;">
                <div class="row"><b class="text-primary d-block py-2 col-sm-12 text-uppercase">{{ $campus->name }}</b></div>
                @foreach ($application->campus_banks()->get() as $bank)
                    <div class="d-flex flex-wrap justify-content-between border-bottom"><span class="flex-1 w-50">FACULTY:</span> <b class="col-sm-12 col-md-8 text-uppercase">{{ $bank->faculty }}</b>.</div>
                    <div class="d-flex flex-wrap justify-content-between border-bottom"><span class="flex-1 w-50">BANK:</span> <b class="col-sm-12 col-md-8 text-uppercase">{{ $bank->bank_name }}</b>.</div>
                    <div class="d-flex flex-wrap justify-content-between border-bottom"><span class="flex-1 w-50">ACCOUNT NAME/ NOM DE COMPTE:</span> <b class="col-sm-12 col-md-8 text-uppercase">{{ $bank->account_name }}</b></div>
                    <div class="d-flex flex-wrap justify-content-between border-bottom"><span class="flex-1 w-50">ACCOUNT NO/ DE COMPTE:</span> <b class="col-sm-12 col-md-8 text-uppercase">{{ $bank->account_number }}</b></div>
                    <hr>
                @endforeach

                <div class="border-bottom py-2">
                    <p class=" py-1">Request a receipt for every amount paid at the bank and present it in school alongside a photocopy for cross
                    checking. <b>Yourapplication shan only be processed upon payment of the Application fee</b>. Toujours demander
                    un reçu pour chaque montant paye a la banque et le presenter a l'ecoomat de l'institute avec deux (02) photocopies
                    pour verification. <b>Votre demande ne sera traitée qu'apres paiement (a la banque) des frais de dossier
                    Admission Criteria /Criteres</b></p>
                    <p class=" py-1">We admit science students With <b>2-25 points</b> in any of the departments of the St Louis of Medical Studies and the
                    Institute of Engineering and Technology. study. Art students are usually admitted With <b>4-25 points</b> and can onlv
                    study Nursing or Midwifery.</p>
                    <p class=" py-1">We shall exceptionally admit arts students With <b>2 points</b> especially earned in the social sciences like <b>Economics</b>
                    and <b>Geography</b>. This admission is <b>conditional and they must prove their worth</b> and stay along With the rest of
                    the class otherwise they will be discontinued at the end of the year.</p>
                    <p class=" py-1"><b>Les candidats avec un BACC scientifique peuvent être admis dans toutes les filières de l'Institut Médicales
                    et de Technologie. Nous admettons ceux qui ont le BACC GI, G3 et A, uniquement dans les programmes
                    suivants ; Soins Infirmier, Sage-Femme ou Agriculture. Cette admission est conditionnelle et ces candidats
                    devront prouver leur valeur en avançant avec le reste de la promotion sinon a la fin de l'année ils seront
                    conseiller de se retirer.</b></p>
                </div>
            </div>
        </div>        
    </div>
@endsection