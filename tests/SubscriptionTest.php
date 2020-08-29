<?php
/**
 * Created by PhpStorm.
 * User: keshtgar
 * Date: 11/11/19
 * Time: 9:49 AM
 */
use PHPUnit\Framework\TestCase;
use Pod\Subscription\Service\SubscriptionService;
use Pod\Base\Service\BaseInfo;
use Pod\Base\Service\Exception\ValidationException;
use Pod\Base\Service\Exception\PodException;

final class SubscriptionTest extends TestCase
{
//    public static $apiToken;
    public static $subscriptionService;
    const TOKEN_ISSUER = 1;
    const API_TOKEN = '{Put Api Token}';
    const API_TOKEN_12582 = '{Put Api Token2}';
    const ACCESS_TOKEN = '{Put Access Token}';
    const CLIENT_ID = '{Put Client Id}';
    const CLIENT_SECRET = '{Put Client Secret}';
    const CONFIRM_CODE = '{Put Confirm Code}';

    public function setUp(): void
    {
        parent::setUp();
        # set serverType to SandBox or Production
        BaseInfo::initServerType(BaseInfo::SANDBOX_SERVER);

        $baseInfo = new BaseInfo();
        $baseInfo->setTokenIssuer(self::TOKEN_ISSUER);
        $baseInfo->setToken(self::API_TOKEN);

        self::$subscriptionService = new SubscriptionService($baseInfo);
    }

    public function testAddSubscriptionPlanAllParameters()
    {
        $periodTypeCodeDaily = 'SUBSCRIPTION_PLAN_PERIOD_TYPE_DAILY';
        $blockType = 'SUBSCRIPTION_PLAN_TYPE_BLOCK';
        $planName = uniqid('plan');

        $params =
        [
            ## ============== Required Parameters  ====================
            'name'              => $planName,
            'price'             => 5000,
            'periodTypeCode'    => $periodTypeCodeDaily,   # بازه زمانی طرح - ماهانه روزانه یا سالانه
            'periodTypeCount'   => 1,# تعداد مورد نظر از بازه زمانی
            'type'              => $blockType, # نوع طرح که یا مسدودی و یا نقدی میباشد
            'guildCode'         => 'INFORMATION_TECHNOLOGY_GUILD',  # کد صنف مورد نظر
            'productEntityId'   => 41497, # کد محصول ثبت شده برای این طرح *توجه مقدار entityId باید فرستاده شود
            ## ============== Optional Parameters  ====================
            'usageCountLimit'   => 5,               # محدودیت میزان دفعات قابل استفاده
            'usageAmountLimit'  => 1000,               # محدودیت میزان مبلغ قابل استفاده
            'permittedGuildCode'    => ['TOURISM_GUILD','INFORMATION_TECHNOLOGY_GUILD'],            # لیست کد صنف های مجاز جهت استفاده
            'permittedBusinessId'   => [12121, 12582],         # شناسه کسب و کارهای مجاز جهت استفاده
            'permittedProductEntityId'  => [41503, 41504],           # لیست شناسه محصولات مجاز جهت استفاده
            'currencyCode'          => 'IRR',                           # IRR
            'scVoucherHash'         => ['{Put Service Call Voucher Hashes}'],
            'scApiKey'              => '{Put service call Api Key}',
        ];

        try {
            $result = self::$subscriptionService->addSubscriptionPlan($params);
            $this->assertFalse($result['hasError']);
            $this->assertEquals(2, count($result['result']['permittedBusinessList']));
            $this->assertEquals(2, count($result['result']['permittedProductList']));
            $this->assertEquals(2, count($result['result']['permittedGuildList']) );

        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testAddSubscriptionPlanRequiredParameters()
    {
        $periodTypeCodeMonthly = 'SUBSCRIPTION_PLAN_PERIOD_TYPE_MONTHLY';
        $periodTypeCodeYearly = 'SUBSCRIPTION_PLAN_PERIOD_TYPE_YEARLY';
        $blockType = 'SUBSCRIPTION_PLAN_TYPE_BLOCK';
        $cashType = 'SUBSCRIPTION_PLAN_TYPE_CASH';
        $planName = uniqid('plan');
        $params =
            [
                ## ============== Required Parameters  ====================
                'name'              => $planName,
                'price'             => 5000,
                'periodTypeCode'    => $periodTypeCodeMonthly,   # بازه زمانی طرح - ماهانه روزانه یا سالانه
                'periodTypeCount'   => 1,# تعداد مورد نظر از بازه زمانی 
                'type'              => $cashType, # نوع طرح که یا مسدودی و یا نقدی میباشد
                'guildCode'         => 'INFORMATION_TECHNOLOGY_GUILD',  # کد صنف مورد نظر
                'productEntityId'         => 41497, # کد محصول ثبت شده برای این طرح *توجه مقدار entityId باید فرستاده شود
            ];

        try {
            $result = self::$subscriptionService->addSubscriptionPlan($params);
            $this->assertFalse($result['hasError']);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testAddSubscriptionPlanValidationError()
    {
        $paramsWithoutRequired = [];
        $paramsWrongValue = [
            'type'              => 'wrongType', # نوع طرح که یا مسدودی و یا نقدی میباشد
            'periodTypeCode'    => 'wrongPeriodTypeCode'
        ];
        try {
            self::$subscriptionService->addSubscriptionPlan($paramsWithoutRequired);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $result = $e->getResult();

            $this->assertArrayHasKey('name', $validation);
            $this->assertEquals('The property name is required', $validation['name'][0]);

            $this->assertArrayHasKey('price', $validation);
            $this->assertEquals('The property price is required', $validation['price'][0]);

            $this->assertArrayHasKey('periodTypeCode', $validation);
            $this->assertEquals('The property periodTypeCode is required', $validation['periodTypeCode'][0]);

            $this->assertArrayHasKey('periodTypeCount', $validation);
            $this->assertEquals('The property periodTypeCount is required', $validation['periodTypeCount'][0]);

            $this->assertArrayHasKey('type', $validation);
            $this->assertEquals('The property type is required', $validation['type'][0]);

            $this->assertArrayHasKey('guildCode', $validation);
            $this->assertEquals('The property guildCode is required', $validation['guildCode'][0]);

            $this->assertArrayHasKey('productId', $validation);
            $this->assertEquals('The property productId is required', $validation['productId'][0]);

            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
        try {
            self::$subscriptionService->addSubscriptionPlan($paramsWrongValue);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $result = $e->getResult();

            $this->assertArrayHasKey('periodTypeCode', $validation);
            $this->assertEquals('Does not have a value in the enumeration ["SUBSCRIPTION_PLAN_PERIOD_TYPE_YEARLY","SUBSCRIPTION_PLAN_PERIOD_TYPE_MONTHLY","SUBSCRIPTION_PLAN_PERIOD_TYPE_DAILY"]', $validation['periodTypeCode'][1]);

            $this->assertArrayHasKey('type', $validation);
            $this->assertEquals('Does not have a value in the enumeration ["SUBSCRIPTION_PLAN_TYPE_BLOCK","SUBSCRIPTION_PLAN_TYPE_CASH"]', $validation['type'][1]);

            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }
    
    public function testUpdateSubscriptionPlanAllParameters()
    {
        $periodTypeCodeYearly = 'SUBSCRIPTION_PLAN_PERIOD_TYPE_YEARLY';
        $planName = uniqid('plan');

        $params =
        [
            ## ============== Required Parameters  ====================
            'id' => 2907,                            // شناسه طرح
            ## ============== Optional Parameters  ====================
            'periodTypeCode'=> $periodTypeCodeYearly,            //کد نوع بازه زمانی (روزانه، ماهانه، سالانه)
            'periodTypeCount'=> 10,        //تعداد مورد نظر از بازه زمانی
            'usageCountLimit'=> 2,       //محدودیت تعداد دفعات استفاده
            'usageAmountLimit'=> 1000,       //محدودیت میزان مبلغ قابل استفاده
            'name'=>  $planName,                  // نام طرح
            'price'=> 2000,                   // قیمت
            'enable'=> true,               //فعال/غیرفعال بودن طرح
            'scVoucherHash'     => ['{Put Service Call Voucher Hashes}'],
            'scApiKey'           => '{Put service call Api Key}',
        ];

        try {
            $result = self::$subscriptionService->updateSubscriptionPlan($params);
            $this->assertFalse($result['hasError']);

        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testUpdateSubscriptionPlanRequiredParameters()
    {
        $params =
            [
                ## ============== Required Parameters  ====================
                'id' => 2907,                            // شناسه طرح
            ];

        try {
            $result = self::$subscriptionService->updateSubscriptionPlan($params);
            $this->assertFalse($result['hasError']);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testUpdateSubscriptionPlanValidationError()
    {
        $params = [];
        try {
            self::$subscriptionService->updateSubscriptionPlan($params);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $result = $e->getResult();

            $this->assertArrayHasKey('id', $validation);
            $this->assertEquals('The property id is required', $validation['id'][0]);

            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testSubscriptionPlanListAllParameters()
    {
        $params =
            [
                ## ================ *Required Parameters  ==================
                'offset' => 0,                          # حد پایین خروجی
                'size' => 20,                             # اندازه خروجی
                ## ============== Optional Parameters  ====================
                'id' => 2907,                                  # شناسه طرح
                'periodTypeCode' =>'SUBSCRIPTION_PLAN_PERIOD_TYPE_DAILY',         # بازه زمانی طرح - ماهانه روزانه یا سالانه
                'fromPrice' =>3000,                                                 # حد پایین قیمت
                'toPrice' =>3000,                                                 # حد بالای قیمت
                'periodTypeCountFrom' => 1,                   # کف تعداد مورد نظر از بازه زمانی
                'periodTypeCountTo' => 100,                   # سقف تعداد مورد نظر از بازه زمانی
                'typeCode'=> 'SUBSCRIPTION_PLAN_TYPE_BLOCK',                     # نوع طرح که یا مسدودی و یا نقدی میباشد
                'enable'=> true,               # فعال یا غیر فعال بودن طرح
                'permittedGuildCode'=> ['INFORMATION_TECHNOLOGY_GUILD', 'TOILETRIES_GUILD'],            # لیست کد صنف های مجاز جهت استفاده
                'permittedBusinessId'=> [12121, 12582],         # شناسه کسب و کارهای مجاز جهت استفاده
                'permittedProductEntityId'=> [41503, 41504],           # لیست شناسه محصولات مجاز جهت استفاده
                'currencyCode' => 'IRR',                           # IRR
                'scVoucherHash'     => ['{Put Service Call Voucher Hashes}'],
                'scApiKey'           => '{Put service call Api Key}',

            ];
        try {
            $result = self::$subscriptionService->subscriptionPlanList($params);

            $this->assertFalse($result['hasError']);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testSubscriptionPlanListRequiredParameters()
    {
        $params = [
            ## ================= *Required Parameters  ===================
            'offset' => 0,                          # حد پایین خروجی
            'size' => 20,                             # اندازه خروجی
        ];
        try {
            $result = self::$subscriptionService->subscriptionPlanList($params);
            $this->assertFalse($result['hasError']);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testSubscriptionPlanListRequiredParametersValidation()
    {
        $params = [
            'offset' => -1,
            'size' => 0,
        ];
        try {
            self::$subscriptionService->subscriptionPlanList($params);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $result = $e->getResult();
            $this->assertArrayHasKey('offset', $validation);
            $this->assertEquals('Must have a minimum value of 0', $validation['offset'][0]);
            $this->assertArrayHasKey('size', $validation);
            $this->assertEquals('Must have a minimum value of 1', $validation['size'][0]);


            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }
    
//   public function testSubscriptionPlanListArrayParametersValidation()
//    {
//        $params = [
//            'offset'                    => 0,
//            'size'                      => 10,
//            'permittedGuildCode'        => ['INFORMATION_TECHNOLOGY_GUILD', 'TOILETRIES_GUILD'],            # لیست کد صنف های مجاز جهت استفاده
//            'permittedBusinessId'       => [12121, 12582],         # شناسه کسب و کارهای مجاز جهت استفاده
//            'permittedProductEntityId'  => [41503, 41504],           # لیست شناسه محصولات مجاز جهت استفاده
//        ];
//        try {
//            $result = self::$subscriptionService->subscriptionPlanList($params);
//            $this->assertFalse($result['hasError']);
////            $this->assertEquals($result['result'][], )
//            print_r($result);die;
//        } catch (ValidationException $e) {
//
//            $validation = $e->getErrorsAsArray();
//            $this->assertNotEmpty($validation);
//
//            $result = $e->getResult();
//            $this->assertArrayHasKey('offset', $validation);
//            $this->assertEquals('Must have a minimum value of 0', $validation['offset'][0]);
//            $this->assertArrayHasKey('size', $validation);
//            $this->assertEquals('Must have a minimum value of 1', $validation['size'][0]);
//
//
//            $this->assertEquals(887, $result['code']);
//        } catch (PodException $e) {
//            $error = $e->getResult();
//            $this->fail('PodException: ' . $error['message']);
//        }
//    }

    public function testRequestSubscriptionAllParameters()
    {
        $params = [
            ## ================ *Required Parameters  ==================
            'subscriptionPlanId' => 2907,                 #  شناسه طرح
            'userId'            => 12043,            # شناسه کاربر
            ## ================ Optional Parameters  ===================
            'scVoucherHash'     => ['{Put Service Call Voucher Hashes}'],
            'scApiKey'           => '{Put service call Api Key}',
        ];
        try {
            $result = self::$subscriptionService->requestSubscription($params);
            $this->assertFalse($result['hasError']);
//            print_r($result);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testRequestSubscriptionRequiredParameters()
    {
//        $this->markTestSkipped('dont need check');
        $params = [
            ## ================ *Required Parameters  ==================
            'subscriptionPlanId' => 2907,                 #  شناسه طرح
            'userId'            => 12043,            # شناسه کاربر
        ];
        try {
            $result = self::$subscriptionService->requestSubscription($params);
            $this->assertFalse($result['hasError']);
//            print_r($result);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testRequestSubscriptionRequiredValidationError()
    {
        $params = [];
        try {
            $result = self::$subscriptionService->requestSubscription($params);
            $this->assertFalse($result['hasError']);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $this->assertArrayHasKey('subscriptionPlanId', $validation);
            $this->assertEquals('The property subscriptionPlanId is required', $validation['subscriptionPlanId'][0]);

            $this->assertArrayHasKey('userId', $validation);
            $this->assertEquals('The property userId is required', $validation['userId'][0]);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testConfirmSubscriptionAllParameters()
    {
        $params = [
            ## ============== *Required Parameters  ==================
            'subscriptionId'        => 1576,  #  شناسه درخواست جهت تایید  # شناسه اشتراک
            'code'                  => '2001738',            # کد پیامک شده
            ## ============== Optional Parameters  ===================
            'scVoucherHash'         => ['{Put Service Call Voucher Hashes}'],
            'scApiKey'              => '{Put service call Api Key}',
        ];

        try {
            $result = self::$subscriptionService->confirmSubscription($params);
            $this->assertFalse($result['hasError']);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->assertEquals('Subscription با شناسه 225 وجود ندارد' , $error['message']);
        }
    }

    public function testConfirmSubscriptionRequiredParameters()
    {
        $params = [
            ## ============== *Required Parameters  ==================
            'subscriptionId'    => 225,  #  شناسه درخواست جهت تایید  # شناسه اشتراک
            'code'              => '4008239',            # کد پیامک شده
        ];

        try {
            $result = self::$subscriptionService->confirmSubscription($params);
            $this->assertFalse($result['hasError']);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->assertEquals('Subscription با شناسه 225 وجود ندارد' , $error['message']);
        }
    }

    public function testConfirmSubscriptionRequiredValidationError()
    {
        $params = [];
        try {
            $result = self::$subscriptionService->confirmSubscription($params);
            $this->assertFalse($result['hasError']);
        } catch (ValidationException $e) {

            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $this->assertArrayHasKey('subscriptionId', $validation);
            $this->assertEquals('The property subscriptionId is required', $validation['subscriptionId'][0]);

            $this->assertArrayHasKey('code', $validation);
            $this->assertEquals('The property code is required', $validation['code'][0]);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testSubscriptionListAllParameters()
    {
        $params = [
            ## ============== *Required Parameters  ==================
            'offset' => 0,
            'size' => 20,
            'subscriptionPlanId' => 2907,  # شناسه اشتراک
            ## ================ Optional Parameters  ===============
            'scVoucherHash'     => ['{Put Service Call Voucher Hashes}'],
            'scApiKey'           => '{Put service call Api Key}',
        ];

        try {
            $result = self::$subscriptionService->subscriptionList($params);
            $this->assertFalse($result['hasError']);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testSubscriptionListRequiredParameters()
    {
        $params = [
            ## ============== *Required Parameters  ==================
            'offset' => 0,
            'size' => 20,
            'subscriptionPlanId' => 2907,  # شناسه اشتراک
        ];
        try {
            $result = self::$subscriptionService->subscriptionList($params);
            $this->assertFalse($result['hasError']);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testSubscriptionListRequiredParametersValidation()
    {
        $params = [];
        try {
            $result = self::$subscriptionService->subscriptionList($params);
        } catch (ValidationException $e) {
            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $this->assertArrayHasKey('offset', $validation);
            $this->assertEquals('The property offset is required', $validation['offset'][0]);

            $this->assertArrayHasKey('size', $validation);
            $this->assertEquals('The property size is required', $validation['size'][0]);

            $this->assertArrayHasKey('subscriptionPlanId', $validation);
            $this->assertEquals('The property subscriptionPlanId is required', $validation['subscriptionPlanId'][0]);

            $result = $e->getResult();
            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testConsumeSubscriptionAllParameters()
    {
        $params = [
            ## ============== *Required Parameters  ==================
            'id'                => 1576,            # شناسه اشتراک
            ## ================ Optional Parameters  ===============
            'usedAmount'        => 1,     # میزان استفاده از اشتراک
            'scVoucherHash'     => ['{Put Service Call Voucher Hashes}'],
            'scApiKey'          => '{Put service call Api Key}',
        ];

        try {
            $result = self::$subscriptionService->consumeSubscription($params);
            $this->assertFalse($result['hasError']);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testConsumeSubscriptionRequiredParameters()
    {
        $params = [
            ## ============== *Required Parameters  ==================
            'id'            => 1576,            # شناسه اشتراک
        ];
        try {
            $result = self::$subscriptionService->consumeSubscription($params);
            $this->assertFalse($result['hasError']);
        } catch (ValidationException $e) {
            $this->fail('ValidationException: ' . $e->getErrorsAsString());
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }

    public function testConsumeSubscriptionRequiredParametersValidation()
    {
        $params = [];
        try {
            $result = self::$subscriptionService->consumeSubscription($params);
        } catch (ValidationException $e) {
            $validation = $e->getErrorsAsArray();
            $this->assertNotEmpty($validation);

            $this->assertArrayHasKey('id', $validation);
            $this->assertEquals('The property id is required', $validation['id'][0]);

            $result = $e->getResult();
            $this->assertEquals(887, $result['code']);
        } catch (PodException $e) {
            $error = $e->getResult();
            $this->fail('PodException: ' . $error['message']);
        }
    }


}