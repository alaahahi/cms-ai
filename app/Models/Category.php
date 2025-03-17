<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name_en', 'name_ar', 'parent_id'];

    // علاقة التصنيف مع الخدمات
    public function services()
    {
        return $this->hasMany(CardService::class, 'category_id');
    }

    // علاقة التصنيفات الرئيسية مع التصنيفات الفرعية
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // علاقة التصنيفات الفرعية مع التصنيف الرئيسي
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
}
