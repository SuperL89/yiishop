<?php

namespace common\models;

//use Yii;
use mdm\admin\components\Configs;
use yii\db\Query;

/**
 * This is the model class for table "sp_category".
 *
 * @property integer $id
 * @property string $title
 * @property integer $parentid
 * @property integer $status
 * @property integer $order 
 */
class Category extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 1;
    const STATUS_ACTIVE = 0;
    public $parent_title;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%category}}';
    }
    
    /**
     * @inheritdoc
     */
    public static function getDb()
    {
        if (Configs::instance()->db !== null) {
            return Configs::instance()->db;
        } else {
            return parent::getDb();
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            //[['parent_title'], 'in','range' => static::find()->select(['title'])->column(),'message' => 'category "{value}" not found.'],
            [['parentid', 'status', 'order'], 'integer'],
            [['parentid'], 'filterParent', 'when' => function() {
                return !$this->isNewRecord;
            }],
            [['title'], 'string', 'max' => 50],
            [['image_url'], 'string', 'max' => 255],
        ];
    }
    
    /**
     * Use to loop detected.
     */
    public function filterParent()
    {
        $parentid = $this->parentid;
        $db = static::getDb();
        $query = (new Query)->select(['parentid'])
        ->from(static::tableName())
        ->where('[[id]]=:id');
        while ($parentid) {
            if ($this->id == $parentid) {
                $this->addError('parent_title', 'Loop detected.');
                return;
            }
            $parentid = $query->params([':id' => $parentid])->scalar($db);
        }
    }
    
    /**
     * Get Category parent
     * @return \yii\db\ActiveQuery
     */
    public function getCategoryParent()
    {
        return $this->hasOne(Category::className(), ['id' => 'parentid']);
    }
    
    /**
     * Get Category children
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasMany(Category::className(), ['parentid' => 'id']);
    }
    
    public static function getCategorySource()
    {
        $tableName = static::tableName();
        return (new \yii\db\Query())
        ->select(['m.id', 'm.title','parent_title' => 'p.title'])
        ->from(['m' => $tableName])
        ->leftJoin(['p' => $tableName], '[[m.parentid]]=[[p.id]]')
        ->all(static::getDb());
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '分类名称',
            'parent_title' => '父类名称',
            'parentid' => '父级分类',
            'status' => '状态',
            'order' => '排序',
        ];
    }
    public static function allStatus()
    {
        return [self::STATUS_ACTIVE=>'正常',self::STATUS_DELETED=>'已禁用'];
    }
    /**
     * 获得用户状态并转为中文显示
     */
    public function getStatusStr()
    {
        return $this->status==self::STATUS_ACTIVE?'正常':'已禁用';
    }
    
    
    public $select_head = [
        ["id"=>0, "title"=>"请选择"],
        ["id"=>0, "title"=>"请选择一级类"],
        ["id"=>0, "title"=>"请选择二级类"],
        ["id"=>0, "title"=>"请选择三级类"],
    ];
    
    /**
     * 获取子列表
     */
    public static function getChildrenList($pid, $level)
    {
        $x[] = (new static)->select_head[0];
        switch ($level) {
            case 1:
                return array_merge($x,static::findAll(['parentid'=>$pid]));
                break;
            default:
                if ($pid) {
                    return array_merge($x,static::findAll(['parentid'=>$pid]));
                } else {
                    return array_merge($x,array());
                }
                break;
        }
    }
}
