<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;

trait LocalizedModelTrait
{
    /**
     * Localize an array by consolidating _ar and _en fields into a single key.
     *
     * @return array
     */
    public function localizeArray(array $array)
    {
        $locale = App::getLocale();

        // Identify all keys that end with _ar or _en
        $localizedKeys = [];
        foreach ($array as $key => $value) {
            if (is_string($key)) {
                if (str_ends_with($key, '_ar') || str_ends_with($key, '_en')) {
                    $baseKey = substr($key, 0, -3);
                    $localizedKeys[$baseKey] = true;
                }
            }
        }

        // Consolidate keys based on active locale
        foreach (array_keys($localizedKeys) as $baseKey) {
            $arVal = $array[$baseKey.'_ar'] ?? null;
            $enVal = $array[$baseKey.'_en'] ?? null;

            if ($locale === 'ar') {
                $array[$baseKey] = $arVal ?? $enVal;
            } else {
                $array[$baseKey] = $enVal ?? $arVal;
            }

            unset($array[$baseKey.'_ar'], $array[$baseKey.'_en']);
        }

        return $array;
    }

    /**
     * Override the default toArray method.
     *
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        return $this->localizeArray($array);
    }
}
