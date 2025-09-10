<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models\HomepageHero;
use app\models\HomepageProduk;
use app\models\HomepageKeunggulan;
use app\models\HomepageTestimoni;

class HomepageController extends Controller
{
    public function actionEdit()
    {
        // HERO (ambil row pertama saja)
        $hero = HomepageHero::find()->one();
        if (!$hero) {
            $hero = new HomepageHero();
        }

        if ($hero->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($hero, 'background_image');
            if ($file) {
                $path = 'web/images/' . $file->baseName . '.' . $file->extension;
                $file->saveAs($path);
                $hero->background_image = 'images/' . $file->baseName . '.' . $file->extension;
            }
            $hero->save(false);
            Yii::$app->session->setFlash('success', 'Hero section updated!');
            return $this->refresh();
        }

        // PRODUK (untuk form tambah baru)
        $newProduk = new HomepageProduk();

        if ($newProduk->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($newProduk, 'image');
            if ($file) {
                $path = 'web/images/' . $file->baseName . '.' . $file->extension;
                $file->saveAs($path);
                $newProduk->image = 'images/' . $file->baseName . '.' . $file->extension;
            }
            $newProduk->save(false);
            Yii::$app->session->setFlash('success', 'Produk baru ditambahkan!');
            return $this->refresh();
        }

        // DATA untuk listing
        $produks = HomepageProduk::find()->all();
        $keunggulans = HomepageKeunggulan::find()->all();
        $testimonis = HomepageTestimoni::find()->all();

        // TAMBAH KEUNGGULAN
        $newKeunggulan = new HomepageKeunggulan();
        if ($newKeunggulan->load(Yii::$app->request->post()) && $newKeunggulan->save()) {
            Yii::$app->session->setFlash('success', 'Keunggulan baru ditambahkan!');
            return $this->refresh();
        }

        // TAMBAH TESTIMONI
        $newTestimoni = new HomepageTestimoni();
        if ($newTestimoni->load(Yii::$app->request->post()) && $newTestimoni->save()) {
            Yii::$app->session->setFlash('success', 'Testimoni baru ditambahkan!');
            return $this->refresh();
        }

        return $this->render('edit', [
            'hero' => $hero,
            'newProduk' => $newProduk,
            'produks' => $produks,
            'newKeunggulan' => $newKeunggulan,
            'keunggulans' => $keunggulans,
            'newTestimoni' => $newTestimoni,
            'testimonis' => $testimonis,
        ]);
    }
}
