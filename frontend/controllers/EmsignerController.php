<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\helpers\FileHelper;
use yii\filters\AccessControl;
use yii\helpers\Url;

class EmsignerController extends Controller
{


    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => [

                    'index',
                    'success',
                    'error',
                    'decrypt',
                    'data',
                    'cancel',
                    'error'

                ],
                'rules' => [
                    [
                        'actions' => ['error', 'index'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => [
                            'index',
                            'success',
                            'decrypt',
                            'data',
                            'cancel',
                            'error'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {

        $ExceptedActions = [
            'success',
            'error',
            'cancel',
            'create'
        ];

        if (in_array($action->id, $ExceptedActions)) {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }


    public function actionIndex()
    {
        $reference_no = Yii::$app->session->get('reference');
        Yii::$app->session->set('SIGNING_SESSION', $reference_no);
        $this->createFiles($reference_no);

        $file = "./jar/Final_ED_Both.jar";
        $arg1 = "Encrypt";
        $arg2 = "./data/" . $reference_no . "/json.txt";
        $arg3 = "./data/" . $reference_no . "/session_key.txt";
        $arg4 = "./data/" . $reference_no . "/encrypted_sessionkey.txt";
        $arg5 = "./data/" . $reference_no . "/encrypted_json_data.txt";
        $arg6 = "./data/" . $reference_no . "/encrypted_hashof_json_data.txt";
        $arg7 = "./certs/certificate.cer";
        $theFiles = array($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7);
        $result = exec("java -jar $file " . implode(' ', $theFiles) . ' 2>&1', $output1, $ret);

        if ($ret === 0) {
            $msg = "JAR file executed successfully:\n" . implode("\n", $output1);
        } else {
            $msg = "Error executing JAR file:\n" . implode("\n", $output1);
        }
        Yii::$app->utility->log($msg, 'Encrypt-' . $reference_no);

        if ($result) {
            // Go to gateway form
            return $this->redirect(['create']);
        }
    }

    public function actionCreate()
    {
        return $this->renderAjax('create');
    }

    public function actionDecrypt($reference = false, $sessionRef)
    {

        $working_session = Yii::$app->session->get('SIGNING_SESSION');

        $sessionLog = 'Passed session ref: ' . $sessionRef . ' || Working session variable: ' . $working_session;
        Yii::$app->utility->logResult($sessionLog, 'SessionTracking-' . $reference);

        if ($reference && !is_null($sessionRef)) {
            $folder = "./data/" . $reference . "/";
            $sessionFolder = "./data/" . $sessionRef . "/";
            $file = "./jar/Final_ED_Both.jar";

            $Decrypted_Signed_Data_file = fopen($folder . "Decrypted_Signed_Data.txt", "w");
            $txt = ""; //should just be empty
            fwrite($Decrypted_Signed_Data_file, $txt);
            fclose($Decrypted_Signed_Data_file);

            $arg1 = "Decrypt";
            $arg2 = $folder . "Encrypted_Signed_Data.txt";
            $arg3 = $sessionFolder . "session_key.txt";
            $arg4 = $sessionFolder . "Decrypted_Signed_Data.txt";
            $theFiles = array($arg1, $arg2, $arg3, $arg4);
            $decrypt = exec("java -jar $file " . implode(' ', $theFiles) . ' 2>&1', $output1, $ret);

            $logName = 'decrypt-' . $reference;
            if ($ret === 0) {
                $msg = "Signed file decrypted successfully:\n" . implode("\n", $output1);
            } else {
                $msg = "Error decrypting signed file:\n" . implode("\n", $output1);
            }
            Yii::$app->utility->logResult($msg, $logName);
        } else {
            $logName = 'decrypt-error-' . $reference;
            Yii::$app->utility->logResult('Decryption Error: ', $logName);
            Yii::$app->session->setFlash('error', 'Signature Session Error, try signing again.');
        }
    }


    public function createFiles($reference)
    {
        $fileDir = "./data/" . $reference . "/";
        if (!is_dir($fileDir)) {
            $result = FileHelper::createDirectory($fileDir, 0755, true);
        }
        $this->generateFile($fileDir, 'json.txt', $this->actionData());
        $this->generateFile($fileDir, 'session_key.txt', $reference);
        $this->generateFile($fileDir, 'encrypted_sessionkey.txt');
        $this->generateFile($fileDir, 'encrypted_json_data.txt');
        $this->generateFile($fileDir, 'encrypted_hashof_json_data.txt');
        //$this->generateFile($fileDir, 'Decrypted_Signed_Data.txt');
        // $this->generateFile($fileDir, 'Encrypted_Signed_Data.txt');
    }

    public function generateFile($path, $name, $content = "")
    {
        $filename = $path . $name;
        $message_dump = print_r($content, TRUE);
        $fp = fopen($filename, 'a');
        fwrite($fp, $message_dump);
        fclose($fp);
    }


    public function read($title, $link)
    {
        $list = env('SP_LIST');
        if ($link) {
            $parts = explode("/", $link);
            $list = $parts[6];
        }
        $result = Yii::$app->recruitment->Read($list, $title);
        //returns base64 from sharepoint integration component
        return is_string($result) ? $result : false;
    }

    public function actionData($path = '')
    {
        $type = Yii::$app->session->get('Document');
        if (Yii::$app->session->has('binary')) {
            // if signed document is not available pull it initially from report codeunit
            $binary = Yii::$app->session->get('binary');
            $base64 = $binary;
            Yii::$app->session->set('signer', 'Initial Signer (Signer one)');

            // use metadata session to check if there is existing signed document
            $metadata = Yii::$app->session->get('metadata');
            $service = $metadata['Service'];

            // distinguish between procurement docs and other documents
            if (in_array($type, Yii::$app->params['attachmentSignage'])) {
                $filter = [
                    'Attachment_No' => $metadata['Application'],
                    'Doc_No' => Yii::$app->session->get('metadata')['Doc_No'],
                ];
            } elseif ($type == 'Salary_Voucher') {
                $filter = [
                    'No' => $metadata['Application']
                ];
            } else {

                $filter = [
                    'Code' => $metadata['Application']
                ];
            }
            $document = Yii::$app->navhelper->findOne($service, $filter);
            if (is_object($document) && property_exists($document, 'Signed_Document') && $document->Signed_Document) {
                Yii::$app->session->set('signer', 'Cosigner (Other Signer)');
                $base64 = $this->read(basename($document->Signed_Document), $document->Signed_Document);
            }
        } else {
            Yii::$app->session->set('signer', 'Default Path... Demo Mode!');
            $binary = file_get_contents($path);
            $base64 = base64_encode($binary);
        }
        $AuthToken = env('ESIGNATURE_TOKEN');
        $data = [
            "SignerID" => Yii::$app->user->id,
            "Name" => Yii::$app->user->identity->{'Employee Name'},
            "FileType" => "PDF",
            "SignatureType" => 2,
            "SelectPage" => "PAGE LEVEL",
            "SignaturePosition" => "Top-Left",
            "AuthToken" => $AuthToken,
            "SignatureType" => 4,
            "File" => $base64,
            "PageNumber" => null,
            "PreviewRequired" => true,
            "PagelevelCoordinates" => null,
            "CustomizeCoordinates" => null,
            "SUrl" => \yii\helpers\Url::home(true) . "emsigner/success",
            "FUrl" => \yii\helpers\Url::home(true) . "emsigner/error",
            "CUrl" => \yii\helpers\Url::home(true) . "emsigner/cancel",
            "ReferenceNumber" => Yii::$app->security->generateRandomString(16),
            "Enableuploadsignature" => true,
            "Enablefontsignature" => true,
            "EnableDrawSignature" => true,
            "EnableeSignaturePad" => true,
            "IsCompressed" => false,
            "IsCosign" => true,
            "PagelevelCoordinates" => '1,418,784,538,724',
            "EnableViewDocumentLink " => true,
            "Storetodb" => true,
            "IsGSTN" => true,
            "IsGSTN3B" => false,
            "IsCustomized" => true,
            "TimeZoneID" => "E. Africa Standard Time",
            "SignatureType" => 0,
            "SignatureMode" => 3
        ];

        $json = json_encode($data);
        $name = 'json-' . Yii::$app->session->get('reference');
        Yii::$app->utility->logResult($json, $name);

        // Incorporate document no on logs names
        $type = Yii::$app->session->get('Document');
        $metadata = Yii::$app->session->get('metadata');
        $No = null;
        if (in_array($type, Yii::$app->params['attachmentSignage'])) {
            $No = Yii::$app->session->get('metadata')['Doc_No'];
        } else {
            $No = $metadata['Application'];
        }

        $No = str_replace('/', '', $No);
        //remove colons
        $document = str_replace(':', '', $No);
        $label = str_replace('_', '', $document);

        Yii::$app->utility->logResult($json, Yii::$app->user->identity->{'Employee No_'} . ' - ' . $label . ' - ' . $name);
        return $json;
    }



    public function actionSuccess()
    {
        //$data = file_get_contents('php://input');
        //$jsonParams = json_decode($data);
        $post = Yii::$app->request->post();
        $sessionRef = Yii::$app->session->get('reference');
        $type = Yii::$app->session->get('Document');

        //$sessionsLog = print_r($_SESSION, TRUE);
        Yii::$app->utility->log($post, Yii::$app->user->identity->{'Employee No_'} . ' - ' . 'Signature Response Payload -' . $sessionRef);

        try {
            if ($post['ReturnStatus'] == 'Success') {
                $reference = $post['Referencenumber']; // comes from signing gateway response
                $signedData = $post['Returnvalue'];
                $file = "./data/" . $reference . '/Encrypted_Signed_Data.txt';
                if (!is_dir(dirname($file))) {
                    $result = FileHelper::createDirectory(dirname($file), 0755, true);
                }
                $fh = fopen($file, "w");
                fwrite($fh, $signedData);
                fclose($fh);

                $decrypt = $this->actionDecrypt($reference, $sessionRef);
                Yii::$app->utility->log($decrypt, Yii::$app->user->identity->{'Employee No_'} . ' - ' . 'decryption result - ' . $reference);


                // get decrypted data
                $signedDataFolder = "./data/" . $sessionRef . "/";
                $decryptedDataFile = $signedDataFolder . 'Decrypted_Signed_Data.txt';
                $signedContent = file_get_contents($decryptedDataFile);
                Yii::$app->utility->logResult($post, Yii::$app->user->identity->{'Employee No_'} . ' -' . 'decryption-content-' . $reference);

                // send the signed file to sharepoint
                if (Yii::$app->session->has('metadata') && !in_array($type, Yii::$app->params['attachmentSignage'])) {
                    $fileName = 'signed-' . Yii::$app->session->get('metadata')['Application'] . '-' . $sessionRef . '.pdf';
                    // remove any forward slash
                    $fileName = str_replace('/', '', $fileName);
                    //remove colons
                    $fileName = str_replace(':', '', $fileName);

                    $binary = base64_decode($signedContent);
                    $spResult = Yii::$app->recruitment->attach_binary($binary, Yii::$app->params['sp']['Signed'], $fileName);
                    //Yii::$app->utility->printrr($spResult);

                    // Update subject Page on NAV
                    $service = Yii::$app->session->get('metadata')['Service'];
                    $filter = [
                        'Code' => Yii::$app->session->get('metadata')['Application']
                    ];

                    if ($type == 'Salary_Voucher') {
                        $filter = [
                            'No' => Yii::$app->session->get('metadata')['Application']
                        ];
                    }
                    $document = Yii::$app->navhelper->findOne($service, $filter);
                    if (is_object($document)) {
                        // Update the Signed Document Path
                        $data = [
                            'Signed_Document' => $spResult,
                            'Key' => $document->Key
                        ];

                        $result = Yii::$app->navhelper->updateData($service, $data);
                        Yii::$app->utility->logResult($result, Yii::$app->user->identity->{'Employee No_'}, ' - ' . 'ERP-UPDATE ' . $reference);
                        $this->acknowledge($document);
                        //Yii::$app->utility->printrr($result);
                    } else {
                        Yii::$app->utility->printrr($document);
                    }
                } else { // other none procurement attachments
                    $fileName = 'signed-' . Yii::$app->session->get('metadata')['title'] . '-' . $sessionRef . '.pdf';
                    // remove any forward slash
                    $fileName = str_replace('/', '', $fileName);
                    //remove colons
                    $fileName = str_replace(':', '', $fileName);

                    $binary = base64_decode($signedContent);
                    $spResult = Yii::$app->recruitment->attach_binary($binary, Yii::$app->params['sp']['Signed'], $fileName);
                    //Yii::$app->utility->printrr($spResult);

                    // Update subject Page on NAV
                    $service = Yii::$app->session->get('metadata')['Service'];
                    $filter = [
                        'Attachment_No' => Yii::$app->session->get('metadata')['Application'],
                        'Doc_No' => Yii::$app->session->get('metadata')['Doc_No'],
                    ];
                    $document = Yii::$app->navhelper->findOne($service, $filter);
                    if (is_object($document)) {
                        // Update the Signed Document Path
                        $data = [
                            'Document_Link' => $spResult,
                            'Key' => $document->Key
                        ];

                        $update = Yii::$app->navhelper->updateData($service, $data);
                        if (is_object($update)) {
                            Yii::$app->session->setFlash('success', 'Document Signed Successfully.');
                        }

                        Yii::$app->utility->logResult($result, Yii::$app->user->identity->{'Employee No_'} . ' - ' . 'ERP-UPDATE-non-proc ' . $reference);
                        //Yii::$app->utility->printrr($result);
                    } else {
                        Yii::$app->utility->printrr($document);
                    }
                }
            } // end success block

        } catch (\Exception $e) {
            Yii::$app->utility->logResult($post, Yii::$app->user->identity->{'Employee No_'} . ' - ' . 'Emudhra-Exception');
            Yii::$app->session->setFlash('error', 'Signature Session expired. Login again and attempt signing again.');
            return $this->redirect(['./site/index']);
            //echo $e->getMessage();
            // exit;
        }



        return $this->render('success', [
            'data' => [], // $jsonParams,
            'post' => $post,
            'sessionRef' => $sessionRef,
            'content' => $signedContent
        ]);
    }

    //Function to show a user has signed minutes opening

    public function acknowledge($document)
    {

        if (Yii::$app->session->has('Document')) {
            $type = Yii::$app->session->get('Document');
            $committee = null;

            if ($type == 'QuoteOpening') {
                $committee = 'Opening';
            } elseif ($type == 'QuotationAnalysis') {
                $committee = 'Analysis';
            }

            if (is_null($committee)) {
                return false;
            }
            $quoteNo = $document->Quote_No;
            $Committee_Type = $committee;
            $Committte_No = Yii::$app->user->identity->{'Employee No_'};

            $params = [
                'Quote_No' => $quoteNo,
                'Committee_Type' => $Committee_Type,
                'Committte_No' => $Committte_No
            ];

            $result = Yii::$app->navhelper->findOne('OpeningCommittee', $params);
            if (is_object($result)) {
                $data = [
                    'Key' => $result->Key,
                    'Signed' => TRUE
                ];

                $sign = Yii::$app->navhelper->updateData('OpeningCommittee', $data);
                if (!is_string($sign)) {
                    \Yii::$app->session->setFlash('success', 'You have successfully signed this document, please proceed and approve.');
                }
            }
        }
    }

    public function actionError()
    {
        $data = file_get_contents('php://input');
        $jsonParams = json_decode($data);
        $postParams = Yii::$app->request->post();
        $data = [$jsonParams, $postParams];
        Yii::$app->utility->logResult($jsonParams);
        return $this->render('error', [
            'data' => $data
        ]);
    }


    public function actionCancel()
    {
        $data = file_get_contents('php://input');
        $jsonParams = json_decode($data);
        $postParams = Yii::$app->request->post();
        $data = [$jsonParams, $postParams];
        Yii::$app->utility->logResult($jsonParams);
        return $this->render('cancel', [
            'data' => $data
        ]);
    }

    public function actionApproveRequest($app, $empNo, $docType = "")
    {
        $service = Yii::$app->params['ServiceName']['HRPortal'];

        $data = [
            'docNo' => $app,
            'approverNo' => $empNo
        ];

        $result = Yii::$app->navhelper->codeunit($service, $data, 'FnApproveDocument');

        if (!is_string($result)) {
            Yii::$app->session->setFlash('success', 'Request Approved Successfully.', true);
            return $this->redirect(Url::toRoute(['approvals/index']));
        } else {
            Yii::$app->session->setFlash('error', 'Error Approving Request.  : ' . $result);
            return $this->redirect(Url::toRoute(['approvals/index']));
        }
    }

    public function actionApprove()
    {
        $service = Yii::$app->params['ServiceName']['HRPortal'];
        $Commentservice = Yii::$app->params['ServiceName']['ApprovalCommentLine'];

        if (Yii::$app->request->post()) {
            $comment = Yii::$app->request->post('comment');
            $documentno = Yii::$app->request->post('documentNo');
            $Approver_No = Yii::$app->request->post('Approver_No');
            $Table_ID = Yii::$app->request->post('Table_ID');
            $Approval_Entry_No = Yii::$app->request->post('Approval_Entry_No');
            $commentData = [
                'Comment' => $comment,
                'Document_No' => $documentno,
                'Approver_No' => $Approver_No
            ];

            $data = [
                'docNo' => $documentno,
                'approverNo' => $Approver_No
            ];
            //save comment
            $Commentrequest = Yii::$app->navhelper->postData($Commentservice, $commentData);
            // Call approve cunit function
            if (is_string($Commentrequest)) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['note' => '<div class="alert alert-danger">Error Approving Request: ' . $Commentrequest . '</div>'];
            }

            $result = Yii::$app->navhelper->codeunit($service, $data, 'FnApproveDocument');

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (!is_string($result)) {
                return ['note' => '<div class="alert alert-success">Request Approved Successfully. </div>'];
            } else {
                return ['note' => '<div class="alert alert-danger">Error Approving Request: ' . $result . '</div>'];
            }
        }
    }
    public function actionRejectRequest($docType = "")
    {
        $service = Yii::$app->params['ServiceName']['HRPortal'];
        $Commentservice = Yii::$app->params['ServiceName']['ApprovalCommentsWeb'];

        if (Yii::$app->request->post()) {
            $comment = Yii::$app->request->post('comment');
            $documentno = Yii::$app->request->post('documentNo');
            $Approver_No = Yii::$app->request->post('Approver_No');
            $Table_ID = Yii::$app->request->post('Table_ID');
            $Approval_Entry_No = Yii::$app->request->post('Approval_Entry_No');
            $commentData = [
                'Comment' => $comment,
                'Document_No' => $documentno,
                'Approval_Entry_No' => $Approval_Entry_No,
                'Table_ID' => $Table_ID
            ];

            $data = [
                'docNo' => $documentno,
                'approverNo' => $Approver_No
            ];
            //save comment
            $Commentrequest = Yii::$app->navhelper->postData($Commentservice, $commentData);
            // Call rejection cu function
            if (is_string($Commentrequest)) {
                Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['note' => '<div class="alert alert-danger">Error Rejecting Request: ' . $Commentrequest . '</div>'];
            }

            $result = Yii::$app->navhelper->codeunit($service, $data, 'FnRejectDocument');

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

            if (!is_string($result)) {
                return ['note' => '<div class="alert alert-success">Request Rejected Successfully. </div>'];
            } else {
                return ['note' => '<div class="alert alert-danger">Error Rejecting Request: ' . $result . '</div>'];
            }
        }
    }
}
