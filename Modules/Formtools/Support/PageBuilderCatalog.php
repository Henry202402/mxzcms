<?php

namespace Modules\Formtools\Support;

class PageBuilderCatalog
{
    public static function blocks(): array
    {
        return [
            [
                'type' => 'section',
                'name' => '区块容器',
                'desc' => '页面的大区块，适合挂背景、边距和多个子元素。',
                'schema' => '{"type":"section","style":{"padding":"48px 0","background":"#ffffff"},"children":[]}',
            ],
            [
                'type' => 'row',
                'name' => '行布局',
                'desc' => '12 栅格行容器，常和 column 搭配做两栏、三栏布局。',
                'schema' => '{"type":"row","children":[]}',
            ],
            [
                'type' => 'column',
                'name' => '列布局',
                'desc' => '行内列单元，通过 span 控制占比。',
                'schema' => '{"type":"column","props":{"span":6},"blocks":[]}',
            ],
            [
                'type' => 'row',
                'name' => '双列布局',
                'desc' => '快速插入 6-6 双列结构，适合图文、卖点或左右信息并排。',
                'schema' => '{"type":"row","style":{"gap":"24px"},"children":[{"type":"column","props":{"span":6},"blocks":[{"type":"heading","props":{"level":"h3","text":"左侧内容"},"style":{"margin":"0 0 12px"}},{"type":"text","props":{"text":"这里放左侧文案、图片说明或模块介绍。"}}]},{"type":"column","props":{"span":6},"blocks":[{"type":"heading","props":{"level":"h3","text":"右侧内容"},"style":{"margin":"0 0 12px"}},{"type":"text","props":{"text":"这里放右侧文案、表单、按钮或补充信息。"}}]}]}',
            ],
            [
                'type' => 'row',
                'name' => '三列布局',
                'desc' => '快速插入 4-4-4 三列结构，适合卡片、服务或优势展示。',
                'schema' => '{"type":"row","style":{"gap":"20px"},"children":[{"type":"column","props":{"span":4},"blocks":[{"type":"heading","props":{"level":"h3","text":"模块一"},"style":{"margin":"0 0 12px"}},{"type":"text","props":{"text":"这里是第一列内容。"}}]},{"type":"column","props":{"span":4},"blocks":[{"type":"heading","props":{"level":"h3","text":"模块二"},"style":{"margin":"0 0 12px"}},{"type":"text","props":{"text":"这里是第二列内容。"}}]},{"type":"column","props":{"span":4},"blocks":[{"type":"heading","props":{"level":"h3","text":"模块三"},"style":{"margin":"0 0 12px"}},{"type":"text","props":{"text":"这里是第三列内容。"}}]}]}',
            ],
            [
                'type' => 'row',
                'name' => '左右分栏 4-8',
                'desc' => '快速插入左窄右宽布局，适合侧栏导航、介绍加表单等场景。',
                'schema' => '{"type":"row","style":{"gap":"24px"},"children":[{"type":"column","props":{"span":4},"blocks":[{"type":"heading","props":{"level":"h3","text":"左侧侧栏"},"style":{"margin":"0 0 12px"}},{"type":"text","props":{"text":"适合放目录、标签、摘要或导航。"}}]},{"type":"column","props":{"span":8},"blocks":[{"type":"heading","props":{"level":"h2","text":"右侧主内容"},"style":{"margin":"0 0 12px"}},{"type":"text","props":{"text":"适合放正文、卡片矩阵、表单或详情内容。"}}]}]}',
            ],
            [
                'type' => 'row',
                'name' => '四列布局',
                'desc' => '快速插入 3-3-3-3 四列结构，适合 Logo、数据或服务网格。',
                'schema' => '{"type":"row","style":{"gap":"18px"},"children":[{"type":"column","props":{"span":3},"blocks":[{"type":"text","props":{"text":"列一"}}]},{"type":"column","props":{"span":3},"blocks":[{"type":"text","props":{"text":"列二"}}]},{"type":"column","props":{"span":3},"blocks":[{"type":"text","props":{"text":"列三"}}]},{"type":"column","props":{"span":3},"blocks":[{"type":"text","props":{"text":"列四"}}]}]}',
            ],
            [
                'type' => 'heading',
                'name' => '标题',
                'desc' => '支持 h1-h6，适合页面主标题和模块标题。',
                'schema' => '{"type":"heading","props":{"level":"h2","text":"模块标题"},"style":{"margin":"0 0 16px"}}',
            ],
            [
                'type' => 'text',
                'name' => '文本',
                'desc' => '普通说明文字或段落文案。',
                'schema' => '{"type":"text","props":{"text":"这里是页面描述内容。"},"style":{"lineHeight":"1.8"}}',
            ],
            [
                'type' => 'image',
                'name' => '图片',
                'desc' => '用于视觉图、横幅、品牌图标。',
                'schema' => '{"type":"image","props":{"src":"data:image/svg+xml,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%22960%22 height=%22640%22 viewBox=%220 0 960 640%22%3E%3Crect width=%22960%22 height=%22640%22 rx=%2236%22 fill=%22%23e2e8f0%22/%3E%3Cpath d=%22M152 470l164-182 120 132 112-126 260 176H152z%22 fill=%22%23cbd5e1%22/%3E%3Ccircle cx=%22312%22 cy=%22208%22 r=%2254%22 fill=%22%2394a3b8%22/%3E%3Ctext x=%2250%25%22 y=%2288%25%22 text-anchor=%22middle%22 font-size=%2238%22 font-family=%22Arial,sans-serif%22 fill=%22%23475569%22%3EImage Placeholder%3C/text%3E%3C/svg%3E","alt":"示意图"}}',
            ],
            [
                'type' => 'button',
                'name' => '按钮',
                'desc' => '跳转按钮或操作入口。',
                'schema' => '{"type":"button","props":{"href":"/contact","text":"联系我们"},"style":{"display":"inline-block","padding":"12px 20px"}}',
            ],
            [
                'type' => 'divider',
                'name' => '分隔线',
                'desc' => '模块之间的视觉分隔。',
                'schema' => '{"type":"divider","style":{"margin":"24px 0","borderColor":"#e5e7eb"}}',
            ],
            [
                'type' => 'text',
                'name' => '引用文本',
                'desc' => '适合放强调语句、客户评价或专题引言。',
                'schema' => '{"type":"text","props":{"text":"一句有记忆点的引言或重点说明。"},"style":{"padding":"18px 20px","background":"#f8fafc","borderLeft":"4px solid #2563eb","fontSize":"16px","lineHeight":"1.9","color":"#334155"}}',
            ],
            [
                'type' => 'button',
                'name' => '主按钮',
                'desc' => '带主色和圆角的转化按钮。',
                'schema' => '{"type":"button","props":{"href":"#","text":"立即咨询"},"style":{"display":"inline-flex","padding":"12px 22px","background":"#2563eb","color":"#ffffff","borderRadius":"999px"}}',
            ],
            [
                'type' => 'image',
                'name' => '品牌 Logo',
                'desc' => '适合放品牌标志、合作方 Logo 或图标。',
                'schema' => '{"type":"image","props":{"src":"/uploads/logo.png","alt":"品牌 Logo"},"style":{"width":"160px","objectFit":"contain"}}',
            ],
            [
                'type' => 'carousel',
                'name' => '轮播横幅',
                'desc' => '适合首页首屏、活动焦点图或多张卖点 Banner。',
                'schema' => '{"type":"carousel","props":{"source_type":"manual","autoplay":"1","interval":"5000","buttonText":"立即咨询","buttonHref":"/contact","slides":[{"title":"这里是一张轮播主视觉","description":"插入后可继续改成真实轮播脚本，当前先用可视化结构承接标题、描述和按钮文案。","image":"https://dummyimage.com/1600x720/2563eb/ffffff&text=Slide+01","buttonText":"立即咨询","buttonHref":"/contact"},{"title":"第二张卖点横幅","description":"适合放活动主推、功能亮点、品牌优势或服务说明。","image":"https://dummyimage.com/1600x720/0f172a/ffffff&text=Slide+02","buttonText":"查看方案","buttonHref":"/solutions"},{"title":"第三张促转化横幅","description":"手动模式适合营销页；切成模型数据后，可直接从内容模型拉取轮播数据。","image":"https://dummyimage.com/1600x720/e2e8f0/0f172a&text=Slide+03","buttonText":"联系我们","buttonHref":"/contact"}]}}',
            ],
            [
                'type' => 'html',
                'name' => '线索表单',
                'desc' => '适合咨询、预约、报名、留资等商业转化场景。',
                'schema' => '{"type":"html","props":{"html":"<div style=\\"padding:24px;border-radius:20px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 12px 28px rgba(15,23,42,.06);\\"><h3 style=\\"margin:0 0 10px;font-size:24px;color:#0f172a;\\">预约咨询</h3><p style=\\"margin:0 0 18px;color:#64748b;line-height:1.8;\\">留下你的联系方式，我们会尽快联系你。</p><div style=\\"display:grid;gap:12px;\\"><input type=\\"text\\" placeholder=\\"请输入姓名\\" style=\\"height:44px;padding:0 14px;border:1px solid #dbe2ea;border-radius:12px;\\"><input type=\\"text\\" placeholder=\\"请输入手机号\\" style=\\"height:44px;padding:0 14px;border:1px solid #dbe2ea;border-radius:12px;\\"><textarea placeholder=\\"请输入需求说明\\" style=\\"min-height:110px;padding:12px 14px;border:1px solid #dbe2ea;border-radius:12px;\\"></textarea><button type=\\"button\\" style=\\"height:46px;border:0;border-radius:12px;background:#2563eb;color:#fff;font-weight:600;cursor:pointer;\\">提交咨询</button></div></div>"}}',
            ],
            [
                'type' => 'html',
                'name' => '提示框',
                'desc' => '高亮展示公告、提醒或操作说明。',
                'schema' => '{"type":"html","props":{"html":"<div style=\\"padding:16px 18px;border-radius:14px;background:#eff6ff;border:1px solid #bfdbfe;color:#1d4ed8;line-height:1.8;\\">这里是一条重要提示信息。</div>"}}',
            ],
            [
                'type' => 'html',
                'name' => '功能卡片',
                'desc' => '适合放能力说明、服务亮点、产品卖点。',
                'schema' => '{"type":"html","props":{"html":"<div style=\\"padding:24px;border-radius:18px;background:#ffffff;border:1px solid #e2e8f0;box-shadow:0 12px 28px rgba(15,23,42,.05);\\"><h3 style=\\"margin:0 0 12px;font-size:20px;color:#0f172a;\\">功能标题</h3><p style=\\"margin:0;color:#64748b;line-height:1.8;\\">这里放一段功能说明、模块介绍或卖点文案。</p></div>"}}',
            ],
            [
                'type' => 'html',
                'name' => '统计卡片',
                'desc' => '适合放数据、数字指标、成果展示。',
                'schema' => '{"type":"html","props":{"html":"<div style=\\"padding:22px 24px;border-radius:18px;background:linear-gradient(135deg,#1d4ed8 0%,#2563eb 100%);color:#ffffff;\\"><div style=\\"font-size:14px;opacity:.82;\\">累计服务</div><div style=\\"margin-top:8px;font-size:38px;font-weight:700;line-height:1;\\">2,580+</div></div>"}}',
            ],
            [
                'type' => 'html',
                'name' => 'FAQ 项',
                'desc' => '适合页面里的常见问题单条内容。',
                'schema' => '{"type":"html","props":{"html":"<div style=\\"padding:20px 22px;border-radius:16px;border:1px solid #e2e8f0;background:#ffffff;\\"><h3 style=\\"margin:0 0 10px;font-size:18px;color:#0f172a;\\">这里是一个常见问题？</h3><p style=\\"margin:0;color:#64748b;line-height:1.8;\\">这里是问题答案说明，可以继续手动修改成真实业务内容。</p></div>"}}',
            ],
            [
                'type' => 'video',
                'name' => '视频嵌入',
                'desc' => '放视频 iframe 或第三方播放器。',
                'schema' => '{"type":"video","props":{"source_type":"embed","title":"视频介绍","embed_url":"https://www.youtube.com/embed/dQw4w9WgXcQ","mp4_url":"","poster":"https://dummyimage.com/1280x720/0f172a/ffffff&text=Video","aspect_ratio":"16:9","controls":"1","autoplay":"0","muted":"0","loop":"0"}}',
            ],
            [
                'type' => 'gallery',
                'name' => '图库画廊',
                'desc' => '适合案例展示、作品集、产品相册、活动现场图。',
                'schema' => '{"type":"gallery","props":{"title":"案例图库","subtitle":"支持手动图片列表，也支持从模型列表拉取封面图。","source_type":"manual","columns":"3","gap":"18px","items":[{"title":"案例一","image":"https://dummyimage.com/960x720/e2e8f0/0f172a&text=Gallery+01","url":"/cases/1"},{"title":"案例二","image":"https://dummyimage.com/960x720/dbeafe/1d4ed8&text=Gallery+02","url":"/cases/2"},{"title":"案例三","image":"https://dummyimage.com/960x720/fef3c7/92400e&text=Gallery+03","url":"/cases/3"}]}}',
            ],
            [
                'type' => 'faq',
                'name' => '常见问题',
                'desc' => '适合官网 FAQ、售前答疑、服务说明、活动须知。',
                'schema' => '{"type":"faq","props":{"title":"常见问题","intro":"把用户最关心的问题集中放在这里，减少重复沟通。","columns":"1","items":[{"question":"多久可以上线？","answer":"通常先搭页面结构，再补真实内容与表单联动，常规营销页可以快速上线。"},{"question":"支持模型数据吗？","answer":"当前已支持轮播、图片、图库等基础模型来源，详情型组件后续继续扩展。"},{"question":"可以继续自定义样式吗？","answer":"可以，常用属性走右侧可视化，复杂样式仍可回到 JSON 微调。"}]}}',
            ],
            [
                'type' => 'stats',
                'name' => '数据指标',
                'desc' => '适合放成交数据、服务规模、增长数字、品牌背书。',
                'schema' => '{"type":"stats","props":{"title":"核心数据","intro":"用一组高亮数字快速建立信任感。","columns":"4","items":[{"label":"服务客户","value":"2580","suffix":"+","description":"覆盖多行业项目"},{"label":"页面转化率","value":"36","suffix":"%","description":"营销活动页平均表现"},{"label":"项目交付","value":"98","suffix":"%","description":"按期上线率"},{"label":"顾问响应","value":"15","suffix":"min","description":"工作时间内快速响应"}]}}',
            ],
            [
                'type' => 'cta',
                'name' => '行动横幅',
                'desc' => '适合页面尾部转化、活动报名、咨询引导。',
                'schema' => '{"type":"cta","props":{"eyebrow":"Ready To Launch","title":"把你的商业页面快速搭起来","description":"统一内容、样式和动效配置，减少重复搭建成本。","primaryText":"立即咨询","primaryHref":"/contact","secondaryText":"查看案例","secondaryHref":"/cases","align":"left"},"style":{"padding":"32px","background":"linear-gradient(135deg,#0f172a 0%,#1e293b 100%)","borderRadius":"24px"}}',
            ],
            [
                'type' => 'navigation',
                'name' => '导航组件',
                'desc' => '适合顶部导航、页内锚点导航、品牌头部导航。',
                'schema' => '{"type":"navigation","props":{"title":"站点导航","logoType":"text","brandHref":"/","logoImage":"","logoSvg":"","logoAlt":"品牌 Logo","layout":"horizontal","items":[{"text":"首页","href":"#hero"},{"text":"产品","href":"#products","children":[{"text":"产品总览","href":"#products"},{"text":"产品定价","href":"#pricing"}]},{"text":"方案","href":"#solutions","children":[{"text":"企业方案","href":"#enterprise"},{"text":"门店方案","href":"#stores"}]},{"text":"联系我们","href":"#contact"}],"ctaText":"立即咨询","ctaHref":"#contact"},"style":{"padding":"12px 18px","background":"#ffffff","border":"1px solid #e2e8f0","borderRadius":"16px"}}',
            ],
            [
                'type' => 'sidebar',
                'name' => '侧边栏组件',
                'desc' => '适合固定悬浮工具栏、返回顶部、咨询入口、自定义快捷栏位，支持独立配色、点击弹出二维码面板，以及富文本 / HTML 自定义内容。',
                'schema' => '{"type":"sidebar","props":{"title":"快捷入口","position":"right","offsetTop":"120px","showBackTop":"1","items":[{"text":"在线咨询","href":"#contact","icon":"咨","actionType":"link","panelType":"custom","panelTitle":"","panelContent":"","panelValue":"","panelHtml":"","background":"#ffffff","color":"#2563eb","borderColor":"#bfdbfe"},{"text":"微信咨询","href":"","icon":"微","actionType":"panel","panelType":"qrcode","panelTitle":"扫码咨询","panelContent":"添加顾问，获取专属方案与报价","panelValue":"https://example.com/wechat","panelHtml":"","background":"#0f172a","color":"#ffffff","borderColor":"rgba(148,163,184,.22)"},{"text":"服务手册","href":"","icon":"册","actionType":"panel","panelType":"custom","panelTitle":"资料领取","panelContent":"下方可以继续展示说明文案或按钮链接。","panelValue":"/uploads/sidebar-brochure.jpg","panelHtml":"<div><h4>活动资料</h4><p>这里可放富文本、自定义 HTML、按钮等内容。</p><p><a href=\"#contact\">立即联系</a></p></div>","background":"#eff6ff","color":"#1d4ed8","borderColor":"#bfdbfe"}]},"style":{"gap":"12px"}}',
            ],
            [
                'type' => 'qrcode',
                'name' => '二维码组件',
                'desc' => '适合引导扫码咨询、关注公众号、下载应用。',
                'schema' => '{"type":"qrcode","props":{"title":"扫码咨询","text":"微信扫码，获取专属顾问服务","value":"https://example.com/contact","size":"140"},"style":{"padding":"20px","background":"#ffffff","border":"1px solid #e2e8f0","borderRadius":"18px","textAlign":"center"}}',
            ],
            [
                'type' => 'login_box',
                'name' => '登录组件',
                'desc' => '适合页面顶部账号入口，未登录显示按钮，已登录显示头像入口。',
                'schema' => '{"type":"login_box","props":{"title":"账号入口","loginText":"立即登录","loginHref":"/login","profileText":"个人中心","profileHref":"/member","avatarUrl":""},"style":{"padding":"14px 16px","background":"#ffffff","border":"1px solid #dbeafe","borderRadius":"999px"}}',
            ],
            [
                'type' => 'html',
                'name' => '自定义 HTML',
                'desc' => '先用代码块承接复杂结构，后续拖拽器可继续扩展成高级组件。',
                'schema' => '{"type":"html","props":{"html":"<div class=\\"hero\\">自定义结构</div>"}}',
            ],
            [
                'type' => 'model_list',
                'name' => '模型列表占位',
                'desc' => '为后续模型列表组件预留协议。',
                'schema' => '{"type":"model_list","props":{"model":"news","limit":6,"template":"card"}}',
            ],
            [
                'type' => 'model_detail',
                'name' => '模型详情占位',
                'desc' => '输出一条模型详情，适合单页、内容详情或页面主内容区。',
                'schema' => '{"type":"model_detail","props":{"model":"about_us","template":"detail"}}',
            ],
        ];
    }
}
