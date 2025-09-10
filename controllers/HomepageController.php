<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
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

    public function actionUpdateProduk($id)
    {
        $model = HomepageProduk::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException("Produk tidak ditemukan.");
        }

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'image');
            if ($file) {
                $path = 'web/images/' . $file->baseName . '.' . $file->extension;
                $file->saveAs($path);
                $model->image = 'images/' . $file->baseName . '.' . $file->extension;
            }
            $model->save(false);
            Yii::$app->session->setFlash('success', 'Produk berhasil diupdate!');
            return $this->redirect(['edit']);
        }

        return $this->render('update_produk', ['model' => $model]);
    }

    public function actionDeleteProduk($id)
    {
        $model = HomepageProduk::findOne($id);
        if ($model) {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Produk berhasil dihapus!');
        }
        return $this->redirect(['edit']);
    }

    /* ================= KEUNGGULAN ================= */
    public function actionUpdateKeunggulan($id)
    {
        $model = HomepageKeunggulan::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException("Keunggulan tidak ditemukan.");
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Keunggulan berhasil diupdate!');
            return $this->redirect(['edit']);
        }

        return $this->render('update_keunggulan', ['model' => $model]);
    }

    public function actionDeleteKeunggulan($id)
    {
        $model = HomepageKeunggulan::findOne($id);
        if ($model) {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Keunggulan berhasil dihapus!');
        }
        return $this->redirect(['edit']);
    }

    /* ================= TESTIMONI ================= */
    public function actionUpdateTestimoni($id)
    {
        $model = HomepageTestimoni::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException("Testimoni tidak ditemukan.");
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Testimoni berhasil diupdate!');
            return $this->redirect(['edit']);
        }

        return $this->render('update_testimoni', ['model' => $model]);
    }

    public function actionDeleteTestimoni($id)
    {
        $model = HomepageTestimoni::findOne($id);
        if ($model) {
            $model->delete();
            Yii::$app->session->setFlash('success', 'Testimoni berhasil dihapus!');
        }
        return $this->redirect(['edit']);
    }
}
