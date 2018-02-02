<?php

namespace Tests;

use DataVerification\Validate;
use DataVerification\VerificationException\VerificationException;
use PHPUnit\Framework\TestCase;

class BooleanActionTest extends TestCase
{
    /**
     * 测试字符串相等
     * Author:kinddylee@gmail.com
     */
    public function testBooleanEqual()
    {
        $validate = new Validate();
        
        $api = ['name'=>'require|boolean|equal|true'];

        //todo string:false
        $data = ['name'=>'false'];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'string:false 类型转换；比较');
        }

        //todo boolean:false
        $data = ['name'=>false];

        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'boolean:false 比较');
        }

        //todo string:somecode
        $data = ['name'=>233];

        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'boolean type err');
        }

        //todo string:ture
        $data = ['name'=>'true'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String:true passed');

        //todo string:ture
        $data = ['name'=>true];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');
    }

    /**
     * 测试boolean类型
     * Author:kinddylee@gmail.com
     */
    public function testBooleanType()
    {
        $validate = new Validate();
        
        //todo boolean type
        $api = ['name'=>'require|boolean'];

        //todo 输入字符串
        $data = ['name'=>'222'];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'boolean type');
        }

        //todo 输入int
        $data = ['name'=>1234567];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (VerificationException $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'boolean type');
            $this->assertStringStartsWith('文件', strval($e), 'First charts of $e');
        }

        //todo input boolean
        $data = ['name'=>false];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');

        //todo input string of boolean
        $data = ['name'=>'true'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');
    }

}
