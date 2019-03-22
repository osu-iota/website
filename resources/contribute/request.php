<?php
session_start();
include_once PUBLIC_FILES . '/include/templates/header.php';
if (!$userIsLoggedIn) {
    echo '<script>location.replace("'. BASE_URL . 'resources/contribute/request/?auth=true' .'");</script>';
    die();
}

$name = $user ? $user->getName() : '';

?>
<?php if ($_GET['sent'] == 'true'): ?>
    <div class="row">
        <div class="col">
            <h3>Thank you!</h3>
            <p>Your request to contribute was submitted successfully. You will receive an email once your request has
                been processed.</p>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col">
            <h1>Permission to Contribute Request Form</h1>
            <p>Please fill out the form below to request permission to contribute to the IOTA resource repository. Only
                users who have led a seminar sponsored by the Internet-of-Things Alliance will be granted permission to
                contribute.</p>
        </div>
    </div>
    <hr/>
    <?php include_once PUBLIC_FILES . '/include/templates/message.php' ?>
    <div class="row">
        <div class="col">
            <form method="post" action="resources/contribute/request/submit.php">
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
                        <label>Role *</label>
                        <textarea required class="form-control" name="role"
                                  placeholder="Please indicate what role you played in the seminar mentioned above"></textarea>
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
<?php include_once PUBLIC_FILES . '/include/templates/footer.php'; ?>
