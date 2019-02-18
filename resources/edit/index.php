<?php
include_once '../authorize.php';
include_once BASE . '/include/templates/header.php'; ?>

<?php if ($_GET['submitted'] == "true"): ?>
    <div class="row">
        <div class="col">
            <h3>Thank you!</h3>
            <p>The changes to your resource were saved successfully.</p>
        </div>
    </div>
<?php else: ?>
    <?php

    // Retrieve the resource info to edit
    $rid = $_GET['r'];

    if (empty($rid) || $rid == '') {
        fail('Could not find resource to edit. Invalid or missing resource id');
    }

    // Default values
    $resource = array();
    $resourceTopics = array();
    $topics = array();
    $oldRdid = null;

    try {
        // Get the info about the resource
        $sql = 'SELECT * FROM iota_resource WHERE rid = :rid';
        $prepared = $db->prepare($sql);
        $prepared->bindParam(':rid', $rid, PDO::PARAM_STR);
        $prepared->execute();
        $prepared->setFetchMode(PDO::FETCH_ASSOC);
        $resource = $prepared->fetchAll()[0];

        // Get the old resource data id
        $sql = 'SELECT rdid FROM iota_resource_data WHERE rid = :rid AND rd_active = :active';
        $active = true;
        $prepared = $db->prepare($sql);
        $prepared->bindParam(':rid', $rid, PDO::PARAM_STR);
        $prepared->bindParam(':active', $active, PDO::PARAM_BOOL);
        $prepared->execute();
        $prepared->setFetchMode(PDO::FETCH_ASSOC);
        $result = $prepared->fetchAll()[0];
        $oldRdid = $result['rdid'];

        // Get all the available topics
        $sql = 'SELECT * FROM iota_resource_topic';
        $topics = $db->query($sql);

        // Get the topics associated with this resource
        $sql = 'SELECT rtid FROM iota_resource_for WHERE rid = :rid';
        $prepared = $db->prepare($sql);
        $prepared->bindParam(':rid', $rid, PDO::PARAM_STR);
        $prepared->execute();
        $prepared->setFetchMode(PDO::FETCH_ASSOC);
        $results = $prepared->fetchAll();
        $resourceTopics = array_map(function ($result) {
            return $result['rtid'];
        }, $results);

    } catch (PDOException $e) {
        $logger->error($e->getMessage());
    }

    $uid = $_SESSION['uid'];

    ?>
    <div class="row">
        <div class="col">
            <h1>Edit <?php echo $resource['r_name'] ?></h1>
        </div>
    </div>
    <?php include_once BASE . '/include/templates/message.php' ?>
    <div class="row form-contribute-resource">
        <div class="col">
            <form method="post" action="resources/edit/submit.php" enctype="multipart/form-data">
                <input type="hidden" name="rid" value="<?php echo $rid ?>"/>
                <input type="hidden" name="oldRdid" value="<?php echo $oldRdid ?>"/>
                <div class="form-row">
                    <div class="col-md-6 col-lg-4">
                        <label>Resource Name *</label>
                        <input required type="text" class="form-control" name="name" maxlength="200"
                               value="<?php echo $resource['r_name'] ?>"/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-lg-8">
                        <label>Resource Description *</label>
                        <textarea required type="text" class="form-control" name="description"
                                  rows="5"><?php echo $resource['r_description'] ?></textarea>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-md-6 col-lg-4">
                        <label for="newTopic">Resource Topics *</label>
                        <div class="resource-topics-container" id="topics">
                            <?php foreach ($topics as $topic): ?>
                                <label><input type="checkbox" name="topics[]"
                                              value="<?php echo $topic['rtid'] ?>"
                                        <?php if (in_array($topic['rtid'], $resourceTopics)) echo 'checked' ?>/>
                                    &nbsp;<?php echo $topic['rt_name'] ?> </label><br>

                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="col-sm 2 col-md-4 col-lg-3">
                        <label>New Resource File (optional)</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="resource">
                            <label class="custom-file-label" for="customFile">Choose File</label>
                        </div>
                    </div>
                </div>
                <div class="form-row form-submit">
                    <div class="col">
                        <button class="btn btn-primary" type="submit">Save Changes</button>
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
<?php include_once BASE . '/include/templates/footer.php'; ?>
