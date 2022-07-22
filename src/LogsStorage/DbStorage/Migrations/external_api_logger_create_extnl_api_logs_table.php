<?php

namespace Brezgalov\ExtApiLogger\LogsStorage\DbStorage\Migrations;

use yii\db\Migration;

/**
 * Handles the creation of table `{{%extnl_api_logs}}`.
 *
 * @codeCoverageIgnore
 */
class external_api_logger_create_extnl_api_logs_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%extnl_api_logs}}', [
            'activity_id' => $this->string()->notNull(),
            'request_group' => $this->string(),
            'request_id' => $this->string(),
            'method' => $this->string()->notNull()->defaultValue('GET'),
            'url' => $this->string()->notNull(),
            'request_params' => $this->text(),
            'request_time' => $this->dateTime()->notNull(),
            'response_status_code' => $this->integer(),
            'response_content' => $this->text(),
            'response_time' => $this->dateTime(),
            'called_from_controller' => $this->string(),
            'called_from_action' => $this->string(),
            'called_by_user' => $this->integer(),
        ]);

        $this->addPrimaryKey(
            'extnl_api_logs_PK_activity_id',
            '{{%extnl_api_logs}}',
            'activity_id'
        );

        $this->createIndex(
            'extnl_api_logs_IDX_request_group',
            '{{%extnl_api_logs}}',
            'request_group'
        );

        $this->createIndex(
            'extnl_api_logs_IDX_request_id',
            '{{%extnl_api_logs}}',
            'request_id'
        );

        $this->createIndex(
            'extnl_api_logs_IDX_called_from_controller',
            '{{%extnl_api_logs}}',
            'called_from_controller'
        );

        $this->createIndex(
            'extnl_api_logs_IDX_called_from_action',
            '{{%extnl_api_logs}}',
            'called_from_action'
        );

        $this->createIndex(
            'extnl_api_logs_IDX_called_by_user',
            '{{%extnl_api_logs}}',
            'called_by_user'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('extnl_api_logs_IDX_request_group', '{{%extnl_api_logs}}');
        $this->dropIndex('extnl_api_logs_IDX_request_id', '{{%extnl_api_logs}}');
        $this->dropIndex('extnl_api_logs_IDX_called_from_controller', '{{%extnl_api_logs}}');
        $this->dropIndex('extnl_api_logs_IDX_called_from_action', '{{%extnl_api_logs}}');
        $this->dropIndex('extnl_api_logs_IDX_called_by_user', '{{%extnl_api_logs}}');
        $this->dropIndex('extnl_api_logs_IDX_called_by_user', '{{%extnl_api_logs}}');

        $this->dropTable('{{%extnl_api_logs}}');
    }
}
