<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <span class="mx-member-footer-copy">{{ cacheGlobalSettingsByKey('website_name') ?: 'MXZCMS' }} &copy; <script>document.write(new Date().getFullYear())</script></span>
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    <span class="mx-member-footer-links">
                        @if(cacheGlobalSettingsByKey('website_icp'))
                            <a href="https://beian.miit.gov.cn/" target="_blank" rel="noopener noreferrer">{{ cacheGlobalSettingsByKey('website_icp') }}</a>
                        @endif
                        <a href="{{ url('/') }}" target="_blank" rel="noopener noreferrer">返回前台</a>
                    </span>
                </div>
            </div>
        </div>
    </div>
</footer>
