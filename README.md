
# VNNOX VERIFICATION

Vnnox RESTful API 参数校验组件

### 特性说明

- 根据模版数据结构，校验外部数据是否包含非法KEY
- 根据模板规则中的必选字段，校验外部数据KEY是否完整
- 根据定义的校验规则，校验外部的数据的Value是否满足指定的类型和指定的范围
- 根据模板数据结构，导出API文档

### 快速使用

```php
<?php
require 'vendor/autoload.php';
// 实例化校验类
$validate = new DataVerification\Validate();

// 定义API模板和校验规则
$api = [
    "username" => "require|int|equal|5",
    "remark" => "require|int|equal|5",
    "parent_1" => [
        "require|int|equal|5"
    ],
    "parent_2" => [
        "2_001" => "optional|int|between|1-4",
        "2_002" => [
            "optional|int|between|1-4",
        ],
        "2_003" => [
            "2_003_001" => "optional|int|between|1-4",
            "2_003_002" => "optional|int|between|1-4",
        ],
    ],
    "parent_3" => [
        [
            "_3_001" => "require|bool|isType|",
            "_3_002" => "require|bool|isType|",
            "_3_003" => "require|bool|isType|",
        ]
    ],
];

// 执行校验
$validate->setDataCriterion( $api )->check( $requestData );
```

### 结构校验

- 简单结构校验(一维数组)

```php
$api = [
    'username'=>'require|string|notEmpty',
    'password'=>'require|string|notEmpty',
    'contact'=>'require|string|notEmpty',
    'address'=>'require|string|notEmpty',
];

$requestData = [
    'username'=>'john',
    'password'=>'123456',
    'contact'=>'0001-8976589765',
    'address'=>'Xi`An YanTa.',
];

// 结构校验通过
$validate->setDataCriterion( $api )->check( $requestData );
```


```php
$api = [
    'username'=>'require|string|notEmpty',
    'password'=>'require|string|notEmpty',
    'contact'=>'require|string|notEmpty',
    'address'=>'require|string|notEmpty',
];

$requestData = [
    'username'=>'john',
    'password'=>'123456',
    'contact'=>'0001-8976589765',
];

// 结构校验失败，缺少必须字段`address`
$validate->setDataCriterion( $api )->check( $requestData );
```


```php
$api = [
    'username'=>'require|string|notEmpty',
    'password'=>'require|string|notEmpty',
    'contact'=>'require|string|notEmpty',
    'address'=>'require|string|notEmpty',
];

$requestData = [
    'username'=>'john',
    'password'=>'123456',
    'contact'=>'0001-8976589765',
    'address'=>'require|string|notEmpty',
    'time'=>'2018:00',
];

// 结构校验失败，多出字段`time`
$validate->setDataCriterion( $api )->check( $requestData );
```


- 复杂结构校验(多维数组)


```php
$api = [
    'actoin'=>'require|string|notEmpty',
    'task'=>['require|string|notEmpty'], //定义多维数组需要添加`[`，`]`，只需定义第一位即可，后面已相同规则校验
    'jobs'=>[
        [
            'jobName'=>'require|string|notEmpty',
            'jobAction'=>'require|string|notEmpty',
        ],
    ],
];

$requestData = [
    'actoin'=>'some_action_name',
    'task'=>['task_1', 'task_2', 'task_3'],
    'jobs'=>[
        [
            'jobName'=>'jobname1',
            'jobAction'=>'jobaction1',
        ],
        [
            'jobName'=>'jobname2',
            'jobAction'=>'jobaction2',
        ],
    ],
];

// 结构校验通过
$validate->setDataCriterion( $api )->check( $requestData );
```


```php
$api = [
    'actoin'=>'require|string|notEmpty',
    'task'=>['require|string|notEmpty'], //定义多维数组需要添加`[`，`]`，只需定义第一位即可，后面已相同规则校验
    'jobs'=>[
        [
            'jobName'=>'require|string|notEmpty',
            'jobAction'=>'require|string|notEmpty',
        ],
    ],
];

$requestData = [
    'actoin'=>'some_action_name',
    'task'=>['task_1', 'task_2', 'task_3'],
    'jobs'=>[
        [
            'jobName'=>'jobname1',
            'jobAction'=>'jobaction1',
        ],
        [
            'jobName'=>'jobname2',
        ],
    ],
];

// 结构校验失败，jobs.1.jobAction缺失
$validate->setDataCriterion( $api )->check( $requestData );
```


### Key值校验

验证规则需满足以下格式：

```php
必须性|数据类型|验证方法|参数
```

| 必须性         | 数据类型        | 验证方法       |参数            |说明             |
| ------------- |:-------------:| ---------------:|:-------------:|:---------------:|
| required      | string        |空                |空             |是否是字符串      |
| optional      | string        |notEmpty         |空             |是否为空          |
|               | string        |lengthOfBitEqual |int            |字符bit长度等于   |
|               | string        |lengthOfBitGreatThan|int         |字符bit长度大于n |
|               | string        |lengthOfCharsEqual|int           |字符个数等于      |
|               | string        |lengthOfBitLessThan|int          |字符bit长度最大值 |
|               | string        |lengthOfBitBetween |'int-int'    |字符bit长度介于n-m之间|
|               | string        |lengthOfCharsGreatThan|int       |字符的个数大于   |
|               | string        |lengthOfCharsLessThan |int       |字符串的个数小于 |
|               | string        |lengthOfCharsBetween|'int-int'   |字符的个数介于n-m之间|
|               | string        |regex            |正则表达式      |正则校验          |
|               | string        |same             |string         |相同              |
|               | string        |notSame          |string         |不相同            |
|               | int           |空               |空             |是否为int         |
|               | int           |greaterThan      |int            |大于              |
|               | int           |lessThan         |int            |小于              |
|               | int           |equal            |int            |等于              |
|               | int           |notEqual         |int            |不等于            |
|               | int           |greaterThanOrEqual|int            |大于等于          |
|               | int           |lessThanOrEqual  |int            |小于等于          |
|               | int           |between          |'int-int'      |大于a小于b        |
|               | enum          |in               |'a,b,c...'     |在枚举项中        |
|               | enum          |notIn            |'a,b,c...'     |不在枚举项中      |
|               | boolean       |空                |空             |是否为boolean类型(包含string:true|false)|
|               | boolean       |equal            |boolean        |等于指定boolean   |
|               | arrays       |空                 |空             |判断类型为Array|
|               | arrays       |notEmpty           |空             |判断数组不为空      |
|               | arrays       |countEqual         |int             |判断数组count()等于|
|               | arrays       |countGreatThan     |int             |判断数组count()大于|
|               | arrays       |countLessThan      |int             |判断数组count()小于|
|               | date         |空                  |空             |校验日志格式是否正确|
|               | date         |format             |string          |校验日期格式满足定义标准，参照php:date函数用法|

#### PHP:date http://php.net/manual/zh/function.date.php


### 扩展自定义规则

当校验规则不满足业务需要时，你可以创建自己的校验类，并将实例添加至工厂中，用于扩展自定义校验规则

- 创建自定义验证类SomeDataValidate.php,并继承DataVerification\VerificationAction\AbstractVerification抽象类

```php
<?php
namespace YourNameSpace;

use DataVerification\VerificationAction\AbstractVerification;
use DataVerification\VerificationException\VerificationException;

class SomeDataValidate extends AbstractVerification
{
    //todo setKeyValidate
    private $keyTip;
    
    //form abstract
    protected function typeConvert($args)
    {
        global $requestKeyNameErrorTip;
        $this->keyTip = $requestKeyNameErrorTip;
                
        return $args;
    }
    
    //form abstract
    public function __call($method, $args) {
        $args = $this->typeConvert($args);
        //如果用户没有传指定校验方式，则只使用默认转换校验类型
        if ($method == 'typeConvert')
            return ;
        
        return call_user_func_array([$this, $method], $args);
    }
    
    //your validate action
    protected function yourCheckAction($value, $parameter)
    {
        throw new VerificationException('校验失败' . $parameter . $value);
    }
}
```

- 将自定义验证类实例注入工厂中

```php
require 'vendor/autoload.php';

//通过静态工厂方法`addVerificationAction`将自定义验证实例注入至工厂中
\DataVerification\VerificationFactory::addVerificationAction('your_usage_label', new \YourNameSpace\SomeDataValidate());

// 实例化校验类
$validate = new DataVerification\Validate();

//设置自定义规则
$apiDefined = [
    "username" => "require|your_usage_label|yourCheckAction|5",
    ];
    
// 执行校验
$validate->setDataCriterion( $apiDefined )->check( $requestData );

```

- 注意规则
通过`addVerificationAction`注入自定义验证规则时，`your_usage_label`不能与已有`数据类型`的冲突，否则已有的验证类会被覆盖，响应的验证方法
也会抛出异常

    
### 安装本组件至项目(Composer)
- 在你的项目的composer.json中加入资源地址

```json
{
    "repositories": [
            {
                "type": "vcs",
                "url": "git@172.16.80.102:vnnox/vnnoxDataVerification.git"
            }
        ]
}
```

- 执行composer require命令

```bash
 composer require vnnox/vnnoxdataverification
```

### 协助开发本组件
- clone仓库至本地
    - `git clone git@172.16.80.102:vnnox/vnnoxDataVerification.git`
    - 安装依赖 `composer install`
- 测试
    - 安装phpunit 5.7.xx
    - 执行测试：在组件根目录执行 `phpunit`
    - 代码覆盖率：组件根目录执行 `phpunit --coverage-html ./report`

### 后续计划

- API Request文档导出
