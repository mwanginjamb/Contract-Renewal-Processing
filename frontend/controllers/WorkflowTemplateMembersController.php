<?php

namespace frontend\controllers;

use Yii;
use app\models\User;
use yii\helpers\Url;
use yii\web\Response;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\bootstrap5\ActiveForm;
use yii\web\NotFoundHttpException;
use app\models\WorkflowTemplateMembers;
use app\models\WorkflowTemplateMembersSearch;

/**
 * WorkflowTemplateMembersController implements the CRUD actions for WorkflowTemplateMembers model.
 */
class WorkflowTemplateMembersController extends Controller
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

    /**
     * Lists all WorkflowTemplateMembers models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new WorkflowTemplateMembersSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WorkflowTemplateMembers model.
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
     * Creates a new WorkflowTemplateMembers model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new WorkflowTemplateMembers();
        $users = ArrayHelper::map(User::find()->all(), 'id', 'username');

        // If it's an AJAX validation request
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($this->request->isPost && $model->load($this->request->post())) {
            $user = User::findOne(['id' => $this->request->post()['WorkflowTemplateMembers']['approver_name']]);

            $model->workflow_id = Yii::$app->request->get('workflow_template_id');
            $model->user_id = $user->id;
            $model->approver_name = $user->username;
            $model->approver_email = $user->email;
            if ($model->save()) {
                return $this->redirect(Url::toRoute(['workflow-template/view', 'id' => $model->workflow_id]));
            }
        } else {
            $model->loadDefaultValues();
        }

        // This is for the initial GET request to load the form
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                'model' => $model,
                'users' => $users
            ]);
        }

        return $this->render('create', [
            'model' => $model,
            'users' => $users
        ]);
    }

    /**
     * Updates an existing WorkflowTemplateMembers model.
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
     * Deletes an existing WorkflowTemplateMembers model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the WorkflowTemplateMembers model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return WorkflowTemplateMembers the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WorkflowTemplateMembers::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
