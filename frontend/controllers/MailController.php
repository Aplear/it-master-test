<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Mail;
use app\models\SearchMail;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * MailController implements the CRUD actions for Mail model.
 */
class MailController extends InitController
{


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'delete', 'show', 'mail-delete', 'in-box'],
                'rules' => [
                    [
                        'actions' => ['index', 'create', 'delete', 'show', 'mail-delete', 'in-box'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Method delete mail
     * @param $id
     * @return Response
     */
    public function actionMailDelete($id)
    {
        $this->mailbox->deleteMail($id);
        return $this->redirect('index');
    }

    /**
     * Method get mail
     * @param $id
     * @return string
     */
    public function actionShow($id)
    {
        $mailContent = $this->mailbox->getMail($id);
        return $this->render('show',[
            'mailContent'=>$mailContent,
        ]);
    }

    /**
     * Method get mails
     * @return string
     */
    public function actionInBox()
    {
        // Read all messaged into an array:
        $mailsIds = $this->mailbox->searchMailbox('ALL');
        // Get the first message and save its attachment(s) to disk:
        $mails = $this->mailbox->getMailsInfo($mailsIds);

        return $this->render('in-box',[
            'mails'=>$mails,
        ]);

    }

    /**
     * Lists all Mail models.
     * @return mixed
     */
    public function actionIndex()
    {


        $searchModel = new SearchMail();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Mail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws Exception
     */
    public function actionCreate()
    {
        $model = new Mail();
        if (Yii::$app->request->isAjax) {
            if($model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($model);
            }
            return $this->renderAjax('create', [
                'model' => $model,
            ]);

        }else {
            if ($model->load(Yii::$app->request->post())) {
                $model->user_id = Yii::$app->user->identity->getId();
                if($model->validate() && $model->sendMail()) {
                    $model->save();
                    return $this->redirect(['index']);
                }

            }
            throw new Exception('Data invalid');
        }

    }

    /**
     * Deletes an existing Mail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Mail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Mail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Mail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
