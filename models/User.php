<?php
namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $isAdmin
 * @property string $photo
 *
 * @property Auth[] $auths
 * @property Comment[] $comments
 */
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['isAdmin'], 'integer'],
            [['name', 'email', 'password', 'photo'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'isAdmin' => 'Is Admin',
            'photo' => 'Photo',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAuths()
    {
        return $this->hasMany(Auth::className(), ['user_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['user_id' => 'id']);
    }


    public static function findIdentity($id)
    {
        return User::findOne($id);
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {

    }


    public function getId()
    {
        return $this->id;
    }


    public function getAuthKey()
    {

    }

    public function validateAuthKey($authKey)
    {

    }

    public static function findByEmail($email)
    {
        return User::find()->where(['email'=>$email])->one();
    }

    public function validatePassword($password)
    {
        return ($this->password == $password) ? true : false ;
    }

    public function create()
    {
        return $this->save(false);
    }
    
    public function saveFromFb($email, $id , $username, $photo)
    {
        $user = User::findIdentity((int)$id);
        
        //var_dump($user) ;die;
        
        if($user)
        {
            return Yii::$app->user->login($user);
        }
        else
        {
            $this->id = $id;
            $this->name = $username;
            $this->photo = $photo;
            $this->email = $email;

            $this->create();

            return Yii::$app->user->login($this);
        }
    }
}
