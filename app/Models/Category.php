<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name_en', 'name_ar'];

    // علاقة التصنيف مع الخدمات
    public function services()
    {
        return $this->hasMany(CardService::class, 'category_id');
    }
}