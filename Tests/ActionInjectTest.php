<?php
/**
 * Created by PhpStorm.
 * User: kinddylee
 * Date: 2018/1/4
 * Time: 下午8:19
 */

namespace Tests;

use DataVerification\Validate;
use DataVerification\VerificationFactory;
use PHPUnit\Framework\TestCase;
use Tests\FakeAction\FakeAction;

class ActionInjectTest extends TestCase
{
    private $validate;

    function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->validate = new Validate();
    }

    /**
     * 测试注入已存在的验证名称
     * Author:kinddylee@gmail.com
     */
    public function testInjectExistAction()
    {
        $fakeActionInstance = new FakeAction();
        try{
            VerificationFactory::addVerificationAction('string', $fakeActionInstance);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'Exists Action must throw Exception');
        }
    }

    /**
     * 测试正确注入
     * Author:kinddylee@gmail.com
     */
    public function testInjectAction()
    {
        $fakeActionInstance = new FakeAction();
        VerificationFactory::addVerificationAction('fakeFilter', $fakeActionInstance);

        //todo 验证成功
        $api = ['name'=>'require|fakeFilter|equal|fake-data'];
        $data = ['name'=>'not-same-as-fake-data'];
        try{
            $this->validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'Exists Action must throw Exception');
        }

        //todo 验证失败
        $data = ['name'=>'fake-data'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');

        //todo 验证数组，自定义校验规则
        $api = [
            'name'=>'require|fakeFilter'
        ];

        $data = [
            'name'=>[
                'key1'=>'a',
                'key2'=>'b',
            ],
        ];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');
    }
}