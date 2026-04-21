<?php

namespace App\Translation;

use Illuminate\Support\Arr;
use Illuminate\Translation\Translator as BaseTranslator;

use function is_array;

class Translator extends BaseTranslator
{
    /**
     * @return array|string|null
     */
    public function get($key, array $replace = [], $locale = null, $fallback = true)
    {
        $results = parent::get($key, $replace, $locale, $fallback);

        if (! str_contains($key, '.') || $results !== $key) {
            if (is_array($results)) {
                return $key;
            }

            return $results;
        }

        $locale = $locale ?: $this->locale;
        $line = Arr::get($this->loaded['*']['*'][$locale], $key);

        if (! isset($line) && $fallback && ! empty($this->getFallback()) && $locale !== $this->getFallback()) {
            $this->load('*', '*', $this->getFallback());
            $line = Arr::get($this->loaded['*']['*'][$this->getFallback()], $key);
        }

        return $this->makeReplacements($line ?: $key, $replace);
    }
}
