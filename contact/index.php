<?php include_once '.meta.php' ?>
<?php include_once BASE . '/include/templates/header.php' ?>

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

    <div class="row">
        <div class="col">
            <form method="POST" action="contact/submit.php">
                <div class="form-row">
                    <div class="form-group col-4">
                        <input class="form-control" type="text" name="name" placeholder="Enter name *" required/>
                    </div>
                    <div class="form-group col-4">
                        <input class="form-control" type="email" name="email" placeholder="Enter email address *"
                               required/>
                    </div>
                    <div class="form-group col-4">
                        <input class="form-control" type="text" name="subject" placeholder="Enter message subject *"
                               required/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col">
                        <textarea class="form-control" rows="10" placeholder="How can we help?" required></textarea>
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
