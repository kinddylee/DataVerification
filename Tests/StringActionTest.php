<?php

namespace Tests;

use DataVerification\Validate;
use PHPUnit\Framework\TestCase;

class StringActionTest extends TestCase
{
    private $validate;

    function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);

        $this->validate = new Validate();
    }

    /**
     * 测试string类型
     * Author:kinddylee@gmail.com
     */
    public function testStringType()
    {
        $api = ['name'=>'require|string'];
        $data = ['name'=>8684];

        //不为字符串
        try{
            $this->validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'String为空出错');
        }

        //字符串
        $data = ['name'=>'some string charactor'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'value is string');
    }

    /**
     * 测试字符串不为空
     * Author:kinddylee@gmail.com
     */
    public function testStringNotEmpty()
    {
        $api = ['name'=>'require|string|notEmpty|dd'];
        $data = ['name'=>''];

        //为空
        try{
            $this->validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'String为空出错');
        }

        //不为空
        $data = ['name'=>'some chractors'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String不为空');
    }

    /**
     * 测试字符串bit长度
     * Author:kinddylee@gmail.com
     */
    public function testStringlengthOfBitEqual()
    {
        $api = ['name'=>'require|string|lengthOfBitEqual|2'];
        $data = ['name'=>'abc'];

        //校验失败
        try{
            $this->validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'String bit长度失败');
        }

        //校验成功
        $data = ['name'=>'ab'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String长度成功');

        //校验成功 中文
        $api = ['name'=>'require|string|lengthOfBitEqual|45'];
        $data = ['name'=>'abc我你他utf-8中文每个汉子占3字节'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String长度成功');
    }

    /**
     * testStringLengthOfBitGreatThan
     * Author:kinddylee@gmail.com
     */
    public function testStringLengthOfBitGreatThan()
    {
        $api = ['name'=>'require|string|lengthOfBitGreatThan|3'];
        $data = ['name'=>'st'];

        //校验失败
        try{
            $this->validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'String bit长度失败');
        }

        //校验成功
        $data = ['name'=>'abcde'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String长度成功');

        //校验成功 中文
        $api = ['name'=>'require|string|lengthOfBitGreatThan|10'];
        $data = ['name'=>'abc我你他utf-8中文每个汉子占3字节'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String长度成功');
    }

    /**
     * testStringLengthOfBitLessThan
     * Author:kinddylee@gmail.com
     */
    public function testStringLengthOfBitLessThan()
    {
        $api = ['name'=>'require|string|lengthOfBitLessThan|5'];
        $data = ['name'=>'abcde'];

        //校验失败
        try{
            $this->validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'String bit长度失败');
        }

        //校验成功
        $data = ['name'=>'abcd'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String长度成功');

        //校验成功 中文
        $api = ['name'=>'require|string|lengthOfBitLessThan|10'];
        $data = ['name'=>'中国ab'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String长度成功');
    }

    /**
     * testStringLengthOfBitBetween
     * Author:kinddylee@gmail.com
     */
    public function testStringLengthOfBitBetween()
    {
        $api = ['name'=>'require|string|lengthOfBitBetween|3-6'];
        $data = ['name'=>'ab'];

        //校验失败
        try{
            $this->validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'String bit长度失败');
        }

        //校验成功
        $data = ['name'=>'abcd'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String长度成功');

        //校验成功 中文
        $api = ['name'=>'require|string|lengthOfBitBetween|2-7'];
        $data = ['name'=>'中国'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String长度成功');
    }

    /**
     * 验证字符长度
     * Author:kinddylee@gmail.com
     */
    public function testStringLengthOfCharsEqual()
    {
        $api = ['name'=>'require|string|lengthOfCharsEqual|3'];
        $data = ['name'=>'ab汉字'];

        //校验失败
        try{
            $this->validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'String 长度失败');
        }

        //校验成功
        $data = ['name'=>'abc'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String长度成功');

        //校验成功 中文
        $api = ['name'=>'require|string|lengthOfCharsEqual|21'];
        $data = ['name'=>'abc我你他utf-8中文每个汉子占3字节'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String长度成功');
    }

    /**
     * testStringLengthOfCharsGreatThan
     * Author:kinddylee@gmail.com
     */
    public function testStringLengthOfCharsGreatThan()
    {
        $api = ['name'=>'require|string|lengthOfCharsGreatThan|3'];
        $data = ['name'=>'汉字'];

        //校验失败
        try{
            $this->validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'String 长度失败');
        }

        //校验成功
        $data = ['name'=>'中国人abc'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String长度成功');
    }

    /**
     * testStringLengthOfCharsLessThan
     * Author:kinddylee@gmail.com
     */
    public function testStringLengthOfCharsLessThan()
    {
        $api = ['name'=>'require|string|lengthOfCharsLessThan|5'];
        $data = ['name'=>'汉字个数为五'];

        //校验失败
        try{
            $this->validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'String 长度失败');
        }

        //校验成功
        $data = ['name'=>'中国人a'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String长度成功');
    }

    /**
     * testStringLengthOfCharsBetween
     * Author:kinddylee@gmail.com
     */
    public function testStringLengthOfCharsBetween()
    {
        $api = ['name'=>'require|string|lengthOfCharsBetween|3-5'];
        $data = ['name'=>'汉字个数为五'];

        //校验失败
        try{
            $this->validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'String 长度失败');
        }

        //校验成功
        $data = ['name'=>'中国人a'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String长度成功');
    }

    /**
     * 测试字符串相等
     * Author:kinddylee@gmail.com
     */
    public function testStringSame()
    {
        $api = ['name'=>'require|string|same|abcd'];
        $data = ['name'=>'abcde'];

        //测试不同
        try{
            $this->validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'String检测到不相同抛出异常');
        }

        //测试相同
        $data = ['name'=>'abcd'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String验证相同成功');
    }

    /**
     * testStringNotSame
     * Author:kinddylee@gmail.com
     */
    public function testStringNotSame()
    {
        $api = ['name'=>'require|string|notSame|abcd'];
        $data = ['name'=>'abcd'];

        //测试不同
        try{
            $this->validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'String检测到不相同抛出异常');
        }

        //测试相同
        $data = ['name'=>'abc'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String验证相同成功');
    }

    /**
     * testStringRegex
     * Author:kinddylee@gmail.com
     */
    public function testStringRegex()
    {
        $api = ['name'=>'require|string|regex|/^http:\/\/([\w.]+)\/([\w]+)\/([\w]+)\.html$/i'];
        $data = ['name'=>'ssss'];
        //测试不同
        try{
            $this->validate->setDataCriterion($api)->check($data);
            $this->assertTrue(false, 'Validate Failure.');
        }catch (\Exception $e)
        {
            $this->assertSame($e->getCode(), 70000000, 'String检测到不相同抛出异常');
        }

        //测试相同
        $data = ['name'=>'http://www.youku.com/show_page/id_ABCDEFG.html'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, 'String验证相同成功');

        //正则带特殊字符
        $api = ['startTime'=>'require|string|regex|/^[0-9]{4}-(0?[1-9]|1[012])-(0?[1-9]|[12][0-9]|3[01])\s(0?[0-9]|1[0-9]|2[0-3]):(0?[0-9]|[1234][0-9]|5[0-9]):(0?[0-9]|[1234][0-9]|5[0-9])$/'];
        $data = ['startTime'=>'2018-12-22 12:33:23'];
        $result = $this->validate->setDataCriterion($api)->check($data);
        $this->assertNull($result, '正则中包含特殊字符');

    }
}
