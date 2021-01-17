<?php

use yii\db\Migration;

/**
 * Class m210116_134337_insert_default_users
 */
class m210116_134337_insert_default_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('user', [
            'username' => 'admin',
            'password_hash' => Yii::$app->security->generatePasswordHash('admin'),
            'auth_key' => '',
            'email' => '',
            'created_at' => new DateTime(),
            'updated_at' => new DateTime(),]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {

    }
}
