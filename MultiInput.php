<?php
namespace mitalcoi\multi;


use yii\base\Widget;

/**
 * Class MultiInput
 * @package mitalcoi\multi
 */
class MultiInput extends Widget
{
    public $url = 'get-multi';
    public $model;
    public $relation;
    public $rowViewPath;
    public $mainLabel;
    public $createLabel='Add';
    public $deleteLabel='Remove';

    public function init()
    {
        parent::init();
        if (!$this->rowViewPath) {
            $this->rowViewPath = '_row';
        }
    }

    public function run()
    {
        MultiAsset::register(
            $this->getView()
        );
        echo $this->render(
            '_create',
            [
                'model' => $this->model,
                'relation' => $this->relation,
                'rowViewPath' => $this->rowViewPath,
                'createLabel' => $this->createLabel,
                'deleteLabel' => $this->deleteLabel,
                'mainLabel' => $this->mainLabel,
                'multi_url'=>$this->url
            ]
        );
    }
} 