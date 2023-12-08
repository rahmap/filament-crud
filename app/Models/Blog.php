<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
	protected $fillable = [
		'category_id',
		'title',
		'content',
		'image'
	];
	
	public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(Category::class);
	}
}
