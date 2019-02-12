<?php
session_start();

$sql = 'SELECT * FROM iota_resource_topic ORDER BY rt_name';
try {
    $res = $db->query($sql);
} catch (PDOException $e) {
    $logger->error($e->getMessage());
    http_send_status(500);
    die();
}

foreach ($res as $topic) {

    $rtid = $topic['rtid'];
    $name = $topic['rt_name'];

    echo '<tr id="' . $rtid . '"><td><a href="resources/topics/?t=' . $rtid . '">' . $name . '</a></td>';

    echo '<td>';

    if ($userIsAdmin) {
        echo '<button class="btn delete" onmouseup="onDeleteTopic(\'' . $rtid . '\',\'' . $name . '\')"><i class="far fa-trash-alt"></i></button>';
        echo '<button class="btn edit" onclick="onEditTopic(\'' . $rtid . '\',\'' . $name . '\')"><i class="far fa-edit"></i></button>';
        echo '<button class="btn cancel" style="display: none;" onclick="onCancelEditTopic(\'' . $rtid . '\')"><i class="fas fa-times"></i></button>';
        echo '<button class="btn save" style="display: none;" onclick="onSaveTopic(\'' . $rtid . '\')"><i class="far fa-save"></i></button>';
    }

    echo '</td>';
    echo '</tr>';
}

if ($userIsAdmin) : ?>
    <script>
        function onDeleteTopic(id, topic) {
            let sure = confirm('Are you sure you want to delete the topic "' + topic + '"?');
            if(sure) {
                $.post('resources/ajax/deletetopic.php', {
                    id: id
                }).done(() => $('#topics').load('resources/ajax/loadtopics.php'))
                    .fail(() => alert('Failed to delete topic'));
            }
        }

        function onEditTopic(id, topic) {
            let $row = $(`tr[id=${id}]`);
            $row.find('a').hide();
            showEditButtons($row, true);
            let input = document.createElement('input');
            input.classList.add('form-control');
            input.value = topic;
            input.id = 'editTopic';
            $row.find('td:first-child').append(input);
        }

        function onSaveTopic(id, newTopic) {
            $.post('resources/ajax/updatetopic.php', {
                id: id,
                name: $('#editTopic').val(),
            }).done(() => $('#topics').load('resources/ajax/loadtopics.php'))
                .fail(() => alert('Failed to update topic'));
        }

        function onCancelEditTopic(id) {
            let $row = $(`tr[id=${id}]`);
            $row.find('input').remove();
            $row.find('a').show();
            showEditButtons($row, false);
        }

        function showEditButtons($row, show) {
            if (!show) {
                $row.find('.delete').show();
                $row.find('.edit').show();
                $row.find('.cancel').hide();
                $row.find('.save').hide();
            } else {
                $row.find('.delete').hide();
                $row.find('.edit').hide();
                $row.find('.cancel').show();
                $row.find('.save').show();
            }
        }
    </script>
<?php endif; ?>
