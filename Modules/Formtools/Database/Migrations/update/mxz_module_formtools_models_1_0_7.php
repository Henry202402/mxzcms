<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        $datas = DB::table('module_formtools_models')->get();
        foreach ($datas as $data) {
            $adminConfig = $this->normalizeAdminConfig(json_decode($data->admin_config, true) ?: []);
            $homeConfig = $this->normalizeHomeConfig(json_decode($data->home_config, true) ?: []);

            DB::table('module_formtools_models')
                ->where('id', '=', $data->id)
                ->update([
                    'admin_config' => json_encode($adminConfig, JSON_UNESCAPED_UNICODE),
                    'home_config' => json_encode($homeConfig, JSON_UNESCAPED_UNICODE),
                ]);
        }
    }

    public function down()
    {
    }

    private function normalizeAdminConfig(array $config): array
    {
        $template = $this->sanitizeTemplateName($config['form_template'] ?? '');
        if (!in_array($template, ['row', 'solo'], true)) {
            $template = 'row';
        }
        $config['form_template'] = $template;
        return $config;
    }

    private function normalizeHomeConfig(array $config): array
    {
        $customListTemplate = $this->sanitizeTemplateName($config['custom_list_template'] ?? '');
        $customDetailTemplate = $this->sanitizeTemplateName($config['custom_detail_template'] ?? '');
        $listTemplate = $this->sanitizeTemplateName($config['list_template'] ?? '');
        $detailTemplate = $this->sanitizeTemplateName($config['detail_template'] ?? '');

        $config['custom_list_template'] = $customListTemplate;
        $config['custom_detail_template'] = $customDetailTemplate;
        $config['list_template'] = $listTemplate ?: $customListTemplate ?: 'list';
        $config['detail_template'] = $detailTemplate ?: $customDetailTemplate ?: 'detail';
        $config['page_num'] = max(0, (int) ($config['page_num'] ?? 20));

        $pageTemplate = strtolower(trim((string) ($config['list_page_template'] ?? 'center')));
        if (!in_array($pageTemplate, ['center', 'left', 'right'], true)) {
            $pageTemplate = 'center';
        }
        $config['list_page_template'] = $pageTemplate;

        return $config;
    }

    private function sanitizeTemplateName($template): string
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
};
