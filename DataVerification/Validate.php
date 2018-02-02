<?php
/**
 * Created by PhpStorm.
 * User: kinddylee
 * Date: 2017/12/26
 * Time: 下午7:01
 */

namespace DataVerification;
use DataVerification\Structure\Picker;
use DataVerification\Structure\StructureCheck;
use DataVerification\VerificationException\VerificationException;

class Validate
{
    /**
     * @var Picker Service
     */
    private $picker;

    /**
     * @var 结构校验类
     */
    private $structureCheckService;

    /**
     * @var 根据数据模板生成[key]结构=>[value]数据验证规则
     */
    private $validateRule;


    function __construct()
    {
        $this->picker = new Picker();
        $this->structureCheckService = new StructureCheck();
    }

    /**
     * 设置API校验模板
     * Author:kinddylee@gmail.com
     */
    public function setDataCriterion( $apiData )
    {
        $this->validateRule = $this->picker->apiDefinedKeys($apiData);
        $this->picker->unsetDefindedKeys();
        return $this;
    }

    public function check( $requestData )
    {
        if (!is_array($requestData)) $requestData = [];

        //todo 验证数据结构是否与定义的结构一致
        $requestDataKeyStructrue = $this->picker->requestDataKeys($requestData);
        $this->picker->unsetDefindedKeys();

        //todo 验证数据结构
        $this->structureCheckService->check($this->validateRule,$requestDataKeyStructrue);

        //todo 根据规则模板验证请求数据
        foreach ($requestDataKeyStructrue as $requestDataKey=>$requestDataValue)
        {
            //todo set globe of requestData API key for error tip
            global $requestKeyNameErrorTip;
            $requestKeyNameErrorTip = $requestDataKey;

            //todo 获取验证规则
            $rule = $this->getRuleFormValidateRuler($requestDataKey) . PHP_EOL;
            //todo 执行验证过程
            $this->doValidate($rule, $requestDataValue);
        }
    }

    public function export()
    {

    }

    /**
     * 通过请求数据的key规则获取数据模板中的校验规则
     * 将请求数据的key中包含数字下标的置为0
     * 通过处理好的key中查找数据模版规则，如果不存在
     * 则抛出异常
     *
     * @param $requestSingleKey 请求数据的key
     * @return mixed 校验规则
     * @throws VerificationException
     * Author:kinddylee@gmail.com
     */
    private function getRuleFormValidateRuler( $requestSingleKey )
    {
        //todo 将Request数据中>0的下标转换为0用户获取验证规则
        $keyTemp = '';
        $keys = explode('.', $requestSingleKey);
        foreach ($keys as $k=>$value)
        {
            if (is_numeric($value) && $k>0)
                $value = 0;
            $keyTemp .= $value . '.';
        }
        $keyTemp = rtrim($keyTemp, '.');

        if (!isset($this->validateRule[$keyTemp]))
            throw new VerificationException('Can`t Found Rule From Request Data Key:' . $keyTemp);

        return $this->validateRule[$keyTemp];
    }

    /**
     * 执行校验
     * @param $rule
     * @param $requestDataValue
     * @throws VerificationException
     * Author:kinddylee@gmail.com
     */
    private function doValidate($rule, $requestDataValue)
    {
        $ruleMixed = explode("|", $rule);

        //todo classActionName
        $classActionName = trim(ucfirst($ruleMixed[1]));

        //todo Method Name
        $method = isset($ruleMixed[2]) ? trim($ruleMixed[2]) : 'typeConvert';

        //todo rule defined
        $parameter = isset($ruleMixed[3]) ? trim($ruleMixed[3]) : null;

        //String - regex $parameter handle for special chars '|' '\s'
        if (strtolower($classActionName) == 'string' && strtolower($method) == 'regex')
        {
            $ruleLength = strlen($rule);
            $regexPos = strpos($rule, 'regex|');
            $parameter = substr($rule, $regexPos+6, $ruleLength-$regexPos+6);
        }

        //todo get instance form factory
        $instance = VerificationFactory::getInstance($classActionName);

        if (!method_exists($instance, $method))
            throw new VerificationException("Method '$method' Not Found In Class '{$classActionName}Action.php' ");

        $instance->$method($requestDataValue, $parameter);
    }
}