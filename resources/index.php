<?php
include_once BASE . '/include/templates/header.php';

session_start();
?>
<div class="row">
    <div class="col">
        <h1>Resource Repository</h1>
        <p>The IoT Alliance offers a repository of resources and materials on topics typically covered during events
            related to the alliance. All users can search and download content from the repository.</p>
        <?php if (!$userIsContributor): ?>
            <p>If you'd like to become a contributor of content to the repository, please
                <a href="contact/">contact us</a>. If you have already been granted rights to contribute materials,
                please <a href="resources/?auth=true">sign in</a>.</p>
        <?php endif; ?>
    </div>
</div>
<?php include_once BASE . '/include/templates/message.php' ?>
<?php if ($userIsContributor): ?>
    <div class="row">
        <div class="col contribute-resource">
            <a href="resources/contribute"><button class="btn btn-primary">Contribute Resource</button></a>
        </div>
    </div>
<?php endif; ?>
<div class="row">
    <div class="col">
        <h4>Topics</h4>
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th></th>
            </tr>
            </thead>
            <tbody id="topics">
            <?php include 'ajax/loadtopics.php' ?>
            </tbody>
            <?php if ($userIsAdmin): ?>
                <tfoot>
                <form method="post" id="addTopicForm" onsubmit="return addTopic();">
                    <tr>
                        <td colspan="2">
                            <input required type="text" class="form-control" name="topic" maxlength="20"
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
        var $input = $('input[name=topic]');
        console.log('hello');
        $.post('resources/ajax/addtopic.php', {
                topic: $input.val()
            },
            (data, status) => {
                $input.val('');
                $('#topics').load('resources/ajax/loadtopics.php');
            });
        return false;
    }
</script>

<?php include_once BASE . '/include/templates/footer.php'; ?>
