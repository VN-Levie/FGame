<?php if (!$hide_header) : ?>
    <footer class="footer">
        <div class="container">

        </div>
    </footer>
<?php endif; ?>
<span href="#" id="toTopBtn" class="cd-top rounded-pill p-3" data-abc="true">
    <i class="fa-solid fa-chevron-up"></i>
</span>
<style>
    #toTopBtn {
        position: fixed;
        bottom: 26px;
        right: 39px;
        z-index: 98;
        /* padding: 21px; */
        background-color: hsla(5, 76%, 62%, .8);

    }
    /* hover cd top change cusor */
    .cd-top:hover {
        cursor: pointer;
    }
</style>
<script>
    $(document).ready(function() {
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('#toTopBtn').fadeIn();
            } else {
                $('#toTopBtn').fadeOut();
            }
        });

        $('#toTopBtn').click(function() {
            $("html, body").animate({
                scrollTop: 0
            }, 1000);
            return false;
        });
    });
</script>

</body>

</html>