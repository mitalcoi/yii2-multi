<?
use yii\helpers\Html;

/**
 * @var \yii\db\ActiveRecord $model
 * @var string $relation
 * @var string $rowViewPath
 * @var string $mainLabel
 * @var string $deleteLabel
 * @var string $createLabel
 * @var string $multi_url
 */
\mitlcoi\multi\AddMultiplePhoneAsset::register($this);
?>
<div id="<?= $relation ?>">
    <?= Html::hiddenInput('multi_url-' . $relation, $multi_url, ['class' => 'multi_url']); ?>
    <?= Html::label($mainLabel) ?>
    <ul>
        <? foreach ($model->multiHold[$relation] as $i => $r): ?>
            <?= $this->render($rowViewPath, ['model' => $r, 'i' => $i, 'deleteLabel' => $deleteLabel]); ?>
        <? endforeach; ?>
    </ul>
</div>
<?=
Html::a(
    $createLabel,
    '#',
    ['id' => 'add-multi', 'onclick' => "js:addMulti('{$relation}');return false;", 'class' => 'btn btn-default']
); ?>
<br/>
<br/>