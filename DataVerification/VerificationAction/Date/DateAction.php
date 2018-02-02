<?php
namespace DataVerification\VerificationAction\Date;

use DataVerification\VerificationAction\AbstractVerification;
use DataVerification\VerificationException\VerificationException;
use SebastianBergmann\CodeCoverage\Report\PHP;

class DateAction extends AbstractVerification
{
    private $keyTip;

    /**
     * typeConvert
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

        if (($timestamp = strtotime($args[0])) === false) {
            throw new VerificationException("The key `{$this->keyTip}`: value `{$args[0]}` date format error.");
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
    protected function format($value, $parameter)
    {
        if ($parameter == '')
            throw new VerificationException("The key `{$this->keyTip}`: parameter is empty.");

        //todo 通过quest的date实例化date对象
        $dateObj = new \DateTime($value);

        /**
         * PHP 毫秒`u`默认为6位，为避免此问题
         * 1-将$parameter中的`u`替换为`X`占位符
         * 2-将用户时间通过format($parameter)转换
         * 3-获取用户时间的毫秒,只取前三位
         * 4-将占位符`X`替换为3位的毫秒
         */
        $defindedDate = $dateObj->format(str_replace('u', 'X', $parameter));
        $microSec = substr($dateObj->format('u'), 0, 3);
        $expectDate = str_replace('X', $microSec, $defindedDate);

        if($expectDate != $value)
        {
            throw new VerificationException("The key `{$this->keyTip}`: value  `$value` format not match `$parameter`");
        }
    }
}