</main> <!-- .container -->
<footer>
    &copy;&nbsp;<?php echo date('Y'); ?> Oregon State University
    <a class="disclaimer" href="https://oregonstate.edu/official-web-disclaimer" target="_blank">Disclaimer</a>
</footer>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
</script>
</body>
</html>
<?php
// In the bootstrap.php file included in the header (from the root level) we opened up a database connection. We need to
// close it here
$db = null;
?>
