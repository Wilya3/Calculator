<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_category`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `category`
 */
class m210529_112734_create_junction_table_for_user_and_category_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_category', [
            'id' => $this->primaryKey()->unsigned(),
            'user_id' => $this->integer()->unsigned(),
            'category_id' => $this->integer()->unsigned()
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-user_category-user_id}}',
            '{{%user_category}}',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            '{{%fk-user_category-user_id}}',
            '{{%user_category}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `category_id`
        $this->createIndex(
            '{{%idx-user_category-category_id}}',
            '{{%user_category}}',
            'category_id'
        );

        // add foreign key for table `category`
        $this->addForeignKey(
            '{{%fk-user_category-category_id}}',
            '{{%user_category}}',
            'category_id',
            '{{%category}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-user_category-user_id}}',
            '{{%user_category}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-user_category-user_id}}',
            '{{%user_category}}'
        );

        // drops foreign key for table `{{%category}}`
        $this->dropForeignKey(
            '{{%fk-user_category-category_id}}',
            '{{%user_category}}'
        );

        // drops index for column `category_id`
        $this->dropIndex(
            '{{%idx-user_category-category_id}}',
            '{{%user_category}}'
        );

        $this->dropTable('{{%user_category}}');
    }
}
