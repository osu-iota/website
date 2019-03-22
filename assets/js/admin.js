let current = null;

function reloadTopics() {
    api.get('/topics?format=html').then(res => {
        document.getElementById('topics').innerHTML = res.content.html;
    });
}

function addTopic() {

    let form = document.getElementById('addTopicForm');
    let fdata = new FormData(form);

    let body = {
        topic: fdata.get('topic')
    };
    api.post('/topics', body).then(res => {
        snackbar(res.message, 'success');
        reloadTopics();
        form.reset();
    }).catch(err => {
        snackbar(err.message, 'error');
    });
    return false;
}

function onDeleteTopic(id, topic) {
    let sure = confirm('Are you sure you want to delete the topic "' + topic + '"? Doing so will ' +
        'remove all resource associations to the topic. This may make searching for the topic difficult.');
    if (sure) {
        api.delete('/topics?id=' + id).then(res => {
            snackbar('Successfully removed topic "' + topic + '"', 'success');
            reloadTopics();
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
        name: newTopic
    };
    api.patch('/topics?id=' + id, data).then(res => {
        snackbar(res.message, 'success');
        reloadTopics();
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
    let body = {
        level: el.value
    };
    api.patch('/users?id=' + el.id, body).then(res => {
        snackbar(res.message, 'success');
    }).catch(err => {
        snackbar(err.message, 'error');
    });
}