<?php

namespace frontend\modules\document;

use Yii;

/**
 * document module definition class
 */
class Document extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'frontend\modules\document\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        Yii::configure($this, require __DIR__ . '/config/config.php');
    }
}
