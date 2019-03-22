<?php
include_once PUBLIC_FILES . '/components/header.php';
include_once '../menu.php';
?>
<div class="admin-main">
    <?php if($_GET['sent'] == 'true'): ?>
        <div class="row">
            <div class="col">
                <h4>Thank you</h4>
                <p>Your email was sent successfully.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col">
                <h1>Send Mass Email</h1>
                <p>Fill out the form below to send out an email to all users currently recorded in the database</p>
            </div>
        </div>
        <hr/>
        <?php include PUBLIC_FILES . '/components/message.php' ?>
        <div class="row">
            <div class="col">
                <form method="post" action="admin/users/email/submit.php">
                    <div class="form-row">
                        <div class="col-lg-6">
                            <label for="subject">Subject *</label>
                            <input required type="text" class="form-control" name="subject" id="subject"/>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-lg-10">
                            <label for="emailContent">Content *</label>
                            <textarea required class="form-control" name="content" id="emailContent" rows="10"></textarea>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <button type="submit" class="btn btn-primary">
                                Send Message
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?>

</div>


<?php include_once PUBLIC_FILES . '/components/footer.php' ?>
