<?php

return [
    'campuses'=>'campuses',
    'campus_degrees'=>'campus/degrees', ///{campus_id}
    'campus_program_levels'=>'campus/program/levels',///{campus_id}/{program_id}
    'campus_degree_certificate_programs'=>'campus/degree/certificate/programs',///{campus_id}/{degree_id}/{certificate_id}
    'certificate_programs'=>'certificate/program', ///{certificate_id} : POST : data{'program_ids':ARRAY, 'certificate_id':INT}.
    'certificates'=>'certificates',
    'degrees'=>'degrees',
    'programs'=>'programs',
    'set_root'=>'_api/root/create',
];