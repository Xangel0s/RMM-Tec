<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareInventory extends Model
{
    use HasFactory;

    protected $table = 'software_inventory';

    protected $fillable = [
        'endpoint_id',
        'software_name',
        'version',
        'install_date',
        'publisher',
        'synced_at',
    ];

    protected $casts = [
        'install_date' => 'datetime',
        'synced_at' => 'datetime',
    ];

    public function endpoint()
    {
        return $this->belongsTo(Endpoint::class);
    }
}
