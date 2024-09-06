<?php

namespace backend\controllers;

use Carbon\Carbon;
use Codeception\Command\Run;
use common\models\RunescapeRank;
use common\models\RunescapeUser;
use common\models\RunescapeUsername;
use common\models\RunescapeUserNote;
use common\models\RunescapeUserSearch;
use backend\models\RunescapeUserReportSearch;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
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
                        'roles' => ['readRunescapeUser'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['updateRunescapeUser'],
                    ],
                    [
                        'actions' => ['report'],
                        'allow' => true,
                        'roles' => ['readRunescapeUserReport'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all RunescapeUser models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RunescapeUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $ranks = RunescapeRank::find()->all();

        $rankEnum = ArrayHelper::map($ranks, 'id', 'name');

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
        $oldUsernames = new ActiveDataProvider([
           'query' => $model->getRunescapeUserNames(),
           'pagination' => [
               'pageSize' => 20,
           ],
       ]);

        $dropLogs = new ActiveDataProvider([
           'query' => $model->getRunescapeDropLogs(),
           'pagination' => [
               'pageSize' => 20,
           ],
       ]);

        $cappingRaffles = new ActiveDataProvider([
            'query' => $model->getCappingRaffles(),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        $notes = RunescapeUserNote::find()->where(['user_id' => $model->id])->orderBy('created_at DESC')->all();
        $newNote = new RunescapeUserNote();
//var_dump(Yii::$app->user);
        if ($newNote->load(Yii::$app->request->post())) {
            $newNote->user_id = $model->id;
            $newNote->creator_id = Yii::$app->user->id;
            $newNote->created_at = Carbon::now()->toDateTimeString();

            if ($newNote->save()) {
                Yii::$app->session->setFlash('success', 'Note has been successfully added.');
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                Yii::$app->session->setFlash('danger', 'Note could not be added. Please try again.');
            }
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'oldUsernames' => $oldUsernames,
            'dropLogs' => $dropLogs,
            'cappingRaffles' => $cappingRaffles,
            'notes' => $notes,
            'newNote' => $newNote,
        ]);
    }

    /**
     * Updates an existing RunescapeUser model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_names = RunescapeUser::findAll(['old_name' => 1]);

        if ($model->load(Yii::$app->request->post())) {
            if (!empty($model->old_user_id)) {
                $oldUser = RunescapeUser::findOne($model->old_user_id);
                $model->assignOldUsername($oldUser);
            }

            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'old_names' => ArrayHelper::map($old_names, 'id', 'username'),
        ]);
    }

    /**
     * Lists all RunescapeUser models who need rank adjustments.
     * @return mixed
     */
    public function actionReport()
    {
        $searchModel = new RunescapeUserReportSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $ranks = RunescapeRank::find()->all();

        $rankEnum = ArrayHelper::map($ranks, 'id', 'name');

        return $this->render('report', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rankEnum' => $rankEnum,
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
