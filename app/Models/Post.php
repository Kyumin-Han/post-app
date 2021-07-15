<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    public function imagePath() {
        // $path = '/storage/images/';
        $path = env('IMAGE_PATH', '/storage/images');
        $imageFile = $this->image ?? 'no_image_available.png';
        return $path.$imageFile;
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function viewers() {
        return $this->belongsToMany(User::class);

        // 테이블 이름의 관례를 따르지 않았을 때는 옵션들을 적어줘야 한다
        // return $this->belongsToMany(User::class, 'post_user', 'post_id', 'user_id', 'id', 'id', 'users');
    }

    public function likes() {
        return $this->belongsTomany(User::class, 'like', 'post_id', 'user_id', 'id', 'id', 'users');
    }
}
