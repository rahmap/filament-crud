<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name'];
	
	public function blogs(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(Blog::class);
	}
}
