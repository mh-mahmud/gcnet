<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCode extends Model {

	public function category() {
		return $this->belongsTo(Category::class);
	}

	public function brand() {
		return $this->belongsTo(Brand::class);
	}

	public function product() {
		return $this->belongsTo(Product::class);
	}

}