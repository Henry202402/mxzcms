<?php

namespace Modules\Formtools\Support;

class PageSourceFormatter
{
    public static function normalizeMetaText(?string $value, int $maxLength = 0): string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return '';
        }

        $value = preg_replace('/\s+/u', ' ', $value) ?: $value;
        if ($maxLength > 0 && function_exists('mb_substr')) {
            return (string) mb_substr($value, 0, $maxLength);
        }

        return $maxLength > 0 ? substr($value, 0, $maxLength) : $value;
    }

    public static function formatSchemaJson(?string $json): string
    {
        $json = trim((string) $json);
        if ($json === '') {
            return '';
        }

        $decoded = json_decode($json, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $json;
        }

        return (string) json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }

    public static function formatHtml(?string $html): string
    {
        $html = trim((string) $html);
        if ($html === '') {
            return '';
        }

        $html = preg_replace('/>\s+</', ">\n<", $html) ?: $html;
        $lines = preg_split('/\r\n|\r|\n/', $html) ?: [];
        $formatted = [];
        $indent = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                if ($formatted !== [] && end($formatted) !== '') {
                    $formatted[] = '';
                }
                continue;
            }

            if (preg_match('/^<\//', $line)) {
                $indent = max(0, $indent - 1);
            }

            $formatted[] = str_repeat('    ', $indent) . $line;

            if (self::isHtmlOpeningTag($line) && !self::isHtmlSelfClosingTag($line)) {
                $indent++;
            }
        }

        return rtrim(implode("\n", $formatted));
    }

    public static function formatCss(?string $css): string
    {
        $css = trim((string) $css);
        if ($css === '') {
            return '';
        }

        $css = preg_replace('/\/\*.*?\*\//s', static function ($matches) {
            return trim((string) $matches[0]);
        }, $css) ?: $css;
        $css = preg_replace('/\s*{\s*/', " {\n", $css) ?: $css;
        $css = preg_replace('/;\s*/', ";\n", $css) ?: $css;
        $css = preg_replace('/\s*}\s*/', "\n}\n", $css) ?: $css;
        $lines = preg_split('/\r\n|\r|\n/', $css) ?: [];
        $formatted = [];
        $indent = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                if ($formatted !== [] && end($formatted) !== '') {
                    $formatted[] = '';
                }
                continue;
            }

            if ($line === '}') {
                $indent = max(0, $indent - 1);
            }

            $formatted[] = str_repeat('    ', $indent) . $line;

            if (substr($line, -1) === '{') {
                $indent++;
            }
        }

        return rtrim(implode("\n", $formatted));
    }

    public static function formatJs(?string $js): string
    {
        $js = trim((string) $js);
        if ($js === '') {
            return '';
        }

        $js = self::stripWrapperTag($js, 'script');
        $js = preg_replace('/;\s*/', ";\n", $js) ?: $js;
        $js = preg_replace('/\{\s*/', "{\n", $js) ?: $js;
        $js = preg_replace('/\}\s*/', "}\n", $js) ?: $js;
        $lines = preg_split('/\r\n|\r|\n/', $js) ?: [];
        $formatted = [];
        $indent = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                if ($formatted !== [] && end($formatted) !== '') {
                    $formatted[] = '';
                }
                continue;
            }

            if ($line === '}' || preg_match('/^\}(?:\)|,|;)?$/', $line)) {
                $indent = max(0, $indent - 1);
            }

            $formatted[] = str_repeat('    ', $indent) . $line;

            if (substr($line, -1) === '{') {
                $indent++;
            }
        }

        return rtrim(implode("\n", $formatted));
    }

    private static function stripWrapperTag(string $content, string $tag): string
    {
        $content = trim($content);
        $pattern = '#^<' . preg_quote($tag, '#') . '\b[^>]*>([\s\S]*)</' . preg_quote($tag, '#') . '>$#i';
        if (preg_match($pattern, $content, $matches)) {
            return trim((string) ($matches[1] ?? ''));
        }

        return $content;
    }

    private static function isHtmlOpeningTag(string $line): bool
    {
        return (bool) preg_match('/^<[a-zA-Z][^>]*>$/', $line)
            && !preg_match('/^<(meta|link|img|input|br|hr|source|area|base|col|embed|param|track|wbr)\b/i', $line);
    }

    private static function isHtmlSelfClosingTag(string $line): bool
    {
        return (bool) preg_match('/\/>$/', $line)
            || (bool) preg_match('/^<(meta|link|img|input|br|hr|source|area|base|col|embed|param|track|wbr)\b/i', $line);
    }
}
