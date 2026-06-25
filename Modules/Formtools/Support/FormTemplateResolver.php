<?php

namespace Modules\Formtools\Support;

class FormTemplateResolver
{
    public const DEFAULT_LIST_TEMPLATE = 'list';
    public const DEFAULT_DETAIL_TEMPLATE = 'detail';
    public const DEFAULT_HANDLE_TEMPLATE = 'handle';
    public const DEFAULT_LIST_PAGE_TEMPLATE = 'center';
    public const DEFAULT_ADMIN_FORM_TEMPLATE = 'row';

    public static function normalizeAdminConfig(array $config = []): array
    {
        $formTemplate = self::sanitizeTemplateName($config['form_template'] ?? '');
        if (!in_array($formTemplate, ['row', 'solo'], true)) {
            $formTemplate = self::DEFAULT_ADMIN_FORM_TEMPLATE;
        }

        $config['form_template'] = $formTemplate;
        return $config;
    }

    public static function normalizeHomeConfig(array $config = []): array
    {
        $customListTemplate = self::sanitizeTemplateName($config['custom_list_template'] ?? '');
        $customDetailTemplate = self::sanitizeTemplateName($config['custom_detail_template'] ?? '');
        $listTemplate = self::sanitizeTemplateName($config['list_template'] ?? '');
        $detailTemplate = self::sanitizeTemplateName($config['detail_template'] ?? '');

        $config['custom_list_template'] = $customListTemplate;
        $config['custom_detail_template'] = $customDetailTemplate;
        $config['list_template'] = $listTemplate ?: $customListTemplate ?: self::DEFAULT_LIST_TEMPLATE;
        $config['detail_template'] = $detailTemplate ?: $customDetailTemplate ?: self::DEFAULT_DETAIL_TEMPLATE;
        $config['page_num'] = max(0, (int) ($config['page_num'] ?? 20));

        $pageTemplate = strtolower(trim((string) ($config['list_page_template'] ?? self::DEFAULT_LIST_PAGE_TEMPLATE)));
        if (!in_array($pageTemplate, ['center', 'left', 'right'], true)) {
            $pageTemplate = self::DEFAULT_LIST_PAGE_TEMPLATE;
        }
        $config['list_page_template'] = $pageTemplate;

        return $config;
    }

    public static function normalizeModelData(array $model): array
    {
        $model['admin_config'] = self::normalizeAdminConfig((array) ($model['admin_config'] ?? []));
        $model['home_config'] = self::normalizeHomeConfig((array) ($model['home_config'] ?? []));
        $model['form_template'] = $model['admin_config']['form_template'];
        $model['list_template'] = $model['home_config']['list_template'];
        $model['detail_template'] = $model['home_config']['detail_template'];
        $model['page_num'] = $model['home_config']['page_num'];
        $model['list_page_template'] = $model['home_config']['list_page_template'];
        return $model;
    }

    public static function resolveFrontendTemplate(string $theme, string $template, string $fallback = self::DEFAULT_LIST_TEMPLATE): array
    {
        $template = self::sanitizeTemplateName($template) ?: $fallback;
        $fallback = self::sanitizeTemplateName($fallback) ?: self::DEFAULT_LIST_TEMPLATE;

        if (self::themeTemplateExists($theme, $template)) {
            return [
                'template' => "themes.{$theme}.model.overwrite.{$template}",
                'name' => $template,
                'source' => 'theme',
            ];
        }

        if (self::builtinTemplateExists($template)) {
            return [
                'template' => "model.{$template}",
                'name' => $template,
                'source' => 'builtin',
            ];
        }

        if ($template !== $fallback) {
            return self::resolveFrontendTemplate($theme, $fallback, self::DEFAULT_LIST_TEMPLATE);
        }

        return [
            'template' => "model." . self::DEFAULT_LIST_TEMPLATE,
            'name' => self::DEFAULT_LIST_TEMPLATE,
            'source' => 'builtin',
        ];
    }

    public static function sanitizeTemplateName(?string $template): string
    {
        $template = trim((string) $template);
        if ($template === '') {
            return '';
        }
        $template = str_replace('\\', '/', $template);
        $template = preg_replace('/\.blade\.php$/i', '', $template);
        $template = preg_replace('/\.php$/i', '', $template);
        $template = trim($template, '/');
        if ($template === '' || strpos($template, '..') !== false) {
            return '';
        }
        if (!preg_match('/^[A-Za-z0-9_\/-]+$/', $template)) {
            return '';
        }
        return $template;
    }

    private static function themeTemplateExists(string $theme, string $template): bool
    {
        return file_exists(public_path('views/themes/' . $theme . '/model/overwrite/' . $template . '.blade.php'));
    }

    private static function builtinTemplateExists(string $template): bool
    {
        return file_exists(base_path('public/views/model/' . $template . '.blade.php'));
    }
}
