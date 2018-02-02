<?php
namespace DataVerification\VerificationAction\Int;

use DataVerification\VerificationAction\AbstractVerification;
use DataVerification\VerificationException\VerificationException;

class IntAction extends AbstractVerification
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

        if (is_bool($args[0]))
        {
            throw new VerificationException("The key {$this->keyTip}: value `$args[0]` is boolean, not Int");
        }

        $int = intval($args[0]);
        if(strval($int) !== strval($args[0]))
        {
            throw new VerificationException("The key {$this->keyTip}: value `$args[0]` is not Int");
        }

        $args[0] = $int;
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
        if ($method == 'typeConvert')
            return ;
        return call_user_func_array([$this, $method], $args);
    }


    /**
     * @name 大于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function greaterThan($value, $parameter)
    {
        $intParameter = !is_null($parameter) ? intval($parameter) : 0;
        if($value <= $intParameter)
        {
            throw new VerificationException("The key `{$this->keyTip}`: Value `$value` is not Great than $intParameter.");
        }
    }


    /**
     * @name 小于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function lessThan($value, $parameter)
    {
        $intParameter = !is_null($parameter) ? intval($parameter) : 0;
        if($value >= $intParameter)
        {
            throw new VerificationException("The key {$this->keyTip}`: value `$value` is not Less than $intParameter.");
        }
    }


    /**
     * @name 等于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function equal($value, $parameter)
    {
        $intParameter = !is_null($parameter) ? intval($parameter) : 0;
        if($value!=$intParameter)
        {
            throw new VerificationException("The key {$this->keyTip}`: value`$value` is not equal as $intParameter.");
        }
    }

    /**
     * @name 不等于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function notEqual($value, $parameter)
    {
        $intParameter = !is_null($parameter) ? intval($parameter) : 0;
        if($value==$intParameter)
        {
            throw new VerificationException("The key {$this->keyTip}`: value `$value` is equal as $intParameter.");
        }
    }

    /**
     * @name 大于等于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function greaterThanOrEqual($value, $parameter)
    {
        $intParameter = !is_null($parameter) ? intval($parameter) : 0;
        if($value<$intParameter)
        {
            throw new VerificationException("The key {$this->keyTip}`: value `$value` is not >= as $intParameter.");
        }
    }


    /**
     * @name 小于等于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function lessThanOrEqual($value, $parameter)
    {
        $intParameter = !is_null($parameter) ? intval($parameter) : 0;

        if($value>$intParameter)
        {
            throw new VerificationException("The key {$this->keyTip}`: value `$value` is not <= as $intParameter.");
        }
    }

    /**
     * @name 介于,大于等于a小于等于b
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function between($value, $parameter)
    {
        $parameterTemp = explode("-",$parameter);
        $min = isset($parameterTemp[0]) ? intval(trim($parameterTemp[0])) : 0;
        $max = isset($parameterTemp[1]) ? intval(trim($parameterTemp[1])) : 0;

        if ($min >= $max)
            throw new VerificationException("The key {$this->keyTip}`: Parameter:`$parameter` Format Error.");

        if($value<$min || $value>$max )
        {
            throw new VerificationException("The key {$this->keyTip}`: value `$value` not between $parameter.");
        }

    }


}