<?php
include_once BASE . '/components/header.php';

$name = $user ? $user->getName() : '';
$email = $user ? $user->getEmail() : '';

?>

<?php if ($_GET["submitted"] === "true"): ?>

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
            <p>Use the form below to contact the OSU Internet of Things Alliance regarding resource contribution access
                or any questions, comments, or concerns you may have.</p>
        </div>
    </div>
    <hr>
    <?php include_once BASE . '/components/message.php' ?>
    <div class="row">
        <div class="col">
            <form id="contactUsForm" onsubmit="return onContactUsFormSubmit()">
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
                               value="<?php echo !empty($email) ? $email : '' ?>"/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-8">
                        <label for="subject">Subject *</label>
                        <input required class="form-control" type="text" name="subject"/>
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
    <script>
        function onContactUsFormSubmit() {

            let form = document.getElementById('contactUsForm');
            let fdata = new FormData(form);

            let body = {
                name: fdata.get('name'),
                email: fdata.get('email'),
                subject: fdata.get('subject'),
                content: fdata.get('content')
            };

            api.post('/contact', body).then(res => {
                window.location.replace(window.location.href.split('?')[0] + '?submitted=true');
            }).catch(err => {
                snackbar(err.message, 'error');
            });

            return false;
        }
    </script>
<?php endif; ?>
<?php include_once BASE . '/components/footer.php' ?>
