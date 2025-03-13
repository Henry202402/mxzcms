<style>
    #backToTopBtn {
        position: fixed;
        bottom: 30px;
        right: 30px;
        padding: 15px 20px;
        font-size: 16px;
        color: white;
        background-color: #000f42;
        border: none;
        border-radius: 3px;
        cursor: pointer;
        z-index: 1000;
        display: none; /* 初始状态隐藏按钮 */
    }
</style>
<!-- 回到顶部按钮 -->
<div id="backToTopBtn">
    <i class="icon-arrow-up7"></i>
</div>
<script>
    window.onscroll = function () {
        scrollFunction();
    };

    function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
            document.getElementById("backToTopBtn").style.display = "block";
        } else {
            document.getElementById("backToTopBtn").style.display = "none";
        }
    }

    document.getElementById("backToTopBtn").addEventListener("click", function () {
        window.scrollTo({top: 0, behavior: 'smooth'});
    });
</script>