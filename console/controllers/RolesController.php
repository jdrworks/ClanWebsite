<?php

namespace console\controllers;

use common\models\User;
use Yii;
use yii\console\Exception;
use yii\console\Controller;

/**
 * Commands for manipulating user roles
 *
 * @package console\controllers
 */
class RolesController extends Controller
{

    /**
     * Assigns the specified role by name to the user by name
     *
     * @param string $rolename the name of the role to assign,
     * @param string $username the name of the user to assign the role to,
     *
     * @throws Exception if the role cannot be assigned to the user,
     *
     * @return int the status of the action execution. 0 means normal, other values mean abnormal.
     */
    public function actionAssignRole(string $rolename, string $username): int
    {
        $auth = Yii::$app->authManager;

        $role = $auth->getRole($rolename);

        if (empty($role)) {
            throw new Exception('No role could be found with name: ' . $rolename);
        }

        $user = User::find()->where(['username' => $username])->one();

        if (empty($user)) {
            throw new Exception('No user could be found with name: ' . $username);
        }

        try {
            $auth->assign($role, $user->getId());
        } catch (\Exception $e) {
            throw new Exception('Could not assign ' . $rolename . ' to ' . $username . ': ' . var_export($e, true));
        }

        return 0;
    }

    /**
     * Revokes the specified role by name from the user by name
     *
     * @param string $rolename the name of the role to revoke,
     * @param string $username the name of the user to revoke the role from,
     *
     * @throws Exception if the role cannot be revoked from the user,
     *
     * @return int the status of the action execution. 0 means normal, other values mean abnormal.
     */
    public function actionRevokeRole(string $rolename, string $username): int
    {
        $auth = Yii::$app->authManager;

        $role = $auth->getRole($rolename);

        if (empty($role)) {
            throw new Exception('No role could be found with name: ' . $rolename);
        }

        $user = User::find()->where(['username' => $username])->one();

        if (empty($user)) {
            throw new Exception('No user could be found with name: ' . $username);
        }

        try {
            $auth->revoke($role, $user->getId());
        } catch (\Exception $e) {
            throw new Exception('Could not assign ' . $rolename . ' to ' . $username . ': ' . var_export($e, true));
        }

        return 0;
    }

}
