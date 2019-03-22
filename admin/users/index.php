<?php
include_once PUBLIC_FILES . '/components/header.php';
include_once '../menu.php';
include_once PUBLIC_FILES . '/lib/users-html.php';

$levels = $config['enums']['privilegeLevels'];

// Get all the users
$users = array();
try {
    $sql = 'SELECT * FROM iota_user ORDER BY u_onid'; // TODO: implement LIMIT for large amount of users
    $users = $db->query($sql);
} catch (PDOException $e) {
    $logger->error($e->getMessage());
}

?>
<div class="admin-main">
    <div class="page">
        <div class="add-user">
            <form id="newUserForm" onsubmit="return onAddNewUser()">
                <input required type="text" class="form-control" name="onid"
                       placeholder="Enter ONID for new user"/>
                <select required name="level" class="form-control user-priv-select">
                    <option value="">Select Level</option>
                    <?php foreach ($levels as $index => $level): ?>
                        <option value="<?php echo $index ?>">
                            <?php echo $level ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-primary" style="margin-left: 20px"><i class="fas fa-plus"></i>&nbsp;User
                </button>
            </form>
        </div>
        <div class="container-email-users">
            <a href="admin/users/email">
                <button class="btn btn-primary" type="button">Email Users</button>
            </a>
        </div>

        <table class="table">
            <thead>
            <th>ONID</th>
            <th>Privilege Level</th>
            <th>Last Login</th>
            <th></th>
            </thead>
            <tbody id="users">
            <?php echo getUsersHtml($users) ?>
            </tbody>
        </table>
    </div>

</div>
<script>
    function onAddNewUser() {

        let form = document.getElementById('newUserForm');
        let fdata = new FormData(form);

        let body = {
            onid: fdata.get('onid'),
            privLevel: fdata.get('level')
        };

        api.post('/users', body).then(res => {
            form.reset();
            snackbar(res.message, 'success');
        }).catch(err => {
            snackbar(err.message, 'error');
        });

        return false;
    }
</script>
<?php include_once PUBLIC_FILES . '/components/footer.php'; ?>
