<?php

namespace Modules\Main\Http\Controllers\Admin;

use App\Support\Telemetry\StatisticReporter;
use Illuminate\Support\Facades\Cache;
use Modules\Formtools\Models\FormPage;
use Modules\Main\Models\HomeMenu;
use Modules\Main\Models\Modules;
use Modules\Main\Services\ServiceModel;
use Modules\ModulesController;
use Mxzcms\Modules\cache\CacheKey;

class MenuController extends ModulesController {

    //菜单列表
    public function themeMenuList() {

        if ($this->request->ajax()) {
            $data = ServiceModel::getThemeMenuList();
            return ['code' => 0, 'msg' => 'ok', 'data' => $data, 'count' => count($data)];
        }
        StatisticReporter::reportSuccess('Usage', $this->resolveThemeIdentification(), Modules::Theme, [
            'entry' => 'admin_theme_menu_list',
        ]);
        return view('admin/func/themeMenuList', [
            'langList' => ServiceModel::getMenuLangOptions(),
        ]);
    }

    //添加菜单
    public function themeMenuAdd() {
        $all = $this->request->all();
        if ($this->request->ajax()) {
            if (!trim($all['name'])) return returnArr(0, '名称不能为空');
            $add = $this->normalizeMenuPayload($all);
            if ($error = $this->validateMenuParent($add)) {
                return returnArr(0, $error);
            }
            $add['created_at'] = getDay();
            $add['updated_at'] = getDay();

            if ($_FILES['cover']['size'] > 0) {
                //文件上传
                try {
                    $add['cover'] = UploadFile(\Request(), "cover", "cover/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
                    $this->resizeImg($all['avatar'], 50, 100, 100);
                } catch (\Exception $exception) {
                    return returnArr(0, $exception->getMessage());
                }
            }

            $res = HomeMenu::query()->insertGetId($add);
            if ($res) {
                return returnArr(200, '添加成功');
            } else {
                return returnArr(0, '添加失败');
            }
        }
        [$menuList, $moduleMenu, $modelMenu, $moduleArray, $langList, $pageMenu] = $this->buildMenuWorkspaceData();
        StatisticReporter::reportSuccess('Usage', $this->resolveThemeIdentification(), Modules::Theme, [
            'entry' => 'admin_theme_menu_add',
        ]);

        return view('admin/func/themeMenuAdd', [
            'menuList' => $menuList,
            'moduleArray' => $moduleArray,
            'moduleMenu' => $moduleMenu,
            'modelMenu' => $modelMenu,
            'langList' => $langList,
            'pageMenu' => $pageMenu,
            'draftMenu' => $this->buildDraftMenu($all),
        ]);
    }

    //编辑菜单
    public function themeMenuEdit() {
        $all = $this->request->all();
        $data = HomeMenu::query()->find($all['id']);
        if ($this->request->ajax()) {
            if (!$data) return returnArr(0, '数据不存在');
            if (!trim($all['name'])) return returnArr(0, '名称不能为空');
            $add = $this->normalizeMenuPayload($all);
            if ($error = $this->validateMenuParent($add, (int) $all['id'])) {
                return returnArr(0, $error);
            }
            $add['status'] = $all['status'] == 1 ? 1 : 2;
            $add['updated_at'] = getDay();

            if ($_FILES['cover']['size'] > 0) {
                //文件上传
                try {
                    $add['cover'] = UploadFile(\Request(), "cover", "cover/" . date("Y/m/d/") . uniqid(), ALLOWEXT, __E("upload_driver"));
                    $this->resizeImg($all['avatar'], 50, 100, 100);
                } catch (\Exception $exception) {
                    return returnArr(0, $exception->getMessage());
                }
            }

            $res = HomeMenu::query()->where('id', $all['id'])->update($add);
            if ($res) {
                return returnArr(200, '编辑成功');
            } else {
                return returnArr(0, '编辑失败');
            }
        }
        if (!$data) return back()->with("errormsg", "数据不存在");
        [$menuList, $moduleMenu, $modelMenu, $moduleArray, $langList, $pageMenu] = $this->buildMenuWorkspaceData();
        StatisticReporter::reportSuccess('Usage', $this->resolveThemeIdentification(), Modules::Theme, [
            'entry' => 'admin_theme_menu_edit',
            'menu_id' => $data['id'],
        ]);
        return view('admin/func/themeMenuEdit', [
            'data' => $data,
            'menuList' => $menuList,
            'moduleArray' => $moduleArray,
            'moduleMenu' => $moduleMenu,
            'modelMenu' => $modelMenu,
            'langList' => $langList,
            'pageMenu' => $pageMenu,
        ]);
    }

    //删除菜单
    public function themeMenuDelete() {
        $all = $this->request->all();
        $data = HomeMenu::find($all['id']);
        if (!$data) return back()->with("errormsg", "数据不存在");

        if (HomeMenu::query()->where('pid', $all['id'])->first()) {
            return back()->with("errormsg", "存在下级，不能删除");
        }

        if (HomeMenu::destroy($all['id'])) {
            return back()->with("successmsg", "删除成功");
        }
        return back()->with("errormsg", "删除失败");
    }

    //菜单启用禁用
    public function themeMenuChangeStatus() {
        $all = $this->request->all();
        $data = HomeMenu::find($all['id']);
        if (!$data) return back()->with("errormsg", "数据不存在");
        if (ServiceModel::whereUpdate(HomeMenu::TABLE_NAME, ['id' => $all['id']], ['status' => $all['status'] == 1 ? 1 : 2, 'updated_at' => getDay()])) {
            return back()->with("successmsg", "操作成功");
        }
        return back()->with("errormsg", "操作失败");
    }

    public function themeMenuMove()
    {
        $all = $this->request->all();
        $direction = trim((string) ($all['direction'] ?? ''));
        if (!in_array($direction, ['up', 'down', 'top'], true)) {
            return returnArr(0, '排序方向错误');
        }

        $menu = HomeMenu::query()->find((int) ($all['id'] ?? 0));
        if (!$menu) {
            return returnArr(0, '数据不存在');
        }

        $siblings = HomeMenu::query()
            ->where('pid', $menu->pid)
            ->where('position', $menu->position)
            ->where(function ($query) use ($menu) {
                $lang = ServiceModel::normalizeMenuLang($menu->lang ?? '');
                if ($lang === '') {
                    $query->whereNull('lang')->orWhere('lang', '');
                    return;
                }
                $query->where('lang', $lang);
            })
            ->orderByDesc('sort')
            ->orderBy('id')
            ->get();

        if ($siblings->count() <= 1) {
            return returnArr(0, '当前层级没有可排序的兄弟菜单');
        }

        $currentIndex = $siblings->search(fn ($item) => (int) $item->id === (int) $menu->id);
        if ($currentIndex === false) {
            return returnArr(0, '排序数据异常');
        }

        if ($direction === 'top') {
            $sorted = $siblings->values()->all();
            usort($sorted, function ($left, $right) use ($menu) {
                if ((int) $left->id === (int) $menu->id) {
                    return -1;
                }
                if ((int) $right->id === (int) $menu->id) {
                    return 1;
                }
                return 0;
            });
            $this->persistSiblingSortOrder($sorted);
            return returnArr(200, '已置顶');
        }

        $swapIndex = $direction === 'up' ? $currentIndex - 1 : $currentIndex + 1;
        if (!isset($siblings[$swapIndex])) {
            return returnArr(0, $direction === 'up' ? '已经是当前层级最前面' : '已经是当前层级最后面');
        }

        $sorted = $siblings->values()->all();
        $target = $sorted[$swapIndex];
        $sorted[$swapIndex] = $sorted[$currentIndex];
        $sorted[$currentIndex] = $target;
        $this->persistSiblingSortOrder($sorted);

        return returnArr(200, '排序已更新');
    }

    public function themeMenuSearchModuleMenu() {
        $all = $this->request->all();
        if (!$all['module']) return returnArr(0, '模块不能为空');
        if (!$all['table']) return returnArr(0, '表名不能为空');
        if (!$all['title']) return returnArr(0, '标题不能为空');
        try {
            $res = hook('SearchMenuFromModule', [
                'moduleName' => $all['module'],
                'table' => $all['table'],
                'title' => $all['title'],
            ])[0];
        } catch (\Exception $exception) {
            dd($exception);
        }
        StatisticReporter::reportSuccess('Usage', $all['module'], Modules::Module, [
            'entry' => 'admin_theme_menu_search_module',
            'scene' => 'theme_menu',
            'table' => $all['table'],
            'title' => $all['title'],
        ]);
        return returnArr(200, 'ok', $res);
    }

    public function themeMenuGeneratePreset()
    {
        $all = $this->request->all();
        $position = in_array(($all['position'] ?? 'top'), ['top', 'bottom', 'footer'], true) ? $all['position'] : 'top';
        $pid = (int) ($all['pid'] ?? 0);
        $lang = ServiceModel::normalizeMenuLang($all['lang'] ?? '');
        $preset = trim((string) ($all['preset'] ?? 'site_basic')) ?: 'site_basic';
        if ($error = $this->validateMenuParent(['pid' => $pid, 'position' => $position, 'lang' => $lang])) {
            return returnArr(0, $error);
        }

        [$menuList, $moduleMenu, $modelMenu] = $this->buildMenuWorkspaceData();
        $models = $modelMenu['models'] ?? [];
        if (!$models) {
            return returnArr(0, '暂无可用模型入口');
        }

        $created = 0;
        $skipped = 0;

        foreach ($this->buildMenuPresetDefinitions($preset) as $index => $definition) {
            $entry = $this->resolveModelPresetEntry($models, $definition['access'], $definition['entry']);
            if (!$entry) {
                $skipped++;
                continue;
            }

            $sourceValue = $definition['access'] . ':' . $definition['entry'];
            $exists = HomeMenu::query()
                ->where('position', $position)
                ->where('pid', $pid)
                ->where(function ($query) use ($lang) {
                    if ($lang === '') {
                        $query->whereNull('lang')->orWhere('lang', '');
                        return;
                    }
                    $query->where('lang', $lang);
                })
                ->where(function ($query) use ($entry, $sourceValue) {
                    $query->where('source_value', $sourceValue)
                        ->orWhere('url', $entry['url']);
                })
                ->first();

            if ($exists) {
                $skipped++;
                continue;
            }

            $payload = [
                'module' => 'Formtools',
                'position' => $position,
                'lang' => $lang,
                'pid' => $pid,
                'sort' => 100 - ($index * 5),
                'name' => $entry['name'],
                'url' => $entry['url'],
                'target' => '_self',
                'icon' => $this->recommendMenuIcon($sourceValue, $entry['name']),
                'icon_character' => '',
                'menu_type' => 'model',
                'source_module' => 'Formtools',
                'source_value' => $sourceValue,
                'status' => 1,
                'created_at' => getDay(),
                'updated_at' => getDay(),
            ];

            if (HomeMenu::query()->insert($payload)) {
                $created++;
            }
        }

        return returnArr(200, "处理完成，新增 {$created} 项，跳过 {$skipped} 项");
    }

    private function resolveThemeIdentification(): string
    {
        return $this->request->query('m', Cache::get('theme', 'default'));
    }

    private function normalizeMenuPayload(array $all): array
    {
        $target = in_array(($all['target'] ?? '_self'), ['_self', '_blank'], true) ? $all['target'] : '_self';
        $menuType = in_array(($all['menu_type'] ?? 'manual'), ['manual', 'module', 'model', 'search', 'page'], true) ? $all['menu_type'] : 'manual';

        return [
            'module' => trim((string) ($all['module'] ?? '')) ?: 'Main',
            'position' => in_array(($all['position'] ?? 'top'), ['top', 'bottom', 'footer'], true) ? $all['position'] : 'top',
            'lang' => ServiceModel::normalizeMenuLang($all['lang'] ?? ''),
            'pid' => (int) ($all['pid'] ?? 0),
            'sort' => (int) ($all['sort'] ?? 0),
            'name' => trim((string) ($all['name'] ?? '')),
            'url' => trim((string) ($all['url'] ?? '')) ?: '#',
            'target' => $target,
            'icon' => trim((string) ($all['icon'] ?? '')),
            'icon_character' => trim((string) ($all['icon_character'] ?? '')),
            'menu_type' => $menuType,
            'source_module' => trim((string) ($all['source_module'] ?? '')),
            'source_value' => trim((string) ($all['source_value'] ?? '')),
        ];
    }

    private function buildMenuWorkspaceData(): array
    {
        $menuList = ServiceModel::getHomeMenu();
        $moduleMenu = hook('GetModuleHomeSetMenu', ['moduleName' => 'System']);
        $modelMenu = hook('GetModelMenu', ['moduleName' => 'Formtools'])[0];
        $moduleArray = array_column(Cache::get(CacheKey::ModulesActive), 'name', 'identification');
        $langList = ServiceModel::getMenuLangOptions();
        $pageMenu = $this->buildPageMenuEntries();

        return [$menuList, $moduleMenu, $modelMenu, $moduleArray, $langList, $pageMenu];
    }

    private function buildMenuPresetDefinitions(string $preset): array
    {
        return match ($preset) {
            'site_basic' => [
                ['access' => 'about', 'entry' => 'single'],
                ['access' => 'news', 'entry' => 'list'],
                ['access' => 'agreement', 'entry' => 'list'],
                ['access' => 'feedback', 'entry' => 'feedback'],
                ['access' => 'contacts', 'entry' => 'single'],
            ],
            'site_form_entry' => [
                ['access' => 'feedback', 'entry' => 'handle'],
                ['access' => 'contacts', 'entry' => 'handle'],
            ],
            default => [],
        };
    }

    private function resolveModelPresetEntry(array $models, string $access, string $entryKey): ?array
    {
        foreach ($models as $model) {
            if (($model['access_identification'] ?? '') !== $access) {
                continue;
            }
            foreach (($model['entries'] ?? []) as $entry) {
                if (($entry['key'] ?? '') === $entryKey) {
                    return $entry;
                }
            }
        }

        return null;
    }

    private function recommendMenuIcon(string $sourceValue, string $name = ''): string
    {
        $value = strtolower($sourceValue . ' ' . $name);

        return match (true) {
            str_contains($value, 'about') || str_contains($value, '关于') => 'fa fa-building-o',
            str_contains($value, 'contact') || str_contains($value, 'contacts') || str_contains($value, '联系') => 'fa fa-phone',
            str_contains($value, 'feedback') || str_contains($value, '留言') => 'fa fa-commenting-o',
            str_contains($value, 'agreement') || str_contains($value, '协议') => 'fa fa-file-text-o',
            str_contains($value, 'news') || str_contains($value, '资讯') || str_contains($value, '动态') => 'fa fa-newspaper-o',
            str_contains($value, 'page:') || str_contains($value, '页面') || str_contains($value, '专题') => 'fa fa-file-o',
            str_contains($value, 'milestone') || str_contains($value, '历程') => 'fa fa-flag-checkered',
            str_contains($value, 'handle') || str_contains($value, '投稿') || str_contains($value, '提交') => 'fa fa-edit',
            default => 'fa fa-circle-o',
        };
    }

    private function buildPageMenuEntries(): array
    {
        return FormPage::query()
            ->orderByDesc('is_nav')
            ->orderBy('sort')
            ->orderBy('id')
            ->get()
            ->map(function (FormPage $page) {
                return [
                    'id' => (int) $page->id,
                    'name' => $page->name,
                    'slug' => $page->slug,
                    'url' => $page->getPublicPath() ?: '#',
                    'preview_url' => $page->getPreviewUrl(),
                    'public_url' => $page->getPublicUrl(),
                    'status' => (int) $page->status,
                    'is_nav' => (int) $page->is_nav,
                    'source_value' => 'page:' . $page->identification,
                ];
            })
            ->values()
            ->all();
    }

    private function buildDraftMenu(array $all): array
    {
        return [
            'pid' => (int) ($all['pid'] ?? 0),
            'position' => in_array(($all['position'] ?? 'top'), ['top', 'bottom', 'footer'], true) ? $all['position'] : 'top',
            'lang' => ServiceModel::normalizeMenuLang($all['lang'] ?? ''),
            'name' => trim((string) ($all['name'] ?? '')),
            'url' => trim((string) ($all['url'] ?? '#')) ?: '#',
            'target' => in_array(($all['target'] ?? '_self'), ['_self', '_blank'], true) ? $all['target'] : '_self',
            'menu_type' => in_array(($all['menu_type'] ?? 'manual'), ['manual', 'module', 'model', 'search', 'page'], true) ? $all['menu_type'] : 'manual',
            'source_module' => trim((string) ($all['source_module'] ?? '')),
            'source_value' => trim((string) ($all['source_value'] ?? '')),
            'icon' => trim((string) ($all['icon'] ?? '')),
            'icon_character' => trim((string) ($all['icon_character'] ?? '')),
            'sort' => (int) ($all['sort'] ?? 0),
        ];
    }

    private function persistSiblingSortOrder(array $siblings): void
    {
        $total = count($siblings);
        foreach ($siblings as $index => $item) {
            HomeMenu::query()->where('id', $item->id)->update([
                'sort' => ($total - $index) * 10,
                'updated_at' => getDay(),
            ]);
        }
    }

    private function validateMenuParent(array $payload, int $currentId = 0): ?string
    {
        $pid = (int) ($payload['pid'] ?? 0);
        if ($pid <= 0) {
            return null;
        }
        if ($currentId > 0 && $pid === $currentId) {
            return '上级菜单不能选择自己';
        }

        $parent = HomeMenu::query()->find($pid);
        if (!$parent) {
            return '上级菜单不存在';
        }
        if (($parent->position ?? '') !== ($payload['position'] ?? '')) {
            return '上级菜单的位置需要与当前菜单一致';
        }

        $parentLang = ServiceModel::normalizeMenuLang($parent->lang ?? '');
        $currentLang = ServiceModel::normalizeMenuLang($payload['lang'] ?? '');
        if ($parentLang !== $currentLang) {
            return '上级菜单的语言范围需要与当前菜单一致';
        }

        return null;
    }
}

