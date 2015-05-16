<?php

namespace star\catalog\models;

use Yii;
use common\models\Tree;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%item}}".
 *
 * @property string $item_id
 * @property string $category_id
 * @property string $outer_id
 * @property string $title
 * @property string $stock
 * @property string $min_number
 * @property string $price
 * @property string $currency
 * @property string $props
 * @property string $props_name
 * @property string $desc
 * @property string $shipping_fee
 * @property integer $is_show
 * @property integer $is_promote
 * @property integer $is_new
 * @property integer $is_hot
 * @property integer $is_best
 * @property string $click_count
 * @property string $wish_count
 * @property integer $review_count
 * @property integer $deal_count
 * @property string $create_time
 * @property string $update_time
 * @property string $language
 * @property string $country
 * @property string $state
 * @property string $city
 *
 * @property ItemImg[] $itemImgs
 * @property PropImg[] $propImgs
 * @property Sku[] $skus
 */
class Item extends \yii\db\ActiveRecord
{
    public $images;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%item}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
//            [['category_id', 'title', 'stock', 'price', 'currency', 'props', 'props_name', 'desc', 'review_count', 'deal_count', 'create_time', 'update_time', 'language', 'country', 'state', 'city'], 'required'],
            [['category_id', 'stock', 'min_number', 'is_show', 'is_promote', 'is_new', 'is_hot', 'is_best', 'click_count', 'wish_count', 'review_count', 'deal_count', 'create_time', 'update_time', 'country', 'state', 'city'], 'integer'],
            [['price', 'shipping_fee'], 'number'],
            [['props', 'props_name', 'desc'], 'string'],
            [['outer_id', 'language'], 'string', 'max' => 45],
            [['title'], 'string', 'max' => 255],
            [['currency'], 'string', 'max' => 20],
        ];
    }

    public function behaviors()
    {
        return [
            'time' => [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'create_time',
                'updatedAtAttribute' => 'update_time',
            ]
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => Yii::t('catalog', 'Item ID'),
            'category_id' => Yii::t('catalog', '分类'),
            'outer_id' => Yii::t('catalog', 'Outer ID'),
            'title' => Yii::t('catalog', '名称'),
            'stock' => Yii::t('catalog', '库存'),
            'min_number' => Yii::t('catalog', '最少订货量'),
            'price' => Yii::t('catalog', '价格'),
            'currency' => Yii::t('catalog', '币种'),
            'props' => Yii::t('catalog', '商品属性 格式：pid:vid;pid:vid'),
            'props_name' => Yii::t('catalog', '商品属性名称。标识着props内容里面的pid和vid所对应的名称。格式为：pid1:vid1:pid_name1:vid_name1;pid2:vid2:pid_name2:vid_name2……(注：属性名称中的冒号\":\"被转换为：\"#cln#\"; 分号\";\"被转换为：\"#scln#\" )'),
            'desc' => Yii::t('catalog', '描述'),
            'shipping_fee' => Yii::t('catalog', '运费'),
            'is_show' => Yii::t('catalog', '是否显示'),
            'is_promote' => Yii::t('catalog', '是否促销'),
            'is_new' => Yii::t('catalog', '是否新品'),
            'is_hot' => Yii::t('catalog', '是否热销'),
            'is_best' => Yii::t('catalog', '是否精品'),
            'click_count' => Yii::t('catalog', '点击量'),
            'wish_count' => Yii::t('catalog', '收藏数'),
            'review_count' => Yii::t('catalog', 'Review Count'),
            'deal_count' => Yii::t('catalog', 'Deal Count'),
            'create_time' => Yii::t('catalog', '创建时间'),
            'update_time' => Yii::t('catalog', '更新时间'),
            'language' => Yii::t('catalog', '语言'),
            'country' => Yii::t('catalog', 'Country'),
            'state' => Yii::t('catalog', 'State'),
            'city' => Yii::t('catalog', 'City'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Tree::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getItemImgs()
    {
        return $this->hasMany(ItemImg::className(), ['item_id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPropImgs()
    {
        return $this->hasMany(PropImg::className(), ['item_id' => 'item_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSkus()
    {
        return $this->hasMany(Sku::className(), ['item_id' => 'item_id']);
    }


    public function loadUploadImages($model){
        $images = [];
        foreach ($_FILES[$model] as $key => $info) {
            foreach($info as $attributes => $v){
                foreach($v as $num=>$value){
                    $images[$attributes][$num][$key] = $value;
                }
            }
        }
        return $images;
    }

    public function saveImage($image){
        if(!in_array($image['type'],['image/jpeg','image/png','image/gif'])){
            $this->addError('images',Yii::t('catalog',$image['type'] .'Type is wrong'));
        }

        $imageName = time().$image['name'];
        $path = Yii::getAlias('@image');

        if (file_exists( $path.'/'. $imageName)){
            $this->addError('images',Yii::t('catalog','Image already exists.'));
        }
        if(!move_uploaded_file($image["tmp_name"],$path .'/'. $imageName)){
            $this->addError('images',Yii::t('catalog','Remove image fail.'));
        }

        return $path .'/'. $imageName;
    }



}