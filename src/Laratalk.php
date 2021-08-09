<?php

namespace FebriHidayan\Laratalk;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config as FacadesConfig;

class Laratalk
{
    /**
     * ISO-639 language codes
     *
     * @var array
     */
    public static $languages = [
        'en' => 'English',
        'id' => 'Indonesian',
    ];

    /**
     * Return an encoded string of app translations.
     *
     * @param string|null $locale
     * @return array
     */
    public static function availableTranslations(?string $locale): array
    {
        return trans(
            'laratalk::app',
            [], 
            $locale ?? Auth::user()->{Config::userLocale()}
        );
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

    /**
     * Convert time for easy reading
     * 
     * @param object $datetime
     * @param bool $today
     * @return string
     */
    public static function lastTime(object $datetime, $today = false)
    {
        $countDay = $datetime->diffInDays();

        $trans = self::availableTranslations(
            Auth::user()->{Config::userLocale()}
        );

        if ($datetime->isToday()) {

            if ($today) {
                return $trans['today'];
            }

            return $datetime->format('H.i');
        }

        if ($datetime->isYesterday()) {
            return  $trans['yesterday'];
        }

        if ($countDay >= 1 && $countDay <= 6) {
            $days = [
                $trans['sunday'],
                $trans['monday'],
                $trans['tuesday'],
                $trans['wednesday'],
                $trans['thursday'],
                $trans['friday'],
                $trans['saturday'],
            ];

            return $days[$datetime->dayOfWeek];
        }

        return $datetime->format('j/n/Y');

    }
}