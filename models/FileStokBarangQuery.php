<?php

namespace app\models;

/**
 * This is the ActiveQuery class for [[FileStokBarang]].
 *
 * @see FileStokBarang
 */
class FileStokBarangQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return FileStokBarang[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return FileStokBarang|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
