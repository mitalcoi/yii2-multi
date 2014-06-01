<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace mitalcoi\multi;
use Yii;
use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class MultiAsset
 * @package mitalcoi\multi
 */
class MultiAsset extends AssetBundle
{
    public $depends = [
        'yii\web\JqueryAsset'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/resources';
        $this->js = [
            'js/add-multiple-phone.js'
        ];
        $this->css = [
            'css/add-multiple-phone.css'
        ];
    }

    public static function register($view, $additionData = [])
    {
        $js = '';

        foreach ($additionData as $key => $value) {
            $js .= "var {$key}='{$value}';";
        }
        if ($js) {
            $view->registerJs($js, View::POS_HEAD);

        }
        return parent::register($view);
    }

}
