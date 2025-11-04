<?php


namespace core\traits;

use core\entities\User\User;
use core\forms\manage\User\UserCreateForm;
use core\forms\manage\User\UserEditForm;
use Yii;
use yii\base\Exception;
use yii\web\Response;

trait UserControllerTrait
{

    /**
     * Creates a new User model.
     * @return mixed
     */
    public function actionCreateMinimal()
    {
        $form = new UserCreateForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()){
            try {
                $user = $this->service->createMinimal($form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create-minimal', ['model' => $form]);
    }

    /**
     * Updates an existing User model.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateMinimal($id)
    {
        $user = $this->findModel($id);

        $form = new UserEditForm($user);
        if ($form->load(Yii::$app->request->post()) && $form->validate()){
            try {
                $this->service->editMinimal($user->id, $form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update-minimal', ['model' => $form, 'user' => $user]);
    }

    /**
     * @param int $id
     * @param int $is_banned
     * @return Response
     * @throws Exception
     */
    public function actionBanned(int $id, int $is_banned): Response
    {
        try {

            $model = $this->findModel($id);
            $model->is_banned = $is_banned;
            $model->status = $is_banned === 1 ? User::STATUS_BANNED : User::STATUS_ACTIVE ;
            $model->save();

            return $this->redirect(['index']);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

}