<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class prefix extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'prefixes';
    protected $softDelete = true;

    protected $hidden = ['deleted_at'];

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}
