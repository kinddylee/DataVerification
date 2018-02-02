<?php
/**
 * 验证数据格式有效性
 * User: kinddylee
 * Date: 2018/1/9
 * Time: 下午6:15
 */

namespace DataVerification\Structure;


use DataVerification\VerificationException\VerificationException;

class StructureCheck
{
    private $intString;

    function __construct()
    {
        for ($i=0;$i<100;$i++)
        {
            $this->intString[] = strval($i);
        }
    }

    public function check($apiStructure, $requestDataStructure)
    {
        $apiKeys = array_keys($apiStructure);
        $requestDataKeys = array_keys($requestDataStructure);

        $this->checkIllegalKey($apiKeys, $requestDataKeys);
        $this->checkRequireKey($apiStructure, $requestDataKeys);
    }


    /**
     * checkIllegalKey
     * @param $apiStructure
     * @param $requestDataStructure
     * @throws VerificationException
     * Author:kinddylee@gmail.com
     */
    private function checkIllegalKey($apiStructure, $requestDataStructure)
    {
        $requestDataStructure = $this->exchangeNum($requestDataStructure);
        foreach ($requestDataStructure as $value)
        {
            if (!in_array($value, $apiStructure))
                throw new VerificationException("The key `$value` not defined in API");
        }
    }

    /**
     * exchangeNum
     * @param $arrayKeys
     * @return mixed
     * Author:kinddylee@gmail.com
     */
    private function exchangeNum( $arrayKeys )
    {
        $line = preg_replace_callback(
            '/\d+/',
            function ($matches) {
                return 0;
            },
            $arrayKeys
        );
        return $line;
    }


    /**
     * checkRequireKey
     * @param $apiStructure
     * @param $requestDataKeys
     * @throws VerificationException
     * Author:kinddylee@gmail.com
     */
    private function checkRequireKey($apiStructure, $requestDataKeys)
    {
        //todo API require key
        $requireKeys = [];
        foreach ($apiStructure as $key=>$value)
        {
            $ruleTemp = explode('|', $value);
            $requireLabel = isset($ruleTemp[0]) ? strtolower(trim($ruleTemp[0])) : 'empty';
            if (!in_array($requireLabel, ['require', 'optional']))
                throw new VerificationException("The key `$key` Requirement Parameter defined error. Wrong defined:`$requireLabel`, you should use `require` or `optional`");

            if ($requireLabel == 'require')
                $requireKeys[] = $key;
        }

        //todo 判断1维KEY与下标为0的数组key
        foreach ($requireKeys as $key)
        {
            if (!in_array($key, $requestDataKeys))
                throw new VerificationException("key `$key` is require,but not found in request data.");
        }

        //todo 校验多维key
        $apiKey = $this->faceCade($requireKeys);
        $requestKey = $this->faceCade($requestDataKeys);

        $this->verifierStructure($apiKey, $requestKey);

    }

    /**
     * 多维数组处理门面
     * @param $keys
     * @return mixed
     * Author:kinddylee@gmail.com
     */
    private function faceCade($keys)
    {
        $r1 = $this->filter1($keys);
        $r2 = $this->filter2($keys);
        $r3 = $this->filter3($r1, $r2);
        $r4 = $this->filter4($r3);
        $r5 = $this->filter5($r4);
        return $r5;
    }

    /**
     * 通过`.d.`拆分key字符串为数组
     * @param $keysData
     * @return array
     * Author:kinddylee@gmail.com
     */
    private function filter1($keysData)
    {
        $result = [];
        foreach ($keysData as $key)
        {
            $explodes = preg_split('/.{\d+}./' , $key);
            if (count($explodes) > 1)
                $result[$key] = $explodes;
        }
        return $result;
    }

    /**
     * 获取`.d.`中的`d`的具体数值
     * @param $keysData
     * @return array
     * Author:kinddylee@gmail.com
     */
    private function filter2($keysData)
    {
        $result = [];
        foreach ($keysData as $key)
        {
            preg_match_all('/.{\d+}./', $key, $matchs);
            if (!empty($matchs[0]))
                $result[$key] = $matchs;
        }
        return $result;
    }

    /**
     * 将`.d.`中的数值拼接到拆封后的数组中
     * @param $keysDataExplode
     * @param $keysDataIndexs
     * @return mixed
     * Author:kinddylee@gmail.com
     */
    private function filter3($keysDataExplode, $keysDataIndexs)
    {
        foreach ($keysDataIndexs as $k=>$v)
        {
            foreach ($v[0] as $k2=>$v2)
            {
                $keysDataExplode[$k][$k2] .=  $v2;
            }
        }
        return $keysDataExplode;
    }

    /**
     * 根据补位原则插入key及value
     * @param $data
     * @return array
     * Author:kinddylee@gmail.com
     */
    private function filter4($data)
    {
        $result = [];
        foreach ($data as $value)
        {
            foreach ($value as $k=>$v)
            {
                if (isset($value[$k]) && isset($value[$k + 1]))
                {
                    $keySums = '';
                    for ($i=0;$i<=$k;$i++)
                    {
                        $keySums .= $value[$i];
                    }
                    $result[$keySums][] = $value[$k+1];
                }
            }
        }
        return $result;
    }

    /**
     * 将值中的数字替换为0
     * 去0后去重
     * @param $data
     * @return mixed
     * Author:kinddylee@gmail.com
     */
    private function filter5($data)
    {
        foreach ($data as $key=>$value)
        {
            $data[$key] = array_unique($this->exchangeNum($value));
        }
        return $data;
    }

    /**
     * 校验多维数组require - key
     * @param $api
     * @param $request
     * @throws VerificationException
     * Author:kinddylee@gmail.com
     */
    private function verifierStructure($api, $request)
    {
        foreach ($request as $k=>$v)
        {
            //todo 将request-key中的数组替换为0
            $requestKeyForApi = $this->exchangeNum($k);

            //todo 获取api中的key对应的value
            $apiValue = $request[$requestKeyForApi];

            if (!empty(array_diff($apiValue, $v)))
                throw new VerificationException("Requirement key is missing near `$k`");

        }
    }
}