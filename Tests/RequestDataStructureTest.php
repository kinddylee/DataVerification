<?php

namespace Tests;

use DataVerification\Validate;
use PHPUnit\Framework\TestCase;

class RequestDataStructureTest extends TestCase
{
    /**
     * 测试数据结构结构缺少
     * Author:kinddylee@gmail.com
     */
    public function testRequestDataStructureMissing()
    {
        $validate = new Validate();

        $api = [
            'name'=>'require|string|same|username',
            'age'=>'require|int|equal|33',
            'family'=>[
                'father'=>'require|string|same|username',
                'mother'=>'require|string|same|username',
            ],
            'cards'=>['require|string|same|username'],
            ['require|string|same|username'],
            'someKey'=>[
                [
                    'bn'=>'require|string|same|username',
                    'bm'=>'require|string|same|username',
                ],
            ],
        ];

        //todo key: `age`  is missing
        $data = [
            'name'=>'username',
        ];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'structure key is missing');
        }

        //todo string:ture
        $data = [
            'name'=>'username',
            'age'=>33,
            'family'=>[
                'father'=>'username',
                'mother'=>'username',
            ],
            'cards'=>['username','username'],
            ['username', 'username'],
            'someKey'=>[
                [
                    'bn'=>'username',
                    'bm'=>'username',
                ],
                [
                    'bn'=>'username',
                    'bm'=>'username',
                ],
                [
                    'bn'=>'username',
                    'bm'=>'username',
                ],
            ],
        ];
        $result = $validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'structure:ok');

        $validate->export();
    }

    /**
     * 测试数据结构结构冗余
     * Author:kinddylee@gmail.com
     */
    public function testRequestDataStructureRedundancy()
    {
        $validate = new Validate();

        $api = [
            'name'=>'require|string|same|username',
            'age'=>'require|int|equal|33',


        ];

        //todo key: `fakeKey`  is Redundancy
        $data = [
            'name'=>'username',
            'age'=>'23',
            'fakeKey'=>'some-data',
            'family'=>[
                'father'=>'require|string|same|username',
                'mother'=>'require|string|same|username',
            ],
            'cards'=>['require|string|same|username'],
            ['require|string|same|username'],
            '33'=>'require|string|same|username',
        ];
        try{
            $validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'structure `age` key is Redundancy');
        }
    }
}
