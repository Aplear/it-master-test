<?php

use yii\db\Migration;

/**
 * Handles the creation of table `mail`.
 */
class m171025_125612_create_mail_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('mail', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'to' => $this->string(),
            'subject' => $this->string(),
            'text' => $this->text(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('mail');
    }
}
