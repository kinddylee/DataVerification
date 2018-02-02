<?php
namespace DataVerification\VerificationAction\Enum;

use DataVerification\VerificationAction\AbstractVerification;
use DataVerification\VerificationException\VerificationException;

class EnumAction extends AbstractVerification
{
    private $keyTip;

    /**
     * $args[0] 为外部数据的value
     * 将value强制转换为响应的类型
     * @param $args
     * @return mixed
     * Author:kinddylee@gmail.com
     */
    protected function typeConvert($args)
    {
        global $requestKeyNameErrorTip;
        $this->keyTip = $requestKeyNameErrorTip;

        if (is_null($args[1]))
        {
            global $requestKeyNameErrorTip;
            throw new VerificationException("The key `{$this->keyTip}`: Parameter not defined");
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
        if ($method == 'typeConvert')
            return ;
        return call_user_func_array([$this, $method], $args);
    }

    /**
     * @name 在枚举项中
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function in($value, $parameter)
    {
        $parameterArray = explode(',',$parameter);
        if(!in_array($value,$parameterArray))
        {
            global $requestKeyNameErrorTip;
            throw new VerificationException("The key `{$this->keyTip}`: $value` is not in `$parameter`");
        }
    }

    /**
     * @name 不在枚举项中
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function notIn($value, $parameter)
    {
        $parameterArray = explode(',',$parameter);
        if(in_array($value,$parameterArray))
        {
            global $requestKeyNameErrorTip;
            throw new VerificationException("The key `{$this->keyTip}`: `$value` is in `$parameter`");
        }
    }


}