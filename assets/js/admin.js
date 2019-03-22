let current = null;

function onDeleteTopic(id, topic) {
    let sure = confirm('Are you sure you want to delete the topic "' + topic + '"? Doing so will ' +
        'remove all resource associations to the topic. This may make searching for the topic difficult.');
    if (sure) {
        let data = {
            id
        };
        api.post('resources/ajax/deletetopic.php', data).then(res => {
            snackbar('Successfully removed topic "' + topic + '"', 'success');
            api.load('resources/ajax/loadtopics.php', 'topics');
        }).catch(err => {
            snackbar(err.message, 'error');
        });
    }
}

function onEditTopic(id, topic) {
    let $row = $(`tr[id=${id}]`);
    $row.find('a').hide();
    showEditButtons($row, true);
    if (current != null) {
        onCancelEditTopic(current);
    }
    let input = document.createElement('input');
    input.classList.add('form-control');
    input.value = topic;
    input.id = 'editTopic';
    $row.find('td:first-child').append(input);
    current = id;
}

function onSaveTopic(id) {
    let newTopic = $('#editTopic').val();
    let data = {
        id,
        name: newTopic
    };
    api.post('resources/ajax/updatetopic.php', data).then(res => {
        snackbar(res.message, 'success');
        api.load('resources/ajax/loadtopics.php', 'topics');
    }).catch(err => {
        snackbar(err.message, 'error');
    });
}

function onCancelEditTopic(id) {
    let $row = $(`tr[id=${id}]`);
    $row.find('input').remove();
    $row.find('a').show();
    showEditButtons($row, false);
    current = null;
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

function onPrivilegeLevelEdit(el) {
    $.post('admin/users/update.php', {
        id: el.id,
        level: el.value
    }).done(() => {
        snackbar("Successfully updated the user's privilege level", 'success');
    }).fail(() => {
        snackbar("Failed to update the user's privilege level", 'error');
    });
}