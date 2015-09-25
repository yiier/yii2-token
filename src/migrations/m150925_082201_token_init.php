<?php

use yii\db\Migration;

class m150925_082201_token_init extends Migration
{
    /**
     * @var string
     */
    protected $tableOptions;
    public $tokenTable = '{{%token}}';
    public $tokenLogTable = '{{%token_log}}';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        switch (Yii::$app->db->driverName) {
            case 'mysql':
                $this->tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
                break;
        }
    }

    public function up()
    {
        $this->createTable($this->tokenTable, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'username' => $this->string()->notNull(),
            'value' => $this->string()->notNull(),
            'ip' => $this->bigInteger()->notNull(),
            'status' => $this->smallInteger()->defaultValue(10),
            'expires_in' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'expired_at' => $this->integer()->notNull(),
        ]);
        $this->createIndex('value_expired_at', $this->tokenTable, ['value', 'expired_at']);
        $this->createIndex('user_id', $this->tokenTable, 'user_id');

        $this->createTable($this->tokenLogTable, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'username' => $this->string()->notNull(),
            'token_id' => $this->integer()->notNull(),
            'token_value' => $this->string()->notNull(),
            'url' => $this->string()->notNull(),
            'ip' => $this->bigInteger()->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);
        $this->createIndex('username', $this->tokenLogTable, 'username');
        $this->createIndex('token_value', $this->tokenLogTable, 'token_value');
    }

    public function down()
    {
        echo "m150925_082201_init_token_table cannot be reverted.\n";
        $this->dropTable($this->tokenTable);
        $this->dropTable($this->tokenLogTable);
        return false;
    }
}
