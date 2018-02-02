<?php

namespace Tests;

use DataVerification\Validate;
use PHPUnit\Framework\TestCase;

class EnumActionTest extends TestCase
{
    /**
     * testIntType
     * Author:kinddylee@gmail.com
     */
    public function testEnumType()
    {
        $validate = new Validate();
        
        $api = ['name'=>'require|enum'];
        $data = ['name'=>'I am not Int'];

        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'int type filter error');
        }
    }

    /**
     * testEnumIn
     * Author:kinddylee@gmail.com
     */
    public function testEnumIn()
    {
        $validate = new Validate();

        $api = ['name'=>'require|enum|in|kinddylee'];

        $data = ['name'=>'223'];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'boolean type');
        }

        $data = ['name'=>'kinddylee'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'int enum type');

        $api = ['name'=>'require|enum|in|1,2,3,b'];

        $data = ['name'=>'0'];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'boolean type');
        }

        $data = ['name'=>1];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'int enum type');

        $data = ['name'=>'2'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'string of int enum type');

        $data = ['name'=>'b'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'string enum type');
    }

    public function testEnumNotIn()
    {
        $validate = new Validate();

        $api = ['name'=>'require|enum|notIn|1,2,3,b'];

        $data = ['name'=>'3'];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'boolean type');
        }

        $data = ['name'=>33];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'int enum type');

        $data = ['name'=>'a'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'string of int enum type');

        $data = ['name'=>'ccc'];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'string enum type');
    }
}
