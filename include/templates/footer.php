</main> <!-- .container -->
<script>
    let snackbarQueue = [];
    function snackbar(message) {
        let sb = document.createElement('div');
        sb.classList.add('snackbar');
        sb.appendChild(document.createTextNode(message));
        snackbarQueue.push(sb);
        if(snackbarQueue.length === 1) {
            showSnackbar();
        }
    }

    function showSnackbar(){
        let el = snackbarQueue.shift();
        document.getElementsByTagName('body')[0].appendChild(el);
        el.classList.add('show');
        setTimeout(function() {
            el.classList.remove('show');
            if(snackbarQueue.length > 0) {
                showSnackbar();
            }
            el.remove();
        }, 3000);
    }
</script>
<footer>
    &copy;&nbsp;<?php echo date('Y'); ?> Oregon State University
    <a class="disclaimer" href="https://oregonstate.edu/official-web-disclaimer" target="_blank">Disclaimer</a>
</footer>
</body>
</html>
<?php
// In the bootstrap.php file included in the header (from the root level) we opened up a database connection. We need to
// close it here
$db = null;
?>
