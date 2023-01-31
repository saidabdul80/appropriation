<?php

namespace App\Casts;

use ArrayObject;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class JsonDecode implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     * 
     */
    public function get($model, string $key, $value, array $attributes)
    {
        
        $data = json_decode($attributes[$key], true);

        return new ArrayObject($data);        
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return json_encode($value);
    }
}
