<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use app\models\HomepageHero;
use app\models\HomepageProduk;
use app\models\KategoriProduk;
use app\models\JenisProduk;
use app\models\HomepageKeunggulan;
use app\models\HomepageTestimoni;
use yii\filters\AccessControl;
use app\models\HomepagePromo;
use app\models\User;

class HomepageController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            $user = Yii::$app->user->identity;
                            return $user instanceof User && $user->isAdmin();
                        }
                    ],
                ],
            ],
        ];
    }

    public function actionEdit()
    {
        $hero = HomepageHero::find()->one();
        if (!$hero) {
            $hero = new HomepageHero();
        }

        $produks = HomepageProduk::find()->all();
        $keunggulans = HomepageKeunggulan::find()->all();
        $testimonis = HomepageTestimoni::find()->all();
        $promos = HomepagePromo::find()->all();

        return $this->render('edit', [
            'hero' => $hero,
            'newProduk' => new HomepageProduk(),
            'produks' => $produks,
            'newKeunggulan' => new HomepageKeunggulan(),
            'keunggulans' => $keunggulans,
            'newTestimoni' => new HomepageTestimoni(),
            'testimonis' => $testimonis,
            'newPromo' => new HomepagePromo(),
            'promos' => $promos,
        ]);
    }

    public function actionEditHero()
    {
        $hero = HomepageHero::find()->one();
        if (!$hero) {
            $hero = new HomepageHero();
        }


        $oldImage = $hero->background_image;

        if ($hero->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($hero, 'background_image');

            if ($file) {

                $fileName = uniqid() . '-' . $file->baseName . '.' . $file->extension;
                $path = Yii::getAlias('@webroot') . '/images/' . $fileName;

                if ($file->saveAs($path)) {

                    if (!empty($oldImage)) {
                        $oldPath = Yii::getAlias('@webroot') . DIRECTORY_SEPARATOR . $oldImage;

                        if (file_exists($oldPath)) {
                            if (@unlink($oldPath)) {
                                Yii::debug("File lama berhasil dihapus: {$oldPath}", __METHOD__);
                            } else {
                                Yii::debug("Gagal menghapus file lama: {$oldPath}", __METHOD__);
                            }
                        } else {
                            Yii::debug("File lama tidak ditemukan di path: {$oldPath}", __METHOD__);
                        }
                    }


                    $hero->background_image = 'images/' . $fileName;
                }
            } else {

                $hero->background_image = $oldImage;
            }

            if ($hero->save()) {
                Yii::$app->session->setFlash('success', 'Slogan utama berhasil diperbarui!');
            }
        }

        return $this->redirect(['edit']);
    }

    public function actionCreatePromo()
    {
        $model = new HomepagePromo();

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload() && $model->save()) {
                Yii::$app->session->setFlash('success', 'Promo berhasil ditambahkan.');
                return $this->redirect(['edit']);
            }
        }

        Yii::$app->session->setFlash('error', 'Gagal menambahkan promo.');
        return $this->redirect(['edit']);
    }

    public function actionUpdatePromo($id)
    {
        $model = HomepagePromo::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException("Promo tidak ditemukan.");
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->imageFile = UploadedFile::getInstance($model, 'imageFile');
            if ($model->upload() && $model->save()) {
                Yii::$app->session->setFlash('success', 'Promo berhasil diperbarui.');
                return $this->redirect(['edit']);
            }
        }

        return $this->render('update_promo', [
            'model' => $model,
        ]);
    }

    public function actionDeletePromo($id)
    {
        $model = HomepagePromo::findOne($id);
        if ($model) {
            if ($model->image && file_exists(Yii::getAlias('@webroot') . '/' . $model->image)) {
                @unlink(Yii::getAlias('@webroot') . '/' . $model->image);
            }
            $model->delete();
            Yii::$app->session->setFlash('success', 'Promo berhasil dihapus.');
        }
        return $this->redirect(['edit']);
    }



    public function actionCreateKeunggulan()
    {
        $model = new HomepageKeunggulan();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Keunggulan baru ditambahkan!');
        }
        return $this->redirect(['edit']);
    }

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

    public function actionCreateTestimoni()
    {
        $model = new HomepageTestimoni();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Testimoni baru ditambahkan!');
        }
        return $this->redirect(['edit']);
    }


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

    public function actionCreateProduk()
    {
        $model = new HomepageProduk();

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'image');

            if ($file) {

                $fileName = time() . '-' . $file->baseName . '.' . $file->extension;
                $path = Yii::getAlias('@webroot') . '/images/' . $fileName;

                if ($file->saveAs($path)) {
                    $model->image = 'images/' . $fileName;
                }
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Produk baru berhasil ditambahkan!');
                return $this->redirect(['admin/produk']);
            } else {
                Yii::$app->session->setFlash('error', 'Gagal menyimpan produk.');
            }
        }

        return $this->render('tambah', [
            'model' => $model,
        ]);
    }

    public function actionUpdateProduk($id)
    {
        $model = HomepageProduk::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException("Produk tidak ditemukan.");
        }


        $oldImage = $model->image;

        if ($model->load(Yii::$app->request->post())) {
            $file = UploadedFile::getInstance($model, 'image');

            if ($file) {
                $fileName = time() . '-' . $file->baseName . '.' . $file->extension;
                $path = Yii::getAlias('@webroot') . '/images/' . $fileName;

                if ($file->saveAs($path)) {

                    if (!empty($oldImage) && file_exists(Yii::getAlias('@webroot') . '/' . $oldImage)) {
                        @unlink(Yii::getAlias('@webroot') . '/' . $oldImage);
                    }
                    $model->image = 'images/' . $fileName;
                }
            } else {

                $model->image = $oldImage;
            }

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Produk berhasil diupdate!');
            }
            return $this->redirect(['admin/produk']);
        }

        return $this->render('update_produk', ['model' => $model]);
    }



    public function actionDeleteProduk($id)
    {
        $model = HomepageProduk::findOne($id);

        if ($model) {

            // Hapus gambar
            if (!empty($model->image)) {

                $imagePath = Yii::getAlias('@webroot') . '/' . $model->image;

                if (file_exists($imagePath)) {
                    @unlink($imagePath);
                }
            }

            $model->delete();

            Yii::$app->session->setFlash(
                'success',
                'Produk berhasil dihapus!'
            );
        }

        return $this->redirect(['admin/produk']);
    }

    public function actionAdminProduk()
    {
        $produk = HomepageProduk::find()
            ->with(['kategori.jenis'])
            ->all();

        return $this->render('/admin/produk', [
            'produk' => $produk,
        ]);
    }

    public function actionCreateKategori()
    {
        $model = new \app\models\KategoriProduk();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            Yii::$app->session->setFlash(
                'success',
                'Kategori berhasil ditambahkan!'
            );

            return $this->redirect(['admin/produk']);
        }

        return $this->render('tambah_kategori', [
            'model' => $model,
        ]);
    }

    public function actionGetKategori($jenis_id)
    {
        $kategori = \app\models\KategoriProduk::find()
            ->where(['jenis_id' => $jenis_id])
            ->all();

        $output = '<option value="">Pilih kategori produk...</option>';

        foreach ($kategori as $item) {

            $output .= '<option value="' . $item->id . '">'
                . $item->nama_kategori .
                '</option>';
        }

        return $output;
    }
}
