<?php

namespace Modules\Formtools\Listeners;

use Modules\Formtools\Models\FormModel;

class GetModelMenu {

    public function handle(\Modules\Formtools\Events\GetModelMenu $event) {
        $rows = FormModel::query()->get([
            'name',
            'identification',
            'access_identification',
            'type',
            'home_config',
        ])->toArray();

        $list = [];
        $models = [];

        foreach ($rows as $row) {
            if (empty($row['access_identification'])) {
                continue;
            }

            $homeConfig = json_decode($row['home_config'] ?? '[]', true) ?: [];
            $listTemplate = $homeConfig['list_template'] ?? 'list';
            $detailTemplate = $homeConfig['detail_template'] ?? 'detail';
            $entries = $this->buildEntryOptions($row, $listTemplate, $detailTemplate);

            $list[] = [
                'name' => $row['name'],
                'url' => $entries[0]['url'] ?? ('list/' . $row['access_identification']),
            ];

            $models[] = [
                'name' => $row['name'],
                'identification' => $row['identification'],
                'access_identification' => $row['access_identification'],
                'type' => $row['type'] ?: 'multi',
                'list_template' => $listTemplate,
                'detail_template' => $detailTemplate,
                'entries' => $entries,
            ];
        }

        return [
            'identification' => 'Formtools',
            'menuList' => $list,
            'models' => $models,
        ];
    }

    private function buildEntryOptions(array $model, string $listTemplate, string $detailTemplate): array
    {
        $access = $model['access_identification'];
        $name = $model['name'];
        $type = $model['type'] ?: 'multi';
        $isFeedback = $listTemplate === 'feedback' || ($model['identification'] ?? '') === 'feedback';

        $entries = [];

        if ($type === 'single') {
            $entries[] = [
                'key' => 'single',
                'label' => '单页详情入口',
                'name' => $name,
                'url' => 'list/' . $access,
            ];
        } elseif ($isFeedback) {
            $entries[] = [
                'key' => 'feedback',
                'label' => '留言页',
                'name' => $name,
                'url' => 'list/' . $access,
            ];
        } else {
            $entries[] = [
                'key' => 'list',
                'label' => '列表页',
                'name' => $name,
                'url' => 'list/' . $access,
            ];
        }

        $entries[] = [
            'key' => 'handle',
            'label' => $isFeedback ? '留言提交页' : '表单页',
            'name' => $isFeedback ? $name . '提交' : $name . '投稿',
            'url' => 'handle/' . $access,
        ];

        return $entries;
    }
}
