<?php

namespace Laratalk;

class Laratalk
{
    /**
     * Return an encoded string of app translations.
     *
     * @param $locale
     * @return array
     */
    public static function availableTranslations($locale): array
    {
        return trans('laratalk::app', [], $locale);
    }

    /**
     * Generate a Gravatar for a given email.
     *
     * @param string $email
     * @param int $size
     * @param string $default
     * @param string $rating
     * @return string
     */
    public static function gravatar(
        string $email,
        int $size = 200,
        string $default = 'retro',
        string $rating = 'g'
    ): string {
        $hash = md5(strtolower(trim($email)));

        return "https://www.gravatar.com/avatar/{$hash}?s={$size}&d={$default}&r={$rating}";
    }
}