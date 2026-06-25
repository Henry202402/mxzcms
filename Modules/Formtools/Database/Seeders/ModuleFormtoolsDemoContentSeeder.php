<?php

namespace Modules\Formtools\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ModuleFormtoolsDemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedAboutUs();
        $this->seedContactUs();
        $this->seedNewsCategories();
        $this->seedNews();
        $this->seedMilestones();
        $this->seedAgreementCategories();
        $this->seedAgreements();
        $this->seedFeedback();
        $this->tuneModelConfigs();
    }

    private function seedAboutUs(): void
    {
        if (DB::table('module_formtools_about_us')->count() > 0) {
            return;
        }

        $now = Carbon::now()->toDateTimeString();
        DB::table('module_formtools_about_us')->insert([
            'content' => <<<HTML
<section>
    <h2>关于我们</h2>
    <p>我们专注于为企业与内容团队提供稳定、灵活、可扩展的数字化内容管理能力，帮助站点在信息展示、表单互动、前台可视化和内容运营之间形成统一闭环。</p>
    <p>Formtools 模块这次整理完成后，后台模型配置、字段管理、内容管理和前台展示已经能够覆盖单页模型、多页模型、首页区块、分页列表、详情展示和表单提交等常见场景。</p>
</section>
<section>
    <h3>我们的能力</h3>
    <ul>
        <li>支持动态模型配置与前后台联动展示</li>
        <li>支持单模型、多模型、分类模型的混合管理</li>
        <li>支持首页区块、列表分页、详情页和留言互动</li>
        <li>支持按业务字段扩展内容类型与展示方式</li>
    </ul>
</section>
<section>
    <h3>服务理念</h3>
    <p>我们坚持“配置优先、兼容历史、页面可落地”的原则，既照顾现有业务数据，也兼顾后续扩展效率。</p>
</section>
HTML,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function seedContactUs(): void
    {
        if (DB::table('module_formtools_contact_us')->count() > 0) {
            return;
        }

        $now = Carbon::now()->toDateTimeString();
        DB::table('module_formtools_contact_us')->insert([
            'company_name' => '梦轩内容科技有限公司',
            'company_address' => '深圳市南山区科技园数字内容创新中心 18 楼',
            'username' => '产品顾问 李经理',
            'phone' => '400-800-2024',
            'email' => 'service@example.com',
            'is_open_leave' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }

    private function seedNewsCategories(): void
    {
        $rows = [
            ['id' => 1, 'cate_name' => '产品动态'],
            ['id' => 2, 'cate_name' => '功能更新'],
            ['id' => 3, 'cate_name' => '客户案例'],
            ['id' => 4, 'cate_name' => '运营观察'],
        ];

        foreach ($rows as $row) {
            DB::table('module_formtools_news_cate')->updateOrInsert(
                ['id' => $row['id']],
                ['cate_name' => $row['cate_name']]
            );
        }
    }

    private function seedNews(): void
    {
        if (DB::table('module_formtools_news')->count() > 0) {
            return;
        }

        $covers = [
            'views/themes/default/assets/img/demos/blog/1.jpg',
            'views/themes/default/assets/img/demos/blog/2.jpg',
            'views/themes/default/assets/img/demos/blog/3.jpg',
            'views/themes/default/assets/img/demos/blog/4.jpg',
            'views/themes/default/assets/img/demos/blog/5.jpg',
            'views/themes/default/assets/img/demos/blog/6.jpg',
        ];
        $categories = [
            1 => '产品动态',
            2 => '功能更新',
            3 => '客户案例',
            4 => '运营观察',
        ];
        $rows = [];

        for ($i = 1; $i <= 24; $i++) {
            $categoryId = (($i - 1) % 4) + 1;
            $publishedAt = Carbon::now()->subDays(24 - $i)->setTime(9 + ($i % 6), 20, 0);
            $rows[] = [
                'cover' => $covers[($i - 1) % count($covers)],
                'pid' => $categoryId,
                'title' => sprintf('%s第 %02d 期：站点内容体验优化记录', $categories[$categoryId], $i),
                'content' => $this->buildNewsContent($i, $categories[$categoryId]),
                'created_at' => $publishedAt->toDateTimeString(),
                'updated_at' => $publishedAt->copy()->addHours(2)->toDateTimeString(),
            ];
        }

        DB::table('module_formtools_news')->insert($rows);
    }

    private function seedMilestones(): void
    {
        if (DB::table('module_formtools_milestone')->count() > 0) {
            return;
        }

        $items = [
            ['title' => '项目立项', 'date' => '2023-03-01', 'content' => '<p>完成站点需求梳理，明确动态模型、内容管理与主题展示三大能力主线。</p>'],
            ['title' => 'Formtools 初版上线', 'date' => '2023-06-18', 'content' => '<p>支持基础模型管理、字段设计和表单提交，形成后台可配置能力。</p>'],
            ['title' => '前台主题接入', 'date' => '2023-10-09', 'content' => '<p>前台首页、列表页、详情页与表单页完成统一路由接入。</p>'],
            ['title' => '兼容性升级', 'date' => '2024-02-24', 'content' => '<p>完成历史模型迁移，兼容单模型、多模型、缺省字段与主题覆写模板。</p>'],
            ['title' => '演示数据与首页联调', 'date' => '2025-12-08', 'content' => '<p>补齐新闻、历程、协议、留言等演示数据，并优化首页区块与分页体验。</p>'],
            ['title' => '内容运营版优化', 'date' => '2026-06-19', 'content' => '<p>针对后台内容管理、字段显示、富文本编辑器和前台交互做一轮完整走查。</p>'],
        ];

        foreach ($items as $index => $item) {
            $createdAt = Carbon::parse($item['date'])->setTime(10, 0)->toDateTimeString();
            $items[$index]['created_at'] = $createdAt;
            $items[$index]['updated_at'] = $createdAt;
        }

        DB::table('module_formtools_milestone')->insert($items);
    }

    private function seedAgreementCategories(): void
    {
        $rows = [
            ['id' => 1, 'cate_name' => '平台协议'],
            ['id' => 2, 'cate_name' => '隐私条款'],
            ['id' => 3, 'cate_name' => '合作规范'],
            ['id' => 4, 'cate_name' => '售后说明'],
        ];

        foreach ($rows as $row) {
            DB::table('module_formtools_agreement_cate')->updateOrInsert(
                ['id' => $row['id']],
                ['cate_name' => $row['cate_name']]
            );
        }
    }

    private function seedAgreements(): void
    {
        if (DB::table('module_formtools_agreement')->count() > 0) {
            return;
        }

        $rows = [];
        $categoryNames = [
            1 => '平台协议',
            2 => '隐私条款',
            3 => '合作规范',
            4 => '售后说明',
        ];

        for ($i = 1; $i <= 12; $i++) {
            $categoryId = (($i - 1) % 4) + 1;
            $createdAt = Carbon::now()->subDays(40 - $i)->setTime(11, 0)->toDateTimeString();
            $rows[] = [
                'cate_id' => $categoryId,
                'name' => sprintf('%s示例协议 %02d', $categoryNames[$categoryId], $i),
                'content' => $this->buildAgreementContent($i, $categoryNames[$categoryId]),
                'seo_keywords' => implode(',', [$categoryNames[$categoryId], '示例协议', '内容管理']),
                'seo_description' => sprintf('这是用于前后台联调展示的%s示例协议内容。', $categoryNames[$categoryId]),
                'status' => 1,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        DB::table('module_formtools_agreement')->insert($rows);
    }

    private function seedFeedback(): void
    {
        if (DB::table('module_formtools_feedback')->count() > 0) {
            return;
        }

        $names = ['张晨', '李悦', '王涵', '赵楠', '陈旭', '周航', '孙萌', '何嘉', '邓倩', '唐峰', '许琳', '高宇', '杨清', '沈星', '曹彬', '潘悦', '郭晨', '林泽'];
        $companies = ['远山科技', '星帆互联', '北岸设计', '云麦网络', '橙序软件', '海图传媒'];
        $rows = [];

        foreach ($names as $index => $name) {
            $createdAt = Carbon::now()->subDays(18 - $index)->setTime(14, 10)->toDateTimeString();
            $rows[] = [
                'full_name' => $name,
                'email' => 'demo' . ($index + 1) . '@example.com',
                'company' => $companies[$index % count($companies)],
                'website' => 'https://example.com/demo-' . ($index + 1),
                'content' => sprintf('这是第 %02d 条演示留言，用于验证后台内容管理、分页展示、字段显示以及前台提交回显。', $index + 1),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ];
        }

        DB::table('module_formtools_feedback')->insert($rows);
    }

    private function tuneModelConfigs(): void
    {
        $this->updateModel('about_us', [
            'show_home_page' => 'yes',
            'home_page_num' => 1,
            'home_page_sort' => 10,
            'home_config' => [
                'list_template' => 'about',
                'detail_template' => 'about',
                'page_num' => 1,
                'list_page_template' => 'center',
                'home_page_title' => '关于我们',
                'home_page_title_color' => '#1f2937',
                'home_page_describe' => '从模型设计到前台展示，形成一套可维护的动态内容运营体系。',
                'home_page_describe_color' => '#6b7280',
                'show_home_type' => 'color',
                'home_page_bg_color' => '#ffffff',
                'detail_page_show_type' => 'color',
                'detail_page_bg_color' => '#ffffff',
            ],
        ]);

        $this->updateModel('contact_us', [
            'show_home_page' => 'yes',
            'home_page_num' => 1,
            'home_page_sort' => 40,
            'home_config' => [
                'list_template' => 'contacts',
                'detail_template' => 'contacts',
                'page_num' => 1,
                'list_page_template' => 'center',
                'home_page_title' => '联系我们',
                'home_page_title_color' => '#1f2937',
                'home_page_describe' => '欢迎通过电话、邮箱或在线留言与我们交流需求。',
                'home_page_describe_color' => '#6b7280',
                'show_home_type' => 'color',
                'home_page_bg_color' => '#f8fafc',
                'detail_page_show_type' => 'color',
                'detail_page_bg_color' => '#ffffff',
            ],
        ]);

        $this->updateModel('news', [
            'show_home_page' => 'yes',
            'home_page_num' => 6,
            'home_page_sort' => 20,
            'home_config' => [
                'list_template' => 'titleContentImage',
                'detail_template' => 'detailLeftList',
                'page_num' => 9,
                'list_page_template' => 'center',
                'home_page_title' => '最新动态',
                'home_page_title_color' => '#111827',
                'home_page_describe' => '聚合产品动态、功能更新、客户案例与运营观察，便于首页直观展示。',
                'home_page_describe_color' => '#6b7280',
                'show_home_type' => 'color',
                'home_page_bg_color' => '#ffffff',
                'detail_page_show_type' => 'color',
                'detail_page_bg_color' => '#ffffff',
            ],
        ]);

        $this->updateModel('milestone', [
            'show_home_page' => 'yes',
            'home_page_num' => 5,
            'home_page_sort' => 30,
            'home_config' => [
                'list_template' => 'milestone',
                'detail_template' => 'detail',
                'page_num' => 10,
                'list_page_template' => 'center',
                'home_page_title' => '发展历程',
                'home_page_title_color' => '#111827',
                'home_page_describe' => '通过里程碑时间轴梳理产品演进与站点迭代过程。',
                'home_page_describe_color' => '#6b7280',
                'show_home_type' => 'color',
                'home_page_bg_color' => '#f8fafc',
                'detail_page_show_type' => 'color',
                'detail_page_bg_color' => '#ffffff',
            ],
        ]);

        $this->updateModel('agreement', [
            'show_home_page' => 'yes',
            'home_page_num' => 6,
            'home_page_sort' => 50,
            'home_config' => [
                'list_template' => 'list',
                'detail_template' => 'detail',
                'page_num' => 10,
                'list_page_template' => 'center',
                'home_page_title' => '协议中心',
                'home_page_title_color' => '#111827',
                'home_page_describe' => '集中展示协议、条款和服务说明，方便首页快速访问常用文档。',
                'home_page_describe_color' => '#6b7280',
                'show_home_type' => 'color',
                'home_page_bg_color' => '#ffffff',
                'detail_page_show_type' => 'color',
                'detail_page_bg_color' => '#ffffff',
            ],
        ]);

        $this->updateModel('feedback', [
            'show_home_page' => 'yes',
            'home_page_num' => 3,
            'home_page_sort' => 60,
            'home_config' => [
                'list_template' => 'feedback',
                'detail_template' => 'detail',
                'page_num' => 10,
                'list_page_template' => 'center',
                'home_page_title' => '在线留言',
                'home_page_title_color' => '#111827',
                'home_page_describe' => '展示最新留言摘要，并支持访客直接进入留言页面提交反馈。',
                'home_page_describe_color' => '#6b7280',
                'show_home_type' => 'color',
                'home_page_bg_color' => '#f8fafc',
                'detail_page_show_type' => 'color',
                'detail_page_bg_color' => '#ffffff',
            ],
        ]);
    }

    private function updateModel(string $identification, array $payload): void
    {
        $model = DB::table('module_formtools_models')->where('identification', $identification)->first();
        if (!$model) {
            return;
        }

        $homeConfig = array_merge(
            json_decode($model->home_config ?: '[]', true) ?: [],
            $payload['home_config'] ?? []
        );

        DB::table('module_formtools_models')
            ->where('identification', $identification)
            ->update([
                'show_home_page' => $payload['show_home_page'] ?? $model->show_home_page,
                'home_page_num' => $payload['home_page_num'] ?? $model->home_page_num,
                'home_page_sort' => $payload['home_page_sort'] ?? $model->home_page_sort,
                'home_config' => json_encode($homeConfig, JSON_UNESCAPED_UNICODE),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]);
    }

    private function buildNewsContent(int $index, string $categoryName): string
    {
        return <<<HTML
<p>这是 {$categoryName} 栏目的第 {$index} 条演示内容，用于验证后台内容管理、前台列表分页、详情页展示和首页区块数据联动。</p>
<p>本次内容主要围绕模型配置、字段兼容、分页展示、UI 呈现和首页推荐位优化展开，确保站点在空数据、旧结构和多模板场景下也能正常运行。</p>
<ul>
    <li>支持后台按模型录入内容</li>
    <li>支持首页自动读取最新数据</li>
    <li>支持前台分页列表与详情跳转</li>
    <li>支持后续继续扩展字段和模板</li>
</ul>
HTML;
    }

    private function buildAgreementContent(int $index, string $categoryName): string
    {
        return <<<HTML
<h3>{$categoryName}示例条款 {$index}</h3>
<p>本协议内容用于演示协议类模型在后台内容管理中的录入效果，以及前台列表、详情与 SEO 字段展示效果。</p>
<p>如需正式上线，请将示例文案替换为真实条款内容，并在后台继续完善关键字、描述和分类信息。</p>
HTML;
    }
}
