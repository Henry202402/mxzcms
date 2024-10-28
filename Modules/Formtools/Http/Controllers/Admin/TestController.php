<?php

namespace Modules\Formtools\Http\Controllers\Admin;

use Illuminate\Support\Facades\DB;
use Modules\ModulesController;

class TestController extends ModulesController {

    public function index() {
        $all = $this->request->all();
        $pageData = getURIByRoute($this->request);
        $datas = DB::table('module_formtools_banners as banners')
                ->leftJoin("module_formtools_banner_cate as banner_cate",'banners.cate_id','banner_cate.id')
                ->select(['banners.*','banner_cate.name as cate_id'])
                ->paginate(getLen());

        return FormTool::create()
            ->field("id","ID")
            ->field("title","名称")
            ->field("url",'图片')
            ->field("cate_id","分类")
            ->rightaction([
                ['actionName'=>'编辑','actionUrl'=>url("admin/formtools/testedit"),'cssClass'=>"btn-success",'param'=>['page'=>$_GET['page']]],
                ['actionName'=>'删除','actionUrl'=>url('admin/formtools/testdel'),'cssClass'=>"btn-danger","confirm"=>true,'param'=>['page'=>$_GET['page']]
            ]],"id")
            ->pageTitle("模型管理")
            ->formTitle("万能模型列表")
            ->searchField("title","请输入名称",$all['title']??"")
            ->searchField("cate_id","请选择分类",$all['cate_id'],"select",[["value"=>1,"name"=>"测试1"],["value"=>2,"name"=>"测试2"]])
            ->listAction([
                ['actionName'=>'添加','actionUrl'=>url("admin/formtools/testadd"),'cssClass'=>"bg-info"],
                ['actionName'=>'删除','actionUrl'=>url('admin/formtools/testdel'),'cssClass'=>"bg-danger"]
            ])
            ->linkAppend($all)
            ->listview($pageData,$datas);

    }

    public function Add(){

        return FormTool::create()
            ->field("name","名称")->placeholder("name","请输入名称")

            ->field("password","密码")->placeholder("password","请输入密码")->formtype("password","password")
            ->field("password2","确认密码")->placeholder("password2","请确认密码")->formtype("password2","password")

            ->field("cate_id","分类")->placeholder("cate_id","请选择分类")->formtype("cate_id","select")
            ->datas('cate_id',[["value"=>1,"name"=>"测试1"],["value"=>2,"name"=>"测试2"]])

            ->field("name2","只读名称")->placeholder("name2","只读名称")->value('name2','我只能读取')->formtype('name2','readonly')

            ->field("name3","禁用名称")->placeholder("name3","禁用名称")->value('name3','禁用读取')->disabled('name3',"disabled")

            ->field("cate_id2","分类2")->placeholder("cate_id2","请选择分类")->formtype("cate_id2","radio")
            ->datas('cate_id2',[["value"=>1,"name"=>"测试3"],["value"=>2,"name"=>"测试4"]])

            ->field("cate_id3[]","分类3")->placeholder("cate_id3[]","请选择分类")->formtype("cate_id3[]","checkbox")
            ->datas('cate_id3[]',[["value"=>1,"name"=>"测试5"],["value"=>2,"name"=>"测试6"]])

            ->field("des","描述")->placeholder("des","请输入描述")->formtype("des","textarea")

            ->field("img",['name'=>'图片','placeholder'=>"请选择图片",'formtype'=>"image"])

            ->field('file',"上传文件")->placeholder("file","请选择文件")->formtype("file","file")


            ->csrf_field()
            ->formTitle("万能模型添加")
            ->pageTitle("模型管理")
            ->formAction(url('admin/formtools/testdo'))
            ->formView();

    }

    public function Edit(){
        return FormTool::create()
            ->field("id","")->formtype("id","hidden")->value("id",5)
            ->field("name","名称")->placeholder("name","请输入名称")->value("name",'名称')

            ->field("password","密码")->placeholder("password","请输入密码")->formtype("password","password")
            ->field("password2","确认密码")->placeholder("password2","请确认密码")->formtype("password2","password")

            ->field("cate_id","分类")->placeholder("cate_id","请选择分类")->formtype("cate_id","select")->value("cate_id",2)
            ->datas('cate_id',[["value"=>1,"name"=>"测试1"],["value"=>2,"name"=>"测试2"]])

            ->field("name2","只读名称")->placeholder("name2","只读名称")->value('name2','我只能读取')->formtype('name2','readonly')

            ->field("name3","禁用名称")->placeholder("name3","禁用名称")->value('name3','禁用读取')->disabled('name3',"disabled")

            ->field("cate_id2","分类2")->placeholder("cate_id2","请选择分类")->formtype("cate_id2","radio")->value("cate_id2",2)
            ->datas('cate_id2',[["value"=>1,"name"=>"测试3"],["value"=>2,"name"=>"测试4"]])

            ->field("cate_id3[]","分类3")->placeholder("cate_id3[]","请选择分类")->formtype("cate_id3[]","checkbox")->value("cate_id3[]",[1,2])
            ->datas('cate_id3[]',[["value"=>1,"name"=>"测试5"],["value"=>2,"name"=>"测试6"]])

            ->field("des","描述")->placeholder("des","请输入描述")->formtype("des","textarea")->value("des",'描述')

            ->field("img",['name'=>'图片','placeholder'=>"请选择图片",'formtype'=>"image"])->value("img",'https://gimg2.baidu.com/image_search/src=http%3A%2F%2Fsafe-img.xhscdn.com%2Fbw1%2Fc03a255b-2dee-4ec6-afd3-9b5ca0af9d55%3FimageView2%2F2%2Fw%2F1080%2Fformat%2Fjpg&refer=http%3A%2F%2Fsafe-img.xhscdn.com&app=2002&size=f9999,10000&q=a80&n=0&g=0n&fmt=auto?sec=1704401180&t=4586b7dc12b7ad8d9b81e13bc06cb76d')

            ->field('file',"上传文件")->placeholder("file","请选择文件")->formtype("file","file")


            ->csrf_field()
            ->formTitle("万能模型添加")
            ->pageTitle("模型管理")
            ->formAction(url('admin/formtools/testdo'))
            ->formView();
    }

    public function Delete(){

    }

    public function Handle(){
        $all = $this->request->all();
        dd($all);
    }

}
