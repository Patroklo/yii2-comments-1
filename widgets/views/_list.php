<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $comment \yii2mod\comments\models\CommentModel */
/* @var $comments array */
/* @var $maxLevel null|integer coments max level */
/* @var $encryptedEntity string */
/* @var $pjax boolean */
?>
<?php if (!empty($comments)) : ?>
    <?php foreach ($comments as $comment) : ?>
        <li class="comment" id="comment-<?php echo $comment->id ?>">
            <div class="comment-content" data-comment-content-id="<?php echo $comment->id ?>">
                <div class="comment-author-avatar">
                    <?php echo $comment->getAvatar(['alt' => $comment->getAuthorName()]); ?>
                </div>
                <div class="comment-details">
                    <?php if ($comment->isActive): ?>
                        <div class="comment-action-buttons">
                            <?php if ($comment->canDelete()): ?>
                                <?php echo Html::a('<span class="glyphicon glyphicon-trash"></span> '.Yii::t('app', 'Delete'), '#', ['data' => ['action' => 'delete', 'url' => Url::to(['/comment/default/delete', 'id' => $comment->id]), 'comment-id' => $comment->id]]); ?>
                            <?php endif; ?>
                            <?php if ($comment->canCreate() && ($comment->level < $maxLevel || is_null($maxLevel))): ?>
                                <?php echo Html::a('<span class="glyphicon glyphicon-share-alt"></span> '.Yii::t('app', 'Reply'), '#', ['class' => 'comment-reply', 'data' => ['action' => 'reply', 'comment-id' => $comment->id]]); ?>
                            <?php endif; ?>
                            <?php if ($comment->canUpdate()): ?>
                                <?php echo Html::a('<span class="glyphicon glyphicon-pencil"></span> '.Yii::t('app', 'Edit'), '#', ['class' => 'comment-edit', 'data' => ['action' => 'edit', 'comment-id' => $comment->id]]); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <div class="comment-author-name">
                        <span><?php echo $comment->getAuthorName(); ?></span>
                        <span class="comment-date">
                            <?php echo $comment->getPostedDate(); ?>
                        </span>
                    </div>
                    <div class="comment-body">
                        <?php echo $comment->getContent(); ?>
                    </div>
                    <?php if ($comment->canUpdate()): ?>
                        <div class="comment-body-edit">
                            <?php echo $this->render('_form', ['commentModel' => $comment, 'encryptedEntity' => $encryptedEntity, 'pjax' => $pjax]); ?>
                        </div>
                    <?php endif; ?>


                </div>
            </div>
            <?php if ($comment->hasChildren()): ?>
                <ul class="children">
                    <?php echo $this->render('_list', ['comments' => $comment->children, 'maxLevel' => $maxLevel, 'encryptedEntity' => $encryptedEntity, 'pjax' => $pjax]) ?>
                </ul>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
<?php endif; ?>