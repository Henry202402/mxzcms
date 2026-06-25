<?php

namespace Modules\Main\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SeoDomainLimit {

    protected string $noindexHeader = 'noindex, nofollow, noarchive, nosnippet';

    public function handle($request, Closure $next) {
        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        $limitedDomains = $this->parseDomains((string) cacheGlobalSettingsByKey('seo_limit_domain'));
        if (empty($limitedDomains)) {
            return $next($request);
        }

        $host = $this->normalizeDomain($request->getHost());
        if (!in_array($host, $limitedDomains, true)) {
            return $next($request);
        }

        if ($this->isBotRequest((string) $request->userAgent())) {
            return response('Forbidden for crawler on this domain', 403)
                ->header('X-Robots-Tag', $this->noindexHeader);
        }

        $response = $next($request);
        $response->headers->set('X-Robots-Tag', $this->noindexHeader);

        return $response;
    }

    protected function shouldSkip(Request $request): bool
    {
        return $request->is('admin/*')
            || $request->is('api/*')
            || $request->is('install*')
            || $request->is('up*');
    }

    protected function parseDomains(string $domains): array
    {
        $items = preg_split('/[\r\n,，;；]+/', $domains) ?: [];
        $items = array_map(fn ($item) => $this->normalizeDomain($item), $items);

        return array_values(array_unique(array_filter($items)));
    }

    protected function normalizeDomain(string $domain): string
    {
        $domain = trim(strtolower($domain));
        if ($domain === '') {
            return '';
        }

        if (str_contains($domain, '://')) {
            $domain = (string) parse_url($domain, PHP_URL_HOST);
        }

        $domain = preg_replace('/:\d+$/', '', $domain);

        return trim((string) $domain, " \t\n\r\0\x0B./");
    }

    protected function isBotRequest(string $userAgent): bool
    {
        $userAgent = strtolower(trim($userAgent));
        if ($userAgent === '') {
            return false;
        }

        $keywords = preg_split('/[\r\n,，;；\s]+/', (string) cacheGlobalSettingsByKey('seo_bot_keywords')) ?: [];
        $keywords = array_values(array_filter(array_map('trim', $keywords)));
        if (empty($keywords)) {
            $keywords = ['bot', 'crawl', 'spider', 'slurp', 'googlebot', 'bingbot', 'baiduspider', 'sogou', 'bytespider'];
        }

        foreach ($keywords as $keyword) {
            if ($keyword !== '' && str_contains($userAgent, strtolower($keyword))) {
                return true;
            }
        }

        return false;
    }
}
