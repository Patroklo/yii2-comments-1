<?php

namespace yii2mod\comments\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\Json;
use yii2mod\comments\models\CommentModel;
use yii2mod\comments\Module;

/**
 * Class Comment
 * @package app\components\comment\widgets
 */
class Comment extends Widget
{
    /**
     * @var \yii\db\ActiveRecord|null Widget model
     */
    public $model;

    /**
     * @var null|integer maximum comments level, level starts from 1, null - unlimited level;
     */
    public $maxLevel = 7;

    /**
     * @var string entity id attribute
     */
    public $entityIdAttribute = 'id';

    /**
     * @var array comment widget client options
     */
    public $clientOptions = [];

    /**
     * @var string
     */
    public $entity;

    /**
     * @var int
     */
    public $entityId;

    /**
     * @var bool
     *
     * Defines if the comment system uses pjax to insert / edit / delete the comments
     */
    public $pjax = FALSE;

    /**
     * @var bool
     *
     * Shows or hides the deleted comments in the widget. Also changes the default behavior
     * of the delete comment button.
     *
     * Warning: if it's set to false and the widget has nestedBehavior activated, the
     * answers to deleted comments won't be shown.
     */
    public $showDeletedComments = TRUE;


    /**
     * @var bool
     *
     * While true, the comments will have a nested behavior in which answers will be shown
     * in a nested way in the _list view. If not, all will have the same hierarchy level
     *
     */
    public $nestedBehavior = TRUE;

    /**
     * Initializes the object.
     * This method is invoked at the end of the constructor after the object is initialized with the
     * given configuration.
     */
    public function init()
    {
        if ($this->model === null && ($this->entity === null || $this->entityId === null))
        {
            throw new InvalidConfigException(Yii::t('app', 'The "model" property must be set.'));
        }
        $this->registerAssets();
    }

    /**
     * Register assets.
     */
    protected function registerAssets()
    {
        // If we have to hide the deleted comments, we will define the javascript
        // to destroy the comment instead of the default functionality.
        if ($this->showDeletedComments === FALSE)
        {
            $this->clientOptions['deleteComment'] = TRUE;
        }

        $view = $this->getView();
        $options = Json::encode($this->clientOptions);
        CommentAsset::register($view);
        $view->registerJs('jQuery.comment(' . $options . ');');
    }

    /**
     * Executes the widget.
     * @return string the result of widget execution to be outputted.
     */
    public function run()
    {
        /* @var $module Module */
        $module = Yii::$app->getModule(Module::$name);
        $commentModelData = $module->model('comment');

        //Create comment model
        /** @var CommentModel $commentModel */
        $commentModel = Yii::createObject($commentModelData);

        //Get entity from widget and hash it.
        if ($this->model)
        {
            $entityModel = $this->model;
            $this->entityId = $entityModel->{$this->entityIdAttribute};
            $this->entity = $commentModel::hashEntityClass($entityModel::className());
        }

        //Get comment tree by entity and entityId
        $comments = $commentModel::getTree($this->entity, $this->entityId, $this->showDeletedComments, $this->nestedBehavior, $this->maxLevel);

        // Add the basic data into the CommentModel in case we need it to
        // check for permissions
        $commentModel->entity = $this->entity;
        $commentModel->entityId = $this->entityId;


        //Encrypt entity and entityId values
        $encryptedEntity = urlencode(Yii::$app->getSecurity()->encryptByKey(Json::encode([
            'entity' => $this->entity,
            'entityId' => $this->entityId,
            'maxLevel' => $this->maxLevel,
            'entityIdAttribute' => $this->entityIdAttribute,
            'clientOptions' => $this->clientOptions,
            'pjax' => $this->pjax,
            'showDeletedComments' => $this->showDeletedComments
        ]), $module::$name));

        return $this->render('index', [
            'comments' => $comments,
            'commentModel' => $commentModel,
            'maxLevel' => $this->maxLevel,
            'encryptedEntity' => $encryptedEntity,
            'pjax' => $this->pjax
        ]);
    }

}