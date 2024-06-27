<?php

namespace app\controllers;

use app\models\Trip;
use app\models\TripSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Query;
use yii\data\Pagination;

/**
 * TripController implements the CRUD actions for Trip model.
 */
class TripController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    //public function actions()
    //{
    //    return [
    //        'error' => [
    //            'class' => 'yii\web\ErrorAction',
    //        ],
    //    ];
    //}

    /**
     * Lists all Trip models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new TripSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Trip model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Trip model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Trip();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Trip model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Driver model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //$this->findModel($id)->delete();
        $this->findModel($id)->softDelete();        
        return $this->redirect(['index']);
    }    

    /**
     * Finds the Trip model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Trip the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Trip::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Canceles an existing Trip model.
     * If cancelling is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */    
    public function actionCancel($id)
    {
        $this->findModel($id)->Cancel();

        return $this->redirect(['index']);
    }

    /**
     * Ends an existing active Trip model.
     * If ends is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */    
    public function actionFinish($id)
    {
        $this->findModel($id)->Finish();

        return $this->redirect(['index']);
    }

    /**
     * Find all active Trips, end them, create new trips on schedule.
     * 
     * @return Trip the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionHandleTrips()
    {
        $model = new Trip();
        $model -> HandleTrips();
        return $this->redirect(['index']);
    }   
    
    public function actionGetReport($date1, $date2) {
        $model = new Trip();
        $data = $model->GetReport($date1, $date2);

        header('Content-type: text/csv');
        header('Content-Disposition: attachment; filename="trip_report_' . date('Ymd') . '_' . date('His') . '.csv"');
        echo iconv('utf-8', 'windows-1251', $data); 
        
    }
}
