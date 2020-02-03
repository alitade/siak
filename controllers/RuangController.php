<?php

namespace app\controllers;

use Yii;
use app\models\Ruang;
use app\models\RuangSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class RuangController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    
    public function actionIndex()
    {
        return $this -> render('index', C1Controller::actionD113()) ;
    
    }

    
    public function actionView($id){

        $model = Ruang::findOne($id);
        return $this->render('/ruang/view', [
            'model' => $model,

        ]);
    
    }

    
    public function actionCreate()
    {
        return C5Controller::actionD113($id);
    }

    public function actionUpdate($id){

        return C2Controller::actionD113($id);
    
    }

    public function actionDelete($id){

        return C4Controller::actionD113($id); 

    }
}
