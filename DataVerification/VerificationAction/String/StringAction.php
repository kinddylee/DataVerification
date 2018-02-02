<?php
/**
 * Created by PhpStorm.
 * User: kinddylee
 * Date: 2017/12/27
 * Time: 下午9:17
 */

namespace DataVerification\VerificationAction\String;

use DataVerification\VerificationAction\AbstractVerification;
use DataVerification\VerificationException\VerificationException;

class StringAction extends AbstractVerification
{

    private $keyTip;

    /**
     * $args[0] 为外部数据的value
     * 将value强制转换为响应的类型
     * @param $args
     * @return mixed
     * @throws VerificationException
     * Author:kinddylee@gmail.com
     */
    protected function typeConvert($args)
    {
        global $requestKeyNameErrorTip;
        $this->keyTip = $requestKeyNameErrorTip;

        if (!is_string($args[0]))
        {
            throw new VerificationException("The key `{$this->keyTip}`:value `$args[0]` format is not `String`. ");
        }
        $args[0] = strval($args[0]);
        return $args;
    }

    /**
     * 调用typeConvert()强制转换类型
     * 调用call_user_func_array()调用实际的验证方法
     *
     * @param $method
     * @param $args
     * @return mixed
     * Author:kinddylee@gmail.com
     */
    public function __call($method, $args) {
        $args = $this->typeConvert($args);
        //如果用户没有传指定校验方式，则只使用默认转换校验类型
        if ($method == 'typeConvert')
            return ;
        return call_user_func_array([$this, $method], $args);
    }

    /**
     * @name 字符串不为空
     * @param $value
     * @param string $parameter
     * @throws VerificationException
     */
    protected function notEmpty($value, $parameter='')
    {
        if($value === '')
        {
            throw new VerificationException("The key `{$this->keyTip}`: Value is Empty.");
        }
    }

    /**
     * @name 长度校验(bit)-等于
     * PHP内置的字符串长度函数strlen()无法正确处理
     * 中文字符串，它得到的只是字符串所占的字节数。对
     * 于GB2312的中文编码，strlen得到的值是汉字个数
     * 的2倍，而对于UTF-8编码的中文，就是3倍，在UTF-8
     * 编码下，一个汉字占3个字节
     *
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function lengthOfBitEqual($value, $parameter)
    {
        $intParameter = intval($parameter);
        $flag = $intParameter==$parameter;
        if($flag && $intParameter>0 )
        {
            $stringLenth = strlen($value);
            if($stringLenth != $intParameter)
            {
                throw new VerificationException("The key `{$this->keyTip}`:value `$value` strlen is $stringLenth not match defined $parameter");
            }
        }
    }

    /**
     * @name 字符串长度(bit)-大于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function lengthOfBitGreatThan($value, $parameter)
    {
        $intParameter = intval($parameter);
        $flag = $intParameter==$parameter;
        if($flag && $intParameter>0 )
        {
            $strlen = strlen($value);
            if($strlen <= $intParameter)
            {
                throw new VerificationException("The key `{$this->keyTip}`:value  `$value` strlen is $strlen, Not Great than $intParameter");
            }
        }
    }

    /**
     * @name 字符串长度(bit)-小于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function lengthOfBitLessThan($value, $parameter)
    {
        $intParameter = intval($parameter);
        $flag = $intParameter==$parameter;
        if($flag && $intParameter>0 )
        {
            $strlen = strlen($value);
            if($strlen >= $intParameter)
            {
                throw new VerificationException("The key `{$this->keyTip}`:value `$value` strlen is $strlen, Not Less than $intParameter");
            }
        }
    }

    /**
     * @name 长度校验(bit)-介于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function lengthOfBitBetween($value, $parameter)
    {
        $parameterTemp = explode("-",$parameter);
        $min = isset($parameterTemp[0]) ? intval(trim($parameterTemp[0])) : 0;
        $max = isset($parameterTemp[1]) ? intval(trim($parameterTemp[1])) : 0;

        global $requestKeyNameErrorTip;

        if ($min >= $max)
        {

            throw new VerificationException("The key `{$this->keyTip}`:Parameter:`$parameter` Format Error.");
        }
        $stringLength = strlen($value);
        if(($stringLength < $min) || ($stringLength > $max))
        {
            throw new VerificationException("The key `{$this->keyTip}`:value `$value`  String length is `$stringLength`,not between $parameter");
        }
    }


    /**
     * @name 长度校验(个数)-等于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function lengthOfCharsEqual($value, $parameter)
    {
        $intParameter = intval($parameter);
        $flag = $intParameter==$parameter;
        if($flag && $intParameter>0 )
        {
            $stringLenth = mb_strlen($value);
            if($stringLenth != $intParameter)
            {
                global $requestKeyNameErrorTip;
                throw new VerificationException("The key `{$this->keyTip}`:value  `$value` mb_strlen is $stringLenth not match defined $parameter");
            }
        }
    }

    /**
     * @name 长度校验(个数)-大于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function lengthOfCharsGreatThan($value, $parameter)
    {
        $intParameter = intval($parameter);
        $flag = $intParameter==$parameter;
        if($flag && $intParameter>0 )
        {
            $strlen = mb_strlen($value);
            if($strlen <= $intParameter)
            {
                throw new VerificationException("The key `{$this->keyTip}`:value  `$value` mb_strlen is $strlen, Not Great than $intParameter");
            }
        }
    }


    /**
     * @name 长度校验(个数)-小于等于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function lengthOfCharsLessThan($value, $parameter)
    {
        $intParameter = intval($parameter);
        $flag = $intParameter==$parameter;
        if($flag && $intParameter>0 )
        {
            $strlen = mb_strlen($value);
            if($strlen >= $intParameter)
            {
                throw new VerificationException("The key `{$this->keyTip}`:value  `$value` mb_strlen is $strlen, Not Less than $intParameter");
            }
        }
    }

    /**
     * @name 长度校验(个数)-介于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function lengthOfCharsBetween($value, $parameter)
    {
        $parameterTemp = explode("-",$parameter);
        $min = isset($parameterTemp[0]) ? intval(trim($parameterTemp[0])) : 0;
        $max = isset($parameterTemp[1]) ? intval(trim($parameterTemp[1])) : 0;

        if ($min >= $max)
            throw new VerificationException("The key `{$this->keyTip}`:Parameter:`$parameter` Format Error.");

        $stringLength = mb_strlen($value);
        if(($stringLength < $min) || ($stringLength > $max))
        {
            throw new VerificationException("The key `{$this->keyTip}`:value `$value`  MB_String length:`$stringLength`,not between $parameter");
        }
    }

    /**
     * @name 正则校验
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function regex($value,$parameter)
    {
        $flag = preg_match($parameter,$value);
        if(!$flag)
        {
            throw new VerificationException("The key `{$this->keyTip}`:value  $value format is error. ");
        }
    }

    /**
     * @name 值全等于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function same($value,$parameter)
    {
        if($value!==$parameter)
        {
            throw new VerificationException("The key `{$this->keyTip}`:value  `$value` is not same as $parameter");
        }
    }

    /**
     * @name 值不全等于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function notSame($value,$parameter)
    {
        if($value===$parameter)
        {
            throw new VerificationException("The key `{$this->keyTip}`:value  `$value` is same as $parameter");
        }
    }

    /**
     * @name 邮箱校验
     * @param $value
     * @param $parameter
     * @throws VerificationException
     * @codeCoverageIgnore
     */
    protected function email($value,$parameter)
    {

    }

}