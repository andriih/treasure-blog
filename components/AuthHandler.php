<?php
namespace app\components;

use app\models\Auth;
use app\models\User;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
/**
 * AuthHandler handles successful authentication via Yii auth component
 */
class AuthHandler extends Controller
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
     $attributes = $this->client->getUserAttributes();
        //var_dump($attributes);die;
      
      $email = ArrayHelper::getValue($attributes, 'email');
      $id = ArrayHelper::getValue($attributes, 'id');
      $username = ArrayHelper::getValue($attributes, 'name');
     // var_dump($username);die;
      $photo = ArrayHelper::getValue($attributes,'cover');
      
      $user = new User();
      
      if($user->saveFromFb($email, $id, $username, $photo))
      {
          return $this->redirect(['site/index']);
      }
        

}}