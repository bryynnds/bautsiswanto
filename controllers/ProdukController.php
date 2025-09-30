<?php

namespace app\controllers;

use yii\web\Controller;
use app\models\HomepageProduk;

class ProdukController extends Controller
{
    public function actionIndex()
    {
        $produks = HomepageProduk::find()->all();

        return $this->render('index', [
            'produks' => $produks,
        ]);
    }
}
