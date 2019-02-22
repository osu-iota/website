<?php
include_once 'authorize.php';
include_once BASE . '/include/templates/header.php';

// Retrieve the number of users of the system
$users = $daoUsers->getAllUsers();
$numUsers = count($users);

// Retrieve all the participation data
try {
    $sql = 'SELECT * FROM iota_participates p, iota_user u ';
    $sql .= 'WHERE p.uid = u.uid ';
    $sql .= 'ORDER BY u_onid';
    $prepared = $db->prepare($sql);
    $prepared->setFetchMode(PDO::FETCH_ASSOC);
    $prepared->execute();
    $results = $prepared->fetchAll();

} catch (PDOException $e) {
    $logger->error($e->getMessage());
}

?>

<div class="row">
    <div class="col">
        <table class="table">
            <thead>
            <th>ONID</th>
            <th>Event</th>
            <th>Club</th>
            <th>Type</th>
            <th>Description</th>
            <th></th>
            </thead>
            <tbody>
            <?php foreach ($results as $result): ?>
                <tr>
                    <td><?php echo $result['u_onid'] ?></td>
                    <td><?php echo $result['p_name'] ?></td>
                    <td><?php echo $result['p_club'] ?></td>
                    <td><?php echo $result['p_type'] ?></td>
                    <td><?php echo $result['p_description'] ?></td>
                    <td>
                        <?php if ($result['p_data']): ?>
                            <a href="report/data-download.php?pd=<?php echo $result['p_data'] ?>" target="_blank">View Selfie</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include_once BASE . '/include/templates/footer.php'; ?>
