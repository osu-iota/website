<?php
include_once PUBLIC_FILES . '/lib/authorize.php';

allowIf($userIsContributor, false);

$css[] = 'assets/css/participate-form.css';
include_once PUBLIC_FILES . '/components/header.php'; ?>

<?php if ($_GET['submitted'] == 'true'): ?>
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

        // Get the old resource config id
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

    $uid = $user->getId();

    ?>
<div class="row">
    <div class="col">
        <h1>Edit: <?php echo $resource['r_name'] ?>
        </h1>
    </div>
</div>
<?php include_once PUBLIC_FILES . '/components/message.php' ?>
<div class="row form-contribute-resource">
    <div class="col">
        <form id="formEditResource" onsubmit="return false;">
            <input type="hidden" name="action" id="action" value="update" />
            <input type="hidden" name="id"
                value="<?php echo $resource['rid'] ?>" />
            <div class="form-row">
                <div class="col-md-6 col-lg-4">
                    <label>Resource Name *</label>
                    <input required type="text" class="form-control" name="name" maxlength="200"
                        value="<?php echo $resource['r_name'] ?>" />
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 col-lg-4">
                    <label>Resource Topics *</label>
                    <div class="resource-topics-container" id="topics">
                        <?php foreach ($topics as $topic): ?>
                        <label>
                            <input type="checkbox" name="topics[]"
                                value="<?php echo $topic['rtid'] ?>"
                                <?php 
                                if (in_array($topic['rtid'], $resourceTopics)) {
                                    echo 'checked';
                                } ?>
                            />
                            &nbsp;<?php echo $topic['rt_name'] ?>
                        </label>
                        <br />
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <a href="#requestNewTopic" data-toggle="collapse" aria-expanded="false"
                        aria-controls="#requestNewTopic">Request a new topic</a>
                    <div class="collapse new-topic-request-container" id="requestNewTopic">
                        <div id="topicRequestResponse">
                            <p id="topicRequestResponseMessage"></p>
                            <button id="btnNewRequest" type="button" class="btn btn-sm btn-primary">Request another
                                topic
                            </button>
                        </div>
                        <div id="topicRequestForm">
                            <div class="req-form">
                                <input class="form-control" id="newTopicSeminar" type="text"
                                    placeholder="Name of seminar" />
                                <input type="text" class="form-control" id="newTopic" placeholder="Suggest a topic" />
                                <button type="button" class="btn btn-sm btn-primary" id="btnTopicRequest">
                                    Submit Topic Request
                                </button>
                                <div class=" loader loader-topic" id="topicLoader"></div>
                            </div>
                            <div class="invalid-feedback" id="topicRequestError"></div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="form-row">
                <div class="col-lg-8">
                    <label>Resource Description *</label>
                    <textarea required class="form-control" name="description" rows="5"><?php echo $resource['r_description'] ?>
                    </textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="col-sm 2 col-md-4 col-lg-3">
                    <label>New File (optional, 10MB Limit)</label>
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="resource">
                        <label class="custom-file-label" for="customFile">Choose File</label>
                    </div>
                </div>
            </div>
            <div class="form-row form-submit">
                <div class="col">
                    <button id="formSubmit" class="btn btn-primary" type="submit">Save Changes</button>
                    <div class="loader" id="formLoader"></div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    (function() {
        // Get references to all elements here
        let eInputTopic = document.getElementById('newTopic');
        let eInputSeminar = document.getElementById('newTopicSeminar');
        let eRequestForm = document.getElementById('topicRequestForm');
        let eResponse = document.getElementById('topicRequestResponse');
        let eResponseMessage = document.getElementById('topicRequestResponseMessage');
        let eError = document.getElementById('topicRequestError');
        let eButtonTopicRequest = document.getElementById('btnTopicRequest');
        let eTopicLoader = document.getElementById('topicLoader');
        let eFormLoader = document.getElementById('formLoader');
        let eFormSubmitButton = document.getElementById('formSubmit');
        let eForm = document.getElementById('formEditResource');
        let eButtonNewTopicRequest = document.getElementById('btnNewRequest');

        $('input[name=resource]').on('change', function(e) {
            //get the file name
            var filename = e.target.files[0].name;
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(filename);
        });

        function onEditResourceFormSubmit() {

            let form = document.getElementById('formEditResource');
            let fdata = new FormData(form);
            let id = fdata.get('id');
            api.post(`/contributions?id=${id}`, fdata, true).then(res => {
                window.location.replace(window.location.href.split('?')[0] + '?submitted=true');
            }).catch(err => {
                snackbar(err.message, 'error');
                hide(eFormLoader);
                show(eFormSubmitButton);
            });

            hide(eFormSubmitButton);
            show(eFormLoader);
        }

        function onTopicRequestSubmit() {

            let body = {
                type: 'topic',
                topic: eInputTopic.value,
                seminar: eInputSeminar.value
            };
            eError.innerText = '';
            api.post('/requests', body).then(res => {
                eResponseMessage.innerText = res.message;
                eInputTopic.value = '';
                eInputSeminar.value = '';
                hide(eRequestForm);
                hide(eTopicLoader);
                show(eButtonTopicRequest);
                show(eResponse);
            }).catch(err => {
                console.log(err);
                eError.innerText = err.message;
                hide(eTopicLoader);
                show(eButtonTopicRequest);
            });
            hide(eButtonTopicRequest);
            show(eTopicLoader);
            return false;
        }

        function onPrepareNewTopicRequest() {
            hide(eResponse);
            show(eRequestForm);
        }

        function onTopicRequestKeyPress(e) {
            if (e.key === 'Enter') {
                freezeEvent(e);
                onTopicRequestSubmit();
            }
        }

        // Add additional listeners here
        eForm.addEventListener('submit', onEditResourceFormSubmit);
        eButtonTopicRequest.addEventListener('mouseup', onTopicRequestSubmit);
        eButtonNewTopicRequest.addEventListener('mouseup', onPrepareNewTopicRequest);
        eInputSeminar.addEventListener('keypress', onTopicRequestKeyPress);
        eInputTopic.addEventListener('keypress', onTopicRequestKeyPress);
    })();
</script>

<?php endif; ?>
<?php include_once PUBLIC_FILES . '/components/footer.php';
