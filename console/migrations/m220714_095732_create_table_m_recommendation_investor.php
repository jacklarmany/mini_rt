<?php

use yii\db\Migration;

class m220714_095732_create_table_m_recommendation_investor extends Migration
{
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            '{{%m_recommendation_investor}}',
            [
                'id' => $this->primaryKey()->comment('Primary key of table is auto increment'),
                'text' => $this->text()->comment('Details'),
                'title_id' => $this->integer()->unsigned()->notNull()->comment('Is foreign keys from relation table title'),
                'monitoring_id' => $this->integer()->notNull()->comment('Is foreign keys from relation table monitoring'),
            ],
            $tableOptions
        );

        $this->createIndex('fk_m_recommendation_investor_title_has_monitoring1_idx', '{{%m_recommendation_investor}}', ['title_id', 'monitoring_id']);
    }

    public function safeDown()
    {
        $this->dropTable('{{%m_recommendation_investor}}');
    }
}
