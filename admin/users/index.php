<?php
include_once BASE . '/include/templates/header.php';
include_once '../menu.php';

$levels = json_decode(file_get_contents(BASE . '/include/data/priv-levels.json'), true);

?>
<div class="admin-main">
    <div class="page">
        <div class="add-user">
            <form onsubmit="return onAddNewUser()">
                <input required type="text" class="form-control" id="newUserOnid"
                       placeholder="Enter ONID for new user"/>
                <select required id="newUserLevel" class="form-control user-priv-select">
                    <option value="">Select Level</option>
                    <?php foreach ($levels as $index => $level): ?>
                        <option value="<?php echo $index ?>">
                            <?php echo $level ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary" style="margin-left: 20px"><i class="fas fa-plus"></i>&nbsp;User</button>
            </form>
        </div>
        <table class="table">
            <thead>
            <th>ONID</th>
            <th>Privilege Level</th>
            <th>Last Login</th>
            <th></th>
            </thead>
            <tbody id="users">
            <?php include_once 'loadusers.php' ?>
            </tbody>
        </table>
    </div>

</div>
<script>
    function onAddNewUser() {
        var onid = document.getElementById('newUserOnid');
        var level = document.getElementById('newUserLevel');

        $.post('admin/users/addnew.php', {
            onid: onid.value,
            privLevel: level.value
        }).done(() => {
            snackbar("Successfully created new user", 'success');
            onid.value = '';
            level.value = '';
            $('#users').load('admin/users/loadusers.php');
        }).fail(() => {
            snackbar("Failed to create new user", 'error');
        });
        return false;
    }
</script>
<?php include_once BASE . '/include/templates/footer.php'; ?>
