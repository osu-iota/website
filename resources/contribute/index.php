<?php
include_once PUBLIC_FILES . '/lib/authorize.php';

allowIf($userIsContributor, false);

$css[] = 'assets/css/participate-form.css';

include_once PUBLIC_FILES . '/components/header.php'; ?>

<?php if ($_GET['submitted'] == 'true'): ?>
<div class="row">
    <div class="col">
        <h3>Thank you!</h3>
        <p>Your resource was uploaded successfully.</p>
    </div>
</div>
<?php else: ?>
<?php

    $topic = array();
    try {
        $sql = 'SELECT * FROM iota_resource_topic';
        $prepared = $db->prepare($sql);
        $prepared->setFetchMode(PDO::FETCH_ASSOC);
        $prepared->execute();
        $topics = $prepared->fetchAll();
    } catch (Exception $e) {
        $logger->error($e->getMessage());
    }
    ?>
<div class="row">
    <div class="col">
        <h1>Contribute New Content</h1>
    </div>
</div>
<?php include_once PUBLIC_FILES . '/components/message.php' ?>
<div class="row form-contribute-resource">
    <div class="col">
        <form id="formContributeResource">
            <input type="hidden" name="action" value="add" />
            <div class="form-row">
                <div class="col-md-6 col-lg-4">
                    <label>Resource Name *</label>
                    <input required type="text" class="form-control" name="name" maxlength="200" />
                </div>
            </div>
            <div class="form-row">
                <div class="col-md-6 col-lg-4">
                    <label for="newTopic">Resource Topics *</label>
                    <div class="resource-topics-container" id="topics">
                        <?php foreach ($topics as $topic): ?>
                        <label>
                            <input type="checkbox" name="topics[]"
                                value="<?php echo $topic['rtid'] ?>" />
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
                    <textarea required type="text" class="form-control" name="description" rows="5"></textarea>
                </div>
            </div>

            <div class="form-row">
                <div class="col-sm 2 col-md-4 col-lg-3">
                    <label>Resource File * (10MB Limit)</label>
                    <div class="custom-file">
                        <input required type="file" class="custom-file-input" name="resource">
                        <label class="custom-file-label" for="customFile">Choose File</label>
                    </div>
                </div>
            </div>
            <div class="form-row form-submit">
                <div class="col">
                    <button id="formSubmit" class="btn btn-primary" type="submit">Submit</button>
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
        let eForm = document.getElementById('formContributeResource');
        let eButtonNewTopicRequest = document.getElementById('btnNewRequest');

        $('input[name=resource]').on('change', function(e) {
            //get the file name
            var filename = e.target.files[0].name;
            //replace the "Choose a file" label
            $(this).next('.custom-file-label').html(filename);
        });

        function onContributeResourceFormSubmit() {

            let form = document.getElementById('formContributeResource');
            let fdata = new FormData(form);

            api.post('/contributions', fdata, true).then(res => {
                window.location.replace(window.location.href.split('?')[0] + '?submitted=true');
            }).catch(err => {
                snackbar(err.message, 'error');
                hide(eFormLoader);
                show(eFormSubmitButton);
            });

            hide(eFormSubmitButton);
            show(eFormLoader);

            return false;
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
        eForm.addEventListener('submit', onContributeResourceFormSubmit);
        eButtonTopicRequest.addEventListener('mouseup', onTopicRequestSubmit);
        eButtonNewTopicRequest.addEventListener('mouseup', onPrepareNewTopicRequest);
        eInputSeminar.addEventListener('keypress', onTopicRequestKeyPress);
        eInputTopic.addEventListener('keypress', onTopicRequestKeyPress);
    })();
</script>

<?php endif; ?>

<?php include_once PUBLIC_FILES . '/components/footer.php';
