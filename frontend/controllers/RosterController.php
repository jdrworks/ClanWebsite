<?php

namespace frontend\controllers;

use common\models\RunescapeRank;
use Yii;
use common\models\RunescapeUser;
use common\models\RunescapeUserSearch;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RosterController implements the CRUD actions for RunescapeUser model.
 */
class RosterController extends Controller
{
    /**
     * Lists all RunescapeUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RunescapeUserSearch();
        $searchModel->in_clan = 1;
        $searchModel->old_name = 0;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $ranks = RunescapeRank::find()->all();

        $rankEnum = ArrayHelper::map($ranks, 'id', 'name');

        Yii::$app->session->setFlash('warning', 'If you see a <i class="fa fa-eye-slash text-danger"></i> by
        your name, or your status isn\'t updating, Please
        <a href="#" class="alert-link"  data-toggle="modal" data-target="#profilePrivateInfo">click here</a>
        for instructions on setting it to public and adusting event log settings.');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'rankEnum' => $rankEnum,
        ]);
    }

    /**
     * Displays a single RunescapeUser model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $dropLogs = new ActiveDataProvider([
           'query' => $model->getRunescapeDropLogs(),
           'pagination' => [
               'pageSize' => 20,
           ],
       ]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'dropLogs' => $dropLogs,
        ]);
    }

    /**
     * Finds the RunescapeUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RunescapeUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RunescapeUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
