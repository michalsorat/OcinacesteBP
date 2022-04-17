<footer>
    <div class="footer-holder">
        <span class="footer-text">Copyright © 2021 Michal Sorát, All rights reserved</span>
    </div>
</footer>

<script>
    $(function () {
        var url = window.location.href;
        $(".navbar-nav .nav-link").each(function () {
            if (url == (this.href)) {
                $(this).closest(".nav-link").addClass("active");
                $(this).closest("nav-link").parent().parent().addClass("active");
            }
        });
    });
</script>
