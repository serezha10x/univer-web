<?php

namespace backend\modules\document;

use Yii;

/**
 * document module definition class
 */
class Document extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\document\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Yii::configure($this, require __DIR__ . '/config/config.php');
    }
}
