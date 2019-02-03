<?php include_once '.meta.php' ?>
</main> <!-- .container -->
<footer>
    &copy;&nbsp;<?php echo date('Y'); ?> Oregon State University
    <a class="disclaimer" href="https://oregonstate.edu/official-web-disclaimer" target="_blank">Disclaimer</a>
</footer>
</body>
</html>
<?php
// In the .meta.php file included in the header (from the root level) we opened up a database connection. We need to
// close it here
$db = null;
?>
