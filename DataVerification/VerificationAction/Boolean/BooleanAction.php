<?php
namespace DataVerification\VerificationAction\Boolean;

use DataVerification\VerificationAction\AbstractVerification;
use DataVerification\VerificationException\VerificationException;

class BooleanAction extends AbstractVerification
{
    private $keyTip;

    /**
     * $args[0] 为外部数据的value
     * 将value强制转换为响应的类型
     * @param $args
     * @return mixed
     * @throws VerificationException
     */
    protected function typeConvert($args)
    {
        global $requestKeyNameErrorTip;
        $this->keyTip = $requestKeyNameErrorTip;

        //todo 强制转换外部数据
        if ($args[0] === 'true')
            $args[0] = true;

        if ($args[0] === 'false')
            $args[0] = false;

        if ($args[0] !== true && $args[0] !== false )
            throw new VerificationException("The key {$this->keyTip}: `$args[0]` format is not Boolean. ");

        //todo 强制转换校验参数
        if (!is_null($args[1]))
        {
            if (!in_array($args[1], ['true','false']))
                throw new VerificationException("The key {$this->keyTip}: The Parameter you defined:`$args[1]` is not `true` or `false`");

            if ($args[1] === 'true')
                $args[1] = true;

            if ($args[1] === 'false')
                $args[1] = false;
        }

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
            return;

        return call_user_func_array([$this, $method], $args);
    }

    /**
     * @name boolean等于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function equal($value, $parameter)
    {
        $valueLabel = $value ? 'true' : 'false';
        $parameterLabel = $parameter ? 'true' : 'false';

        if ($value !== $parameter)
            throw new VerificationException("The key {$this->keyTip}:value:`$valueLabel` not equal rule: $parameterLabel ");
    }
}