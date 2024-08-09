<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasTableName
{
    /*
      * Get the table name of the model.
      *
      * @return string
      */

    public static function getTableName(): string
    {
        $model = new static();
        return $model->getTable();
    }
}
