<?php
/**
 * Created by PhpStorm.
 * User :  keshtgar
 * Date :  6/28/19
 * Time : 10:29 AM
 *
 * $baseInfo BaseInfo
 */
namespace Pod\Subscription\Service;

use Pod\Base\Service\BaseService;
use Pod\Base\Service\ApiRequestHandler;

class SubscriptionService extends BaseService
{
    private $header;
    private static $subscriptionApi;

    public function __construct($baseInfo)
    {
        parent::__construct();
        self::$jsonSchema = json_decode(file_get_contents(__DIR__. '/../jsonSchema.json'), true);
        $this->header = [
            '_token_issuer_'    =>  $baseInfo->getTokenIssuer(),
            '_token_'           => $baseInfo->getToken(),
        ];
        self::$subscriptionApi = require __DIR__ . '/../config/apiConfig.php';
    }

    public function addSubscriptionPlan($params) {
        $apiName = 'addSubscriptionPlan';
        array_walk_recursive($params, 'self::prepareData');

        $paramKey = self::$subscriptionApi[$apiName]['method'] == 'GET' ? 'query' : 'form_params';

        $relativeUri = self::$subscriptionApi[$apiName]['subUri'];

        $option = [
            'headers' => $this->header,
            $paramKey => $params, // set query param for validation
        ];

        self::validateOption($apiName, $option, $paramKey);
        // prepare params to send
        $withBracketParams = [];
        if (isset($params['permittedGuildCode'])) {
            $withBracketParams['permittedGuildCode'] = $params['permittedGuildCode'];
            unset($params['permittedGuildCode']);
        }
        if (isset($params['permittedBusinessId'])) {
            $withBracketParams['permittedBusinessId'] = $params['permittedBusinessId'];
            unset($params['permittedBusinessId']);
        }
        if (isset($params['permittedProductId'])) {
            $withBracketParams['permittedProductId'] = $params['permittedProductId'];
            unset($params['permittedProductId']);
        }
        $option['withBracketParams'] = $withBracketParams;
        $option['withoutBracketParams'] = $params;
        //  unset `query` key because query string will be build in ApiRequestHandler and will be added to uri so dont need send again in query params
        unset($option['query']);
        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$subscriptionApi[$apiName]['baseUri']],
            self::$subscriptionApi[$apiName]['method'],
            $relativeUri,
            $option,
            false,
            true
        );
    }

    public function subscriptionPlanList($params) {
        $apiName = 'subscriptionPlanList';
        $paramKey = self::$subscriptionApi[$apiName]['method'] == 'GET' ? 'query' : 'form_params';
        $relativeUri = self::$subscriptionApi[$apiName]['subUri'];

        array_walk_recursive($params, 'self::prepareData');

        $option = [
            'headers' => $this->header,
            $paramKey => $params,
        ];

        self::validateOption($apiName, $option, $paramKey);

        // prepare params to send
        $withBracketParams = [];
        if (isset($params['permittedGuildCode'])) {
            $withBracketParams['permittedGuildCode'] = $params['permittedGuildCode'];
            unset($params['permittedGuildCode']);
        }
        if (isset($params['permittedBusinessId'])) {
            $withBracketParams['permittedBusinessId'] = $params['permittedBusinessId'];
            unset($params['permittedBusinessId']);
        }
        if (isset($params['permittedProductId'])) {
            $withBracketParams['permittedProductId'] = $params['permittedProductId'];
            unset($params['permittedProductId']);
        }
        $option['withBracketParams'] = $withBracketParams;
        $option['withoutBracketParams'] = $params;
        //  unset `query` key because query string will be build in ApiRequestHandler and will be added to uri so dont need send again in query params
        unset($option['query']);
        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$subscriptionApi[$apiName]['baseUri']],
            self::$subscriptionApi[$apiName]['method'],
            $relativeUri,
            $option,
            false,
            true
        );
    }

    public function updateSubscriptionPlan($params) {
        $apiName = 'updateSubscriptionPlan';
        $paramKey = self::$subscriptionApi[$apiName]['method'] == 'GET' ? 'query' : 'form_params';
        $relativeUri = self::$subscriptionApi[$apiName]['subUri'];

        array_walk_recursive($params, 'self::prepareData');

        $option = [
            'headers' => $this->header,
            $paramKey => $params,
        ];

        self::validateOption($apiName, $option, $paramKey);
        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$subscriptionApi[$apiName]['baseUri']],
            self::$subscriptionApi[$apiName]['method'],
            $relativeUri,
            $option
        );

    }

    public function requestSubscription($params) {
        $apiName = 'requestSubscription';
        $paramKey = self::$subscriptionApi[$apiName]['method'] == 'GET' ? 'query' : 'form_params';
        $relativeUri = self::$subscriptionApi[$apiName]['subUri'];

        array_walk_recursive($params, 'self::prepareData');

        $option = [
            'headers' => $this->header,
            $paramKey => $params,
        ];

        self::validateOption($apiName, $option, $paramKey);
        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$subscriptionApi[$apiName]['baseUri']],
            self::$subscriptionApi[$apiName]['method'],
            $relativeUri,
            $option
        );
    }

    public function confirmSubscription($params) {
        $apiName = 'confirmSubscription';
        $paramKey = self::$subscriptionApi[$apiName]['method'] == 'GET' ? 'query' : 'form_params';
        $relativeUri = self::$subscriptionApi[$apiName]['subUri'];

        array_walk_recursive($params, 'self::prepareData');

        $option = [
            'headers' => $this->header,
            $paramKey => $params,
        ];

        self::validateOption($apiName, $option, $paramKey);
        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$subscriptionApi[$apiName]['baseUri']],
            self::$subscriptionApi[$apiName]['method'],
            $relativeUri,
            $option
        );
    }

    public function subscriptionList($params) {
        $apiName = 'subscriptionList';
        $paramKey = self::$subscriptionApi[$apiName]['method'] == 'GET' ? 'query' : 'form_params';
        $relativeUri = self::$subscriptionApi[$apiName]['subUri'];

        array_walk_recursive($params, 'self::prepareData');

        $option = [
            'headers' => $this->header,
            $paramKey => $params,
        ];

        self::validateOption($apiName, $option, $paramKey);
        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$subscriptionApi[$apiName]['baseUri']],
            self::$subscriptionApi[$apiName]['method'],
            $relativeUri,
            $option,
            false
        );
    }

    public function consumeSubscription($params) {
        $apiName = 'consumeSubscription';
        $paramKey = self::$subscriptionApi[$apiName]['method'] == 'GET' ? 'query' : 'form_params';
        $relativeUri = self::$subscriptionApi[$apiName]['subUri'];

        array_walk_recursive($params, 'self::prepareData');

        $option = [
            'headers' => $this->header,
            $paramKey => $params,
        ];

        self::validateOption($apiName, $option, $paramKey);
        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$subscriptionApi[$apiName]['baseUri']],
            self::$subscriptionApi[$apiName]['method'],
            $relativeUri,
            $option
        );
    }
}