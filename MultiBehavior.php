<?php
namespace mitalcoi\multi;


use yii\base\Behavior;
use Closure;
use yii\db\ActiveRecord;

/**
 * Class MultiBehavior
 * @package mitalcoi\multi
 */
class MultiBehavior extends Behavior
{

    /**
     * @var array scenarios
     */
    public $scenarios = [ActiveRecord::SCENARIO_DEFAULT];
    /**
     * @var array of relations in format: $relationName =>$fqn, ...
     */
    public $relationsMap = [];

    /**
     * @var Closure[] should realize process logic
     *
     * ```php
     * function ($owner, $relatedModel)
     * ```
     *
     * - `$owner`: the current data model being rendered
     * - `$relatedModel`: relation data model
     */
    public $process;

    /**
     * @var Closure[] determines whether the afterSave trigger work
     *
     * ```php
     * function ($owner)
     * ```
     *
     * - `$owner`: the current data model being rendered
     */
    public $afterSaveCondition;

    /**
     * @var Closure[] determines whether the afterFind trigger work
     *
     * ```php
     * function ($owner)
     * ```
     *
     * - `$owner`: the current data model being rendered
     */
    public $afterFindCondition;

    /** @inheritdoc */
    public function init()
    {
        parent::init();
        foreach ($this->relationsMap as $relationName => $fqn) {
            $this->multiHold[$relationName] = [];
            $this->_oldMulti[$relationName] = [];
            $this->_forceClearAllMulti[$relationName] = false;
        }
        if (count($this->relationsMap) === 1) {
            foreach ($this->relationsMap as $r => $fqn) {
                foreach (['process', 'afterSaveCondition', 'afterFindCondition'] as $cl) {
                    if ($this->{$cl} && !is_array($this->{$cl})) {
                        $tmp = $this->{$cl};
                        $this->{$cl} = [];
                        $this->{$cl}[$r] = $tmp;

                    }
                }
            }
        }
    }

    private function checkScenario()
    {
        return in_array($this->owner->scenario, $this->scenarios);
    }

    /** @inheritdoc */
    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
            ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            ActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
        ];
    }

    public function processMulti($r)
    {
        if ($this->checkScenario()) {
            $old_ids = [];
            $new_ids = [];
            foreach ($this->_oldMulti[$r] as $a) {
                $old_ids[] = $a->id;
            }
            if (!$this->_forceClearAllMulti[$r]) {
                foreach ($this->multiHold[$r] as $multi) {
                    call_user_func($this->process[$r], $this->owner, $multi);
                    $new_ids[] = $multi->id;
                }
            }
            $toDelete = array_diff($old_ids, $new_ids);
            $class = $this->relationsMap[$r];
            foreach ($toDelete as $id) {
                $class::deleteAll(['id' => $id]);
            }
        }
    }

    protected function _loadMulti($r, array $phones = [])
    {
        $this->multiHold[$r] = [];
        $class = $this->relationsMap[$r];
        foreach ($phones as $a) {
            if (isset($a['id'])) {
                $model = $class::findOne($a['id']);
                unset($a['id']);
            } else {
                $model = new $class;
            }

            $model->attributes = $a;
            $this->multiHold[$r][] = $model;
        }
    }

    private $_oldMulti = [];
    private $_forceClearAllMulti;
    public $multiHold = [];

    public function afterFind()
    {
        $condition = true;
        foreach ($this->relationsMap as $r => $fqn) {
            if (isset($this->afterFindCondition[$r])) {
                $condition = call_user_func($this->afterFindCondition[$r], $this->owner);
            }
            if ($condition) {
                foreach ($this->owner->{$r} as $mu) {
                    $this->_oldMulti[$r][] = $this->multiHold[$r][] = $mu;
                }
            }
            $condition = true;
        }
    }

    public function afterSave()
    {
        if ($this->checkScenario()) {
            $condition = true;
            foreach ($this->relationsMap as $r => $fqn) {
                if (isset($this->afterSaveCondition[$r])) {
                    $condition = call_user_func($this->afterSaveCondition[$r], $this->owner);
                }
                if ($condition) {
                    $this->processMulti($r);
                }
                $condition = true;
            }
        }
    }


    public function loadMulti($data, $formName = null)
    {
        if ($this->checkScenario()) {
            foreach ($this->relationsMap as $r => $fqn) {
                $form = $this->getFormName($fqn);
                if (isset($data[$form])) {
                    $this->_loadMulti($r, $data[$form]);
                } else {
                    if ($this->owner->{$r}) {
                        $this->_forceClearAllMulti[$r] = true;
                    }
                }
            }
        }
        return $this->owner->load($data, $formName);
    }

    public function validateMulti($attributes = null, $clearErrors = true)
    {
        $owner = $this->owner;
        if ($this->checkScenario()) {
            $valid = true;
            foreach ($this->relationsMap as $r => $fqn) {
                $first = $owner::validateMultiple($this->multiHold[$r]);
                $second = $owner->validate($attributes, $clearErrors);
                $valid = $second && $first && $valid;
            }
            return $valid;
        } else {
            return $owner->validate($attributes, $clearErrors);
        }
    }

    /**
     * @param $fqn
     * @return string
     */
    private function getFormName($fqn)
    {
        return array_reverse(explode('\\', $fqn))[0];
    }
} 
