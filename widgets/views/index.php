<?php
/* @var $this \yii\web\View */
/* @var $comments array */
/* @var $commentModel \yii2mod\comments\models\CommentModel */
/* @var $maxLevel null|integer coments max level */
/* @var $encryptedEntity string */
/* @var $pjax boolean */
?>
<div class="comments row">
    <div class="col-md-11 col-sm-11">
        <div class="title-block clearfix">
            <h3 class="h3-body-title"><?php echo 'Comments'; ?></h3>

            <div class="title-seperator"></div>
        </div>
        <?php

        if ($pjax === TRUE)
        {
            \yii\widgets\Pjax::begin([
                'id' => 'new_country',
                'enablePushState' => false,
            ]);
        }
        ?>
        <ol class="comments-list">
            <?php echo $this->render('_list', ['comments' => $comments, 'maxLevel' => $maxLevel, 'encryptedEntity' => $encryptedEntity, 'pjax' => $pjax]) ?>
        </ol>
        <?php if ($commentModel->canCreate()) : ?>
            <?php

            if ($pjax === TRUE)
            {
                echo \common\widgets\Alert::widget();
            }

            echo $this->render('_form', ['commentModel' => $commentModel, 'encryptedEntity' => $encryptedEntity, 'pjax' => $pjax]);
            ?>
        <?php endif; ?>
        <?php
        if ($pjax === TRUE)
        {
            \yii\widgets\Pjax::end();
        }
        ?>
    </div>
</div>