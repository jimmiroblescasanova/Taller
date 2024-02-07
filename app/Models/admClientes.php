<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Scopes\ExcludeClientCeroScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class admClientes extends Model
{
    use HasFactory;

    protected $connection = 'sqlcompac';

    protected $table = 'admClientes';

    public $timestamps = false;

    protected $primaryKey = 'CIDCLIENTEPROVEEDOR';

    protected static function booted(): void
    {
        static::addGlobalScope(new ExcludeClientCeroScope);
    }
}
