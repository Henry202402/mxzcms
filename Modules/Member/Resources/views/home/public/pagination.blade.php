<style>

    .custom-pagination-top {
        display: flex;
        justify-content: center;
    }

    .custom-pagination-top .pagination {
        text-align: center;
        line-height: 35px;
        font-size: 16px;
    }

    .custom-pagination-top .pagination li {
        float: left;
        width: 35px;
        height: 35px;
        background-color: #fff;
        list-style: none;
        cursor: pointer;
        border-radius: 50%;
        margin: 0 2px;
    }

    .custom-pagination-top .pageDiv {
        float: right;
        margin-top: 20px;
    }

    .custom-pagination-top .pagination li a {
        width: 90%;
        height: 90%;
        color: #707d8a;
        text-decoration-line: none;
        display: inline-block;

    }

    .custom-pagination-top .active {
        background-color: #3b5de7 !important;
        color: #fff;
        border-radius: 50%;
    }

    .custom-pagination-top .active a {
        color: #fff !important;

    }

    .custom-pagination-top li:hover {
        background-color: #f0f0f0;
        border-radius: 50%;

    }

    .custom-pagination-top li:hover a {
        color: #3b5de7 !important;
    }

    .custom-pagination-top li:first-child, .custom-pagination-top li:last-child {
        font-size: 25px;
    }

    .custom-pagination-top .active:hover a {
        color: #fff !important;
    }
</style>

<div class="custom-pagination-top">
    {{ $pageDataArray->appends($_GET)->links('formtools::admin.public.pagination',["data"=>$pageDataArray]) }}
</div>
<script>

</script>