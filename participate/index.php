<?php include_once '.meta.php'; ?>
<?php include_once BASE . '/include/templates/header.php'; ?>
<?php include_once 'formvars.php'; ?>

<div class="row">
    <div class="col">
        <h1>IOTA Participation Form</h1>
    </div>
</div>

<div class="row">
    <div class="col">
        <form>
            <div class="form-row">
                <div class="col-md-4 form-group">
                    <label>Name (first and last) *</label>
                    <input class="form-control" type="text" name="name"/>
                </div>
                <div class="col-md-4 form-group">
                    <label>ONID (&commat;oregonstate.edu) *</label>
                    <input class="form-control" type="text" name="onid"/>
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-4" id="types">
                    <label><strong>Participation Type</strong></label>
                    <?php
                    // Types of participation
                    foreach ($participationTypes as $id => $name) {
                        $fid = 'type' . str_replace(' ', '', $name);
                        echo '<div class="form-check">';
                        echo '<input class="form-check-input" type="radio" name="type" id="' . $fid . '" value="' . $id . '"/>';
                        echo '<label class="form-check-label" for="' . $fid . '">' . $name . '</label>';
                        echo '</div>';
                    } ?>
                </div>
                <div class="col-md-4" id="clubs">
                    <label><strong>Club Name</strong></label>
                    <?php
                    // Different possible clubs
                    foreach ($clubNames as $id => $name) {
                        $fid = 'club' . str_replace(' ', '', $name);
                        echo '<div class="form-check">';
                        echo '<input class="form-check-input" type="radio" name="club" id="' . $fid . '" value="' . $id . '"/>';
                        echo '<label class="form-check-label" for="' . $fid . '">' . $name . '</label>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
            <div class="form-row">
                <div class="col">
                    <button class="btn btn-primary" type="submit">Submit</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    $('input[type=radio][name=type]').change(function () {
        if (this.value === 'event' || this.value === 'meeting') {
            $('#clubs').show();
        } else {
            $('#clubs').hide();
        }
    });
    $('input[type=radio][name=club]').change(function () {
        console.log(this.value);
    });
</script>
<?php include_once BASE . '/include/templates/footer.php'; ?>
