<?
use yii\helpers\Html;
use app\models\ExternalPhone;

/**
 * @var ExternalPhone $model
 * @var integer $i
 * @var string $deleteLabel
 */
?>
<li class="multi-row row">
    <div class="col-lg-5 form-group">
        <?=
        Html::a(
            'X',
            '#',
            ['title' => isset($deleteLabel) ? $deleteLabel : 'Delete' . ' #' . ($i + 1), 'class' => 'delete-multi']
        ); ?>
        <h5><?= '#' . ($i + 1) ?></h5>
        <?
        echo Html::activeTextInput($model, "[$i]number", ['class' => 'form-control']);
        echo Html::error($model, "[$i]number", ['class' => 'help-block']);

        if (!$model->isNewRecord):?>
            <?= Html::activeHiddenInput($model, "[$i]id") ?>
        <? endif; ?>

    </div>
</li>
