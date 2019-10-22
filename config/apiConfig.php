<?php
return
    [
        "addSubscriptionPlan" => [
            "baseUri"   =>  'PLATFORM-ADDRESS',
            "subUri" => 'nzh/doServiceCall',
            "method"    =>  'GET'
        ],

        "subscriptionPlanList" => [
            "baseUri"   =>  'PLATFORM-ADDRESS',
            "subUri"    =>  'nzh/doServiceCall',
            "method"    =>  'GET'
        ],

        "updateSubscriptionPlan" => [
            'baseUri'   => 'PLATFORM-ADDRESS',
            'subUri'    => 'nzh/doServiceCall',
            'method'    => 'POST'
        ],

        "requestSubscription" =>  [
            "baseUri" =>  'PLATFORM-ADDRESS',
            "subUri" =>  'nzh/doServiceCall',
            "method" =>  'POST'
        ],

        "confirmSubscription" =>  [
            "baseUri" =>  'PLATFORM-ADDRESS',
            "subUri" =>  'nzh/doServiceCall',
            "method" =>  'POST'
        ],


        "subscriptionList" =>  [
            "baseUri" =>  'PLATFORM-ADDRESS',
            "subUri" =>  'nzh/doServiceCall',
            "method" =>  'GET'
        ],

        "consumeSubscription" =>  [
            "baseUri" =>  'PLATFORM-ADDRESS',
            "subUri" =>  'nzh/doServiceCall',
            "method" =>  'POST'
        ]
    ];