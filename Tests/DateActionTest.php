<?php

namespace Tests;

use DataVerification\Validate;
use PHPUnit\Framework\TestCase;

class DateActionTest extends TestCase
{
    /**
     * testIntType
     * Author:kinddylee@gmail.com
     */
    public function testEnumType()
    {
        $validate = new Validate();
        
        $api = ['someDate'=>'require|date'];
        $data = ['someDate'=>'I am not date format'];

        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'int type filter error');
        }

        $data = ['someDate'=>'2018-13-44'];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'int type filter error');
        }

        $data = ['someDate'=>'2018-12-10 12:63:33'];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'int type filter error');
        }

        $data = ['someDate'=>'2018'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'date format ok.');

        $data = ['someDate'=>'2018-01-01'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'date format ok.');

        $data = ['someDate'=>'12:50'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'date format ok.');

        $data = ['someDate'=>'Wed, 25 Sep 2013 15:28:57 -0700'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'date format ok.');

        $data = ['someDate'=>'03.10.01'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'date format ok.');
    }

    /**
     * testEnumIn
     * Author:kinddylee@gmail.com
     */
    public function testFormat()
    {
        $validate = new Validate();

        $api = ['name'=>'require|date|format|Y-m-d'];

        $data = ['name'=>'2018-02-02 14'];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'boolean type');
        }

        $data = ['name'=>'2018-02-02'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'date format ok.');


        $api = ['name'=>'require|date|format|Y-m-d H:i:s'];
        $data = ['name'=>'2018-02-02 14:33'];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate format Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'date type');
        }

        $data = ['name'=>'2018-02-02 12:31:22'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'date format ok.');


        $api = ['name'=>'require|date|format|Y-m-d H:i:s.u'];
        $data = ['name'=>'2018-02-02 14:33'];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate format Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'date type');
        }

        $data = ['name'=>'2018-02-02 12:31:22.999'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'date format ok.');

        $data = ['name'=>'2018-02-02 12:31:22.000'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'date format ok.');
    }
}
