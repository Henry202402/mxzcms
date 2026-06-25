<?php

namespace Modules\System\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Modules\Formtools\Http\Controllers\Admin\FormTool;
use Modules\Main\Services\ServiceModel;
use Modules\ModulesController;
use Modules\System\Models\Setting;
use Modules\System\Services\SitemapService;

class SeoController extends ModulesController {

    protected function seoTokenDefinitions(): array
    {
        return [
            '{{website_name}}' => '站点名称',
            '{{website_title}}' => '站点名称',
            '{{website_keywords}}' => '站点默认关键词',
            '{{website_description}}' => '站点默认描述',
            '{{model_name}}' => '模型名称',
            '{{model_title}}' => '模型名称',
            '{{data_title}}' => '当前内容标题',
            '{{data_name}}' => '当前内容名称',
            '{{detail_title}}' => '当前内容标题',
            '{{detail_name}}' => '当前内容名称',
            '{{detaill_title}}' => '当前内容标题（兼容旧写法）',
            '{{detaill_name}}' => '当前内容名称（兼容旧写法）',
            '{{model_home_page_title}}' => '模型首页标题',
            '{{model_home_page_describe}}' => '模型首页描述',
            '{{current_lang}}' => '当前语言',
            '{{current_url}}' => '当前页面地址',
        ];
    }

    protected function buildTokenNote(array $tokens): string
    {
        $definitions = $this->seoTokenDefinitions();
        $notes = [];
        foreach ($tokens as $token) {
            if (!isset($definitions[$token])) {
                continue;
            }
            $notes[] = $token . ' ' . $definitions[$token];
        }

        return $notes ? '可用变量：' . implode('；', $notes) : '当前字段不支持变量。';
    }

    protected function normalizeSeoText($value, int $limit = 0): string
    {
        $value = html_entity_decode((string) $value, ENT_QUOTES, 'UTF-8');
        $value = trim(preg_replace('/\s+/u', ' ', strip_tags($value)));
        if ($limit > 0 && mb_strlen($value, 'UTF-8') > $limit) {
            $value = mb_substr($value, 0, $limit, 'UTF-8');
        }

        return $value;
    }

    protected function excerptContent($content, int $limit = 190): string
    {
        $text = $this->normalizeSeoText($content);
        if ($text === '') {
            return '';
        }

        if (mb_strlen($text, 'UTF-8') <= $limit) {
            return $text;
        }

        return rtrim(mb_substr($text, 0, $limit, 'UTF-8')) . '...';
    }

    protected function normalizeSeoContext($data): array
    {
        if (!is_array($data)) {
            $data = (array) $data;
        }

        if (isset($data['data']) && is_array($data['data'])) {
            $data['data'] = (object) $data['data'];
        }
        if (isset($data['model']) && is_array($data['model'])) {
            $data['model'] = (object) $data['model'];
        }
        if (isset($data['model']->home_seo_config) && is_string($data['model']->home_seo_config)) {
            $data['model']->home_seo_config = json_decode($data['model']->home_seo_config, true) ?: [];
        }
        if (isset($data['model']->home_seo_detail_config) && is_string($data['model']->home_seo_detail_config)) {
            $data['model']->home_seo_detail_config = json_decode($data['model']->home_seo_detail_config, true) ?: [];
        }

        return $data;
    }

    protected function buildReplaceMap(array $data): array
    {
        $model = $data['model'] ?? (object) [];
        $detail = $data['data'] ?? (object) [];
        $detailTitle = $detail->seo_title ?? $detail->title ?? $detail->name ?? $model->name ?? '';
        $detailName = $detail->name ?? $detail->title ?? $model->name ?? '';

        return [
            '{{website_name}}' => $this->normalizeSeoText(cacheGlobalSettingsByKey("website_name"), 120),
            '{{website_title}}' => $this->normalizeSeoText(cacheGlobalSettingsByKey("website_name"), 120),
            '{{website_keywords}}' => $this->normalizeSeoText(cacheGlobalSettingsByKey("website_keys"), 255),
            '{{website_description}}' => $this->normalizeSeoText(cacheGlobalSettingsByKey("website_desc"), 255),
            '{{model_name}}' => $this->normalizeSeoText($model->name ?? '', 120),
            '{{model_title}}' => $this->normalizeSeoText($model->name ?? '', 120),
            '{{data_title}}' => $this->normalizeSeoText($detailTitle, 120),
            '{{data_name}}' => $this->normalizeSeoText($detailName, 120),
            '{{detail_title}}' => $this->normalizeSeoText($detailTitle, 120),
            '{{detail_name}}' => $this->normalizeSeoText($detailName, 120),
            '{{detaill_title}}' => $this->normalizeSeoText($detailTitle, 120),
            '{{detaill_name}}' => $this->normalizeSeoText($detailName, 120),
            '{{model_home_page_title}}' => $this->normalizeSeoText($model->home_config['home_page_title'] ?? '', 120),
            '{{model_home_page_describe}}' => $this->normalizeSeoText($model->home_config['home_page_describe'] ?? '', 255),
            '{{current_lang}}' => $this->normalizeSeoText(currentHomeLang(), 32),
            '{{current_url}}' => $this->normalizeSeoText(url()->full(), 255),
        ];
    }

    protected function renderSeoTemplate($subject, array $replaceMap, int $limit = 0): string
    {
        return $this->normalizeSeoText(strtr((string) $subject, $replaceMap), $limit);
    }

    protected function robotsGeneratedSectionStart(): string
    {
        return '# BEGIN MXZCMS SEO DOMAIN LIMIT';
    }

    protected function robotsGeneratedSectionEnd(): string
    {
        return '# END MXZCMS SEO DOMAIN LIMIT';
    }

    protected function parseLimitedDomains(string $domains): array
    {
        $items = preg_split('/[\r\n,，;；]+/', $domains) ?: [];
        $items = array_map(fn ($item) => $this->normalizeDomainValue($item), $items);

        return array_values(array_unique(array_filter($items)));
    }

    protected function normalizeDomainValue(string $domain): string
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

    protected function stripGeneratedRobotsSection(string $robots): string
    {
        $start = preg_quote($this->robotsGeneratedSectionStart(), '/');
        $end = preg_quote($this->robotsGeneratedSectionEnd(), '/');
        $robots = preg_replace('/\R?' . $start . '[\s\S]*?' . $end . '\R?/u', PHP_EOL, $robots) ?? $robots;

        return trim($robots);
    }

    protected function buildRobotsDomainLimitSection(?string $domains = null): string
    {
        $domains = $domains ?? (string) cacheGlobalSettingsByKey('seo_limit_domain');
        $limitedDomains = $this->parseLimitedDomains($domains);
        if (empty($limitedDomains)) {
            return '';
        }

        $lines = [
            $this->robotsGeneratedSectionStart(),
            '# 以下域名已在 SEO 配置中设置为限制收录与爬虫访问。',
            '# 当前系统已通过 X-Robots-Tag 和蜘蛛拦截中间件生效。',
            '# 由于 robots.txt 为共享文件，无法按域名单独返回 Disallow 规则。',
            '# 若某个限制域名为独立部署站点，可参考以下建议规则：',
        ];

        foreach ($limitedDomains as $domain) {
            $lines[] = '# [' . $domain . ']';
            $lines[] = '# User-agent: *';
            $lines[] = '# Disallow: /';
        }

        $lines[] = $this->robotsGeneratedSectionEnd();

        return implode(PHP_EOL, $lines);
    }

    protected function buildRobotsContent(string $robots, ?string $domains = null): string
    {
        $baseContent = $this->stripGeneratedRobotsSection($robots);
        $generatedSection = $this->buildRobotsDomainLimitSection($domains);

        if ($baseContent !== '' && $generatedSection !== '') {
            return $baseContent . PHP_EOL . PHP_EOL . $generatedSection . PHP_EOL;
        }

        if ($generatedSection !== '') {
            return $generatedSection . PHP_EOL;
        }

        return $baseContent === '' ? '' : $baseContent . PHP_EOL;
    }

    protected function flattenSitemapUrls($items): array
    {
        $urls = [];
        foreach ((array) $items as $item) {
            if (is_array($item)) {
                $urls = array_merge($urls, $this->flattenSitemapUrls($item));
                continue;
            }
            if (is_object($item) && method_exists($item, 'toArray')) {
                $urls = array_merge($urls, $this->flattenSitemapUrls($item->toArray()));
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

    protected function normalizeSitemapUrls(array $urls): array
    {
        $normalized = [];
        foreach ($this->flattenSitemapUrls($urls) as $url) {
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

    protected function buildSitemapXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;
        foreach ($urls as $url) {
            $xml .= "    <url>" . PHP_EOL;
            $xml .= "      <loc>" . e($url) . "</loc>" . PHP_EOL;
            $xml .= "      <lastmod>" . date('c') . "</lastmod>" . PHP_EOL;
            $xml .= "   </url>" . PHP_EOL;
        }
        $xml .= "</urlset>";

        return $xml;
    }

    protected function writeSitemapFile(string $path, string $content): void
    {
        $bytes = @file_put_contents($path, $content);
        if ($bytes === false) {
            throw new \RuntimeException('文件写入失败：' . $path);
        }
    }

    public function updateSitemap()
    {
        try {
            $result = app(SitemapService::class)->generate();
            return returnArr(200, '更新成功，共生成 ' . $result['count'] . ' 条 URL');
        } catch (\Throwable $e) {
            return returnArr(500, '更新失败：' . $e->getMessage());
        }
    }

    public function seoConfig(){
        $pageData = getURIByRoute($this->request);
        $pageData['title'] = "基本配置";
        $pageData['subtitle'] = "SEO配置";

        if($this->request->isMethod("post")){
            $all = $this->request->all();
            unset($all["_token"]);
            unset($all["sitemap"]);
            $robotstxt = $this->buildRobotsContent((string) ($all["seo_robots"] ?? ''), (string) ($all["seo_limit_domain"] ?? ''));
            file_put_contents(public_path("robots.txt"),$robotstxt);
            unset($all["seo_robots"]);
            foreach ($all as $key=>$value){
                $type = "seo";
                $module = "Main";
                if(in_array($key,["website_name","website_keys","website_desc"])){
                    $type = "website";
                }
                ServiceModel::SettingInsertOrUpdate($module,$type,$key,$value);
            }
            cacheGlobalSettings(2);
            return redirect(url("admin/system/seo/config"))->with("pageDataMsg","操作成功")->with("pageDataStatus",200);
        }

        $robotsPath = public_path("robots.txt");
        $robotstxt = file_exists($robotsPath) ? file_get_contents($robotsPath) : "User-agent: *\nDisallow:";
        $robotstxt = $this->buildRobotsContent($robotstxt, (string) cacheGlobalSettingsByKey('seo_limit_domain'));
        $sitemapXmlPath = public_path('sitemap.xml');
        $sitemapGzPath = public_path('sitemap.xml.gz');
        $urlListPath = public_path('urllist.txt');
        $websiteTitle = (string) cacheGlobalSettingsByKey("website_name");
        $websiteKeywords = (string) cacheGlobalSettingsByKey("website_keys");
        $websiteDesc = (string) cacheGlobalSettingsByKey("website_desc");
        $robotsLineCount = $robotstxt === '' ? 0 : count(preg_split('/\r\n|\r|\n/', trim($robotstxt)));
        $tokenDefinitions = $this->seoTokenDefinitions();
        $tokenPreview = implode(' / ', array_keys($tokenDefinitions));
        $pageData['pageWorkbench'] = [
            'title' => 'SEO 配置工作台',
            'desc' => '这一页负责内容列表 SEO 模板、详情页 SEO 模板、robots.txt 和 sitemap 生成；首页默认 TDK 仍以基础设置中的网站信息为主。',
            'sub' => '覆盖顺序：内容单条 SEO > 模型 SEO 配置 > 系统通用 SEO 模板 > 基础设置默认 TDK',
            'actions' => [
                [
                    'label' => '基础设置 TDK',
                    'url' => moduleAdminJump('System', 'base/baseConfig?type=0'),
                    'class' => 'btn btn-default',
                ],
                [
                    'label' => '查看 sitemap.xml',
                    'url' => url('/sitemap.xml'),
                    'class' => 'btn btn-info',
                    'target' => '_blank',
                ],
            ],
            'stats' => [
                [
                    'label' => '首页默认标题',
                    'value' => $websiteTitle ?: '未设置',
                    'valueStyle' => 'font-size:20px;line-height:1.3;',
                    'desc' => '基础设置中的网站名称会作为首页和兜底标题来源。',
                ],
                [
                    'label' => '模板变量',
                    'value' => count($tokenDefinitions) . ' 个',
                    'desc' => $tokenPreview,
                ],
                [
                    'label' => 'robots 规则',
                    'value' => $robotsLineCount . ' 行',
                    'desc' => '当前 robots.txt 中已维护的规则总行数。',
                ],
                [
                    'label' => 'sitemap 状态',
                    'value' => file_exists($sitemapXmlPath) ? '已生成' : '未生成',
                    'desc' => file_exists($sitemapXmlPath) ? '最近修改：' . date('Y-m-d H:i', filemtime($sitemapXmlPath)) : '保存后可直接点击“更新 sitemap”生成文件。',
                ],
            ],
        ];

        $formtool = FormTool::create();
        $formtool->actionName('保存 SEO 配置');
        $formtool->backName('返回上一页');
        $formtool->tips('首页默认 SEO 标题、关键词、描述同时出现在基础设置中。建议将基础设置作为默认站点信息，这里重点维护通用模板、robots.txt 与 sitemap 工具。');

        $formtool->group("section","首页默认 SEO", [
            'notes' => '这里展示的是基础设置中的网站名称、关键词、描述。它们既是首页默认 SEO，也是通用模板的最终兜底值。',
            'columns' => 2,
        ], function (FormTool $formtool) use ($websiteTitle, $websiteKeywords, $websiteDesc) {
            $formtool->field("website_name","页面标题",$websiteTitle)
                ->notes("website_name","建议保持为站点正式名称，供首页和兜底标题直接使用。");
            $formtool->field("website_keys","关键词",$websiteKeywords)
                ->notes("website_keys","建议控制在 100 个字符以内，避免关键词堆砌。");
            $formtool->field("website_desc","页面描述",$websiteDesc)
                ->formtype("website_desc","textarea")
                ->rows("website_desc", 4)
                ->full()
                ->notes("website_desc","建议控制在 200 个字符以内，未命中其它模板时会回退到这里。");
        });

        $formtool->group("section2","通用内容列表 SEO 模板", [
            'notes' => '适用于模型列表页、栏目页等内容聚合页面，支持模板变量组合输出。',
            'columns' => 2,
        ], function (FormTool $formtool) {
            $formtool->field("seo_title","页面标题",cacheGlobalSettingsByKey("seo_title"))
                ->notes("seo_title", $this->buildTokenNote(['{{model_name}}', '{{model_title}}', '{{website_name}}', '{{current_lang}}', '{{current_url}}']));

            $formtool->field("seo_keywords","关键词",cacheGlobalSettingsByKey("seo_keywords"))
                ->notes("seo_keywords", $this->buildTokenNote(['{{model_name}}', '{{website_keywords}}', '{{current_lang}}']));

            $formtool->field("seo_website_desc","页面描述",cacheGlobalSettingsByKey("seo_website_desc"))
                ->formtype("seo_website_desc","textarea")
                ->rows("seo_website_desc", 4)
                ->full()
                ->notes("seo_website_desc", $this->buildTokenNote(['{{model_name}}', '{{model_home_page_describe}}', '{{website_description}}', '{{current_lang}}']) . '；为空时会回退到基础设置里的网站描述。');
        });

        $formtool->group("section4","通用内容详情页 SEO 模板", [
            'notes' => '当单条内容没有单独填写 SEO 时，这组模板会作为详情页回退方案。',
            'columns' => 2,
        ], function (FormTool $formtool) {
            $formtool->field("seo_title_detail","页面标题",cacheGlobalSettingsByKey("seo_title_detail"))
                ->notes("seo_title_detail", $this->buildTokenNote(['{{model_name}}', '{{data_title}}', '{{data_name}}', '{{detail_title}}', '{{detail_name}}', '{{model_home_page_title}}', '{{website_name}}', '{{current_lang}}', '{{current_url}}']));

            $formtool->field("seo_keywords_detail","关键词",cacheGlobalSettingsByKey("seo_keywords_detail"))
                ->notes("seo_keywords_detail", $this->buildTokenNote(['{{model_name}}', '{{data_title}}', '{{data_name}}', '{{detail_title}}', '{{detail_name}}', '{{website_keywords}}', '{{current_lang}}']));

            $formtool->field("seo_website_desc_detail","页面描述",cacheGlobalSettingsByKey("seo_website_desc_detail"))
                ->formtype("seo_website_desc_detail","textarea")
                ->rows("seo_website_desc_detail", 4)
                ->full()
                ->notes("seo_website_desc_detail", $this->buildTokenNote(['{{model_name}}', '{{data_title}}', '{{data_name}}', '{{detail_title}}', '{{detail_name}}', '{{model_home_page_describe}}', '{{website_description}}', '{{current_lang}}']) . '；为空时会继续回退到列表页描述模板，再回退到基础设置网站描述。');
        });

        $buttonurl = url('admin/system/seo/updateSitemap');
        //url, para, fn,type="GET",dataType="html"
        $formtool->group("section5","网站地图与 robots", [
            'notes' => '用于维护搜索引擎抓取工具链。sitemap 会输出到 public 目录，robots.txt 会直接覆盖站点同名文件。',
            'columns' => 2,
        ], function (FormTool $formtool) use ($sitemapXmlPath, $sitemapGzPath, $urlListPath, $robotstxt) {
            $sitemapNotes = [
                '可访问地址：' . url("/")."/sitemap.xml",
                '压缩文件：' . url("/")."/sitemap.xml.gz",
                'URL 列表：' . url("/")."/urllist.txt",
                '当前状态：' . (file_exists($sitemapXmlPath) ? '已生成' : '未生成'),
            ];
            if (file_exists($sitemapXmlPath)) {
                $sitemapNotes[] = 'sitemap.xml 大小：' . round(filesize($sitemapXmlPath) / 1024, 2) . ' KB';
            }
            if (file_exists($sitemapGzPath)) {
                $sitemapNotes[] = 'sitemap.xml.gz 大小：' . round(filesize($sitemapGzPath) / 1024, 2) . ' KB';
            }
            if (file_exists($urlListPath)) {
                $sitemapNotes[] = 'urllist.txt 最近更新时间：' . date('Y-m-d H:i', filemtime($urlListPath));
            }

            $buttonHtml = '<button type="button" class="btn btn-sm btn-danger" onclick="getPage(\'' . addslashes(url('admin/system/seo/updateSitemap')) . '\', {_token: \'' . addslashes(csrf_token()) . '\'}, function (res) { var code = parseInt((res && (res.statusCode || res.code || res.status || 0)), 10); var msg = (res && (res.msg || res.message)) || \'更新失败\'; layer.msg(msg, {icon: code === 200 ? 1 : 2}); }, \'post\', \'json\'); return false;">更新 sitemap</button>';
            $formtool->field("sitemap","网站地图",$buttonHtml,'word')
                ->half()
                ->notes("sitemap", implode('；', $sitemapNotes));

            $formtool->field("seo_robots","robots.txt",$robotstxt)
                ->formtype("seo_robots","textarea")
                ->rows("seo_robots", 16)
                ->full()
                ->notes("seo_robots","常用指令：User-agent、Disallow、Allow、Crawl-delay、Sitemap。保存后会直接写入 public/robots.txt；下方带 `MXZCMS SEO DOMAIN LIMIT` 标记的区块会根据域名限制自动生成。");
        });

        $formtool->group("section3","蜘蛛限制", [
            'notes' => '用于限制指定域名的收录与蜘蛛访问。命中限制域名后，页面响应会自动附带 noindex,nofollow；命中蜘蛛关键词的请求会直接拦截。',
            'columns' => 2,
        ], function (FormTool $formtool) {
            $formtool->field("seo_limit_domain","域名限制",cacheGlobalSettingsByKey("seo_limit_domain"))
                ->notes("seo_limit_domain","多个域名可用逗号或换行分隔，例如：m.example.com、test.example.com。命中这些域名后，页面会返回 X-Robots-Tag: noindex,nofollow。");

            $formtool->field("seo_bot_keywords","蜘蛛常用关键词",cacheGlobalSettingsByKey("seo_bot_keywords"))
                ->formtype("seo_bot_keywords","textarea")
                ->rows("seo_bot_keywords", 6)
                ->notes("seo_bot_keywords","多个关键词可用回车、逗号或空格分隔，如：bot、crawl、spider、googlebot。命中限制域名且 UA 包含这些关键词时会直接拦截。");
        });

        $formtool->csrf_field();

        $formtool->formAction(url("admin/system/seo/config"));

        return $formtool->formView($pageData);

    }

    public function GetSeo($data){
        if($data['moduleName'] == "Main"){
            switch ($data['controller']){
                case "Home\Model":
                    $seoconfig = [
                        "title" => $this->replace($data,'title'),
                        "keywords" => $this->replace($data,'keyword'),
                        "description" => $this->replace($data,'description')
                    ];
                    break;
                default:
                    $seoconfig = [
                        "title" => $this->normalizeSeoText(cacheGlobalSettingsByKey("website_name"), 120),
                        "keywords" => $this->normalizeSeoText(cacheGlobalSettingsByKey("website_keys"), 255),
                        "description" => $this->normalizeSeoText(cacheGlobalSettingsByKey("website_desc"), 255)
                    ];
                    break;
            }
            return $seoconfig;
        }

    }

    private function replace($data,$tdk=null)
    {
        $data = $this->normalizeSeoContext($data);
        $homeSeoConfig = (array) ($data['model']->home_seo_config ?? []);
        $homeSeoDetailConfig = (array) ($data['model']->home_seo_detail_config ?? []);
        $detailTitle = $data['data']->seo_title ?? $data['data']->title ?? $data['data']->name ?? $data['model']->name ?? '';
        $detailName = $data['data']->name ?? $data['data']->title ?? $data['model']->name ?? '';
        $replaceMap = $this->buildReplaceMap($data);
        $string = "";
        switch ($tdk){
            case "title":
                if($data['model']->type=="multi" && $data['action']=="list"){
                    $subject = $homeSeoConfig['title']?:cacheGlobalSettingsByKey("seo_title")?:$data['model']->name;
                    $string = $this->renderSeoTemplate($subject, $replaceMap, 120);

                }else{
                    if($data['data']->seo_title || $data['data']->name || $data['data']->title){
                        $string = $this->normalizeSeoText($data['data']->seo_title?:$data['data']->name?:$data['data']->title, 120);
                    }else{
                        $subject = $homeSeoDetailConfig['title']?:cacheGlobalSettingsByKey("seo_title_detail")?:$data['model']->name;
                        $string = $this->renderSeoTemplate($subject, $replaceMap, 120);
                    }
                }


                break;
            case "keyword":
                if($data['model']->type=="multi" && $data['action']=="list"){
                    $subject = $homeSeoConfig['keyword']?:cacheGlobalSettingsByKey("seo_keywords")?:cacheGlobalSettingsByKey("website_keys");
                    $string = $this->renderSeoTemplate($subject, $replaceMap, 255);

                }else{
                    if($data['data']->seo_keywords){
                        $string = $this->normalizeSeoText($data['data']->seo_keywords, 255);
                    }else{
                        $subject = $homeSeoDetailConfig['keyword']?:cacheGlobalSettingsByKey("seo_keywords_detail")?:cacheGlobalSettingsByKey("website_keys");
                        $string = $this->renderSeoTemplate($subject, $replaceMap, 255);
                    }
                }
                break;
            case "description":
                if($data['model']->type=="multi" && $data['action']=="list"){
                    $subject = $homeSeoConfig['describe']?:cacheGlobalSettingsByKey("seo_website_desc")?:cacheGlobalSettingsByKey("website_desc");
                    $string = $this->renderSeoTemplate($subject, $replaceMap, 255);

                }else{
                    if($data['data']->seo_description){
                        $string = $this->normalizeSeoText($data['data']->seo_description, 255);
                    }else{
                        $subject = $homeSeoDetailConfig['describe']?:cacheGlobalSettingsByKey("seo_website_desc_detail")?:cacheGlobalSettingsByKey("seo_website_desc")?:cacheGlobalSettingsByKey("website_desc");
                        $string = $this->renderSeoTemplate($subject, $replaceMap, 255);
                        if(!$string && $data['data']->content){
                            $string = $this->excerptContent($data['data']->content, 190);
                        }
                    }
                }


            default:

        }

        return $string;
    }

}
