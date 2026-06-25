<?php

namespace Modules\Formtools\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Formtools\Models\FormModel;

class PageSchemaRenderer
{
    public static function render($schema, array $context = []): string
    {
        if (is_string($schema)) {
            $schema = json_decode($schema, true);
        }

        if (!is_array($schema)) {
            return '';
        }

        $nodes = $schema['sections'] ?? $schema['blocks'] ?? [];
        if (!is_array($nodes) || $nodes === []) {
            return '';
        }

        $html = [];
        foreach ($nodes as $node) {
            if (is_array($node) && self::shouldRenderNode($node, $context)) {
                $html[] = self::renderNode($node, $context);
            }
        }

        $responsiveStyleTag = self::buildResponsiveStyleTag($nodes);
        $themeAttributes = self::buildThemeRootAttributes(isset($schema['theme']) && is_array($schema['theme']) ? $schema['theme'] : []);

        return $responsiveStyleTag
            . '<div class="mx-page-root"' . $themeAttributes . '>'
            . implode("\n", array_filter($html))
            . '</div>';
    }

    private static function renderNode(array $node, array $context = []): string
    {
        if (!self::shouldRenderNode($node, $context)) {
            return '';
        }

        $type = (string) ($node['type'] ?? 'div');
        $props = isset($node['props']) && is_array($node['props']) ? $node['props'] : [];
        $style = self::buildStyleAttribute(isset($node['style']) && is_array($node['style']) ? $node['style'] : [], $node);
        $attributes = self::buildAttributes($node, $props);
        $childKey = self::getChildCollectionKey($type, $node);
        $children = $childKey !== '' && isset($node[$childKey]) && is_array($node[$childKey]) ? $node[$childKey] : [];

        if ($type === 'section') {
            return '<section' . $attributes . $style . '>' . self::renderSectionChildren($children, $props, $context) . '</section>';
        }

        if ($type === 'row') {
            return '<div' . self::buildAttributes($node, $props, ['mx-page-row']) . $style . '>' . self::renderChildren($children, $context) . '</div>';
        }

        if ($type === 'column') {
            $span = max(1, min(12, (int) ($props['span'] ?? 12)));
            $blocks = isset($node['blocks']) && is_array($node['blocks']) ? $node['blocks'] : $children;

            return '<div' . self::buildAttributes($node, $props, ['mx-page-col', 'mx-page-col--' . $span]) . $style . '>' . self::renderChildren($blocks, $context) . '</div>';
        }

        if ($type === 'heading') {
            $level = strtolower((string) ($props['level'] ?? 'h2'));
            $tag = in_array($level, ['h1', 'h2', 'h3', 'h4', 'h5', 'h6'], true) ? $level : 'h2';

            return '<' . $tag . $attributes . $style . '>' . e((string) ($props['text'] ?? '')) . '</' . $tag . '>';
        }

        if ($type === 'text') {
            return '<p' . $attributes . $style . '>' . nl2br(e((string) ($props['text'] ?? ''))) . '</p>';
        }

        if ($type === 'button') {
            $href = (string) ($props['href'] ?? '#');
            $label = (string) ($props['text'] ?? '按钮');
            $target = trim((string) ($props['target'] ?? ''));
            $targetAttribute = $target !== '' ? ' target="' . e($target) . '"' : '';
            $align = trim((string) ($props['align'] ?? 'left'));
            $align = in_array($align, ['left', 'center', 'right', 'full'], true) ? $align : 'left';
            $variant = trim((string) ($props['variant'] ?? 'solid'));
            $variant = in_array($variant, ['solid', 'outline', 'ghost'], true) ? $variant : 'solid';

            return '<a href="' . e($href) . '"' . $targetAttribute . self::buildAttributes($node, $props, ['mx-page-button', 'mx-page-button--' . $align, 'mx-page-button--' . $variant]) . $style . '>' . e($label) . '</a>';
        }

        if ($type === 'image') {
            $src = (string) ($props['src'] ?? '');
            $alt = (string) ($props['alt'] ?? '');
            if (trim((string) ($props['source_type'] ?? '')) === 'model_detail') {
                $payload = self::queryModelDetailItem($props, $context);
                $item = $payload['item'] ?? [];
                $src = trim((string) ($item['image'] ?? ''));
                $alt = trim((string) ($item['title'] ?? $alt));
                $href = trim((string) ($item['url'] ?? ''));
                if ($src !== '' && $href !== '') {
                    $align = trim((string) ($props['align'] ?? 'left'));
                    $align = in_array($align, ['left', 'center', 'right'], true) ? $align : 'left';
                    return '<a href="' . e($href) . '">' . '<img src="' . e($src) . '" alt="' . e($alt) . '"' . self::buildAttributes($node, $props, ['mx-page-image', 'mx-page-image--' . $align]) . $style . '>' . '</a>';
                }
            }
            if ($src === '') {
                return '';
            }
            $align = trim((string) ($props['align'] ?? 'left'));
            $align = in_array($align, ['left', 'center', 'right'], true) ? $align : 'left';
            return '<img src="' . e($src) . '" alt="' . e($alt) . '"' . self::buildAttributes($node, $props, ['mx-page-image', 'mx-page-image--' . $align]) . $style . '>';
        }

        if ($type === 'carousel') {
            return self::renderCarousel($node, $props, $style, $context);
        }

        if ($type === 'video') {
            return self::renderVideo($node, $props, $style);
        }

        if ($type === 'gallery') {
            return self::renderGallery($node, $props, $style, $context);
        }

        if ($type === 'faq') {
            return self::renderFaq($node, $props, $style);
        }

        if ($type === 'stats') {
            return self::renderStats($node, $props, $style);
        }

        if ($type === 'cta') {
            return self::renderCta($node, $props, $style);
        }

        if ($type === 'divider') {
            return '<hr' . $attributes . $style . '>';
        }

        if ($type === 'model_list') {
            return self::renderModelList($node, $props, $style, $context);
        }

        if ($type === 'model_detail') {
            return self::renderModelDetail($node, $props, $style, $context);
        }

        if ($type === 'html') {
            return '<div' . self::buildAttributes($node, $props, ['mx-page-html']) . $style . '>' . (string) ($props['html'] ?? '') . '</div>';
        }

        if ($type === 'navigation') {
            return self::renderNavigation($node, $props, $style);
        }

        if ($type === 'sidebar') {
            return self::renderSidebar($node, $props, $style);
        }

        if ($type === 'qrcode') {
            return self::renderQrCode($node, $props, $style);
        }

        if ($type === 'login_box') {
            return self::renderLoginBox($node, $props, $style, $context);
        }

        return '<div' . $attributes . $style . '>' . self::renderChildren($children, $context) . '</div>';
    }

    private static function renderChildren(array $children, array $context = []): string
    {
        $html = [];
        foreach ($children as $child) {
            if (is_array($child)) {
                $html[] = self::renderNode($child, $context);
            }
        }

        return implode("\n", array_filter($html));
    }

    private static function renderSectionChildren(array $children, array $props, array $context = []): string
    {
        $content = self::renderChildren($children, $context);
        if ((string) ($props['contentWidth'] ?? '') !== 'contained') {
            return $content;
        }

        $innerWidth = trim((string) ($props['innerWidth'] ?? '1180px'));
        $innerStyle = $innerWidth !== '' ? ' style="max-width:' . e($innerWidth) . ';"' : '';

        return '<div class="mx-page-section__inner"' . $innerStyle . '>' . $content . '</div>';
    }

    private static function renderCarousel(array $node, array $props, string $style, array $context = []): string
    {
        $slides = self::resolveCarouselSlides($props, $context);
        if ($slides === []) {
            return '<div' . self::buildAttributes($node, $props, ['mx-page-carousel']) . $style . '>'
                . '<div class="mx-page-empty">当前轮播还没有可展示的数据，请先补手动轮播项或绑定模型来源。</div>'
                . '</div>';
        }

        $slidesHtml = [];
        $dots = [];
        foreach ($slides as $index => $slide) {
            $isActive = $index === 0;
            $buttonText = trim((string) ($slide['buttonText'] ?? ''));
            $buttonHref = trim((string) ($slide['buttonHref'] ?? ''));
            $slidesHtml[] = '<article class="mx-page-carousel__slide' . ($isActive ? ' is-active' : '') . '" data-carousel-slide="' . $index . '" aria-hidden="' . ($isActive ? 'false' : 'true') . '">'
                . '<div class="mx-page-carousel__media">'
                . '<img src="' . e((string) ($slide['image'] ?? 'https://dummyimage.com/1600x720/e2e8f0/0f172a&text=Slide')) . '" alt="' . e((string) ($slide['title'] ?? '轮播图')) . '">'
                . '</div>'
                . '<div class="mx-page-carousel__overlay">'
                . '<div class="mx-page-carousel__meta">Slide ' . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) . ' / ' . e((string) count($slides)) . '</div>'
                . '<h2 class="mx-page-carousel__title">' . e((string) ($slide['title'] ?? '轮播标题')) . '</h2>'
                . (!empty($slide['description']) ? '<p class="mx-page-carousel__desc">' . e((string) $slide['description']) . '</p>' : '')
                . ($buttonText !== '' ? '<a class="mx-page-carousel__button" href="' . e($buttonHref !== '' ? $buttonHref : '#') . '">' . e($buttonText) . '</a>' : '')
                . '</div>'
                . '</article>';
            $dots[] = '<button type="button" class="mx-page-carousel__dot' . ($isActive ? ' is-active' : '') . '" data-carousel-dot="' . $index . '" aria-pressed="' . ($isActive ? 'true' : 'false') . '" aria-label="切换到第 ' . ($index + 1) . ' 张"></button>';
        }

        return '<section' . self::buildAttributes($node, $props, ['mx-page-carousel']) . ' data-carousel-autoplay="' . (((string) ($props['autoplay'] ?? '0') === '1') ? '1' : '0') . '" data-carousel-interval="' . e((string) ($props['interval'] ?? '4500')) . '"' . $style . '>'
            . '<div class="mx-page-carousel__slides">' . implode('', $slidesHtml) . '</div>'
            . '<div class="mx-page-carousel__dots">' . implode('', $dots) . '</div>'
            . '</section>';
    }

    private static function renderVideo(array $node, array $props, string $style): string
    {
        $sourceType = trim((string) ($props['source_type'] ?? 'embed'));
        $title = trim((string) ($props['title'] ?? ''));
        $ratio = self::normalizeAspectRatio((string) ($props['aspect_ratio'] ?? '16:9'));
        $ratioPadding = self::aspectRatioPadding($ratio);

        $inner = '';
        if ($sourceType === 'mp4') {
            $mp4Url = trim((string) ($props['mp4_url'] ?? ''));
            $poster = trim((string) ($props['poster'] ?? ''));
            if ($mp4Url === '') {
                $inner = '<div class="mx-page-empty">当前视频还没有 MP4 地址。</div>';
            } else {
                $attrs = [
                    'src="' . e($mp4Url) . '"',
                    'playsinline'
                ];
                if ($poster !== '') {
                    $attrs[] = 'poster="' . e($poster) . '"';
                }
                if ((string) ($props['controls'] ?? '1') !== '0') {
                    $attrs[] = 'controls';
                }
                if ((string) ($props['autoplay'] ?? '0') === '1') {
                    $attrs[] = 'autoplay';
                }
                if ((string) ($props['muted'] ?? '0') === '1') {
                    $attrs[] = 'muted';
                }
                if ((string) ($props['loop'] ?? '0') === '1') {
                    $attrs[] = 'loop';
                }
                $inner = '<video class="mx-page-video__frame" ' . implode(' ', $attrs) . '></video>';
            }
        } else {
            $embedUrl = trim((string) ($props['embed_url'] ?? ''));
            if ($embedUrl === '') {
                $inner = '<div class="mx-page-empty">当前视频还没有嵌入地址。</div>';
            } else {
                $inner = '<iframe class="mx-page-video__frame" src="' . e($embedUrl) . '" title="' . e($title !== '' ? $title : '视频播放') . '" allowfullscreen></iframe>';
            }
        }

        return '<section' . self::buildAttributes($node, $props, ['mx-page-video']) . $style . '>'
            . ($title !== '' ? '<div class="mx-page-video__title">' . e($title) . '</div>' : '')
            . '<div class="mx-page-video__viewport" style="padding-top:' . e($ratioPadding) . ';">' . $inner . '</div>'
            . '</section>';
    }

    private static function renderGallery(array $node, array $props, string $style, array $context = []): string
    {
        $title = trim((string) ($props['title'] ?? ''));
        $subtitle = trim((string) ($props['subtitle'] ?? ''));
        $columns = max(2, min(6, (int) ($props['columns'] ?? 3)));
        $gap = trim((string) ($props['gap'] ?? '18px'));
        $items = self::resolveGalleryItems($props, $context);
        if ($items === []) {
            return '<section' . self::buildAttributes($node, $props, ['mx-page-gallery']) . $style . '>'
                . '<div class="mx-page-empty">当前图库还没有可展示的数据，请先补手动图片项或绑定模型来源。</div>'
                . '</section>';
        }

        $cards = [];
        foreach ($items as $item) {
            $image = trim((string) ($item['image'] ?? ''));
            $cardTitle = trim((string) ($item['title'] ?? ''));
            $url = trim((string) ($item['url'] ?? ''));
            $media = '<div class="mx-page-gallery__media">'
                . '<img src="' . e($image !== '' ? $image : 'https://dummyimage.com/960x720/e2e8f0/0f172a&text=Gallery') . '" alt="' . e($cardTitle !== '' ? $cardTitle : '图库图片') . '">'
                . '</div>'
                . ($cardTitle !== '' ? '<div class="mx-page-gallery__caption">' . e($cardTitle) . '</div>' : '');
            if ($url !== '') {
                $media = '<a class="mx-page-gallery__card-link" href="' . e($url) . '">' . $media . '</a>';
            }
            $cards[] = '<article class="mx-page-gallery__card">' . $media . '</article>';
        }

        return '<section' . self::buildAttributes($node, $props, ['mx-page-gallery']) . $style . '>'
            . (($title !== '' || $subtitle !== '') ? '<div class="mx-page-gallery__head">'
                . ($title !== '' ? '<h3 class="mx-page-gallery__title">' . e($title) . '</h3>' : '')
                . ($subtitle !== '' ? '<p class="mx-page-gallery__subtitle">' . e($subtitle) . '</p>' : '')
                . '</div>' : '')
            . '<div class="mx-page-gallery__grid" style="grid-template-columns:repeat(' . $columns . ',minmax(0,1fr));' . ($gap !== '' ? 'gap:' . e($gap) . ';' : '') . '">'
            . implode('', $cards)
            . '</div>'
            . '</section>';
    }

    private static function renderFaq(array $node, array $props, string $style): string
    {
        $title = trim((string) ($props['title'] ?? ''));
        $intro = trim((string) ($props['intro'] ?? ''));
        $columns = max(1, min(2, (int) ($props['columns'] ?? 1)));
        $items = [];
        foreach ((array) ($props['items'] ?? []) as $item) {
            if (!is_array($item)) {
                continue;
            }
            $question = trim((string) ($item['question'] ?? ''));
            $answer = trim((string) ($item['answer'] ?? ''));
            if ($question === '' && $answer === '') {
                continue;
            }
            $isOpen = count($items) === 0;
            $items[] = '<article class="mx-page-faq__item' . ($isOpen ? ' is-open' : '') . '" data-faq-item>'
                . ($question !== '' ? '<button type="button" class="mx-page-faq__question" data-faq-trigger aria-expanded="' . ($isOpen ? 'true' : 'false') . '"><span>' . e($question) . '</span><span class="mx-page-faq__icon" aria-hidden="true"></span></button>' : '')
                . ($answer !== '' ? '<div class="mx-page-faq__answer" data-faq-panel' . ($isOpen ? '' : ' hidden') . '>' . nl2br(e($answer)) . '</div>' : '')
                . '</article>';
        }
        if ($items === []) {
            $items[] = '<div class="mx-page-empty">当前 FAQ 还没有问题项，请先补充问题和答案。</div>';
        }

        return '<section' . self::buildAttributes($node, $props, ['mx-page-faq']) . ' data-faq="accordion"' . $style . '>'
            . (($title !== '' || $intro !== '') ? '<div class="mx-page-faq__head">'
                . ($title !== '' ? '<h3 class="mx-page-faq__title">' . e($title) . '</h3>' : '')
                . ($intro !== '' ? '<p class="mx-page-faq__intro">' . e($intro) . '</p>' : '')
                . '</div>' : '')
            . '<div class="mx-page-faq__list mx-page-faq__list--cols-' . $columns . '">'
            . implode('', $items)
            . '</div>'
            . '</section>';
    }

    private static function renderStats(array $node, array $props, string $style): string
    {
        $title = trim((string) ($props['title'] ?? ''));
        $intro = trim((string) ($props['intro'] ?? ''));
        $columns = max(2, min(6, (int) ($props['columns'] ?? 4)));
        $items = [];
        foreach ((array) ($props['items'] ?? []) as $item) {
            if (!is_array($item)) {
                continue;
            }
            $label = trim((string) ($item['label'] ?? ''));
            $value = trim((string) ($item['value'] ?? ''));
            $suffix = trim((string) ($item['suffix'] ?? ''));
            $description = trim((string) ($item['description'] ?? ''));
            if ($label === '' && $value === '' && $description === '') {
                continue;
            }
            $items[] = '<article class="mx-page-stats__item">'
                . ($label !== '' ? '<div class="mx-page-stats__label">' . e($label) . '</div>' : '')
                . '<div class="mx-page-stats__value">' . e($value !== '' ? $value : '0') . ($suffix !== '' ? '<span>' . e($suffix) . '</span>' : '') . '</div>'
                . ($description !== '' ? '<div class="mx-page-stats__desc">' . e($description) . '</div>' : '')
                . '</article>';
        }
        if ($items === []) {
            $items[] = '<div class="mx-page-empty">当前数据组件还没有指标项，请先补充数字内容。</div>';
        }

        return '<section' . self::buildAttributes($node, $props, ['mx-page-stats']) . $style . '>'
            . (($title !== '' || $intro !== '') ? '<div class="mx-page-stats__head">'
                . ($title !== '' ? '<h3 class="mx-page-stats__title">' . e($title) . '</h3>' : '')
                . ($intro !== '' ? '<p class="mx-page-stats__intro">' . e($intro) . '</p>' : '')
                . '</div>' : '')
            . '<div class="mx-page-stats__grid" style="grid-template-columns:repeat(' . $columns . ',minmax(0,1fr));">'
            . implode('', $items)
            . '</div>'
            . '</section>';
    }

    private static function renderCta(array $node, array $props, string $style): string
    {
        $eyebrow = trim((string) ($props['eyebrow'] ?? ''));
        $title = trim((string) ($props['title'] ?? ''));
        $description = trim((string) ($props['description'] ?? ''));
        $primaryText = trim((string) ($props['primaryText'] ?? ''));
        $primaryHref = trim((string) ($props['primaryHref'] ?? ''));
        $secondaryText = trim((string) ($props['secondaryText'] ?? ''));
        $secondaryHref = trim((string) ($props['secondaryHref'] ?? ''));
        $align = trim((string) ($props['align'] ?? 'left'));
        $align = in_array($align, ['left', 'center'], true) ? $align : 'left';
        $actionsAlign = trim((string) ($props['actionsAlign'] ?? ($align === 'center' ? 'center' : 'left')));
        $actionsAlign = in_array($actionsAlign, ['left', 'center', 'right'], true) ? $actionsAlign : 'left';
        $primaryVariant = trim((string) ($props['primaryVariant'] ?? 'solid'));
        $primaryVariant = in_array($primaryVariant, ['solid', 'outline', 'ghost'], true) ? $primaryVariant : 'solid';
        $secondaryVariant = trim((string) ($props['secondaryVariant'] ?? 'ghost'));
        $secondaryVariant = in_array($secondaryVariant, ['solid', 'outline', 'ghost'], true) ? $secondaryVariant : 'ghost';

        return '<section' . self::buildAttributes($node, $props, ['mx-page-cta', 'mx-page-cta--' . $align]) . $style . '>'
            . '<div class="mx-page-cta__body">'
            . ($eyebrow !== '' ? '<div class="mx-page-cta__eyebrow">' . e($eyebrow) . '</div>' : '')
            . ($title !== '' ? '<h3 class="mx-page-cta__title">' . e($title) . '</h3>' : '')
            . ($description !== '' ? '<p class="mx-page-cta__desc">' . e($description) . '</p>' : '')
            . '<div class="mx-page-cta__actions mx-page-cta__actions--' . e($actionsAlign) . '">'
            . ($primaryText !== '' ? '<a class="mx-page-cta__button mx-page-cta__button--' . e($primaryVariant) . '" href="' . e($primaryHref !== '' ? $primaryHref : '#') . '">' . e($primaryText) . '</a>' : '')
            . ($secondaryText !== '' ? '<a class="mx-page-cta__button mx-page-cta__button--' . e($secondaryVariant) . '" href="' . e($secondaryHref !== '' ? $secondaryHref : '#') . '">' . e($secondaryText) . '</a>' : '')
            . '</div>'
            . '</div>'
            . '</section>';
    }

    private static function renderModelList(array $node, array $props, string $style, array $context = []): string
    {
        $limit = max(1, min(24, (int) ($props['limit'] ?? 6)));
        $template = trim((string) ($props['template'] ?? 'card'));
        $title = trim((string) ($props['title'] ?? '模型列表'));
        $payload = self::queryModelListItems($props, $context);

        if (($payload['items'] ?? []) === []) {
            $message = $payload['message'] ?? '当前模型还没有可展示的数据。';
            return '<section' . self::buildAttributes($node, $props, ['mx-page-model-list', 'mx-page-model-list--' . $template]) . $style . '>'
                . ($title !== '' ? '<div class="mx-page-model-list__head"><h3 class="mx-page-model-list__title">' . e($title) . '</h3></div>' : '')
                . '<div class="mx-page-empty">' . e($message) . '</div>'
                . '</section>';
        }

        $items = [];
        foreach ($payload['items'] as $item) {
            $items[] = self::renderModelListItem($item, $template);
        }

        return '<section' . self::buildAttributes($node, $props, ['mx-page-model-list', 'mx-page-model-list--' . $template]) . $style . '>'
            . ($title !== '' ? '<div class="mx-page-model-list__head"><h3 class="mx-page-model-list__title">' . e($title) . '</h3></div>' : '')
            . '<div class="mx-page-model-list__grid mx-page-model-list__grid--' . e($template) . '">' . implode('', $items) . '</div>'
            . '</section>';
    }

    private static function renderModelListItem(array $item, string $template): string
    {
        $title = trim((string) ($item['title'] ?? '未命名内容'));
        $summary = trim((string) ($item['summary'] ?? ''));
        $date = trim((string) ($item['date'] ?? ''));
        $image = trim((string) ($item['image'] ?? ''));
        $url = trim((string) ($item['url'] ?? ''));
        $titleHtml = $url !== ''
            ? '<a class="mx-page-model-card__title-link" href="' . e($url) . '">' . e($title) . '</a>'
            : e($title);
        $footerHtml = $url !== ''
            ? '<a class="mx-page-model-card__link" href="' . e($url) . '">查看详情</a>'
            : '<span class="mx-page-model-card__link is-static">内容详情</span>';
        $metaHtml = $date !== '' ? '<div class="mx-page-model-card__meta">' . e($date) . '</div>' : '';
        $summaryHtml = $summary !== '' ? '<p class="mx-page-model-card__summary">' . e($summary) . '</p>' : '';
        $mediaHtml = $image !== ''
            ? '<div class="mx-page-model-card__media"><img src="' . e($image) . '" alt="' . e($title) . '"></div>'
            : '';

        if ($template === 'list') {
            return '<article class="mx-page-model-card mx-page-model-card--list">'
                . $mediaHtml
                . '<div class="mx-page-model-card__content">'
                . $metaHtml
                . '<h4 class="mx-page-model-card__title">' . $titleHtml . '</h4>'
                . $summaryHtml
                . '<div class="mx-page-model-card__footer">' . $footerHtml . '</div>'
                . '</div>'
                . '</article>';
        }

        return '<article class="mx-page-model-card">'
            . $mediaHtml
            . '<div class="mx-page-model-card__content">'
            . $metaHtml
            . '<h4 class="mx-page-model-card__title">' . $titleHtml . '</h4>'
            . $summaryHtml
            . '<div class="mx-page-model-card__footer">' . $footerHtml . '</div>'
            . '</div>'
            . '</article>';
    }

    private static function renderModelDetail(array $node, array $props, string $style, array $context = []): string
    {
        $template = trim((string) ($props['template'] ?? 'detail'));
        $title = trim((string) ($props['title'] ?? '模型详情'));
        $payload = self::queryModelDetailItem($props, $context);

        if (empty($payload['item'])) {
            return '<section' . self::buildAttributes($node, $props, ['mx-page-model-detail']) . $style . '>'
                . ($title !== '' ? '<div class="mx-page-model-detail__head"><h3 class="mx-page-model-detail__title">' . e($title) . '</h3></div>' : '')
                . '<div class="mx-page-empty">' . e($payload['message'] ?? '当前没有可展示的详情内容。') . '</div>'
                . '</section>';
        }

        $item = $payload['item'];
        $detailTitle = trim((string) ($item['title'] ?? '未命名内容'));
        $summary = trim((string) ($item['summary'] ?? ''));
        $content = trim((string) ($item['content'] ?? ''));
        $date = trim((string) ($item['date'] ?? ''));
        $image = trim((string) ($item['image'] ?? ''));
        $url = trim((string) ($item['url'] ?? ''));
        $titleHtml = $url !== ''
            ? '<a class="mx-page-placeholder-link" href="' . e($url) . '">' . e($detailTitle) . '</a>'
            : e($detailTitle);
        $actionHtml = $url !== ''
            ? '<div class="mx-page-detail-card__actions"><a class="mx-page-detail-card__button" href="' . e($url) . '">继续阅读</a></div>'
            : '';

        return '<section' . self::buildAttributes($node, $props, ['mx-page-model-detail']) . $style . '>'
            . ($title !== '' ? '<div class="mx-page-model-detail__head"><h3 class="mx-page-model-detail__title">' . e($title) . '</h3></div>' : '')
            . '<article class="mx-page-detail-card' . ($image !== '' ? ' mx-page-detail-card--media' : '') . '">'
            . ($image !== '' ? '<div class="mx-page-detail-card__media"><img src="' . e($image) . '" alt="' . e($detailTitle) . '"></div>' : '')
            . '<div class="mx-page-detail-card__content">'
            . ($date !== '' ? '<div class="mx-page-detail-card__meta">' . e($date) . '</div>' : '')
            . '<h2 class="mx-page-detail-card__title">' . $titleHtml . '</h2>'
            . ($summary !== '' ? '<p class="mx-page-detail-card__summary">' . e($summary) . '</p>' : '')
            . ($content !== '' ? '<div class="mx-page-detail-card__body">' . nl2br(e($content)) . '</div>' : '')
            . $actionHtml
            . '</div>'
            . '</article>'
            . '</section>';
    }

    private static function renderNavigation(array $node, array $props, string $style): string
    {
        $title = trim((string) ($props['title'] ?? ''));
        $logoType = trim((string) ($props['logoType'] ?? 'text'));
        $brandHref = trim((string) ($props['brandHref'] ?? '/'));
        $logoImage = trim((string) ($props['logoImage'] ?? ''));
        $logoSvg = trim((string) ($props['logoSvg'] ?? ''));
        $logoAlt = trim((string) ($props['logoAlt'] ?? ($title !== '' ? $title : '品牌 Logo')));
        $layout = trim((string) ($props['layout'] ?? 'horizontal')) === 'vertical' ? 'vertical' : 'horizontal';
        $items = self::normalizeLinkItems($props['items'] ?? []);
        $ctaText = trim((string) ($props['ctaText'] ?? ''));
        $ctaHref = trim((string) ($props['ctaHref'] ?? ''));
        if ($items === []) {
            $items = [
                ['text' => '首页', 'href' => '/'],
                ['text' => '产品', 'href' => '/products'],
                ['text' => '联系我们', 'href' => '/contact'],
            ];
        }

        $links = [];
        foreach ($items as $item) {
            $links[] = self::renderNavigationItem($item);
        }

        return '<nav' . self::buildAttributes($node, $props, ['mx-page-nav', 'mx-page-nav--' . $layout]) . $style . '>'
            . self::renderNavigationBrand($logoType, $title, $brandHref, $logoImage, $logoSvg, $logoAlt)
            . '<div class="mx-page-nav__list">' . implode('', $links) . '</div>'
            . ($ctaText !== '' ? '<a class="mx-page-nav__cta" href="' . e($ctaHref !== '' ? $ctaHref : '#') . '">' . e($ctaText) . '</a>' : '')
            . '</nav>';
    }

    private static function renderSidebar(array $node, array $props, string $style): string
    {
        $title = trim((string) ($props['title'] ?? ''));
        $position = trim((string) ($props['position'] ?? 'right')) === 'left' ? 'left' : 'right';
        $offsetTop = trim((string) ($props['offsetTop'] ?? '120px'));
        $showBackTop = (string) ($props['showBackTop'] ?? '1') === '1';
        $items = self::normalizeSidebarItems($props['items'] ?? []);
        $itemHtml = [];
        $panelHtml = [];
        foreach ($items as $item) {
            $label = trim((string) ($item['text'] ?? '快捷入口'));
            $href = trim((string) ($item['href'] ?? '#'));
            $actionType = trim((string) ($item['actionType'] ?? 'link')) === 'panel' ? 'panel' : 'link';
            $panelId = self::buildSidebarPanelId((string) ($node['id'] ?? 'sidebar'), count($panelHtml));
            $iconMarkup = self::renderSidebarIcon((string) ($item['icon'] ?? ''), $label);
            $contentMarkup = '<span class="mx-page-sidebar__content"><span class="mx-page-sidebar__text">' . e($label) . '</span></span>';
            $styleAttr = self::buildSidebarItemStyleAttribute($item);
            if ($actionType === 'panel') {
                $panelHtml[] = self::renderSidebarPanel($item, $panelId);
                $itemHtml[] = '<button type="button" class="mx-page-sidebar__link mx-page-sidebar__trigger" data-sidebar-panel-trigger="' . e($panelId) . '" aria-expanded="false"' . $styleAttr . '>' . $iconMarkup . $contentMarkup . '</button>';
                continue;
            }
            $itemHtml[] = '<a class="mx-page-sidebar__link" href="' . e($href !== '' ? $href : '#') . '" title="' . e($label) . '"' . $styleAttr . '>' . $iconMarkup . $contentMarkup . '</a>';
        }

        return '<aside' . self::buildAttributes($node, $props, ['mx-page-sidebar', 'mx-page-sidebar--' . $position]) . ' data-sidebar-position="' . e($position) . '" data-sidebar-offset="' . e($offsetTop !== '' ? $offsetTop : '120px') . '"' . $style . '>'
            . ($title !== '' ? '<div class="mx-page-sidebar__title">' . e($title) . '</div>' : '')
            . ($itemHtml !== [] ? '<div class="mx-page-sidebar__list">' . implode('', $itemHtml) . '</div>' : '')
            . ($showBackTop ? '<button type="button" class="mx-page-sidebar__backtop" data-sidebar-backtop="1"><span class="mx-page-sidebar__icon">Top</span><span class="mx-page-sidebar__content"><span class="mx-page-sidebar__text">返回顶部</span></span></button>' : '')
            . ($panelHtml !== [] ? '<div class="mx-page-sidebar__panels">' . implode('', $panelHtml) . '</div>' : '')
            . '</aside>';
    }

    private static function renderQrCode(array $node, array $props, string $style): string
    {
        $title = trim((string) ($props['title'] ?? '扫码咨询'));
        $text = trim((string) ($props['text'] ?? ''));
        $value = trim((string) ($props['value'] ?? ''));
        $size = max(96, min(320, (int) ($props['size'] ?? 140)));
        $qrUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=' . $size . 'x' . $size . '&data=' . rawurlencode($value !== '' ? $value : 'https://example.com');

        return '<div' . self::buildAttributes($node, $props, ['mx-page-qrcode']) . $style . '>'
            . '<div class="mx-page-qrcode__image"><img src="' . e($qrUrl) . '" alt="' . e($title) . '"></div>'
            . '<div class="mx-page-qrcode__title">' . e($title) . '</div>'
            . ($text !== '' ? '<div class="mx-page-qrcode__text">' . e($text) . '</div>' : '')
            . ($value !== '' ? '<div class="mx-page-qrcode__value">' . e($value) . '</div>' : '')
            . '</div>';
    }

    private static function renderLoginBox(array $node, array $props, string $style, array $context = []): string
    {
        $title = trim((string) ($props['title'] ?? '账号入口'));
        $loginHref = trim((string) ($props['loginHref'] ?? '/login'));
        $loginText = trim((string) ($props['loginText'] ?? '立即登录'));
        $profileText = trim((string) ($props['profileText'] ?? '个人中心'));
        $profileHref = trim((string) ($props['profileHref'] ?? '/member'));
        $authUserName = trim((string) ($context['auth_user_name'] ?? ($props['authUserName'] ?? '')));
        $authUserAvatar = trim((string) ($props['avatarUrl'] ?? ''));

        if ($authUserName === '') {
            $authUserName = self::extractAuthUserName(auth()->user());
        }
        if ($authUserAvatar === '') {
            $authUserAvatar = trim((string) ($context['auth_user_avatar'] ?? ($props['authUserAvatar'] ?? '')));
        }
        if ($authUserAvatar === '') {
            $authUserAvatar = self::extractAuthUserAvatar(auth()->user());
        }

        $isLoggedIn = auth()->check();
        $avatarMarkup = $authUserAvatar !== ''
            ? '<span class="mx-page-login-box__avatar"><img src="' . e($authUserAvatar) . '" alt="' . e($authUserName !== '' ? $authUserName : $profileText) . '"></span>'
            : '<span class="mx-page-login-box__avatar is-fallback">' . e(self::makeAvatarLetter($authUserName !== '' ? $authUserName : $profileText)) . '</span>';

        return '<div' . self::buildAttributes($node, $props, ['mx-page-login-box']) . $style . '>'
            . ($title !== '' ? '<div class="mx-page-login-box__label">' . e($title) . '</div>' : '')
            . ($isLoggedIn
                ? '<a class="mx-page-login-box__profile" href="' . e($profileHref !== '' ? $profileHref : '#') . '">' . $avatarMarkup . '<span class="mx-page-login-box__profile-text">' . e($authUserName !== '' ? $authUserName : $profileText) . '</span></a>'
                : '<a class="mx-page-login-box__button" href="' . e($loginHref !== '' ? $loginHref : '#') . '">' . e($loginText) . '</a>')
            . '</div>';
    }

    private static function queryModelListItems(array $props, array $context = []): array
    {
        $limit = max(1, min(24, (int) ($props['limit'] ?? 6)));
        $template = trim((string) ($props['template'] ?? 'card'));
        [$resolved, $error] = self::resolveModelSource($props, $context);
        if ($error !== null) {
            return [
                'items' => [],
                'meta' => 'template: ' . $template . ' / limit: ' . $limit,
                'message' => $error,
            ];
        }

        [$model, $tableName, $columns, $fields] = $resolved;

        $query = DB::table($tableName);
        if (in_array('status', $columns, true) && (string) ($props['status'] ?? 'publish') !== 'all') {
            $query->where('status', 1);
        }
        self::applyModelListOrder($query, $columns, $props);
        $rows = $query->limit($limit)->get();

        $titleField = self::pickColumnField($props, $fields, $columns, ['title_field'], ['title', 'name', 'cate_name', 'company_name', 'full_name', 'username']);
        $imageField = self::pickColumnField($props, $fields, $columns, ['image_field', 'cover_field'], ['cover', 'thumb', 'image', 'logo', 'avatar', 'pic']);
        $summaryField = self::pickColumnField($props, $fields, $columns, ['summary_field', 'desc_field'], ['description', 'summary', 'remark', 'content', 'company_address']);
        $dateField = self::pickColumnField($props, $fields, $columns, ['date_field'], ['created_at', 'updated_at', 'date', 'publish_time']);
        $urlField = self::pickColumnField($props, $fields, $columns, ['url_field', 'link_field'], ['url', 'link']);
        $detailPrefix = trim((string) ($props['detail_prefix'] ?? ''));

        $items = [];
        foreach ($rows as $row) {
            $rowArray = (array) $row;
            $title = self::extractTextValue($rowArray, $titleField, ['title', 'name', 'cate_name', 'company_name', 'full_name', 'username', 'id']);
            $summary = self::extractSummaryValue($rowArray, $summaryField);
            $date = self::extractDateValue($rowArray, $dateField);
            $image = self::extractImageValue($rowArray, $imageField);
            $url = self::extractUrlValue($rowArray, $urlField, $detailPrefix);

            $items[] = [
                'title' => $title,
                'summary' => $summary,
                'date' => $date,
                'image' => $image,
                'url' => $url,
            ];
        }

        return [
            'items' => $items,
            'meta' => '模型: ' . $model->name . ' / template: ' . $template . ' / limit: ' . $limit,
        ];
    }

    private static function queryModelDetailItem(array $props, array $context = []): array
    {
        $template = trim((string) ($props['template'] ?? 'detail'));
        [$resolved, $error] = self::resolveModelSource($props, $context);
        if ($error !== null) {
            return [
                'item' => null,
                'meta' => 'template: ' . $template,
                'message' => $error,
            ];
        }

        [$model, $tableName, $columns, $fields] = $resolved;
        $query = DB::table($tableName);
        if (in_array('status', $columns, true) && (string) ($props['status'] ?? 'publish') !== 'all') {
            $query->where('status', 1);
        }

        $recordId = (int) ($props['record_id'] ?? $props['id'] ?? 0);
        if ($recordId > 0 && in_array('id', $columns, true)) {
            $query->where('id', $recordId);
        } else {
            self::applyModelListOrder($query, $columns, $props);
        }

        $row = $query->first();
        if (!$row) {
            return [
                'item' => null,
                'meta' => '模型: ' . $model->name . ' / template: ' . $template,
                'message' => '模型 `' . $model->name . '` 当前没有可展示的详情数据。',
            ];
        }

        $titleField = self::pickColumnField($props, $fields, $columns, ['title_field'], ['title', 'name', 'cate_name', 'company_name', 'full_name', 'username']);
        $imageField = self::pickColumnField($props, $fields, $columns, ['image_field', 'cover_field'], ['cover', 'thumb', 'image', 'logo', 'avatar', 'pic']);
        $summaryField = self::pickColumnField($props, $fields, $columns, ['summary_field', 'desc_field'], ['description', 'summary', 'remark']);
        $contentField = self::pickColumnField($props, $fields, $columns, ['content_field', 'body_field'], ['content', 'body', 'detail']);
        $dateField = self::pickColumnField($props, $fields, $columns, ['date_field'], ['created_at', 'updated_at', 'date', 'publish_time']);
        $urlField = self::pickColumnField($props, $fields, $columns, ['url_field', 'link_field'], ['url', 'link']);
        $detailPrefix = trim((string) ($props['detail_prefix'] ?? ''));
        $rowArray = (array) $row;

        return [
            'item' => [
                'title' => self::extractTextValue($rowArray, $titleField, ['title', 'name', 'cate_name', 'company_name', 'full_name', 'username', 'id']),
                'summary' => self::extractSummaryValue($rowArray, $summaryField),
                'content' => self::extractContentValue($rowArray, $contentField),
                'date' => self::extractDateValue($rowArray, $dateField),
                'image' => self::extractImageValue($rowArray, $imageField),
                'url' => self::extractUrlValue($rowArray, $urlField, $detailPrefix),
            ],
            'meta' => '模型: ' . $model->name . ' / template: ' . $template,
        ];
    }

    private static function resolveModelSource(array $props, array $context = []): array
    {
        $modelIdentification = trim((string) ($props['model'] ?? ($context['page_model_identification'] ?? '')));
        if ($modelIdentification === '') {
            return [null, '请先在区块 props 里填写 model 标识，例如 news。'];
        }

        $model = FormModel::query()->where('identification', $modelIdentification)->first();
        if (!$model) {
            return [null, '模型 `' . $modelIdentification . '` 不存在。'];
        }

        $tableName = 'module_formtools_' . $modelIdentification;
        if (!Schema::hasTable($tableName)) {
            return [null, '模型 `' . $model->name . '` 的数据表不存在。'];
        }

        $columns = Schema::getColumnListing($tableName);
        $fields = json_decode((string) ($model->fields ?? ''), true);
        $fields = is_array($fields) ? $fields : [];

        return [[
            $model,
            $tableName,
            $columns,
            $fields,
        ], null];
    }

    private static function applyModelListOrder($query, array $columns, array $props): void
    {
        $orderBy = trim((string) ($props['order_by'] ?? ''));
        $direction = strtolower(trim((string) ($props['order_direction'] ?? 'desc')));
        $direction = in_array($direction, ['asc', 'desc'], true) ? $direction : 'desc';

        if ($orderBy !== '' && in_array($orderBy, $columns, true)) {
            $query->orderBy($orderBy, $direction);
            return;
        }

        if (in_array('sorts', $columns, true)) {
            $query->orderBy('sorts', 'desc');
        } elseif (in_array('sort', $columns, true)) {
            $query->orderBy('sort', 'desc');
        }

        if (in_array('id', $columns, true)) {
            $query->orderBy('id', 'desc');
        }
    }

    private static function pickColumnField(array $props, array $fields, array $columns, array $propKeys, array $fallbacks): string
    {
        foreach ($propKeys as $propKey) {
            $value = trim((string) ($props[$propKey] ?? ''));
            if ($value !== '' && in_array($value, $columns, true)) {
                return $value;
            }
        }

        foreach ($fallbacks as $fallback) {
            if (in_array($fallback, $columns, true)) {
                return $fallback;
            }
        }

        foreach ($fields as $field) {
            $identification = (string) ($field['identification'] ?? '');
            if ($identification !== '' && in_array($identification, $columns, true) && !in_array($identification, ['id', 'status', 'uid'], true)) {
                return $identification;
            }
        }

        return '';
    }

    private static function extractTextValue(array $row, string $primaryField, array $fallbacks): string
    {
        $candidates = array_filter(array_merge([$primaryField], $fallbacks));
        foreach ($candidates as $field) {
            if (!array_key_exists($field, $row)) {
                continue;
            }
            $value = trim(strip_tags((string) $row[$field]));
            if ($value !== '') {
                return $value;
            }
        }

        return '未命名内容';
    }

    private static function extractSummaryValue(array $row, string $summaryField): string
    {
        $value = '';
        if ($summaryField !== '' && array_key_exists($summaryField, $row)) {
            $value = (string) $row[$summaryField];
        }

        if ($value === '') {
            foreach (['description', 'summary', 'remark', 'content', 'company_address'] as $field) {
                if (!empty($row[$field])) {
                    $value = (string) $row[$field];
                    break;
                }
            }
        }

        $value = trim(preg_replace('/\s+/', ' ', strip_tags($value)));
        if ($value === '') {
            return '';
        }

        return mb_substr($value, 0, 88);
    }

    private static function extractContentValue(array $row, string $contentField): string
    {
        $value = '';
        if ($contentField !== '' && array_key_exists($contentField, $row)) {
            $value = (string) $row[$contentField];
        }

        if ($value === '') {
            foreach (['content', 'body', 'detail', 'description'] as $field) {
                if (!empty($row[$field])) {
                    $value = (string) $row[$field];
                    break;
                }
            }
        }

        $value = trim(preg_replace('/\s+/', ' ', strip_tags($value)));
        if ($value === '') {
            return '';
        }

        return mb_substr($value, 0, 500);
    }

    private static function extractDateValue(array $row, string $dateField): string
    {
        $value = $dateField !== '' ? (string) ($row[$dateField] ?? '') : '';
        if ($value === '') {
            foreach (['created_at', 'updated_at', 'date'] as $field) {
                if (!empty($row[$field])) {
                    $value = (string) $row[$field];
                    break;
                }
            }
        }

        return trim($value);
    }

    private static function extractImageValue(array $row, string $imageField): string
    {
        $value = $imageField !== '' ? (string) ($row[$imageField] ?? '') : '';
        if ($value === '') {
            foreach (['cover', 'thumb', 'image', 'logo', 'avatar', 'pic'] as $field) {
                if (!empty($row[$field])) {
                    $value = (string) $row[$field];
                    break;
                }
            }
        }

        return $value !== '' ? (string) GetUrlByPath($value) : '';
    }

    private static function extractUrlValue(array $row, string $urlField, string $detailPrefix): string
    {
        if ($urlField !== '' && !empty($row[$urlField])) {
            return (string) $row[$urlField];
        }

        if ($detailPrefix !== '' && isset($row['id'])) {
            return rtrim($detailPrefix, '/') . '/' . $row['id'];
        }

        return '';
    }

    private static function shouldRenderNode(array $node, array $context = []): bool
    {
        $visibility = isset($node['visibility']) && is_array($node['visibility']) ? $node['visibility'] : [];
        $effect = trim((string) ($visibility['effect'] ?? 'always'));
        if ($effect === '' || $effect === 'always') {
            return true;
        }

        $rule = trim((string) ($visibility['rule'] ?? ''));
        if ($rule === '') {
            return true;
        }

        $matched = self::matchesVisibilityRule($visibility, $context);
        if ($effect === 'show') {
            return $matched;
        }
        if ($effect === 'hide') {
            return !$matched;
        }

        return true;
    }

    private static function matchesVisibilityRule(array $visibility, array $context = []): bool
    {
        $primaryMatched = self::matchesSingleVisibilityRule(
            trim((string) ($visibility['rule'] ?? '')),
            $visibility,
            '',
            $context
        );
        $extraRule = trim((string) ($visibility['extraRule'] ?? ''));
        if ($extraRule === '') {
            return $primaryMatched;
        }

        $extraMatched = self::matchesSingleVisibilityRule($extraRule, $visibility, 'extra', $context);
        $logic = trim((string) ($visibility['logic'] ?? 'all'));

        return $logic === 'any'
            ? ($primaryMatched || $extraMatched)
            : ($primaryMatched && $extraMatched);
    }

    private static function matchesSingleVisibilityRule(string $rule, array $visibility, string $prefix, array $context = []): bool
    {
        if ($rule === 'logged_in') {
            return !empty($context['auth_check']);
        }
        if ($rule === 'guest') {
            return empty($context['auth_check']);
        }
        if ($rule === 'url_param') {
            $param = trim((string) ($visibility[$prefix !== '' ? ($prefix . 'Param') : 'param'] ?? ''));
            $expected = trim((string) ($visibility[$prefix !== '' ? ($prefix . 'Value') : 'value'] ?? ''));
            $query = isset($context['query']) && is_array($context['query']) ? $context['query'] : [];
            if ($param === '' || !array_key_exists($param, $query)) {
                return false;
            }
            if ($expected === '') {
                return true;
            }
            return trim((string) $query[$param]) === $expected;
        }
        if ($rule === 'device') {
            $device = trim((string) ($context['device'] ?? 'desktop'));
            $targets = self::normalizeStringList($visibility[$prefix !== '' ? ($prefix . 'Devices') : 'devices'] ?? ($visibility[$prefix !== '' ? ($prefix . 'Device') : 'device'] ?? ''));
            return in_array($device, $targets, true);
        }

        return true;
    }

    private static function isCurrentNavHref(string $href): bool
    {
        $href = trim($href);
        if ($href === '' || $href === '#') {
            return false;
        }

        $currentPath = trim((string) parse_url(request()->getRequestUri(), PHP_URL_PATH));
        $targetPath = trim((string) parse_url($href, PHP_URL_PATH));
        if ($targetPath === '') {
            $targetPath = $href;
        }

        return rtrim($currentPath, '/') === rtrim($targetPath, '/');
    }

    private static function normalizeStringList($value): array
    {
        if (is_array($value)) {
            return array_values(array_filter(array_map(static function ($item) {
                return trim((string) $item);
            }, $value)));
        }

        $value = trim((string) $value);
        if ($value === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', preg_split('/[\s,|]+/', $value) ?: [])));
    }

    private static function normalizeLinkItems($items): array
    {
        if (!is_array($items)) {
            return [];
        }

        $result = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $text = trim((string) ($item['text'] ?? ''));
            $href = trim((string) ($item['href'] ?? '#'));
            if ($text === '') {
                continue;
            }
            $result[] = [
                'text' => $text,
                'href' => $href !== '' ? $href : '#',
                'children' => self::normalizeLinkItems($item['children'] ?? []),
            ];
        }

        return $result;
    }

    private static function renderNavigationItem(array $item): string
    {
        $children = isset($item['children']) && is_array($item['children']) ? $item['children'] : [];
        $classes = ['mx-page-nav__link'];
        $childHtml = '';
        $isCurrent = self::isCurrentNavHref((string) ($item['href'] ?? '#'));

        if ($children !== []) {
            $classes[] = 'has-children';
            $childItems = [];
            foreach ($children as $child) {
                if (self::isCurrentNavHref((string) ($child['href'] ?? '#'))) {
                    $isCurrent = true;
                }
                $childItems[] = '<a class="mx-page-nav__submenu-link' . (self::isCurrentNavHref((string) ($child['href'] ?? '#')) ? ' is-active' : '') . '" href="' . e($child['href'] ?? '#') . '">' . e($child['text'] ?? '子菜单') . '</a>';
            }
            $childHtml = '<div class="mx-page-nav__submenu">' . implode('', $childItems) . '</div>';
        }

        if ($isCurrent) {
            $classes[] = 'is-active';
        }

        return '<div class="mx-page-nav__item">'
            . '<a class="' . e(implode(' ', $classes)) . '" href="' . e($item['href'] ?? '#') . '">' . e($item['text'] ?? '导航项') . '</a>'
            . $childHtml
            . '</div>';
    }

    private static function renderNavigationBrand(string $logoType, string $title, string $brandHref, string $logoImage, string $logoSvg, string $logoAlt): string
    {
        $brandBody = '';
        $logoType = in_array($logoType, ['text', 'image', 'svg', 'image_text'], true) ? $logoType : 'text';
        if (($logoType === 'image' || $logoType === 'image_text') && $logoImage !== '') {
            $brandBody .= '<span class="mx-page-nav__brand-logo"><img src="' . e($logoImage) . '" alt="' . e($logoAlt) . '"></span>';
        }
        if (($logoType === 'svg' || $logoType === 'image_text') && $logoSvg !== '') {
            $brandBody .= '<span class="mx-page-nav__brand-logo mx-page-nav__brand-logo--svg">' . $logoSvg . '</span>';
        }
        if ($logoType === 'text' || $logoType === 'image_text' || $brandBody === '') {
            $brandBody .= '<span class="mx-page-nav__title">' . e($title !== '' ? $title : '品牌站点') . '</span>';
        }

        return '<a class="mx-page-nav__brand" href="' . e($brandHref !== '' ? $brandHref : '#') . '">' . $brandBody . '</a>';
    }

    private static function normalizeSidebarItems($items): array
    {
        if (!is_array($items)) {
            return [];
        }
        $result = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $text = trim((string) ($item['text'] ?? ''));
            $href = trim((string) ($item['href'] ?? '#'));
            $icon = trim((string) ($item['icon'] ?? ''));
            $actionType = trim((string) ($item['actionType'] ?? 'link')) === 'panel' ? 'panel' : 'link';
            $panelType = trim((string) ($item['panelType'] ?? 'qrcode')) === 'custom' ? 'custom' : 'qrcode';
            $panelTitle = trim((string) ($item['panelTitle'] ?? ''));
            $panelContent = trim((string) ($item['panelContent'] ?? ''));
            $panelValue = trim((string) ($item['panelValue'] ?? ''));
            $panelHtml = trim((string) ($item['panelHtml'] ?? ''));
            $background = trim((string) ($item['background'] ?? ''));
            $color = trim((string) ($item['color'] ?? ''));
            $borderColor = trim((string) ($item['borderColor'] ?? ''));
            if ($text === '') {
                continue;
            }
            $result[] = [
                'text' => $text,
                'href' => $href !== '' ? $href : '#',
                'icon' => $icon,
                'actionType' => $actionType,
                'panelType' => $panelType,
                'panelTitle' => $panelTitle,
                'panelContent' => $panelContent,
                'panelValue' => $panelValue,
                'panelHtml' => $panelHtml,
                'background' => $background,
                'color' => $color,
                'borderColor' => $borderColor,
            ];
        }

        return $result;
    }

    private static function buildSidebarItemStyleAttribute(array $item): string
    {
        $style = [];
        if (trim((string) ($item['background'] ?? '')) !== '') {
            $style[] = '--mx-sidebar-item-bg:' . trim((string) $item['background']);
        }
        if (trim((string) ($item['color'] ?? '')) !== '') {
            $style[] = '--mx-sidebar-item-color:' . trim((string) $item['color']);
        }
        if (trim((string) ($item['borderColor'] ?? '')) !== '') {
            $style[] = '--mx-sidebar-item-border:' . trim((string) $item['borderColor']);
        }
        return $style !== [] ? ' style="' . e(implode(';', $style)) . '"' : '';
    }

    private static function buildSidebarPanelId(string $nodeId, int $index): string
    {
        $value = preg_replace('/[^A-Za-z0-9\-_:.]+/', '-', 'sidebar-panel-' . ($nodeId !== '' ? $nodeId : 'sidebar') . '-' . $index);
        return is_string($value) ? $value : ('sidebar-panel-' . $index);
    }

    private static function buildQrCodeUrl(string $value, int $size = 160): string
    {
        $normalized = $value !== '' ? $value : 'https://example.com';
        $normalizedSize = max(96, min(260, $size));
        return 'https://api.qrserver.com/v1/create-qr-code/?size=' . $normalizedSize . 'x' . $normalizedSize . '&data=' . rawurlencode($normalized);
    }

    private static function renderSidebarPanel(array $item, string $panelId): string
    {
        $panelType = trim((string) ($item['panelType'] ?? 'qrcode')) === 'custom' ? 'custom' : 'qrcode';
        $panelTitle = trim((string) ($item['panelTitle'] ?? ($item['text'] ?? '快捷面板')));
        $panelContent = trim((string) ($item['panelContent'] ?? ''));
        $panelValue = trim((string) ($item['panelValue'] ?? ''));
        $panelHtml = trim((string) ($item['panelHtml'] ?? ''));
        $customImage = $panelType === 'custom' && self::isSidebarImageSource($panelValue) ? $panelValue : '';

        return '<div class="mx-page-sidebar__panel" data-sidebar-panel="' . e($panelId) . '" hidden>'
            . '<button type="button" class="mx-page-sidebar__panel-close" data-sidebar-panel-close="' . e($panelId) . '" aria-label="关闭">×</button>'
            . '<div class="mx-page-sidebar__panel-card">'
            . '<div class="mx-page-sidebar__panel-title">' . e($panelTitle) . '</div>'
            . ($panelType === 'qrcode'
                ? '<div class="mx-page-sidebar__panel-qrcode"><img src="' . e(self::buildQrCodeUrl($panelValue, 160)) . '" alt="' . e($panelTitle) . '"></div>'
                    . ($panelContent !== '' ? '<div class="mx-page-sidebar__panel-text">' . nl2br(e($panelContent)) . '</div>' : '')
                    . ($panelValue !== '' ? '<div class="mx-page-sidebar__panel-value">' . e($panelValue) . '</div>' : '')
                : ($panelHtml !== '' ? '<div class="mx-page-sidebar__panel-rich">' . $panelHtml . '</div>' : '')
                    . ($customImage !== '' ? '<div class="mx-page-sidebar__panel-media"><img src="' . e($customImage) . '" alt="' . e($panelTitle) . '"></div>' : '')
                    . ($panelContent !== '' ? '<div class="mx-page-sidebar__panel-text">' . nl2br(e($panelContent)) . '</div>' : '')
                    . ($panelValue !== '' && $customImage === '' ? '<div class="mx-page-sidebar__panel-value">' . e($panelValue) . '</div>' : ''))
            . '</div>'
            . '</div>';
    }

    private static function renderSidebarIcon(string $source, string $fallbackText): string
    {
        $value = trim($source);
        if (self::isSidebarSvgSource($value)) {
            return '<span class="mx-page-sidebar__icon mx-page-sidebar__icon--svg">' . $value . '</span>';
        }
        if (self::isSidebarImageSource($value)) {
            return '<span class="mx-page-sidebar__icon mx-page-sidebar__icon--image"><img src="' . e($value) . '" alt=""></span>';
        }
        return '<span class="mx-page-sidebar__icon">' . e($value !== '' ? $value : self::makeAvatarLetter($fallbackText)) . '</span>';
    }

    private static function isSidebarSvgSource(string $value): bool
    {
        return preg_match('/^\s*<svg[\s>]/i', $value) === 1;
    }

    private static function isSidebarImageSource(string $value): bool
    {
        if ($value === '') {
            return false;
        }
        return preg_match('/^(data:image\/|https?:\/\/|\/\/|\/|\.\/|\.\.\/)/i', $value) === 1
            || preg_match('/\.(svg|png|jpe?g|gif|webp)(\?.*)?$/i', $value) === 1;
    }

    private static function makeAvatarLetter(string $text): string
    {
        $text = trim($text);
        if ($text === '') {
            return 'U';
        }

        if (function_exists('mb_substr')) {
            return strtoupper((string) mb_substr($text, 0, 1));
        }

        return strtoupper(substr($text, 0, 1));
    }

    private static function extractAuthUserName($user): string
    {
        if (!$user) {
            return '';
        }

        foreach (['nickname', 'name', 'username', 'realname', 'real_name'] as $field) {
            $value = trim((string) data_get($user, $field, ''));
            if ($value !== '') {
                return $value;
            }
        }

        return '';
    }

    private static function extractAuthUserAvatar($user): string
    {
        if (!$user) {
            return '';
        }

        foreach (['avatar', 'headimg', 'head_img', 'photo', 'image', 'thumb'] as $field) {
            $value = trim((string) data_get($user, $field, ''));
            if ($value !== '') {
                return $value;
            }
        }

        return '';
    }

    private static function normalizeAnchorId($value): string
    {
        $anchor = preg_replace('/[^A-Za-z0-9\-_:.]+/', '', str_replace(' ', '-', ltrim(trim((string) $value), '#')));
        return is_string($anchor) ? $anchor : '';
    }

    private static function resolveCarouselSlides(array $props, array $context = []): array
    {
        if (trim((string) ($props['source_type'] ?? 'manual')) === 'model_list') {
            $payload = self::queryModelListItems($props, $context);
            $slides = [];
            foreach (($payload['items'] ?? []) as $item) {
                $slides[] = [
                    'title' => (string) ($item['title'] ?? ''),
                    'description' => (string) ($item['summary'] ?? ''),
                    'image' => (string) ($item['image'] ?? ''),
                    'buttonText' => trim((string) ($props['buttonText'] ?? '查看详情')),
                    'buttonHref' => (string) ($item['url'] ?? ''),
                ];
            }
            return $slides;
        }

        $slides = [];
        foreach ((array) ($props['slides'] ?? []) as $slide) {
            if (!is_array($slide)) {
                continue;
            }
            $title = trim((string) ($slide['title'] ?? ''));
            $description = trim((string) ($slide['description'] ?? ''));
            $image = trim((string) ($slide['image'] ?? ''));
            if ($title === '' && $description === '' && $image === '') {
                continue;
            }
            $slides[] = [
                'title' => $title,
                'description' => $description,
                'image' => $image,
                'buttonText' => trim((string) ($slide['buttonText'] ?? ($props['buttonText'] ?? ''))),
                'buttonHref' => trim((string) ($slide['buttonHref'] ?? ($props['buttonHref'] ?? ''))),
            ];
        }
        return $slides;
    }

    private static function resolveGalleryItems(array $props, array $context = []): array
    {
        if (trim((string) ($props['source_type'] ?? 'manual')) === 'model_list') {
            $payload = self::queryModelListItems($props, $context);
            $items = [];
            foreach (($payload['items'] ?? []) as $item) {
                $items[] = [
                    'title' => (string) ($item['title'] ?? ''),
                    'image' => (string) ($item['image'] ?? ''),
                    'url' => (string) ($item['url'] ?? ''),
                ];
            }
            return $items;
        }

        $items = [];
        foreach ((array) ($props['items'] ?? []) as $item) {
            if (!is_array($item)) {
                continue;
            }
            $title = trim((string) ($item['title'] ?? ''));
            $image = trim((string) ($item['image'] ?? ''));
            $url = trim((string) ($item['url'] ?? ''));
            if ($title === '' && $image === '' && $url === '') {
                continue;
            }
            $items[] = [
                'title' => $title,
                'image' => $image,
                'url' => $url,
            ];
        }

        return $items;
    }

    private static function normalizeAspectRatio(string $ratio): string
    {
        $ratio = trim($ratio);
        if (!preg_match('/^\s*(\d+(?:\.\d+)?)\s*:\s*(\d+(?:\.\d+)?)\s*$/', $ratio, $matches)) {
            return '16:9';
        }
        $width = (float) $matches[1];
        $height = (float) $matches[2];
        if ($width <= 0 || $height <= 0) {
            return '16:9';
        }
        return $width . ':' . $height;
    }

    private static function aspectRatioPadding(string $ratio): string
    {
        [$width, $height] = array_map('floatval', explode(':', $ratio));
        if ($width <= 0 || $height <= 0) {
            return '56.25%';
        }
        return round(($height / $width) * 100, 4) . '%';
    }

    private static function buildAttributes(array $node, array $props, array $extraClasses = []): string
    {
        $classes = $extraClasses;
        $className = trim((string) ($props['className'] ?? ''));
        if ($className !== '') {
            $classes[] = $className;
        }

        $attributes = [];
        $anchorId = self::normalizeAnchorId($props['anchor'] ?? '');
        if ($anchorId !== '') {
            $attributes[] = ' id="' . e($anchorId) . '"';
        }
        $nodeId = trim((string) ($node['id'] ?? ''));
        if ($nodeId !== '') {
            $attributes[] = ' data-node-id="' . e($nodeId) . '"';
        }

        $motion = isset($node['motion']) && is_array($node['motion']) ? $node['motion'] : [];
        $effect = trim((string) ($motion['effect'] ?? ''));
        if ($effect !== '' && $effect !== 'none') {
            $attributes[] = ' data-motion-effect="' . e($effect) . '"';
            $attributes[] = ' data-motion-duration="' . e(trim((string) ($motion['duration'] ?? '0.7s'))) . '"';
            $attributes[] = ' data-motion-delay="' . e(trim((string) ($motion['delay'] ?? '0s'))) . '"';
        }

        if ($classes !== []) {
            $attributes[] = ' class="' . e(trim(implode(' ', $classes))) . '"';
        }

        return implode('', $attributes);
    }

    private static function buildThemeRootAttributes(array $theme): string
    {
        $theme = self::normalizeTheme($theme);
        $pairs = [];
        foreach (self::themeStyleMap() as $key => $cssVariable) {
            $value = trim((string) ($theme[$key] ?? ''));
            if ($value !== '') {
                $pairs[] = $cssVariable . ':' . $value;
            }
        }

        $attributes = [];
        if ($pairs !== []) {
            $attributes[] = ' style="' . e(implode(';', $pairs)) . '"';
        }
        foreach (self::themeDataMap() as $key => $attributeName) {
            $value = trim((string) ($theme[$key] ?? ''));
            if ($value !== '') {
                $attributes[] = ' ' . $attributeName . '="' . e($value) . '"';
            }
        }

        return implode('', $attributes);
    }

    private static function normalizeTheme(array $theme): array
    {
        $normalized = self::defaultTheme();
        foreach ($theme as $key => $value) {
            if (!array_key_exists($key, $normalized)) {
                continue;
            }
            $trimmedValue = trim((string) $value);
            if ($trimmedValue !== '') {
                $normalized[$key] = $trimmedValue;
            }
        }

        return $normalized;
    }

    private static function defaultTheme(): array
    {
        return [
            'primary' => '#2563eb',
            'primaryContrast' => '#ffffff',
            'accent' => '#0f172a',
            'accentSoft' => 'rgba(37, 99, 235, 0.08)',
            'surface' => '#ffffff',
            'surfaceMuted' => '#f8fafc',
            'surfaceElevated' => 'rgba(255, 255, 255, 0.96)',
            'text' => '#0f172a',
            'textMuted' => '#64748b',
            'heading' => '#0f172a',
            'border' => '#e2e8f0',
            'heroGradient' => 'linear-gradient(135deg, #0f172a 0%, #1d4ed8 52%, #38bdf8 100%)',
            'accentGradient' => 'linear-gradient(135deg, #2563eb 0%, #38bdf8 100%)',
            'shadowSoft' => '0 18px 40px rgba(15, 23, 42, 0.08)',
            'shadowStrong' => '0 28px 60px rgba(15, 23, 42, 0.20)',
            'radiusCard' => '24px',
            'radiusSection' => '28px',
            'radiusPill' => '999px',
            'buttonStyle' => 'solid',
            'cardStyle' => 'elevated',
            'navStyle' => 'glass',
        ];
    }

    private static function themeStyleMap(): array
    {
        return [
            'primary' => '--mx-color-primary',
            'primaryContrast' => '--mx-color-primary-contrast',
            'accent' => '--mx-color-accent',
            'accentSoft' => '--mx-color-accent-soft',
            'surface' => '--mx-color-surface',
            'surfaceMuted' => '--mx-color-surface-muted',
            'surfaceElevated' => '--mx-color-surface-elevated',
            'text' => '--mx-color-text',
            'textMuted' => '--mx-color-text-muted',
            'heading' => '--mx-color-heading',
            'border' => '--mx-color-border',
            'heroGradient' => '--mx-gradient-hero',
            'accentGradient' => '--mx-gradient-accent',
            'shadowSoft' => '--mx-shadow-soft',
            'shadowStrong' => '--mx-shadow-strong',
            'radiusCard' => '--mx-radius-card',
            'radiusSection' => '--mx-radius-section',
            'radiusPill' => '--mx-radius-pill',
        ];
    }

    private static function themeDataMap(): array
    {
        return [
            'buttonStyle' => 'data-button-style',
            'cardStyle' => 'data-card-style',
            'navStyle' => 'data-nav-style',
        ];
    }

    private static function buildResponsiveStyleTag(array $nodes): string
    {
        $tabletRules = [];
        $mobileRules = [];
        self::collectResponsiveRules($nodes, $tabletRules, $mobileRules);

        $chunks = [];
        if ($tabletRules !== []) {
            $chunks[] = '@media (max-width: 991px){' . implode('', $tabletRules) . '}';
        }
        if ($mobileRules !== []) {
            $chunks[] = '@media (max-width: 767px){' . implode('', $mobileRules) . '}';
        }

        if ($chunks === []) {
            return '';
        }

        return '<style>' . implode('', $chunks) . '</style>';
    }

    private static function collectResponsiveRules(array $nodes, array &$tabletRules, array &$mobileRules): void
    {
        foreach ($nodes as $node) {
            if (!is_array($node)) {
                continue;
            }

            $tabletRule = self::buildResponsiveNodeRule($node, 'tablet');
            if ($tabletRule !== '') {
                $tabletRules[] = $tabletRule;
            }

            $mobileRule = self::buildResponsiveNodeRule($node, 'mobile');
            if ($mobileRule !== '') {
                $mobileRules[] = $mobileRule;
            }

            $childKey = self::getChildCollectionKey((string) ($node['type'] ?? ''), $node);
            if ($childKey !== '' && isset($node[$childKey]) && is_array($node[$childKey])) {
                self::collectResponsiveRules($node[$childKey], $tabletRules, $mobileRules);
            }
        }
    }

    private static function buildResponsiveNodeRule(array $node, string $device): string
    {
        $nodeId = trim((string) ($node['id'] ?? ''));
        if ($nodeId === '') {
            return '';
        }

        $responsive = isset($node['responsive']) && is_array($node['responsive']) ? $node['responsive'] : [];
        $config = isset($responsive[$device]) && is_array($responsive[$device]) ? $responsive[$device] : [];
        if ($config === []) {
            return '';
        }

        $declarations = [];
        $style = isset($config['style']) && is_array($config['style']) ? $config['style'] : [];
        foreach ($style as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            $cssKey = preg_replace('/([a-z])([A-Z])/', '$1-$2', (string) $key);
            $declarations[] = strtolower($cssKey) . ':' . trim((string) $value);
        }

        if ((string) ($node['type'] ?? '') === 'column') {
            $span = (int) ($config['span'] ?? 0);
            if ($span >= 1 && $span <= 12) {
                $declarations[] = 'grid-column:span ' . $span;
            }
        }

        if ($declarations === []) {
            return '';
        }

        return '[data-node-id="' . e($nodeId) . '"]{' . implode(';', $declarations) . '}';
    }

    private static function buildStyleAttribute(array $style, array $node = []): string
    {
        $pairs = [];
        $specialStyleMap = [
            'hoverBackground' => '--mx-hover-background',
            'hoverColor' => '--mx-hover-color',
            'hoverBorderColor' => '--mx-hover-border-color',
            'hoverBoxShadow' => '--mx-hover-box-shadow',
            'hoverTransform' => '--mx-hover-transform',
            'ctaButtonMinHeight' => '--mx-cta-button-min-height',
        ];
        $currentStyleMap = [
            'background' => '--mx-current-background',
            'color' => '--mx-current-color',
            'borderColor' => '--mx-current-border-color',
            'boxShadow' => '--mx-current-box-shadow',
        ];
        foreach ($style as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            if (isset($specialStyleMap[$key])) {
                $pairs[] = $specialStyleMap[$key] . ':' . trim((string) $value);
                continue;
            }

            $cssKey = preg_replace('/([a-z])([A-Z])/', '$1-$2', (string) $key);
            $trimmedValue = trim((string) $value);
            $pairs[] = strtolower($cssKey) . ':' . $trimmedValue;
            if (isset($currentStyleMap[$key])) {
                $pairs[] = $currentStyleMap[$key] . ':' . $trimmedValue;
            }
        }

        $motion = isset($node['motion']) && is_array($node['motion']) ? $node['motion'] : [];
        $effect = trim((string) ($motion['effect'] ?? ''));
        if ($effect !== '' && $effect !== 'none') {
            $duration = trim((string) ($motion['duration'] ?? '0.7s'));
            $delay = trim((string) ($motion['delay'] ?? '0s'));
            $pairs[] = '--mx-motion-duration:' . ($duration !== '' ? $duration : '0.7s');
            $pairs[] = '--mx-motion-delay:' . ($delay !== '' ? $delay : '0s');
        }

        if ($pairs === []) {
            return '';
        }

        return ' style="' . e(implode(';', $pairs)) . '"';
    }

    private static function getChildCollectionKey(string $type, array $node): string
    {
        if ($type === 'section' || $type === 'row' || array_key_exists('children', $node)) {
            return 'children';
        }

        if ($type === 'column' || array_key_exists('blocks', $node)) {
            return 'blocks';
        }

        return '';
    }
}
