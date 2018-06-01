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
            'provider' => $this->string(20)->defaultValue(null)->comment('授权提供商'),
            'user_id' => $this->integer()->notNull(),
            'username' => $this->string(120)->defaultValue(null),
            'value' => $this->string(120)->notNull(),
            'ip' => $this->string(120)->notNull()->comment('ip'),
            'status' => $this->tinyInteger(1)->defaultValue(10)->comment('状态 10 正常 0删除'),
            'expires_in' => $this->integer()->notNull()->comment('有效时间，单位秒'),
            'created_at' => $this->integer()->unsigned()->defaultValue(null),
            'updated_at' => $this->integer()->unsigned()->defaultValue(null),
            'expired_at' => $this->integer()->unsigned()->defaultValue(null)->comment('过期时间戳'),
        ]);
        $this->createIndex('value_expired_at', $this->tokenTable, ['value', 'expired_at']);
        $this->createIndex('user_id', $this->tokenTable, 'user_id');

        $this->createTable($this->tokenLogTable, [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'username' => $this->string(120)->defaultValue(null),
            'token_id' => $this->integer()->notNull(),
            'token_value' => $this->string(120)->notNull(),
            'method' => $this->string(10)->comment('请求类型'),
            'url' => $this->string()->notNull(),
            'ip' => $this->string(120)->notNull()->comment('ip'),
            'created_at' => $this->integer()->unsigned()->defaultValue(null),
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
