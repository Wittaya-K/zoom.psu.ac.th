<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zoom extends Model
{
    use SoftDeletes;

    protected $fillable = ['zoom_email','zoom_number', 'password', 'description','category_id','expire_at'];

    /**
     * Set attribute to money format
     * @param $input
     */
    public function setPasswordAttribute($input)
    {
        $this->attributes['password'] = $input ? $input : null;
    }
    public function booking()
    {
        return $this->HasOne(Booking::class, 'zoom_id')->withTrashed();
    }
}
