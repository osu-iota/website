<?php

if(!$levels) {
    $levels = json_decode(file_get_contents(BASE . '/include/data/priv-levels.json'), true);
}

$users = array();
try {
    $sql = 'SELECT * FROM iota_user ORDER BY u_onid'; // TODO: implement LIMIT for large amount of users
    $users = $db->query($sql);
} catch (PDOException $e) {
    $logger->error($e->getMessage());
} ?>

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
<script>
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
</script>
