<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Project extends Model
{
    protected $fillable = ['name','distiption','url','slogan','image','status','user_id'];
    protected $appends = ['image_url'];

    public function user() { return $this->belongsTo(User::class); } // исправлено

    public function getImageUrlAttribute(): ?string
    {
        return $this->image
            ? asset('storage/'.preg_replace('#^storage/#','',$this->image))
            : null;
    }
    // (не добавляй $casts['image'=>'integer']!)
}