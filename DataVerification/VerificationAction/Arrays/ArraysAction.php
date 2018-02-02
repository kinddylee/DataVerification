<?php
namespace DataVerification\VerificationAction\Arrays;

use DataVerification\VerificationAction\AbstractVerification;
use DataVerification\VerificationException\VerificationException;

class ArraysAction extends AbstractVerification
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

        if (!is_array($args[0]))
            throw new VerificationException("The key {$this->keyTip}: Input data `$args[0]` is not Array.");

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
     * @name Array 不为空
     * @param $value
     * @param string $parameter
     * @throws VerificationException
     * Author:kinddylee@gmail.com
     */
    protected function notEmpty($value, $parameter='')
    {
        if(empty($value))
            throw new VerificationException("The key {$this->keyTip}: Array Value is Empty.");
    }

    /**
     * @name Array数量等于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     */
    protected function countEqual($value, $parameter)
    {
        if (!is_numeric($parameter))
            throw new VerificationException("The key {$this->keyTip}: Action `countEqual` \$parameter need numeric,defined is `$parameter`");

        $arrayCount = count($value);
        if ( $arrayCount != intval($parameter))
            throw new VerificationException("The key {$this->keyTip}: Array count is $arrayCount,not equal `$parameter`");
    }

    /**
     * @name Array数量大于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     * Author:kinddylee@gmail.com
     */
    protected function countGreatThan($value, $parameter)
    {
        if (!is_numeric($parameter))
            throw new VerificationException("The key {$this->keyTip}: Action `countEqual` \$parameter need numeric,defined is `$parameter`");

        $arrayCount = count($value);
        if ( $arrayCount <= intval($parameter))
            throw new VerificationException("The key {$this->keyTip}: Array count is $arrayCount,not great than `$parameter`");
    }

    /**
     * @name Array数量小于
     * @param $value
     * @param $parameter
     * @throws VerificationException
     * Author:kinddylee@gmail.com
     */
    protected function countLessThan($value, $parameter)
    {
        if (!is_numeric($parameter))
            throw new VerificationException("The key {$this->keyTip}: Action `countEqual` \$parameter need numeric,defined is `$parameter`");

        $arrayCount = count($value);
        if ( $arrayCount >= intval($parameter))
            throw new VerificationException("The key {$this->keyTip}: Array count is $arrayCount,not less than `$parameter`");
    }
}