<?php
/**
 * 测试扩展自定义校验类
 * 校验已存在的Action
 */

namespace Tests\FakeAction;


use DataVerification\VerificationAction\AbstractVerification;
use DataVerification\VerificationException\VerificationException;

class FakeAction extends AbstractVerification
{
    protected function typeConvert($args)
    {
        return $args;
    }

    public function __call($name, $arguments)
    {
        $arguments = $this->typeConvert($arguments);
        //如果用户没有传指定校验方式，则只使用默认转换校验类型
        if ($name == 'typeConvert')
            return ;
        return call_user_func_array([$this, $name], $arguments);
    }

    protected function equal($value, $parameter)
    {
        if ($value != $parameter)
            throw new VerificationException('Not equal');
    }

}