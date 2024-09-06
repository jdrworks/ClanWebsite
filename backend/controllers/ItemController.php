<?php

namespace backend\controllers;

use PHPHtmlParser\Dom;
use Yii;
use common\models\RunescapeItem;
use backend\models\RunescapeItemSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ItemController implements the CRUD actions for RunescapeItem model.
 */
class ItemController extends Controller
{
    const WIKI_BASE_URL = 'https://runescape.wiki/';

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
                        'roles' => ['readRunescapeItem'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['updateRunescapeItem'],
                    ],
                    [
                        'actions' => ['create'],
                        'allow' => true,
                        'roles' => ['createRunescapeItem'],
                    ],
                    [
                        'actions' => ['delete'],
                        'allow' => true,
                        'roles' => ['deleteRunescapeItem'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all RunescapeItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new RunescapeItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single RunescapeItem model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new RunescapeItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RunescapeItem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        Yii::$app->session->setFlash('warning', 'Be careful with item names. Jagex <em>usually</em> lower
        cases the item name, while upper casing the Nouns, such as "Armadyl chainskirt" or "gloves of subjugation".
        If you guess wrong on the name, it will cause duplicates when someone gets the "correct" drop. Code may also
        need to be adjusted to properly name the item to enable wiki compatibility.');

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RunescapeItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $newImageUrl = null;
            if (isset(Yii::$app->request->post()['itemImage'])) {
                $newImageUrl = Yii::$app->request->post()['itemImage'];
            } elseif (isset(Yii::$app->request->post()['manualItemImage'])) {
                $newImageUrl = Yii::$app->request->post()['manualItemImage'];
            }

            if ($newImageUrl) {
                $basePath = str_replace('backend', '', Yii::getAlias('@app'));
                $filename = $basePath . 'frontend/web/images/items/' . $model->urlName . '.png';

                file_put_contents($filename, file_get_contents($newImageUrl));
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        $wikiString = file_get_contents(self::WIKI_BASE_URL . $model->urlName);

        $dom = new Dom();
        $dom->loadStr($wikiString);
        $imageAnchors = [];
        $domImages = $dom->find('a.image');
        foreach ($domImages as $domImage) {
            foreach($domImage->find('img') as $image) {
                if (!empty($image->getAttribute('src'))) {
                    $imageAnchors[] = self::WIKI_BASE_URL . $image->getAttribute('src');
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'imageAnchors' => $imageAnchors,
        ]);
    }

    /**
     * Deletes an existing RunescapeItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the RunescapeItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RunescapeItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RunescapeItem::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
