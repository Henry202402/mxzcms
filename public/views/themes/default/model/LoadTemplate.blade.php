@include("themes.default.public.head")
@include("themes.default.public.preloader")

<style>
    a, a:hover {
        text-decoration-line: none;
    }
</style>
<div class="page js-page ">
    @include("themes.default.public.topMenu")
    @include("themes.default.public.header")
    @include($template)
    <footer class="js-footer-is-fixed">
        <div class="footer">
            @include("themes.default.public.footerMenu")
        </div>
    </footer>
</div>
@include("themes.default.public.js")
</body>
</html>
