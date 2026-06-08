<?php

namespace app\controllers;

use yii\web\Controller;
use Yii;
use app\models\HomepageProduk;
use app\models\JenisProduk;
use app\models\KategoriProduk;

class ProdukController extends Controller
{
    public function actionIndex()
    {
        $query = HomepageProduk::find()
            ->joinWith(['kategori.jenis']);

        $search = Yii::$app->request->get('search');
        $jenisId = Yii::$app->request->get('jenis');
        $kategoriId = Yii::$app->request->get('kategori');
        $sort = Yii::$app->request->get('sort');

        if (!empty($search)) {

            $query->andWhere([
                'or',
                ['like', 'homepage_produk.title', $search],
                ['like', 'kategori_produk.nama_kategori', $search],
                ['like', 'jenis_produk.nama_jenis', $search],
            ]);
        }

        if (!empty($jenisId)) {
            $query->andWhere([
                'kategori_produk.jenis_id' => $jenisId
            ]);
        }

        if (!empty($kategoriId)) {
            $query->andWhere([
                'homepage_produk.kategori_id' => $kategoriId
            ]);
        }

        switch ($sort) {

            case 'nama_asc':
                $query->orderBy(['homepage_produk.title' => SORT_ASC]);
                break;

            case 'nama_desc':
                $query->orderBy(['homepage_produk.title' => SORT_DESC]);
                break;

            case 'harga_asc':
                $query->orderBy(['homepage_produk.harga_kg' => SORT_ASC]);
                break;

            case 'harga_desc':
                $query->orderBy(['homepage_produk.harga_kg' => SORT_DESC]);
                break;

            default:
                $query->orderBy(['homepage_produk.id' => SORT_DESC]);
        }

        $produks = $query->all();

        $jenisAktif = null;

        if (!empty($jenisId)) {
            $jenisAktif = JenisProduk::findOne($jenisId);
        }

        $kategoriList = !empty($jenisId)
            ? KategoriProduk::find()
                ->where(['jenis_id' => $jenisId])
                ->all()
            : KategoriProduk::find()->all();

        if (Yii::$app->request->isAjax) {

            return $this->renderPartial('_produk_grid', [
                'produks' => $produks
            ]);
        }

        return $this->render('index', [
            'produks' => $produks,
            'jenisList' => JenisProduk::find()->all(),
            'kategoriList' => $kategoriList,
            'jenisAktif' => $jenisAktif,
        ]);
    }
}
