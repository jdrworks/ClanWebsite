<?php

namespace backend\controllers;

use Yii;
use common\models\CappingRaffle;
use backend\models\CappingRaffleSearch;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CappingRaffleController implements the CRUD actions for CappingRaffle model.
 */
class CappingRaffleController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['readCappingRaffle'],
                    ],
                    [
                        'actions' => ['update', 'reset', 'paid'],
                        'allow' => true,
                        'roles' => ['updateCappingRaffle'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all CappingRaffle models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CappingRaffleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CappingRaffle model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $dataProvider = new ActiveDataProvider([
            'query' => $model->getRunescapeUsers(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);


        return $this->render('view', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing CappingRaffle model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionReset($id)
    {
        $model = $this->findModel($id);

        if ($model->pickRaffleWinner()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    public function actionPaid($id)
    {
        $model = $this->findModel($id);

        $model->paid = 1;

        if ($model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    /**
     * Finds the CappingRaffle model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CappingRaffle the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CappingRaffle::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
