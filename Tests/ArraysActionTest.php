<?php

namespace Tests;

use DataVerification\Validate;
use PHPUnit\Framework\TestCase;

class ArraysActionTest extends TestCase
{
    /**
     * testArrayType
     * Author:kinddylee@gmail.com
     */
    public function testArrayType()
    {
        $validate = new Validate();
        
        $api = ['name'=>'require|arrays'];
        $data = ['name'=>'I am not Int'];

        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Arrays Type Assert.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'Arrays Type Assert.');
        }

        $data = ['name'=>[1,2,3,4]];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'Arrays Type Assert.');
    }

    /**
     * testArrayNotEmpty
     * Author:kinddylee@gmail.com
     */
    public function testArrayNotEmpty()
    {
        $validate = new Validate();

        $api = ['name'=>'require|arrays|notEmpty'];

        $data = ['name'=>[]];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Array not empty.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'Array not empty.');
        }
        $data = ['name'=>[1,2,3]];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'Array not empty.');
    }

    /**
     * testArrayCountEqual
     * Author:kinddylee@gmail.com
     */
    public function testArrayCountEqual()
    {
        $validate = new Validate();

        $api = ['name'=>'require|arrays|countEqual|5'];

        $data = ['name'=>[1,2,3,4]];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Array count equal.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'Array count equal.');
        }
        $data = ['name'=>[[],[],[],[],[]]];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'Array count equal.');
    }

    /**
     * testArrayCountGreatThan
     * Author:kinddylee@gmail.com
     */
    public function testArrayCountGreatThan()
    {
        $validate = new Validate();

        $api = ['name'=>'require|arrays|countGreatThan|2'];

        $data = ['name'=>[1,3]];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Array count great than.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'Array count great than.');
        }
        $data = ['name'=>[[],[],[],[],[]]];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'Array count great than.');
    }

    /**
     * testArrayCountLessThan
     * Author:kinddylee@gmail.com
     */
    public function testArrayCountLessThan()
    {
        $validate = new Validate();

        $api = ['name'=>'require|arrays|countLessThan|2'];

        $data = ['name'=>[1,3,4]];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Array count less than.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'Array count less than.');
        }
        $data = ['name'=>[[]]];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'Array count less than.');
    }
}
