<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
	protected $fillable = [
		'project',
		'version',
		'ip',
	];
}
