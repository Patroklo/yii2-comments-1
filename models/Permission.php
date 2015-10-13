<?php

namespace yii2mod\comments\models;

/**
 * Class CommentPermission
 * @package yii2mod\comments\models
 */
class CommentPermission
{

    const CREATE = 'comments.create';
    const UPDATE = 'comments.update';
    const UPDATE_OWN = 'comments.update.own';
    const DELETE = 'comments.delete';
    const DELETE_OWN = 'comments.delete.own';
}