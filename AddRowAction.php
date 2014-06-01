<?php
namespace mitalcoi\multi;

use yii\base\Action;

/**
 * Class AddRowAction
 * @package mitalcoi\multi
 */
class AddRowAction extends Action
{
    public $modelClass;

    public function run($index)
    {
        $class = $this->modelClass;
        $model = new $class;
        return $this->controller->renderPartial('@mitalcoi/multi/views/_row', ['i' => $index, 'model' => $model]);

    }
} 