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
    private static $jsonSchema;
    private static $subscriptionApi;
    private static $serviceProductId;
    private static $baseUri;

    public function __construct($baseInfo)
    {
        parent::__construct();
        self::$jsonSchema = json_decode(file_get_contents(__DIR__ . '/../config/validationSchema.json'), true);
        $this->header = [
            '_token_issuer_'    =>  $baseInfo->getTokenIssuer(),
            '_token_'           => $baseInfo->getToken(),
        ];
        self::$subscriptionApi = require __DIR__ . '/../config/apiConfig.php';
        self::$serviceProductId = require __DIR__ . '/../config/serviceProductId.php';
        self::$baseUri = self::$config[self::$serverType];
        self::$serviceProductId = self::$serviceProductId[self::$serverType];
    }

    public function addSubscriptionPlan($params) {
        $apiName = 'addSubscriptionPlan';
        array_walk_recursive($params, 'self::prepareData');

        $paramKey = self::$subscriptionApi[$apiName]['method'] == 'GET' ? 'query' : 'form_params';

        $relativeUri = self::$subscriptionApi[$apiName]['subUri'];

        if (isset($params['productEntityId'])) {
            $params['productId'] = $params['productEntityId'];
            unset($params['productEntityId']);
        }

        if (isset($params['permittedProductEntityId'])) {
            $params['permittedProductId'] = $params['permittedProductEntityId'];
            unset($params['permittedProductEntityId']);
        }

        $option = [
            'headers' => $this->header,
            $paramKey => $params, // set query param for validation
        ];

        self::validateOption($option, self::$jsonSchema[$apiName], $paramKey);
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

        # set service call product Id
        $params['scProductId'] = self::$serviceProductId[$apiName];
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
        if (isset($params['permittedProductEntityId'])) {
            $params['permittedProductId'] = $params['permittedProductEntityId'];
            unset($params['permittedProductEntityId']);
        }

        $option = [
            'headers' => $this->header,
            $paramKey => $params,
        ];

        self::validateOption($option, self::$jsonSchema[$apiName], $paramKey);

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

        # set service call product Id
        $params['scProductId'] = self::$serviceProductId[$apiName];
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
        $optionHasArray = false;
        $method = self::$subscriptionApi[$apiName]['method'];
        $paramKey = $method == 'GET' ? 'query' : 'form_params';
        $relativeUri = self::$subscriptionApi[$apiName]['subUri'];

        array_walk_recursive($params, 'self::prepareData');

        $option = [
            'headers' => $this->header,
            $paramKey => $params,
        ];

        self::validateOption($option, self::$jsonSchema[$apiName], $paramKey);

        # prepare params to send
        # set service call product Id
        $option[$paramKey]['scProductId'] = self::$serviceProductId[$apiName];

        if (isset($params['scVoucherHash'])) {
            $option['withoutBracketParams'] =  $option[$paramKey];
            unset($option[$paramKey]);
            $optionHasArray = true;
            $method = 'GET';
        }
        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$subscriptionApi[$apiName]['baseUri']],
            $method,
            $relativeUri,
            $option,
            false,
            $optionHasArray
        );

    }

    public function requestSubscription($params) {
        $apiName = 'requestSubscription';
        $optionHasArray = false;
        $method = self::$subscriptionApi[$apiName]['method'];
        $paramKey = $method == 'GET' ? 'query' : 'form_params';
        $relativeUri = self::$subscriptionApi[$apiName]['subUri'];

        array_walk_recursive($params, 'self::prepareData');

        $option = [
            'headers' => $this->header,
            $paramKey => $params,
        ];

        self::validateOption($option, self::$jsonSchema[$apiName], $paramKey);
        # prepare params to send
        # set service call product Id
        $option[$paramKey]['scProductId'] = self::$serviceProductId[$apiName];

        if (isset($params['scVoucherHash'])) {
            $option['withoutBracketParams'] =  $option[$paramKey];
            unset($option[$paramKey]);
            $optionHasArray = true;
            $method = 'GET';
        }
        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$subscriptionApi[$apiName]['baseUri']],
            $method,
            $relativeUri,
            $option,
            false,
            $optionHasArray
        );
    }

    public function confirmSubscription($params) {
        $apiName = 'confirmSubscription';
        $optionHasArray = false;
        $method = self::$subscriptionApi[$apiName]['method'];
        $paramKey = $method == 'GET' ? 'query' : 'form_params';
        $relativeUri = self::$subscriptionApi[$apiName]['subUri'];

        array_walk_recursive($params, 'self::prepareData');

        $option = [
            'headers' => $this->header,
            $paramKey => $params,
        ];

        self::validateOption($option, self::$jsonSchema[$apiName], $paramKey);
        # prepare params to send
        # set service call product Id
        $option[$paramKey]['scProductId'] = self::$serviceProductId[$apiName];

        if (isset($params['scVoucherHash'])) {
            $option['withoutBracketParams'] =  $option[$paramKey];
            unset($option[$paramKey]);
            $optionHasArray = true;
            $method = 'GET';
        }
        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$subscriptionApi[$apiName]['baseUri']],
            $method,
            $relativeUri,
            $option,
            false,
            $optionHasArray
        );
    }

    public function subscriptionList($params) {
        $apiName = 'subscriptionList';
        $optionHasArray = false;
        $method = self::$subscriptionApi[$apiName]['method'];
        $paramKey = $method == 'GET' ? 'query' : 'form_params';
        $relativeUri = self::$subscriptionApi[$apiName]['subUri'];

        array_walk_recursive($params, 'self::prepareData');

        $option = [
            'headers' => $this->header,
            $paramKey => $params,
        ];

        self::validateOption($option, self::$jsonSchema[$apiName], $paramKey);
        # prepare params to send
        # set service call product Id
        $option[$paramKey]['scProductId'] = self::$serviceProductId[$apiName];

        if (isset($params['scVoucherHash'])) {
            $option['withoutBracketParams'] =  $option[$paramKey];
            unset($option[$paramKey]);
            $optionHasArray = true;
            $method = 'GET';
        }
        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$subscriptionApi[$apiName]['baseUri']],
            $method,
            $relativeUri,
            $option,
            false,
            $optionHasArray
        );
    }

    public function consumeSubscription($params) {
        $apiName = 'consumeSubscription';
        $optionHasArray = false;
        $method = self::$subscriptionApi[$apiName]['method'];
        $paramKey = $method == 'GET' ? 'query' : 'form_params';
        $relativeUri = self::$subscriptionApi[$apiName]['subUri'];

        array_walk_recursive($params, 'self::prepareData');

        $option = [
            'headers' => $this->header,
            $paramKey => $params,
        ];

        self::validateOption($option, self::$jsonSchema[$apiName], $paramKey);
        # prepare params to send
        # set service call product Id
        $option[$paramKey]['scProductId'] = self::$serviceProductId[$apiName];

        if (isset($params['scVoucherHash'])) {
            $option['withoutBracketParams'] =  $option[$paramKey];
            unset($option[$paramKey]);
            $optionHasArray = true;
            $method = 'GET';
        }
        return ApiRequestHandler::Request(
            self::$config[self::$serverType][self::$subscriptionApi[$apiName]['baseUri']],
            $method,
            $relativeUri,
            $option,
            false,
            $optionHasArray
        );
    }
}