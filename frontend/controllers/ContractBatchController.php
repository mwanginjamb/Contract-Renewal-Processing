<?php

namespace frontend\controllers;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use app\models\Contracts;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\models\ContractBatch;
use app\models\DurationUnits;
use common\models\ExcelImport;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\models\ContractBatchSearch;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * ContractBatchController implements the CRUD actions for ContractBatch model.
 */
class ContractBatchController extends Controller
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
                        'view'
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
                            ],
                            'allow' => true,
                            'roles' => ['hr'],
                        ],
                    ],
                ]
            ]
        );
    }

    /**
     * Lists all ContractBatch models.
     *
     * @return string
     */
    public function actionIndex()
    {
        // $searchModel = new ContractBatchSearch();
        //  $dataProvider = $searchModel->search($this->request->queryParams);

        $batches = ContractBatch::find()->all();

        return $this->render('index', [
            'batches' => $batches
        ]);
    }

    /**
     * Displays a single ContractBatch model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $lineContracts = Contracts::find()->where(['contract_batch_id' => $id])->all();
        return $this->render('view', [
            'model' => $this->findModel($id),
            'contracts' => $lineContracts
        ]);
    }

    /**
     * Creates a new ContractBatch model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new ContractBatch();

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
     * Updates an existing ContractBatch model.
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
     * Deletes an existing ContractBatch model.
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
     * Finds the ContractBatch model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ContractBatch the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContractBatch::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    public function durationId($unit)
    {
        $unit = ucwords($unit);
        $result = DurationUnits::find()->select('id')->where(['unit' => $unit])->one();
        return $result->id ?? 0;
    }

    /** Excel import functions */

    public function actionExcelImport($batch)
    {
        $model = new ExcelImport();
        $model->batch = $batch;
        return $this->render('import', ['model' => $model]);
    }

    public function actionImport()
    {
        $model = new ExcelImport();
        if ($model->load(Yii::$app->request->post())) {
            $excelUpload = UploadedFile::getInstance($model, 'excel_doc');
            $model->excel_doc = $excelUpload;
            if ($uploadedFile = $model->upload()) {
                // Extract data from  uploaded file
                $sheetData = $this->extractData($uploadedFile);
                unlink($uploadedFile);
                // save the data
                $this->saveData($sheetData, $model->batch);
            } else {
                return $this->redirect(['excel-import']);
            }
        } else {
            return $this->redirect(['excel-import']);
        }
    }

    private function extractData($file)
    {
        $spreadsheet = IOFactory::load($file);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        return $sheetData;
    }

    private function saveData($sheetData, $batch)
    {
        // Yii::$app->utility->printrr($sheetData);
        $existingUnits = ArrayHelper::index(Contracts::find()->select(['contract_number'])->where(['contract_batch_id' => $batch])->AsArray()->all(), 'contract_number');
        $existing = array_unique(array_keys($existingUnits));

        $rowOffset = 1;

        $filteredArray = array_filter($sheetData, function ($item) use ($existing) {
            return !in_array($item['A'], $existing) && !is_null($item['A']);
        });

        // Check for batch number consistency
        if ($batch !== $filteredArray[1]['B']) {
            Yii::$app->session->setFlash('error', 'Your template Batch ID is not consistent with that of Batch No. (' . $batch . ')  whose contract batches you are importing.');
            return $this->redirect(Url::toRoute(['contract-batch/view', 'id' => $batch]));
        }

        $contractees = [];
        foreach ($filteredArray as $key => $data) {
            // Read from 3rd row
            if ($key > 3) {
                // Yii::$app->utility->printrr($filteredArray[$key]['A']);
                if (!is_null($data['A'])) {
                    /**
                     * Save the units
                     */
                    $contractees[] = [
                        'contract_batch_id' => $filteredArray[1]['B'],
                        'contract_number' => $data['A'],
                        'employee_name' => $data['B'],
                        'employee_number' => $data['C'],
                        'duration_unit' => $this->durationId($data['D']),
                        'contract_duration' => $data['E'],
                        'employee_workstation' => $data['F'],
                    ];

                }
            }

        }

        // Do a batch insert
        $insert = 0;
        if (count($contractees)) {
            $insert = Yii::$app->db->createCommand()->batchInsert(
                Contracts::tableName(),
                [
                    'contract_batch_id',
                    'contract_number',
                    'employee_name',
                    'employee_number',
                    'duration_unit',
                    'contract_duration',
                    'employee_workstation'
                ],
                $contractees
            )->execute();
            Yii::$app->session->setFlash('success', $insert . '-  contracts have been imported successfully. ');
        } else {
            if (count($existing)) {
                Yii::$app->session->setFlash('error', count($existing) . ' duplication conflicts were found on your import.');
            }
        }



        return $this->redirect(Url::toRoute(['view', 'id' => $batch]));
    }
    public function actionDownload($templateName)
    {
        // Define the path to the template file
        $templateFilePath = Yii::getAlias('@webroot/templates/' . $templateName);

        // Check if the file exists before proceeding
        if (file_exists($templateFilePath)) {
            // Set response headers for file download
            Yii::$app->response->sendFile($templateFilePath, 'template.xlsx', [
                'mimeType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            ]);
        } else {
            // Handle the case where the template file doesn't exist
            Yii::$app->session->setFlash('error', 'Template file not found.');
            // Redirect or display an error message
            Yii::$app->getResponse()->redirect(Yii::$app->request->referrer);
        }
    }
}
