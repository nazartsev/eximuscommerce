<?php
/**
 * User controller
 */
class DefaultController extends SAdminController
{ 

	/**
	 * Display users list
	 */
	public function actionIndex()
	{
		$model = new User('search');

		if (!empty($_GET['User']))
			$model->attributes = $_GET['User'];

		$dataProvider = $model->search();
		$dataProvider->pagination->pageSize = Yii::app()->params['adminPageSize'];

		$this->render('list', array(
			'model'=>$model,
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Create new user
	 */
	public function actionCreate()
	{
		$this->actionUpdate(true);
	}

	/**
	 * Create/update user
	 * @param boolean $new
	 */
	public function actionUpdate($new = false)
	{
		if ($new === true)
			$model = new User;
		else
			$model = User::model()->findByPk($_GET['id']);

		if (!$model)
			throw new CHttpException(400, 'Bad request.');

		$form = new SAdminForm('application.modules.users.views.admin.default.userForm', $model);

		if (Yii::app()->request->isPostRequest)
		{
			$model->attributes = $_POST['User'];

			if($model->validate())
			{
				$model->save();
				$this->setFlashMessage(Yii::t('UsersModule.core', 'Изменения успешно сохранены'));

				if (isset($_POST['REDIRECT']))
					$this->smartRedirect($model);
				else
					$this->redirect(array('index'));
			}
		}

		$this->render('update', array(
			'model'=>$model,
			'form'=>$form,
		));
	}

	/**
	 * Delete user by Pk
	 */
	public function actionDelete()
	{
		if (Yii::app()->request->isPostRequest)
		{
			$model = User::model()->findByPk($_GET['id']);

			if ($model && ($model->id != Yii::app()->user->id))
				$model->delete();

			if (!Yii::app()->request->isAjaxRequest)
				$this->redirect('index');
		}
	}

}
