<style>
    #hPagination .laypage_main a {
        font-size: 14px;
        color: #666;
        padding: 15px 12px;
        margin: 5px;
        line-height: 0em;
        border: 1px solid #999;
        display: inline-block;
        text-decoration: none;
    }

    #hPagination .laypage_curr {
        color: #fff;
        border-color: #3388ff;
        background-color: #3388ff;

        font-size: 14px;
        padding: 16px 13px;
        margin: 5px;
        line-height: 0em;
        display: inline-block;
        text-decoration: none;
    }
</style>
<script src="{{url('views/modules/assets/laypage/laypage.js')}}" charset="UTF-8"></script>
<script>
    var page = 1;

    //分頁
    function dataPage(curr, pages) {
        var path = window.location.origin + window.location.pathname;
        laypage({
            cont: 'hPagination', //容器。值支持id名、原生dom对象，jquery对象,
            pages: pages, //总页数
            curr: curr,
            // first: false, //将首页显示为数字1,。若不显示，设置false即可
            // last: false, //将尾页显示为总页数。若不显示，设置false即可
            prev: '<', //若不显示，设置false即可
            next: '>',//若不显示，设置false即可
            jump: function (e, first) { //触发分页后的回调
                if (!first) { //一定要加此判断，否则初始时会无限刷新
                    // console.log(e.curr);
                    var str = '';
                    page = e.curr;
                    str += `?page=${page}`;
                    @foreach($_GET as $k=>$g)
                         @if($k!='page')str += `&{{$k}}=` + "{{$g}}";@endif
                    @endforeach
                    window.location = path + str;
                }
            }
        })
    }

    $(function () {
        dataPage('{{$data->currentPage()}}', '{{$data->lastPage()}}');
    })
</script>