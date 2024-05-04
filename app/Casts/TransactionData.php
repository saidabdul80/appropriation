<?php

namespace App\Casts;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class TransactionData implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        if (! isset($attributes[$key])) {
            return;
        }

        $data = json_decode($attributes[$key], true);
        foreach($data as &$item) {
            // Check if the item has a 'type' key and its value is 'date'
            if (isset($item['type']) && $item['type'] === 'date' && isset($item['value'])) {
                // Parse the date value using Carbon and format it
                $item['value'] = Carbon::parse($item['value'])->format('d F, Y');
            }
        }

        return $data;
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
        return [$key => json_encode($value)];
    }
}
