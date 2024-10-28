<footer class="footer">
    <span>Copyright &copy; {{date("Y")}} {{cacheGlobalSettingsByKey('website_name')}}</span>
    <span class="small text-secondary">Powered By  <a @if(cacheGlobalSettingsByKey('use_of_cloud')==1) href="https://www.mxzcms.com" @endif class="text-secondary" target="_blank">{{config("app.name")}}</a></span>
</footer>

<style type="text/css">
    .common-loading{
        padding-bottom: 80px;
        width: 100%;
        margin-left: 0px;
        padding: 0px 30px 30px 30px;
        display: none;
    }
</style>
