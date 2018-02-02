<?php
/**
 * Created by PhpStorm.
 * User: kinddylee
 * Date: 2017/12/26
 * Time: 下午5:19
 */

namespace DataVerification\VerificationAction;

/**
 * Class AbstractVerification
 * @codeCoverageIgnore
 * @package DataVerification\VerificationAction
 */
abstract class AbstractVerification
{
    /**
     * 强制转化数据类型
     * 并验证转化后的数据是否与类定义的数据类型一致
     *
     * @return mixed
     * Author:kinddylee@gmail.com
     */
    abstract protected function typeConvert($args);

    /**
     * 魔术方法
     * 自类中必须实现魔术方法
     * 子类中具体的验证方法必须使用protected修饰符
     * 客户端调用验证方法时，因没有权限会触发魔术方
     * 发__call()
     *
     * 1、魔术方法中先调用typeCheck()强制转换数据类型
     * 2、再调用call_user_func_array()调用实际的验
     * 方法
     *
     * @param $name
     * @param $arguments
     * @return mixed
     * Author:kinddylee@gmail.com
     */
    abstract public function __call($name, $arguments);
}