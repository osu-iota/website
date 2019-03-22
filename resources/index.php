<?php
include_once PUBLIC_FILES . '/components/header.php';
include_once PUBLIC_FILES . '/lib/topic-html.php';

$topics = array();
$sql = 'SELECT * FROM iota_resource_topic ORDER BY rt_name';
try {
    $topics = $db->query($sql);
} catch (PDOException $e) {
    $logger->error($e->getMessage());
}

session_start();
?>
<div class="row">
    <div class="col">
        <h1>Resource Repository</h1>
        <p>The IoT Alliance offers a repository of resources and materials on topics typically covered during events
            related to the alliance. All users can search and download content from the repository.</p>
    </div>
</div>
<?php include_once PUBLIC_FILES . '/components/message.php' ?>

<div class="row">
    <div class="col contribute-resource">
        <?php if ($userIsContributor): ?>
            <a href="resources/contribute">
                <button class="btn btn-primary">Contribute Resource</button>
            </a>
        <?php elseif ($userIsLoggedIn): ?>
            <a href="resources/contribute/request">
                <button class="btn btn-primary">Request Permission to Contribute</button>
            </a>
        <?php else: ?>
            <a href="resources/?auth=true">
                <button class="btn btn-primary">Sign in to Contribute</button>
            </a>
        <?php endif; ?>
    </div>
</div>
<div class="row">
    <div class="col">
        <h4>Topics</h4>
        <table class="table available-topics">
            <tbody id="topics">
            <?php echo getTopicHtml($topics) ?>
            </tbody>
            <?php if ($userIsAdmin): ?>
                <tfoot>
                <form id="addTopicForm" onsubmit="return addTopic();">
                    <tr>
                        <td colspan="2">
                            <input required type="text" class="form-control" name="topic" maxlength="100"
                                   placeholder="Enter new topic"/>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <button type="submit" class="btn btn-primary" style="float: right;">Add Topic</button>
                        </td>
                    </tr>
                </form>
                </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>
<script>
    function addTopic() {

        let form = document.getElementById('addTopicForm');
        let fdata = new FormData(form);

        let body = {
            topic: fdata.get('topic')
        };
        api.post('/topics', body).then(data => {
            eInputTopic.value = '';
            snackbar(data.message, 'success');
            api.get('/topics?format=html').then(res => {
                $('topics').html(res.content.html);
                form.reset();
            }).catch(err => {
                window.location.reload(true);
            });
        }).catch(err => {
            snackbar(err.message, 'error');
        });
        return false;
    }
</script>

<?php include_once PUBLIC_FILES . '/components/footer.php'; ?>
