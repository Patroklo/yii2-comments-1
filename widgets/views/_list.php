<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $widget \yii2mod\comments\widgets\Comment */
?>
<?= \yii\widgets\ListView::widget([
    'dataProvider' => $provider,
    'itemOptions' => ['class' => 'item'],
    'layout' => '{items}',
    'itemView' => function ($model, $key, $index) use ($widget)
    {
        return $this->render('_item', ['model' => $model, 'widget' => $widget]);
    }
]) ?>