<?php
/**
 * Created by PhpStorm.
 * User: cfeo
 * Date: 29/6/2016
 * Time: 4:59 PM
 */

namespace Galpa\ProductsCustomer;


use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];


    public function scopeSearch($query, $str)
    {
        
    }
}