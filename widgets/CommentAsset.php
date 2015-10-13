<?php

namespace yii2mod\comments\widgets;

use yii\web\AssetBundle;
use yii2mod\comments\Module;

/**
 * Class CommentAsset
 * @package yii2mod\comments
 */
class CommentAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/yii2mod/yii2-comments/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/comment.js'
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        'css/comment.css'
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset'
    ];


    public function init()
    {
        parent::init();

        /** @var Module $module */
        $module = \Yii::$app->getModule(Module::$name);
        $assetMap = $module->assetMap;

        $typeList = ['sourcePath', 'js', 'css', 'depends'];

        foreach ($typeList as $type)
        {
            if (array_key_exists($type, $assetMap))
            {
                $this->$type = $assetMap[$type];
            }
        }

    }
}