<?php
/**
 * 单例工厂
 * 用于返回单例对象
 */
namespace DataVerification;

use DataVerification\VerificationException\VerificationException;

class VerificationFactory
{
    /**
     * @method 存储实例化对象
     */

    private static $Action;

    /**
     * @var array 路由地址
     * 动态获取`DataVerification\VerificationAction`
     * 命名空间下的子命名空间与其中的类：
     * [
     * 'arr'=>'DataVerification\VerificationAction\Arr\ArrayAction',
     * 'array'=>'DataVerification\VerificationAction\Arr\ArrayAction',
     * 'boolean'=>'DataVerification\VerificationAction\Boolean\BoolAction',
     * 'bool'=>'DataVerification\VerificationAction\Boolean\BoolAction',
     * ]
     *
     */
    private static $router;

    /**
     * 静态单例工厂
     *
     * 静态变量数组$Action存储实例化对象
     * 如果对象存在则直接返回，如果不存在
     * 则实例化对象并赋值['className']=>$instance
     *
     * @param $className
     * @return mixed
     * @throws VerificationException 如果类不存在则抛出异常，如果类名在路由中不存在则抛出异常
     * Author:kinddylee@gmail.com
     */
    public static function getInstance( $className )
    {
        self::setRouter();

        $className = strtolower($className);
        if (!isset(self::$Action[$className]))
        {
            if (!isset(self::$router[$className]))
                throw new VerificationException("ClassName Not Found In Router: $className");

            if (!class_exists(self::$router[$className]))
                throw new VerificationException("VerificationAction Class Not Found: " . self::$router[$className]);

            self::$Action[$className] = new self::$router[$className];
        }
        return self::$Action[$className];
    }

    /**
     * 具体的验证规则的扩展
     * 在工厂中动态的设置静态属性$Action
     * 用户可以添加自己的校验类至本项目中
     *
     * @param $usageKey
     * @param $instance
     * @throws VerificationException
     * Author:kinddylee@gmail.com
     */
    public static function addVerificationAction($usageKey, $instance)
    {
        self::setRouter();

        if (isset(self::$router[strtolower($usageKey)]))
            throw new VerificationException("Usage Has Been Exists In This Project:`$usageKey`");

        if (!is_object($instance))
            throw new VerificationException("The Verification Action You Just Set is not Object: \$instance must be Object");

        self::$Action[strtolower($usageKey)] = $instance;
    }

    /**
     * 动态设置路由，检测`/VerificationAction/`下存在
     * 子目录并且子目录中存在SomeAction.class文件，则
     * 将类的信息写入到静态router中
     *
     * Author:kinddylee@gmail.com
     */
    public static function setRouter()
    {
        //todo 动态设置路由名称及命名空间
        if (is_null(self::$router))
        {
            //todo 获取`/VerificationAction/`目录中的子目录及类信息
            $pathName = rtrim(dirname(__FILE__), '/') . '/VerificationAction/';
            foreach(scandir($pathName) as $dirName)
            {
                if (is_dir($pathName . $dirName) && $dirName != '.' && $dirName != '..')
                {
                    //todo 将子目录名称设置为key
                    $ActionNameSpace[$dirName] = null;
                    foreach (scandir($pathName . $dirName . '/') as $classFileName)
                    {
                        if ( $classFileName != '.' && $classFileName != '..' && strpos($classFileName, 'Action'))
                        {
                            //todo 将包含Action类文件名称设置为value
                            $ActionNameSpace[$dirName] = $classFileName;
                        }
                    }
                }
            }

            //todo 通过目录信息设置路由
            foreach ($ActionNameSpace as $k=>$v)
            {
                if (!is_null($v))
                {
                    $routerKeyExplode = explode('Action',$v);
                    $routerNameSpaceExplode = explode('.',$v);
                    $routerKey = $routerKeyExplode[0];
                    $routerNameSpace = $routerNameSpaceExplode[0];
                    self::$router[strtolower($routerKey)] = "DataVerification\VerificationAction\\$k\\$routerNameSpace";
                }
            }
        }
    }
}