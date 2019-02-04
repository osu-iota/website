<?php include_once BASE . '/include/templates/header.php';

if (!$userIsContributor):
    ?>
    <?php
    $_SESSION['message'] = array(
        'content' => 'You do not have permissions to contribute content.',
        'type' => 'info'
    );
    header('Location: ' . BASE_URL . '/resources/')
    ?>
<?php elseif($_GET['submitted'] == "true"): ?>
    <div class="row">
        <div class="col">
            <h3>Thank you!</h3>
            <p>Your resource was uploaded successfully.</p>
        </div>
    </div>
<?php else: ?>
    <?php
    $uid = $_SESSION['uid'];
    // Get all the topics
    $sql = 'SELECT * FROM iota_resource_topic';
    $topics = $db->query($sql);
    ?>
    <div class="row">
        <div class="col">
            <h1>Contribute New Content</h1>
        </div>
    </div>
    <?php include_once BASE . '/include/templates/message.php' ?>
    <div class="row form-contribute-resource">
        <div class="col">
            <form method="post" action="resources/contribute/submit.php" enctype="multipart/form-data">
                <input type="hidden" name="uid" value="<?php echo $uid ?>"/>
                <div class="form-row">
                    <div class="col-md-6 col-lg-4">
                        <label>Resource Name *</label>
                        <input required type="text" class="form-control" name="name" maxlength="200"/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-lg-8">
                        <label>Resource Description *</label>
                        <textarea required type="text" class="form-control" name="description" rows="5"></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 col-lg-4">
                        <label for="newTopic">Resource Topics *</label>
                        <div class="resource-topics-container" id="topics">
                            <?php
                            foreach ($topics as $topic) {
                                echo '<label><input type="checkbox" name="topics[]" value="' . $topic['rtid'] . '"/>&nbsp;' . $topic['rt_name'] . '</label><br>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-sm 2 col-md-4 col-lg-3">
                        <label>Resource File *</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="resource">
                            <label class="custom-file-label" for="customFile">Choose File</label>
                        </div>
                    </div>
                </div>
                <div class="form-row form-submit">
                    <div class="col">
                        <button class="btn btn-primary" type="submit">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        $('input[name=resource]').on('change', function (e) {
            //get the file name
            var filename = e.target.files[0].name;
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(filename);
        });
    </script>

<?php endif; ?>
