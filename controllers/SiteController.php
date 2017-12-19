<?php

namespace app\controllers;


use app\models\Article;
use app\models\Category;
use app\models\CommentForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\SignupForm;
use app\models\ContactForm;
use yii\data\Pagination;
use yii\helpers\Url;


use app\components\AuthHandler;




class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }
    
    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {

        $data       =   Article::getAll(1);
        $popular    =   Article::getPopular();
        $recent     =   Article::getRecent();
        $categories =   Category::getAll();
       

        return $this->render('index',[
            'articles'   => $data['articles'],
            'pagination' => $data['pagination'],
            'popular'    => $popular,
            'recent'     => $recent,
            'categories' => $categories
            
        ]);
    }

    public function actionView($id)
    {
        $article = Article::findOne($id);
        $popular    =   Article::getPopular();
        $recent     =   Article::getRecent();
        $categories =   Category::getAll();
        $comments = $article->comments;
        $commentForm = new CommentForm();

        return $this->render('single',[
            'article' => $article,
            'popular'    => $popular,
            'recent'     => $recent,
            'categories' => $categories,
            'comments'   => $comments,
            'commentForm' => $commentForm
        ]);
    }

    public function actionCategory($id)
    {
        $data = Category::getArticlesByCategory($id);
        $popular = Article::getPopular();
        $recent     = Article::getRecent();
        $categories = Category::getAll();

        return $this->render('category',[
            'articles'=>$data['articles'],
            'pagination'=>$data['pagination'],
            'popular'=>$popular,
            'recent'=>$recent,
            'categories'=>$categories
        ]);
    }

    
    /**
     * Login action.
     *
     * @return Response|string
     */
    // public function actionLogin()
    // {

    //     if (!Yii::$app->user->isGuest) {
    //         return $this->goHome();
    //     }

    //     $model = new LoginForm();
    //     if ($model->load(Yii::$app->request->post()) && $model->login()) {
    //         return $this->goBack();
    //     }
    //     return $this->render('login', [
    //         'model' => $model,
    //     ]);
    // }

    // public function actionSignup()
    // {
    //     $model = new SignupForm();

    //     return $this->render('signup',['model'=>$model]);
    // }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
