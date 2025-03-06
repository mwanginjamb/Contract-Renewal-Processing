<?php

namespace frontend\controllers;

use app\models\ContractBatch;
use Yii;
use yii\web\Controller;
use app\models\Contracts;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use app\models\DurationUnits;
use yii\filters\AccessControl;
use app\models\ContractsSearch;
use app\models\WorkflowEntries;
use app\models\WorkflowTemplate;
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
                'access' => [
                    'class' => AccessControl::className(),
                    'only' => [
                        'index',
                        'create',
                        'update',
                        'delete',
                        'view',
                        'approvals',
                        'track-approval',
                        'cancel-approval',
                        'send-for-approval',
                        'approve'
                    ],
                    'rules' => [
                        [
                            'actions' => ['signup'],
                            'allow' => true,
                            'roles' => ['?'],
                        ],
                        [
                            'actions' => [
                                'index',
                                'create',
                                'update',
                                'delete',
                                'view',
                                'approvals',
                                'track-approval',
                                'cancel-approval',
                                'send-for-approval',
                                'approve'
                            ],
                            'allow' => true,
                            'roles' => ['@'],
                        ],
                    ],
                ]
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
        //$searchModel = new ContractsSearch();
        // $dataProvider = $searchModel->search($this->request->queryParams);
        $contracts = Contracts::find()->all();
        return $this->render('index', [
            'contracts' => $contracts
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
        if (!Yii::$app->utility->isValidSharepointLink($model->original_contract_path)) {
            $model->original_contract_path = null;
            $model->save();
        }
        return $this->render('view', [
            'model' => $model,
            'content' => ($model->original_contract_path && Yii::$app->utility->isValidSharepointLink($model->original_contract_path)) ? Yii::$app->sharepoint->getBinary($model->original_contract_path) : NULL,
            'signed_content' => ($model->signed_contract_path && Yii::$app->utility->isValidSharepointLink($model->signed_contract_path)) ? Yii::$app->sharepoint->getBinary($model->signed_contract_path) : NULL

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
        $durationUnits = ArrayHelper::map(DurationUnits::find()->all(), 'id', 'unit');
        $batches = ArrayHelper::map(ContractBatch::find()->all(), 'id', 'batch_description');

        return $this->render('create', [
            'model' => $model,
            'durationUnits' => $durationUnits,
            'content' => $model->original_contract_path ? Yii::$app->sharepoint->getBinary($model->original_contract_path) : NULL,
            'batches' => $batches,
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
            'content' => ($model->original_contract_path && Yii::$app->utility->isValidSharepointLink($model->original_contract_path)) ? Yii::$app->sharepoint->getBinary($model->original_contract_path) : NULL
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
                if (!Yii::$app->utility->isValidSharepointLink($parentDocument->original_contract_path)) {
                    $parentDocument->original_contract_path = NULL;
                    $parentDocument->save();
                }
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
        $contractModel = $this->findModel($contractId);
        // Use the default/first Workflow Template where workflow_name is not null
        $approvalTemplate = WorkflowTemplate::find()->where(['IS NOT', 'workflow_name', NULL])->orderBy(['id' => SORT_DESC])->one();
        if ($approvalTemplate) {
            // Get WorkflowTemplateMembers for this template
            $members = $approvalTemplate->workflowMembers;
            // if members exist create workflow entries for the subject contract
            if ($members) {
                foreach ($members as $member) {
                    $workflowEntry = new WorkflowEntries();
                    $workflowEntry->template_id = $approvalTemplate->id;
                    $workflowEntry->approver_id = $member->user_id;
                    $workflowEntry->sequence = $member->sequence;
                    $workflowEntry->contract_id = $contractId;
                    if ($member->sequence == 1) {
                        $workflowEntry->approval_status = 1; // Pending
                    } else {
                        $workflowEntry->approval_status = 4; // created
                    }


                    //  Yii::$app->utility->printrr($workflowEntry);
                    if ($workflowEntry->save()) {
                        // Update approval status of the contract model to pending status
                        $contractModel->approval_status = 1;
                        $contractModel->save();
                        Yii::$app->session->setFlash('success', 'Contract sent for approval successfully.');
                    } else {
                        //  Yii::$app->utility->printrr($workflowEntry);
                        Yii::$app->session->setFlash('error', 'Error sending contract for approval.');
                    }

                }
            } else {
                Yii::$app->session->setFlash('error', 'Error sending contract for approval: No Workflow Template Members Set.');
                return $this->redirect(['view', 'id' => $contractId]);
            }
        } else {
            Yii::$app->session->setFlash('error', 'Error sending contract for approval: No Approval Template Set.');
        }
        return $this->redirect(['view', 'id' => $contractId]);
    }

    public function actionCancelApproval($id)
    {
        $contractID = $id;
        // Delete all approval entries
        $deleteCount = WorkflowEntries::deleteAll(['contract_id' => $contractID]);
        if ($deleteCount) {
            // Update the contract status
            $contract = $this->findModel($id);
            $contract->approval_status = NULL;
            $contract->save();
            Yii::$app->session->setFlash('success', 'Contract approval request cancelled successfully.');
        }

        return $this->redirect(['view', 'id' => $id]);

    }

    // Track Approvals by showing related approval entries for a contract

    public function actionTrackApproval($id)
    {
        $contractID = $id;
        $contract = $this->findModel($id);
        if ($contract) {
            // Get Approval entries
            $approvalEntries = $contract->workflowEntries;
            return $this->render('entries', [
                'entries' => $approvalEntries
            ]);
        }
        Yii::$app->session->setFlash('error', 'Could not track approval status for subject contract.');
        return $this->goBack();
    }

    // Display contract Approval requests for a logged in approver
    public function actionApprovals()
    {
        $approvals = WorkflowEntries::find()->where(['approver_id' => Yii::$app->user->id, 'approval_status' => 1])->all();
        return $this->render('approvals', [
            'approvals' => $approvals
        ]);
    }

    // Approve a contract and push the workflow to next approver

    public function actionApprove($id)
    {
        $approvalEntryID = $id;
        $entry = WorkflowEntries::find()->where(['id' => $approvalEntryID])->one();
        $entry->approval_status = 2;
        if ($entry->save()) {
            // move to next sequence 
            $nextSequence = WorkflowEntries::find()->where(['contract_id' => $entry->contract_id])
                ->andWhere(['>', 'sequence', $entry->sequence])->one();
            if ($nextSequence) {
                $nextSequence->approval_status = 1;
                $nextSequence->save();
                Yii::$app->session->setFlash('success', 'contract has been approved.');
                return $this->redirect(['approvals']);
            } else {
                // mark contract as fully approved
                $contract = Contracts::find()->where(['id' => $entry->contract_id])->one();
                $contract->approval_status = 2;
                if ($contract->save()) {
                    Yii::$app->session->setFlash('success', 'contract has been fully signed and approved.');
                }
            }
            return $this->redirect(['approvals']);
        }

    }

    public function actionSign($id)
    {
        $contract = $this->findModel($id);
        $No = $contract->contract_number;
        $link = $contract->signed_contract_path ? $contract->signed_contract_path : $contract->original_contract_path;
        $title = basename($link);
        //sharepoint metadata
        $metadata = [
            'Application' => $No ?? '', // contract number
            'title' => $title,
            'path' => $link
        ];
        Yii::$app->session->set('metadata', $metadata);
        //Yii::$app->utility->printrr(Yii::$app->utility->isValidSharepointLink($link));
        // check validity of initial document
        if (Yii::$app->utility->isValidSharepointLink($link)) {
            $reference = $No . '_' . Yii::$app->security->generateRandomString(5);
            Yii::$app->session->set('reference', $reference);
            return $this->redirect(['./emsigner', 'metadata' => $metadata, 'reference' => $reference]);
        } else {
            Yii::$app->session->setFlash('error', 'The original contract document is not valid. Please contact the system administrator.. ');
            return $this->redirect(['view', 'id' => $No]);
        }
    }

    public function isMycontract()
    {
        /**
         * Check if the logged in user is the owner of the contract
         */
    }

    public function icansign()
    {
        /**
         * Check if the logged in user can sign the contract and is in the workflow
         */
    }


}
