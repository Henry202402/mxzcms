<?php

namespace App\Support\I18n;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Main\Services\ServiceModel;

class ThemeTranslator
{
    public const DEFAULT_LOCALE = 'zh-CN';

    public static function translate(string $key, array $replace = [], ?string $theme = null, ?string $locale = null): string
    {
        $theme = $theme ?: static::currentTheme();
        $locale = static::currentLocale($locale);
        $line = static::lines($theme, $locale)[$key] ?? static::fallbackLines($theme)[$key] ?? $key;

        return static::replace($line, $replace);
    }

    public static function currentLocale(?string $locale = null): string
    {
        if ($locale) {
            return static::normalizeLocale($locale);
        }

        $defaultLocale = static::defaultLocale();
        if (!static::isMultilingualEnabled()) {
            if (session('homelang') !== $defaultLocale) {
                session()->put('homelang', $defaultLocale);
            }

            return $defaultLocale;
        }

        $requestLocale = request()->get('lang');
        $requestLocale = static::normalizeLocale($requestLocale);
        if ($requestLocale && $requestLocale !== session('homelang')) {
            session()->put('homelang', $requestLocale);
            static::clearCachedSessionLocale();
        }

        $sessionLocale = static::normalizeLocale(session('homelang'));
        if ($sessionLocale) {
            return $sessionLocale;
        }

        session()->put('homelang', $defaultLocale);

        return $defaultLocale;
    }

    public static function currentTheme(): string
    {
        if (cache()->has('theme')) {
            return cache()->get('theme');
        }

        $theme = DB::table('themes')->where('status', 1)->value('identification') ?: 'default';
        cache()->forever('theme', $theme);

        return $theme;
    }

    public static function lines(string $theme, string $locale): array
    {
        return Cache::rememberForever(static::cacheKey($theme, $locale), function () use ($theme, $locale) {
            $path = public_path('views/themes/' . $theme . '/lang/' . $locale . '/lang.json');
            if (!is_file($path)) {
                return [];
            }

            $contents = file_get_contents($path);
            $decoded = json_decode($contents, true);

            return is_array($decoded) ? $decoded : [];
        });
    }

    public static function fallbackLines(string $theme): array
    {
        return static::lines($theme, static::DEFAULT_LOCALE);
    }

    public static function clearCachedSessionLocale(): void
    {
        $theme = cache()->get('theme');
        if ($theme) {
            Cache::forget(static::cacheKey($theme, session('homelang') ?: static::DEFAULT_LOCALE));
        }
        Cache::forget('homelangList');
    }

    private static function cacheKey(string $theme, string $locale): string
    {
        return 'theme_lang_pack.' . $theme . '.' . $locale;
    }

    public static function defaultLocale(): string
    {
        $configured = static::normalizeLocale(cacheGlobalSettingsByKey('default_language'));

        return $configured ?: static::DEFAULT_LOCALE;
    }

    public static function isMultilingualEnabled(): bool
    {
        return (int) cacheGlobalSettingsByKey('multilingual') === 1;
    }

    public static function availableLocales(): array
    {
        return array_keys(ServiceModel::getLangList());
    }

    public static function normalizeLocale(?string $locale): string
    {
        $locale = trim((string) $locale);
        if ($locale === '') {
            return '';
        }

        return in_array($locale, static::availableLocales(), true) ? $locale : '';
    }

    private static function replace(string $line, array $replace): string
    {
        if (empty($replace)) {
            return $line;
        }

        $replacements = [];
        foreach ($replace as $name => $value) {
            $replacements[':' . $name] = $value;
        }

        return strtr($line, $replacements);
    }
}
