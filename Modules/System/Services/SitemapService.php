<?php

namespace Modules\System\Services;

class SitemapService
{
    protected function flattenUrls($items): array
    {
        $urls = [];
        foreach ((array) $items as $item) {
            if (is_array($item)) {
                $urls = array_merge($urls, $this->flattenUrls($item));
                continue;
            }
            if (is_object($item) && method_exists($item, 'toArray')) {
                $urls = array_merge($urls, $this->flattenUrls($item->toArray()));
                continue;
            }
            if (is_string($item)) {
                $url = trim($item);
                if ($url !== '') {
                    $urls[] = $url;
                }
            }
        }

        return $urls;
    }

    protected function normalizeUrls(array $urls): array
    {
        $normalized = [];
        foreach ($this->flattenUrls($urls) as $url) {
            if (!preg_match('#^https?://#i', $url)) {
                $url = url(ltrim($url, '/'));
            }
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $normalized[] = $url;
            }
        }

        $normalized = array_values(array_unique($normalized));
        sort($normalized);

        return $normalized;
    }

    protected function buildXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
        foreach ($urls as $url) {
            $xml .= '    <url>' . PHP_EOL;
            $xml .= '      <loc>' . e($url) . '</loc>' . PHP_EOL;
            $xml .= '      <lastmod>' . date('c') . '</lastmod>' . PHP_EOL;
            $xml .= '   </url>' . PHP_EOL;
        }
        $xml .= '</urlset>';

        return $xml;
    }

    protected function writeFile(string $path, string $content): void
    {
        $bytes = @file_put_contents($path, $content);
        if ($bytes === false) {
            throw new \RuntimeException('文件写入失败：' . $path);
        }
    }

    public function generate(): array
    {
        $hookResults = hook('GetSiteMapUrl');
        $urls = $this->normalizeUrls($hookResults);
        if (empty($urls)) {
            throw new \RuntimeException('未获取到可生成的 URL，请先检查 GetSiteMapUrl 返回值');
        }

        $xml = $this->buildXml($urls);
        $urlList = implode(PHP_EOL, $urls) . PHP_EOL;

        $this->writeFile(public_path('sitemap.xml'), $xml);
        $this->writeFile(public_path('sitemap.xml.gz'), gzencode($xml));
        $this->writeFile(public_path('urllist.txt'), $urlList);

        return [
            'count' => count($urls),
            'urls' => $urls,
            'xml_path' => public_path('sitemap.xml'),
            'gzip_path' => public_path('sitemap.xml.gz'),
            'url_list_path' => public_path('urllist.txt'),
        ];
    }
}
