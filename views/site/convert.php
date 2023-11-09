<?php
use app\models\Course;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;



/** @var yii\web\View $this */
/** @var app\models\ConvertForm $model */
/** @var app\models\Valute[] $valutes */
/** @var float|bool $res */

$this->title = 'Convert';

?>
<div class="convert-index">
    <h1><?= Html::encode($this->title) ?></h1>

        <div class="row">
            <div class="col-lg-5">

                <?php $form = ActiveForm::begin(['id' => 'convert-form']); ?>

                    <?= $form->field($model, 'val')->textInput(['autofocus' => true,'maxlength'=>15])->label('Сумма') ?>
                    <?= $form->field($model,'fromCourse')->dropDownList(ArrayHelper::map($valutes,'char_code','name_valute'))->label('Перевести из'); ?>
                    <?= $form->field($model,'toCourse')->dropDownList(Arrayhelper::map($valutes,'char_code','name_valute'))->label('Перевести в'); ?>

                    <div class="form-group">
                        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary', 'name' => 'convert-button']) ?>
                    </div>

                <?php ActiveForm::end(); ?>

            </div>
        </div>

    <?php if ($res) :?>
    <?= $res ?>
    <?php endif; ?>
</div>
