<?php
return
    [
        "addSubscriptionPlan" => [
            "baseUri"   =>  'PLATFORM-ADDRESS',
            "subUri"    =>  'nzh/biz/addSubscriptionPlan/',
            "method"    =>  'GET'
        ],

        "subscriptionPlanList" => [
            "baseUri"   =>  'PLATFORM-ADDRESS',
            "subUri"    =>  'nzh/biz/subscriptionPlanList/',
            "method"    =>  'GET'
        ],

        "updateSubscriptionPlan" => [
            'baseUri'   => 'PLATFORM-ADDRESS',
            'subUri'    => 'nzh/biz/updateSubscriptionPlan/',
            'method'    => 'POST'
        ],

        "requestSubscription" =>  [
            "baseUri" =>  'PLATFORM-ADDRESS',
            "subUri" =>  'nzh/biz/requestSubscription',
            "method" =>  'POST'
        ],

        "confirmSubscription" =>  [
            "baseUri" =>  'PLATFORM-ADDRESS',
            "subUri" =>  'nzh/biz/confirmSubscription/',
            "method" =>  'POST'
        ],


        "subscriptionList" =>  [
            "baseUri" =>  'PLATFORM-ADDRESS',
            "subUri" =>  'nzh/biz/subscriptionList/',
            "method" =>  'GET'
        ],

        "consumeSubscription" =>  [
            "baseUri" =>  'PLATFORM-ADDRESS',
            "subUri" =>  'nzh/biz/consumeSubscription/',
            "method" =>  'POST'
        ]
    ];




