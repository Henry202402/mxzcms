<?php

namespace Modules\System\Http\Controllers\Admin;

use Modules\Formtools\Http\Controllers\Admin\FormTool;
use Modules\Main\Services\ServiceModel;
use Modules\ModulesController;
use Modules\System\Models\Setting;

class SeoController extends ModulesController {

    public function seoConfig(){
        $pageData = getURIByRoute($this->request);
        $pageData['title'] = "基本配置";
        $pageData['subtitle'] = "SEO配置";

        if($this->request->isMethod("post")){
            $all = $this->request->all();
            unset($all["_token"]);
            $robotstxt = $all["seo_robots"];
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

        $robotstxt = file_get_contents(public_path("robots.txt"));

        $formtool = FormTool::create();

        $formtool->field("section","首页SEO配置","",'section');
        $formtool->field("website_name","页面标题",cacheGlobalSettingsByKey("website_name"));
        $formtool->field("website_keys","关键词",cacheGlobalSettingsByKey("website_keys"));
        $formtool->field("website_desc","页面描述",cacheGlobalSettingsByKey("website_desc"))->formtype("website_desc","textarea");

        $formtool->field("section2","通用内容列表SEO配置",'','section');
        $formtool->field("seo_title","页面标题",cacheGlobalSettingsByKey("seo_title"))
            ->notes("seo_title","{{model_name}} 模型名称");

        $formtool->field("seo_keywords","关键词",cacheGlobalSettingsByKey("seo_keywords"))
            ->notes("seo_keywords","{{model_name}} 模型名称");

        $formtool->field("seo_website_desc","页面描述",cacheGlobalSettingsByKey("seo_website_desc"))
            ->formtype("seo_website_desc","textarea")
            ->notes("seo_website_desc","{{model_name}} 模型名称");



        $formtool->field("section4","通用内容详情页SEO配置",'','section');
        $formtool->field("seo_title_detail","页面标题",cacheGlobalSettingsByKey("seo_title_detail"))
            ->notes("seo_title_detail","{{model_name}} 模型名称 {{data_title}} 页面标题 {{data_name}} 页面name {{model_home_page_title}} {{model_home_page_describe}}");

        $formtool->field("seo_keywords_detail","关键词",cacheGlobalSettingsByKey("seo_keywords_detail"))
            ->notes("seo_keywords_detail","{{model_name}} 模型名称 {{data_title}} 页面标题 {{data_name}} 页面name {{model_home_page_title}} {{model_home_page_describe}}");


        $formtool->field("seo_website_desc_detail","页面描述",cacheGlobalSettingsByKey("seo_website_desc_detail"))
            ->formtype("seo_website_desc_detail","textarea")
            ->notes("seo_website_desc_detail","{{model_name}} 模型名称 {{data_title}} 页面标题 {{data_name}} 页面name {{model_home_page_title}} {{model_home_page_describe}}");



        $formtool->field("section3","蜘蛛限制",'','section');
        $formtool->field("seo_limit_domain","域名限制",cacheGlobalSettingsByKey("seo_limit_domain"))
            ->notes("seo_limit_domain","不接受蜘蛛爬行,多个域名用英文逗号隔开");

        $formtool->field("seo_bot_keywords","蜘蛛常用关键词",cacheGlobalSettingsByKey("seo_bot_keywords"))
            ->formtype("seo_bot_keywords","textarea")
            ->notes("seo_bot_keywords","蜘蛛常用关键词,多个关键词回车隔开，关键词如：'bot', 'crawl', 'spider'");


        $formtool->field("seo_robots","robots.txt",$robotstxt)
            ->formtype("seo_robots","textarea")
            ->notes("seo_robots","robots.txt文件内容，User-agent 指令：指定针对哪些爬虫（User-agent）设置规则。Disallow 指令：指定不允许爬虫访问的目录或页面。Allow 指令：指定允许爬虫访问的目录或页面。Crawl-delay 指令：指定爬虫爬取页面的时间间隔。Sitemap 指令：指定网站地图的 URL 地址。");

        $formtool->csrf_field();

        $formtool->formAction(url("admin/system/seo/config"));

        return $formtool->formView($pageData);

    }

    public function GetSeo($data){
        if($data['moduleName'] == "Main"){
            switch ($data['controller']){
                case "Home\Model":
                    switch ($data['action']){
                        case "list":
                            $seoconfig = [
                                "title" => $this->replace($data,cacheGlobalSettingsByKey("seo_title")?:cacheGlobalSettingsByKey("website_name")),
                                "keywords" => $this->replace($data,cacheGlobalSettingsByKey("seo_keywords")?:cacheGlobalSettingsByKey("website_keys")),
                                "description" => $this->replace($data,cacheGlobalSettingsByKey("seo_website_desc")?:cacheGlobalSettingsByKey("website_desc"))
                            ];
                            break;
                        case "detail":
                            $seoconfig = [
                                "title" => $this->replace($data,cacheGlobalSettingsByKey("seo_title_detail")?:cacheGlobalSettingsByKey("website_name")),
                                "keywords" => $this->replace($data,cacheGlobalSettingsByKey("seo_keywords_detail")?:cacheGlobalSettingsByKey("website_keys")),
                                "description" => $this->replace($data,cacheGlobalSettingsByKey("seo_website_desc_detail")?:cacheGlobalSettingsByKey("website_desc"))
                            ];
                            break;
                    }

                    break;
                default:
                    $seoconfig = [
                        "title" => cacheGlobalSettingsByKey("website_name"),
                        "keywords" => cacheGlobalSettingsByKey("website_keys"),
                        "description" => cacheGlobalSettingsByKey("website_desc")
                    ];
                    break;
            }
            return $seoconfig;
        }

    }

    private function replace($data,$string)
    {
        //"{{model_name}} 模型名称 {{data_title}} 页面标题 {{data_name}} 页面name {{model_home_page_title}} {{model_home_page_describe}}"
        if (isset($data['data']) && is_array($data['data'])) $data['data'] = (object)$data['data'];
        if (isset($data['model']) && is_array($data['model'])) $data['model'] = (object)$data['model'];
        $string = str_replace("{{data_title}}", $data['data']->title, $string);
        $string = str_replace("{{data_name}}", $data['data']->name, $string);
        $string = str_replace("{{model_name}}", $data['model']->name, $string);
        $string = str_replace("{{model_home_page_title}}", $data['model']->home_page_title, $string);
        if($data['data']->content){
            $string = str_replace(
                "{{model_home_page_describe}}",
                mb_substr(
                    str_replace(
                        array("<br>", "\r", "\n"),
                    "",
                    strip_tags($data['data']->content)
                    ), 0, 190, 'utf8').'....',
                $string
            );
        }else{
            $string = str_replace("{{model_home_page_describe}}", $data['model']->home_page_describe, $string);
        }
        return $string;
    }

}
