<?php

namespace app\controllers;


use app\models\LoginForm;
use app\models\SignupForm;
use app\models\User;
use Yii;
use yii\web\Controller;


class AuthController extends Controller
{
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionSignup()
    {
        $model = new SignupForm();

        if(Yii::$app->request->isPost)
        {
            $model->load(Yii::$app->request->post());
            if($model->signup())
            {
                return $this->redirect(['auth/login']);
            }
        }
        
        return $this->render('signup', [ 'model' => $model ]);
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    public function actionAuthLoginFb($first_name,$id,$cover)
    {
        var_dump($first_name,$id,$cover);die;
    }

    public function actionTest()
    {
        $user = User::findOne(1);

        Yii::$app->user->login($user);
        //Yii::$app->user->logout($user);

        if (Yii::$app->user->isGuest) {
            echo 'Користувач НЕ авторизований!!!';
        } else {
            echo "Користувач авторизований";
        }

    }
}