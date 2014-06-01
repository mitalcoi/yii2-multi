<?php
namespace mitalcoi\multi;

/**
 * Class AddRowAction
 * @package mitalcoi\multi
 */
class AddRowAction extends \yii\web\Action
{
    public $modelClass;

    public function run($index)
    {
        $class = $this->modelClass;
        $model = new $class;
        return $this->controller->renderPartial('@mitalcoi/multi/views/_row', ['i' => $index, 'model' => $model]);

    }
} 