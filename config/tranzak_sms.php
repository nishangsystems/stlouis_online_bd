<?php

return [
    // API DETAILS
    
    // 'nishang_sms_app_id'=>'apgp0qkft9r0gj',
    // 'nishang_sms_api_key'=>'PROD_914BD53103B44527AB46A22B40C794BC',
    // 'nishang_sms_sender_id'=>'NISHANG',
    // 'errandia_sms_app_id'=>'apaft0xrqe9kbi',
    // 'errandia_sms_api_key'=>'PROD_ECA65323BB134EBF9A222A9775CAA63E',
    // 'errandia_sms_sender_id'=>'ERRANDIA',
    // 'biaka_sms_app_id'=>'apnxwqcrve5s7l',
    // 'biaka_sms_api_key'=>'PROD_4178E5BB64DF486FA778B5716C3E06AA',
    // 'sms_sender_id_alt'=>'VAMVAM',
    // 'biaka_sms_sender_id'=>'BUIB',


    // END POINTS
    'base'=>'https://dsapi.tranzak.me',
    'san_base'=>'https://sandbox.dsapi.tranzak.me',
    'token'=>'/auth/token',
    'account_details'=>'/xp021/v1/account/details?accountId=',
    'sub_accounts'=>'/mapi/xp021/v1/account/accounts',
    'send_sms'=>'/dn088/v1/sms/api/send-v2',
    'send_otp'=>'/dn088/v1/sms/api/send-otp',
    'otp_templates'=>[
        'en'=>'TEM_2604291513042LB',
        'fr'=>'TEM_2604291513341WR',
        'default'=>'TEM_2604291513042LB',
    ],



    // CREDENTIAL CACHE FEILD NAMES
    'sms_token'=>'TRANZAK_SMS_API_TOKEN',
    'sms_transaction'=>'TRANZAK_PLATFORM_TRANSACTION',
    'sms_data'=>'TRANZAK_SMS_DATA',
];