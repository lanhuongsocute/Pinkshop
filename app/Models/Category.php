<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Category extends Model
{
    use HasFactory;
    protected $fillable = ['title','slug','summary','photo','is_parent','parent_id','status'];

    public static function shiftChild($cat_id){
        return Category::whereIn('id',$cat_id)->update(['parent_id'=>null]);
    }
    public static function c_create($data)
    {
        $slug = Str::slug($data['title']);
        $slug_count = Category::where('slug',$slug)->count();
        if($slug_count > 0)
        {
            $slug .= time().'-'.$slug;
        }
        $data['slug'] = $slug;
        $cat = Category::create($data);
        return $cat;
    }
}
