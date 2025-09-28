<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

class HomepagePromo extends ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $imageFile;

    public static function tableName()
    {
        return 'homepage_promo';
    }

    public function rules()
    {
        return [
            [['title', 'start_date', 'end_date'], 'required'],
            [['title'], 'string', 'max' => 255],
            [['start_date', 'end_date'], 'safe'],
            [['image'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'extensions' => 'png, jpg, jpeg, webp', 'skipOnEmpty' => true],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => 'Judul Promo',
            'image' => 'Banner Promo',
            'start_date' => 'Tanggal Mulai',
            'end_date' => 'Tanggal Berakhir',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            if ($this->imageFile) {
                $fileName = 'promo_' . time() . '.' . $this->imageFile->extension;
                $path = 'images/' . $fileName;

                // hapus file lama jika ada
                if ($this->image && file_exists(Yii::getAlias('@webroot') . '/' . $this->image)) {
                    @unlink(Yii::getAlias('@webroot') . '/' . $this->image);
                }

                $this->imageFile->saveAs(Yii::getAlias('@webroot') . '/' . $path);
                $this->image = $path;
            }
            return true;
        }
        return false;
    }
}
