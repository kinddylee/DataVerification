<?php

namespace Tests;

use DataVerification\Validate;
use DataVerification\VerificationException\VerificationException;
use PHPUnit\Framework\TestCase;

class IntActionTest extends TestCase
{
    /**
     * testIntType
     * Author:kinddylee@gmail.com
     */
    public function testIntType()
    {
        $validate = new Validate();
        
        $api = ['name'=>'require|int'];
        $data = ['name'=>'I am not Int'];

        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'int type filter error');
        }

        $data = ['name'=>'a2234'];

        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'int type filter error');
        }

        $data = ['name'=>'23432ab'];

        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'int type filter error');
        }

        $data = ['name'=>'123'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');

        $data = ['name'=>123];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');
    }

    /**
     * 测试boolean类型
     * Author:kinddylee@gmail.com
     */
    public function testIntGreaterThan()
    {
        $validate = new Validate();

        $api = ['name'=>'require|int|greaterThan|668'];

        $data = ['name'=>'222'];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'boolean type');
        }

        $data = ['name'=>889];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');

        $data = ['name'=>'999'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');
    }

    /**
     * testIntLessThan
     * Author:kinddylee@gmail.com
     */
    public function testIntLessThan()
    {
        $validate = new Validate();

        $api = ['name'=>'require|int|lessThan|10'];
        $data = ['name'=>'22'];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'catch data error');
        }

        $data = ['name'=>3];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');

        $data = ['name'=>'2'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');
    }

    /**
     * testIntEqual
     * Author:kinddylee@gmail.com
     */
    public function testIntEqual()
    {
        $validate = new Validate();

        $api = ['name'=>'require|int|equal|6688'];
        $data = ['name'=>'44'];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'catch data error');
        }

        $data = ['name'=>6688];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');

        $data = ['name'=>'6688'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');
    }

    /**
     * testIntNotEqual
     * Author:kinddylee@gmail.com
     */
    public function testIntNotEqual()
    {
        $validate = new Validate();

        $api = ['name'=>'require|int|notEqual|25'];
        $data = ['name'=>'25'];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'catch data error');
        }

        $data = ['name'=>6688];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');

        $data = ['name'=>'6688'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');
    }

    /**
     * testIntGreaterThanOrEqual
     * Author:kinddylee@gmail.com
     */
    public function testIntGreaterThanOrEqual()
    {
        $validate = new Validate();

        $api = ['name'=>'require|int|greaterThanOrEqual|25'];
        $data = ['name'=>'24'];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'catch data error');
        }

        $data = ['name'=>25];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');

        $data = ['name'=>'33'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');
    }

    /**
     * testIntLessThanOrEqual
     * Author:kinddylee@gmail.com
     */
    public function testIntLessThanOrEqual()
    {
        $validate = new Validate();

        $api = ['name'=>'require|int|lessThanOrEqual|25'];
        $data = ['name'=>'26'];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'catch data error');
        }

        $data = ['name'=>25];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');

        $data = ['name'=>'15'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');
    }

    /**
     * testIntBetween
     * Author:kinddylee@gmail.com
     */
    public function testIntBetween()
    {
        $validate = new Validate();

        $api = ['name'=>'require|int|between|3-5'];
        $data = ['name'=>'26'];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'catch data error');
        }

        $data = ['name'=>3];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');

        $data = ['name'=>'4'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'boolean:true passed');
    }
}
