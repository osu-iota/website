<?php
include_once BASE . '/include/templates/header.php';
session_start();

$name = $_SESSION['fname'] . ' ' . $_SESSION['lname'];
$email = $_SESSION['email'];

?>

<?php if ($_GET["sent"] === "true"): ?>

    <div class="row">
        <div class="col">
            <h1>Thank you!</h1>
            <p>Your message has been sent successfully.</p>
        </div>
    </div>

<?php else: ?>
    <div class="row">
        <div class="col">
            <h1>Contact Us</h1>
            <p>Use the form below to contact the OSU Internet of Things Alliance regarding any questions, comments, or
                concerns you may have.</p>
        </div>
    </div>
    <hr>
    <?php include_once BASE . '/include/templates/message.php' ?>
    <div class="row">
        <div class="col">
            <form method="POST" action="contact/submit.php">
                <div class="form-row">
                    <div class="form-group col-sm-8 col-md-4">
                        <label for="name">Name *</label>
                        <input required class="form-control" type="text" name="name"
                               value="<?php echo !empty($name) ? $name : '' ?>"/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-sm-8 col-md-4">
                        <label for="email">Email *</label>
                        <input required class="form-control" type="email" name="email"
                               placeholder="Enter email address *"
                               value="<?php echo !empty($email) ? $email : '' ?>"/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label for="subject">Subject *</label>
                        <input required class="form-control" type="text" name="subject"
                               placeholder="Enter message subject *"/>
                    </div>
                    <div class="form-group col-12">
                        <textarea required name="content" class="form-control" rows="10"
                                  placeholder="How can we help?"></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>
<?php include_once BASE . '/include/templates/footer.php' ?>
