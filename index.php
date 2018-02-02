<?php
/**
 * Created by PhpStorm.
 * User: kinddylee
 * Date: 2017/12/28
 * Time: 下午7:36
 */
require 'vendor/autoload.php';


try{
    $api = [
        'player_id'=>'require|int',
        'startTime'=>'require|string|lengthOfCharsBetween|2-5',
        'endTime'=>'require|string|regex|/^[0-9]{4}-(0?[1-9]|1[012])-(0?[1-9]|[12][0-9]|3[01])\s(0?[0-9]|1[0-9]|2[0-3]):(0?[0-9]|[1234][0-9]|5[0-9]):(0?[0-9]|[1234][0-9]|5[0-9])$/',
        'mediaList'=>['require|int'],
        'time' => 'require|date|format|d/m/Y H:i:s.u'
    ];

    $data = [
        'player_id'=>'223',
        'startTime'=>'requi',
        'endTime'=>'2018-08-03 12:33:32',
        'mediaList'=>['3'],
        'time' => '02/02/2018 12:33:17.032'
    ];

    $validate = new \DataVerification\Validate();
    $validate->setDataCriterion($api)->check($data);

}catch (\DataVerification\VerificationException\VerificationException $exception)
{
    echo $exception;
}