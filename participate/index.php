<?php
include_once '.meta.php';
include_once BASE . '/include/templates/header.php';
session_start();
$onid = $_SESSION['onid'];
$name = $_SESSION['fname'] . ' ' . $_SESSION['lname'];
$uid = $_SESSION['uid'];

$ptTypes = json_decode(file_get_contents(BASE . '/include/data/participation-types.json'), true);
$clubs = json_decode(file_get_contents(BASE . '/include/data/clubs.json'), true);
?>

<?php if ($_GET["submitted"] === "true"): ?>

    <div class="row">
        <div class="col">
            <h1>Thank you!</h1>
            <p>Your participation has been recorded successfully.</p>
        </div>
    </div>

<?php else: ?>

    <div class="row">
        <div class="col">
            <h1>IOTA Participation Form</h1>
            <p>Please fill out the form below to indicate how you participated in the IoT Alliance.</p>
        </div>
    </div>
    <hr/>
    <?php include BASE . '/include/templates/message.php' ?>
    <?php if (empty($onid) || $onid == false): ?>
        <div class="row">
            <div class="col">
                <p>Please <a href="participate/?auth=true">sign in using your ONID</a> to complete the form</p>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col">
                <form method="POST" action="participate/submit.php" id="participationForm">
                    <input type="hidden" name="uid" value="<?php echo $uid ?>"/>
                    <div class="form-row">
                        <div class="col-md-4 form-group">
                            <label>Name (first and last) *</label>
                            <input required class="form-control" type="text" name="name" value="<?php echo $name ?>"
                                   readonly/>
                        </div>
                        <div class="col-md-4 form-group">
                            <label>ONID *</label>
                            <input required class="form-control" type="text" name="onid" value="<?php echo $onid ?>"
                                   readonly/>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-3" id="types">
                            <label>Participation Type *</label>
                            <?php
                            // Types of participation
                            foreach ($ptTypes as $type) {
                                echo '<div class="form-check">';
                                echo '<input required class="form-check-input" type="radio" name="type" value="' . $type['code'] . '"/>';
                                echo '<label class="form-check-label">' . $type['name'] . '</label>';
                                echo '</div>';
                            } ?>
                        </div>
                    </div>
                    <div class="form-row top-buffer" id="clubEventName">
                        <div class="col-md-3">
                            <label>Event Name *</label>
                            <input class="form-control" name="event"/>
                        </div>
                    </div>
                    <div class="form-row top-buffer" id="clubs">
                        <div class="col-md-4">
                            <label>Club Name *</label>
                            <?php
                            // Different possible clubs
                            foreach ($clubs as $club) {
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input" type="radio" name="club" value="' . $club['code'] . '"/>';
                                echo '<label class="form-check-label">' . $club['name'] . '</label>';
                                echo '</div>';
                            }
                            ?>
                        </div>
                        <div class="form-row top-buffer">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="selfieAddon">Selfie</span>
                                    </div>
                                    <div class="custom-file">
                                        <input type="file" name="selfie" accept="image/*" class="custom-file-input"
                                               id="selfie"
                                               aria-describedby="selfieAddon" formenctype="multipart/form-data">
                                        <label class="custom-file-label" for="selfie">Choose image</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-row top-buffer">
                        <div class="col-md-6">
                            <label for="description">Description *</label>
                            <textarea required class="form-control" name="description"
                                      placeholder="Briefly describe your participation here" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="form-row" id="submitParticipationButton">
                        <div class="col top-buffer">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <script>
            $('input[type=radio][name=type]').change(function () {
                if (this.value === 'event') {
                    $('#clubEventName').show();
                    $('input[name=club]').attr('required', true);
                    $('input[name=event]').attr('required', true);
                    $('input[name=selfie]').attr('required', true);
                    $('#clubs').show();
                } else if (this.value === 'meeting') {
                    $('input[name=club]').attr('required', true);
                    $('input[name=selfie]').attr('required', true);
                    $('#clubs').show();
                    $('#clubEventName').hide();
                } else {
                    $('input[name=club]').attr('required', false);
                    $('input[name=event]').attr('required', false);
                    $('input[name=selfie]').attr('required', false);
                    $('#clubEventName').hide();
                    $('#clubs').hide();
                }
            });
            $('#selfie').on('change', function (e) {
                //get the file name
                var filename = e.target.files[0].name;
                //replace the "Choose a file" label
                $(this).next('.custom-file-label').html(filename);
            });
        </script>
    <?php endif; ?>


<?php endif; ?>

<?php include_once BASE . '/include/templates/footer.php'; ?>
