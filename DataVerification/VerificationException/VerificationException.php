<?php
/**
 * Created by PhpStorm.
 * User: kinddylee
 * Date: 2017/12/27
 * Time: 下午4:55
 */

namespace DataVerification\VerificationException;


class VerificationException extends \Exception
{
    // 重定义构造器使 message 变为必须被指定的属性
    public function __construct($message, $code = 0, Exception $previous = null) {
        // 自定义的代码

        $code = 70000000;
        // 确保所有变量都被正确赋值
        parent::__construct($message, $code, $previous);
    }

    // 自定义字符串输出的样式
    public function __toString() {
        return "文件:$this->file ".PHP_EOL."行数:$this->line ".PHP_EOL."错误({$this->code}):$this->message " . PHP_EOL;
    }
}