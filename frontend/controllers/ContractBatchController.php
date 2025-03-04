<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\models\ContractBatch;
use common\models\ExcelImport;
use yii\web\NotFoundHttpException;
use app\models\ContractBatchSearch;

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
        return $this->render('view', [
            'model' => $this->findModel($id),
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

        $existingUnits = ArrayHelper::index(Unit::find()->select(['id'])->where(['batch' => $batch])->AsArray()->all(), 'id');
        $existing = array_unique(array_keys($existingUnits));

        $rowOffset = 1;

        $filteredArray = array_filter($sheetData, function ($item) use ($existing) {
            return !in_array($item['A'], $existing) && !is_null($item['A']);
        });

        // Check for property consistency
        if ($batch !== $filteredArray[1]['B']) {
            Yii::$app->session->setFlash('error', 'Your template property id is not consistent with that of property (' . $batch . ')  whose units you are importing.');
            return $this->redirect(Url::toRoute(['property/view', 'id' => $batch]));
        }

        foreach ($filteredArray as $key => $data) {
            // Read from 3rd row
            if ($key > 3) {
                // Yii::$app->utility->printrr($filteredArray[$key]['A']);
                if (!is_null($data['A'])) {
                    /**
                     * Save the units
                     */
                    $model = new Unit();
                    $model->unit_name = $data['A'] ?? '';
                    $model->batch = $filteredArray[1]['B'];


                    if (!$model->save()) {
                        foreach ($model->errors as $k => $v) {
                            Yii::error('Unit Commit error: ' . $v[0] . ' <b>Got value</b>: <i><u>' . $model->$k . '</u> <b>for Unit:' . $data['A'] . '</b> - On Row:</b>  ' . ($key - $rowOffset) . '  ' . $data['A'], 'dbinfo');
                            Yii::$app->session->setFlash('error', $v[0] . ' <b>Got value</b>: <i><u>' . $model->$k . '</u> <b>for Unit:' . $data['C'] . '</b> - On Row:</b>  ' . ($key - $rowOffset) . '  ' . $data['A']);
                        }
                    } else {
                        // Save tenants
                        $tenant = new Tenant();
                        $tenant->principle_tenant_name = $data['B'] ?? '';
                        $tenant->billing_email_address = str_replace(' ', '', $data['C']) ?? '';
                        $tenant->agreed_rent_payable = $data['D'] ?? 0;
                        $tenant->agreed_water_rate = $data['E'] ?? 0;
                        $tenant->service_charge = $data['F'] ?? 0;
                        $tenant->house_number = $model->id;
                        $tenant->cell_number = $data['G'] ?? '';
                        if (!$tenant->save()) {
                            foreach ($tenant->errors as $k => $v) {
                                Yii::error('Tenant saving error: ' . $v[0] . ' <b>Got value</b>: <i><u>' . $tenant->$k . '</u>', 'dbinfo');
                            }
                        } else {
                            Yii::info('Imported tenant' . VarDumper::dumpAsString($tenant), 'dbinfo');
                        }
                        Yii::$app->session->setFlash('success', 'Congratulations, all valid records are completely imported into the system.');
                    }
                }
            }
            if (count($existing)) {
                Yii::$app->session->setFlash('error', count($existing) . ' duplication conflicts were found on your import.');
            }
        }


        return $this->redirect(Url::toRoute(['property/view', 'id' => $model->batch]));
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
