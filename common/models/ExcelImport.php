<?php

/**
 * Created by PhpStorm.
 * User: HP ELITEBOOK 840 G5
 * Date: 11/16/2021
 * Time: 12:04 PM
 */

namespace common\models;

use Yii;
use yii\base\Model;

class ExcelImport extends Model
{
    public $excel_doc;
    public $batch;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['batch', 'integer'],
            ['excel_doc', 'required'],
            [['excel_doc'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls, xlsx'],
            [['excel_doc'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls, xlsx', 'mimeTypes' => 'application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'message' => 'Only Spreadsheet documents are allowed.'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'excel_doc' => 'Excel Template',

        ];
    }

    public function upload()
    {
        $upload_dir = env('IMPORTS_DIR');
        if ($this->validate()) {
            Yii::$app->session->set('tempName', Yii::$app->security->generateRandomString(8));
            $placeholderName = Yii::$app->session->get('tempName');
            $uploadPath = $upload_dir . '\\' . $placeholderName . '.' . $this->excel_doc->extension;
            Yii::$app->utility->createDir($uploadPath); // if upload dir is missing create it please.
            // Upload file here
            $this->excel_doc->saveAs($uploadPath);

            return $uploadPath;
        } else {


            $this->addError('excel_doc', $this->errors['excel_doc'][0]);
            Yii::$app->session->setFlash('error', $this->errors['excel_doc'][0]);
        }
    }
}
