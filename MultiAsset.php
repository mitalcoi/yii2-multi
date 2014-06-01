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
            'js/add-multiple.js'
        ];
        $this->css = [
            'css/add-multiple.css'
        ];
    }
}
