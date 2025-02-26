<?php

namespace frontend\controllers;

use app\models\WorkflowEntries;
use app\models\WorkflowTemplate;
use Yii;
use yii\web\Controller;
use app\models\Contracts;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use app\models\DurationUnits;
use app\models\ContractsSearch;
use yii\web\NotFoundHttpException;

/**
 * ContractsController implements the CRUD actions for Contracts model.
 */
class ContractsController extends Controller
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

    public function beforeAction($action)
    {
        $ExceptedActions = [
            'upload',
        ];

        if (in_array($action->id, $ExceptedActions)) {
            $this->enableCsrfValidation = false;
        }
        return parent::beforeAction($action);
    }

    /**
     * Lists all Contracts models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ContractsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contracts model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
            'content' => $model->original_contract_path ? Yii::$app->sharepoint->getBinary($model->original_contract_path) : NULL
        ]);
    }

    /**
     * Creates a new Contracts model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Contracts();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }
        $durationUnits = ArrayHelper::map(DurationUnits::find()->all(), 'id', 'unit'); // DurationUnits::find()->all()

        return $this->render('create', [
            'model' => $model,
            'durationUnits' => $durationUnits,
            'content' => $model->original_contract_path ? Yii::$app->sharepoint->getBinary($model->original_contract_path) : NULL
        ]);
    }

    /**
     * Updates an existing Contracts model.
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

        $durationUnits = ArrayHelper::map(DurationUnits::find()->all(), 'id', 'unit'); // DurationUnits::find()->all()

        return $this->render('update', [
            'model' => $model,
            'durationUnits' => $durationUnits,
            'content' => $model->original_contract_path ? Yii::$app->sharepoint->getBinary($model->original_contract_path) : NULL
        ]);
    }

    /**
     * Deletes an existing Contracts model.
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
     * Finds the Contracts model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Contracts the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contracts::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionUpload()
    {
        $targetPath = '';
        if ($_FILES) {
            $uploadedFile = $_FILES['attachment']['name'];
            list($pref, $ext) = explode('.', $uploadedFile);
            $targetPath = './uploads/' . Yii::$app->utility->processPath($pref) . '.' . $ext; // Create unique target upload path
            $attachmentName = Yii::$app->utility->processPath($pref) . '.' . $ext;
            // Create upload directory if it dnt exist.
            if (!is_dir(dirname($targetPath))) {
                FileHelper::createDirectory(dirname($targetPath));
                chmod(dirname($targetPath), 0755);
            }
        }

        // Upload
        if (Yii::$app->request->isPost) {
            $parentDocument = $this->findModel(Yii::$app->request->post('Key'));
            $parentDocumentNo = NULL;
            if (is_object($parentDocument)) {
                $parentDocumentNo = $parentDocument->contract_number;
            }
            //  \Yii::$app->utility->printrr($parentDocumentNo);



            // Create a directory to store doc related to this proposal
            $folder = Yii::$app->sharepoint->createFolder($parentDocumentNo);


            $metadata = [];
            if (is_object($parentDocument) && isset($parentDocument->id)) {
                $metadata = [
                    'Application' => $parentDocument->contract_number,
                    'Employee' => $parentDocument->employee_number,
                    'Station' => $parentDocument->employee_workstation
                ];
            }
            Yii::$app->session->set('metadata', $metadata);


            $file = $_FILES['attachment']['tmp_name'];
            $binary = file_get_contents($file);

            //Return JSON
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ($binary) {
                // Upload to sharepoint

                $spResult = Yii::$app->sharepoint->attach_toLibrary(env('SP_LIBRARY') . '\\' . $parentDocumentNo, $binary, $attachmentName, $metadata = [], TRUE);
                Yii::$app->session->set('SP_PATH', $spResult);
                $parentDocument->original_contract_path = $spResult;
                $parentDocument->save();

                return [
                    'status' => 'success',
                    'message' => 'File Uploaded Successfully. :- ' . $spResult,
                    'filePath' => $targetPath
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Could not upload file at the moment.',
                    'error' => "Error uploading the file: " . Yii::$app->params['phpFileUploadErrors'][$_FILES["attachment"]["error"]],
                ];
            }
        }


    }


    public function actionSendForApproval($id)
    {
        $contractId = $id;
        // Use the default/first Workflow Template where workflow_name is not null
        $approvalTemplate = WorkflowTemplate::find()->where(['IS NOT', 'workflow_name', NULL])->orderBy(['id' => SORT_DESC])->one();
        if ($approvalTemplate) {
            // Get WorkflowTemplateMembers for this template
            $members = $approvalTemplate->workflowMembers;
            // if members exist create workflow entries for the subject contract
            //Yii::$app->utility->printrr($members);
            if ($members) {
                foreach ($members as $member) {
                    $workflowEntry = new WorkflowEntries();
                    $workflowEntry->template_id = $approvalTemplate->id;
                    $workflowEntry->approver_id = $member->approver_id;
                    $workflowEntry->contract_id = $contractId;
                    $workflowEntry->approvalStatus = 1;


                    if ($workflowEntry->save()) {
                        Yii::$app->session->setFlash('success', 'Contract sent for approval successfully.');
                    } else {
                        Yii::$app->session->setFlash('error', 'Error sending contract for approval.');
                    }

                }
            }
        } else {
            Yii::$app->session->setFlash('error', 'Error sending contract for approval: No Approval Template Set.');
        }
        return $this->redirect(['view', 'id' => $contractId]);
    }
}
