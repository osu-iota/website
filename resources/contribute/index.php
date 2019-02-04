<?php include_once BASE . '/include/templates/header.php';

if(!$userIsContributor):
?>
    <?php
    $_SESSION['message'] = array(
        'content' => 'You do not have permissions to contribute content.',
        'type' => 'info'
    );
    header('Location: ' . BASE_URL . '/resources/')
?>
<?php else: ?>
    <div class="row">
        <div class="col">
            <h1>Contribute New Content</h1>
        </div>
    </div>
<?php endif; ?>
