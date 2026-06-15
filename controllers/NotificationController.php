<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\Pagination;

use app\models\Notification;

class NotificationController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['index', 'read-all'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],

            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'read-all' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $query = Notification::find()
            ->where([
                'user_id' => Yii::$app->user->id
            ])
            ->orderBy(['id' => SORT_DESC]);

        $totalNotifications = $query->count();

        $pages = new Pagination([
            'totalCount' => $query->count(),
            'pageSize' => 5,
        ]);

        $notifications = $query
            ->offset($pages->offset)
            ->limit($pages->limit)
            ->all();

        return $this->render('/notification/index', [
            'notifications' => $notifications,
            'pages' => $pages,
            'totalNotifications' => $totalNotifications,
        ]);
    }

    public function actionReadAll()
    {
        Yii::$app->response->format =
            \yii\web\Response::FORMAT_JSON;

        Notification::updateAll(
            ['is_read' => 1],
            [
                'user_id' => Yii::$app->user->id,
                'is_read' => 0
            ]
        );

        return [
            'success' => true
        ];
    }
}