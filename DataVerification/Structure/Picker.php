<?php
/**
 * 获取数据结构类
 * Data Structure
 *
 * [
    'username'=>'require|str|lengthGT|5',
    'emails'=>
    [
    'optional|int|between|1-4'
    ],

    'subUser'=>
    [
    'username'=>'require|enum|exist|11,33,22',
    'password'=>'require|bool|isType|',
    ],

    'content'=>
    [
    [
    'tital'=>'require|str|lengthGT|5',
    'context'=>'require|str|lengthGT|5',
    ]
    ],
    ];
 *
 * 返回数据结构如下
 *
 * [
 *      'username'=>'require|str|lengthGT|5',
 *      'emails.0'=>'optional|int|between|1-4',
 *      'subUser.username'=>'require|enum|exist|11,33,22',
 *      'subUser.password'=>'require|bool|isType|',
 *      'content.0.tital'=>'require|str|lengthGT|5',
 *      'content.0.context'=>'require|str|lengthGT|5',
 * ]
 *
 */

namespace DataVerification\Structure;
use DataVerification\VerificationException\VerificationException;

class Picker
{
    /**
     * @var 根据客户端定义的模板生成的key结构及校验规则
     */
    private $definedKeys;

    /**
     * @var 组件自定义校验规则
     */
    private $actionNames;

    /**
     * @var 用户定义的API中使用了自定义规则
     * 的key
     */
    private $_keyOfApiDefindeUseOtherAction = [];

    function __construct()
    {
        $this->actionNames = $this->getActions();
    }

    /**
     * 递归API结构，返回所有的KEY值
     * 如果定的API校验规则中有自定义
     * 扩展的校验规则，则将key名称记录
     * 至$_keyOfApiDefindeUseOtherAction类变量中
     *
     * @param $definedData
     * @param string $keys
     * @return API定义的数据结构
     * Author:kinddylee@gmail.com
     */
    public function apiDefinedKeys($definedData, $keys='')
    {
        foreach ($definedData as $key=>$value)
        {
            //todo 如果不为数组
            if (!is_array($value))
            {
                $definedKey = ltrim($keys .'.'. $key, '.');

                //todo apiDefined key为用户自定义校验规则时则记录当前key
                $actionNameTemp = explode('|', $value);
                $actionName = isset($actionNameTemp[1]) ? $actionNameTemp[1] : 'string';
                if (!in_array(ucfirst($actionName), $this->actionNames))
                {
                    $this->_keyOfApiDefindeUseOtherAction[] = $definedKey;
                }

                $this->definedKeys[$definedKey] = $value;
            }
            else
            {
                $this->apiDefinedKeys($value, $keys .'.'. $key);
            }
        }
        return $this->definedKeys;
    }

    /**
     * requestDataKeys
     *
     * 递归客户端请求数据，返回数据结构
     * 具体值 自定义校验规则 -> 记录key=>value
     * 具体值 系统校验规则 -> 记录key=>value
     * 数组 自定义校验规则 -> 记录key=>value
     * 数组 系统校验规则 -> 递归
     *
     * @param $definedData
     * @param string $keys
     * @return 客户端请求数据的结构
     * @throws VerificationException
     * Author:kinddylee@gmail.com
     */
    public function requestDataKeys($definedData, $keys='')
    {
        foreach ($definedData as $key=>$value)
        {
            $definedKey = ltrim($keys .'.'. $key, '.');

            if (!is_array($value) && in_array($definedKey, $this->_keyOfApiDefindeUseOtherAction))
            {
                $this->definedKeys[$definedKey] = $value;
            }
            else if (!is_array($value) && !in_array($definedKey, $this->_keyOfApiDefindeUseOtherAction))
            {
                $this->definedKeys[$definedKey] = $value;
            }
            else if (is_array($value) && in_array($definedKey, $this->_keyOfApiDefindeUseOtherAction))
            {
                $this->definedKeys[$definedKey] = $value;
            }
            else if (is_array($value) && !in_array($definedKey, $this->_keyOfApiDefindeUseOtherAction))
            {
                $this->requestDataKeys($value, $keys .'.'. $key);
            }
            else
            {
                throw new VerificationException('Systme Error,Please contact Administrator.');// @codeCoverageIgnore
            }
        }
        return $this->definedKeys;
    }

    /**
     * 清空变量
     * Author:kinddylee@gmail.com
     */
    public function unsetDefindedKeys()
    {
        $this->definedKeys = [];
    }

    /**
     * 获取VerificationAction文件目录下
     * 的ActionName,如StringAction.php
     * 获取的为`String`,为了让`Array`类型
     * 的数据停止递推，数组中排除`Array`字符
     *
     * Author:kinddylee@gmail.com
     */
    private function getActions()
    {
        $result = [];
        $dir = rtrim(dirname(dirname(__FILE__)), '/') . '/VerificationAction/';
        $dirInfo = (scandir($dir));

        foreach ($dirInfo as $value)
        {
            if ($value != '.' && $value !='..' && is_dir($dir . $value . '/') && strtolower($value) != 'arrays')
                $result[] =$value;
        }
        return $result;
    }
}
