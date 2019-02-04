<?php
session_start();

if ($_SESSION['message']): ?>
    <?php $message = $_SESSION['message']['content'];
    $type = $_SESSION['message']['type'];
    $_SESSION['message'] = null;

    $cssClass = 'dark';
    switch ($type) {
        case 'error':
            $cssClass = 'danger';
            break;
        case 'warning':
            $cssClass = 'warning';
            break;
        case 'info':
            $cssClass = 'info';
            break;
        case 'success':
            $cssClass = 'success';
            break;
    }
    ?>
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="alert alert-<?php echo $cssClass ?>" role="alert">
                <?php echo $message ?>
            </div>
        </div>
    </div>
<?php endif; ?>
