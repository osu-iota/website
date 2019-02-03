<?php
include_once '.meta.php';
include_once BASE . '/include/templates/header.php';

session_start();

// Get all the topics
$sql = 'SELECT * FROM iota_resource_topic ORDER BY rt_name';
try {
    $res = $db->query($sql);
} catch (PDOException $e) {
    $logger->error($e->getMessage());
}

?>
<div class="row">
    <div class="col">
        <h1>Resource Repository</h1>
    </div>
</div>
<div class="row">
    <div class="col">
        <h4>Topics</h4>
        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <!---<th>Contributors</th>--->
            </tr>
            </thead>
            <tbody id="topics">
            <?php
            foreach ($res as $topic) {
                echo '<tr>';
                echo '<td>' . $topic['rt_name'] . '</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
            <?php if ($_SESSION['privilegeLevel'] > 1): ?>
                <tfoot>
                <form method="post" id="addTopicForm" onsubmit="return addTopic();">
                    <tr>
                        <td>
                            <input required type="text" class="form-control" name="topic" maxlength="20"
                                   placeholder="Enter new topic"/>
                        </td>
                    </tr>
                    <tr>
                        <td>
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
        $.post('resources/addtopic.php', {
                topic: $input.val()
            },
            (data, status) => {
                $input.val('');
                $('#topics').load('resources/loadtopics.php');
            });
        return false;
    }
</script>

<?php include_once BASE . '/include/templates/footer.php'; ?>
