<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportBatch extends Model
{
    protected $fillable = [
        'file_name',
        'total_records',
        'imported_records',
        'status',
        'progress',
        'filter',
        'imported_at'
    ];

    protected $casts = [
        'imported_at' => 'datetime',
    ];

    public function phones()
    {
        return $this->hasMany(ExtractedPhone::class, 'import_batch_id');
    }
}

