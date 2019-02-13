<?php
include_once BASE . '/include/templates/header.php';
include_once '../menu.php';

$levels = json_decode(file_get_contents(BASE . '/include/data/priv-levels.json'), true);

$users = array();
try {
    $sql = 'SELECT * FROM iota_user ORDER BY u_onid'; // TODO: implement LIMIT for large amount of users
    $users = $db->query($sql);
} catch (PDOException $e) {
    $logger->error($e->getMessage());
}

?>
<div class="admin-main">
    <div class="add-user">
        <form onsubmit="return onAddUser()">
            <input required type="text" class="form-control" id="newUserOnid" placeholder="Enter ONID for new user" />
            <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i>&nbsp;User</button>
        </form>
    </div>
    <table class="table">
        <thead>
        <th>ONID</th>
        <th>Privilege Level</th>
        <th>Last Login</th>
        <th></th>
        </thead>
        <tbody>
        <?php foreach ($users as $user): ?>
            <tr class="user">
                <td><?php echo $user['u_onid'] ?></td>
                <td>
                    <select id="<?php echo $user['uid'] ?>" class="form-control user-priv-select"
                            onChange="onPrivilegeLevelEdit(this)">
                        <?php foreach ($levels as $index => $level): ?>
                            <option value="<?php echo $index ?>" <?php if ($index == $user['u_privilege_level']) echo 'selected' ?>>
                                <?php echo $level ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
                <td><?php echo date("F j, Y, g:i a", $user['u_last_login']) ?></td>
                <td></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<script>
    function onPrivilegeLevelEdit(el) {
        $.post('admin/users/update.php', {
            id: el.id,
            level: el.value
        }).done(() => {
            snackbar("Successfully updated the user's privilege level");
        }).fail(() => {
            snackbar("Failed to update the user's privilege level");
        });
    }
</script>
<?php include_once BASE . '/include/templates/footer.php'; ?>
