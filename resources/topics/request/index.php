<?php

include_once BASE . '/include/templates/header.php';

if (!$userIsLoggedIn) {
    echo '<script>location.replace("' . BASE_URL . 'resources/topics/request/?auth=true' . '");</script>';
    die();
}

include_once '../../authorize.php';

$name = $user ? $user->getName() : '';

?>
<?php if ($_GET['sent'] == 'true'): ?>
    <div class="row">
        <div class="col">
            <h3>Thank you!</h3>
            <p>Your request to add your topic has been submitted successfully. You will receive an email regarding the
                acceptance status of your request shortly.</p>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col">
            <h1>Topic Addition Request Form</h1>
            <p>Please fill out the form below to request an additional topic be added to the resource repository.</p>
        </div>
    </div>
    <hr/>
    <?php include_once BASE . '/include/templates/message.php' ?>
    <div class="row">
        <div class="col">
            <form method="post" action="resources/topics/request/submit.php">
                <div class="form-row">
                    <div class="col-md-4">
                        <label for="name">Name *</label>
                        <input required class="form-control" name="name" type="text" value="<?php echo $name ?>"/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-4">
                        <label for="seminar">Seminar *</label>
                        <input required class="form-control" name="seminar" type="text"/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-8">
                        <label>Topic *</label>
                        <input required type="text" class="form-control" name="topic" />
                    </div>
                </div>
                <div class="form-row">
                    <div class="col">
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>
<?php include_once BASE . '/include/templates/footer.php'; ?>
