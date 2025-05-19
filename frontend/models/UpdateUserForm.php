<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class UpdateUserForm extends Model
{
    public $username;
    public $email;
    public $password;

    public $password_confirm;
    public $staff_id_number;

    public $previousEmail;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            //['username', 'required'],
            // ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            // ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['staff_id_number', 'integer', 'min' => 10000, 'max' => 99999],
            ['staff_id_number', 'required'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     */
    public function updateDetails()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = User::findIdentity(Yii::$app->user->id);
        // $user->username = $this->username;
        // $user->email = $this->email;
        $user->staff_id_number = $this->staff_id_number;

        $adminEmails = ['Chesiro.Tungwony@usamru-k.org', 'fnmwangi@kemri.go.ke', 'Stephen.Nyutu@usamru-k.org', 'fnjambi@outlook.com'];

        if ($user->save()) {
            Yii::$app->session->setFlash('info', 'Details updated successfully.');
            // downgrade role except for know System Administrators - zero trust
            $auth = Yii::$app->authManager;
            $userRole = $auth->getRole('officer');
            $adminRole = $auth->getRole('admin');

            // Revoke User Roles
            $auth->removeAllAssignments($user->id);

            if ($userRole && $adminRole) {
                if (in_array($this->email, $adminEmails)) {
                    $auth->assign($adminRole, $user->id);
                } else {
                    $auth->assign($userRole, $user->id);
                }
            }
            return $this->sendEmail($user);
        }

        return false;
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailUpdate-html'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account Details Updated at ' . Yii::$app->name)
            ->send();
    }
}
