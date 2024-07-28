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
        if (!isset($attributes[$key])) {
            return;
        }

        $data = json_decode($attributes[$key], true);

        foreach ($data as &$item) {
            // Check if the item has a 'type' key and its value is 'date'
            if (isset($item['type']) && $item['type'] === 'date' && isset($item['value'])) {
                // Parse the date value using Carbon and format it
                try {
                    // Try to parse the date as d/m/Y
                    $date = Carbon::createFromFormat('d/m/Y', $item['value']);
                } catch (\Exception $e) {
                    try {
                        // If parsing as d/m/Y fails, try to parse as Y-m-d
                        $date = Carbon::createFromFormat('Y-m-d', $item['value']);
                    } catch (\Exception $e) {
                        // If both parsing attempts fail, leave the date as it is
                        $date = $item['value'];
                    }
                }
                // If date parsing succeeded, format it to d F, Y
                if ($date instanceof Carbon) {
                    $item['value'] = $date->format('d F, Y');
                }
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
        foreach ($value as &$item) {
            if (isset($item['type']) && $item['type'] === 'date' && isset($item['value'])) {
                try {
                    // Try to parse the date as d/m/Y
                    $date = Carbon::createFromFormat('d/m/Y', $item['value']);
                } catch (\Exception $e) {
                    try {
                        // If parsing as d/m/Y fails, try to parse as Y-m-d
                        $date = Carbon::createFromFormat('Y-m-d', $item['value']);
                    } catch (\Exception $e) {
                        // If both parsing attempts fail, leave the date as it is
                        $date = $item['value'];
                    }
                }
                // If date parsing succeeded, format it to Y-m-d for storage
                if ($date instanceof Carbon) {
                    $item['value'] = $date->format('Y-m-d');
                }
            }
        }

        $jsonValue = json_encode($value);
        $jsonValue = str_replace("\ufeff", '', $jsonValue);

        return [$key => $jsonValue];
    }
}
