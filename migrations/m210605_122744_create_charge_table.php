<?php

use yii\db\Migration;

/**
 * Handles the creation of table `charge`.
 */
class m210605_122744_create_charge_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('charge', [
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->text()->null(),
            'amount' => $this->decimal(13, 3)->notNull(),
            'date' => $this->date()->notNull(),
            'user_category_id' => $this->integer()->unsigned()->notNull()
        ]);

        $this->createIndex(
            'idx-charge-user_category_id',
            'charge',
            'user_category_id'
        );

        $this->addForeignKey(
            'fk-charge-user_category_id',
            'charge',
            'user_category_id',
            'user_category',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-charge-user_category_id',
            'charge'
        );

        $this->dropIndex(
            'idx-charge-user_category_id',
            'charge'
        );

        $this->dropTable('charge');
    }
}
