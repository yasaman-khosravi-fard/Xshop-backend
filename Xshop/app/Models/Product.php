<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Product extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'title', 'price', 'description','quantity','type'
    ];

    public function images()
    {
        return $this->hasMany(Image::class);
    }
}
