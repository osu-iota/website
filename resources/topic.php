<?php include_once PUBLIC_FILES . '/components/header.php';

$rtid = $_GET['t'];

// Get the resource topic (just in case their aren't any resources associated with it
$sql = 'SELECT rt_name FROM iota_resource_topic WHERE rtid = :id';
$prepared = $db->prepare($sql);
$prepared->bindParam(':id', $rtid, PDO::PARAM_STR);
$prepared->execute();
$prepared->setFetchMode(PDO::FETCH_ASSOC);
$topic = $prepared->fetchAll()[0]['rt_name'];

// Get all of the resources associated with the topic
$sql = 'SELECT rt.*, r.*, rd.rdid, rd.rd_extension, rd.rd_downloads, u.uid ';
$sql .= 'FROM iota_resource_topic rt, iota_resource_for rf, iota_resource r, iota_resource_data rd, iota_user u, iota_contributes c ';
$sql .= 'WHERE rt.rtid = rf.rtid AND rf.rid = r.rid AND rd.rid = r.rid AND c.rdid = rd.rdid AND c.uid = u.uid AND rt.rtid = :id ';
$sql .= 'ORDER BY rd.rd_date DESC';
$prepared = $db->prepare($sql);
$prepared->bindParam(':id', $rtid, PDO::PARAM_STR);
$prepared->execute();
$prepared->setFetchMode(PDO::FETCH_ASSOC);
$results = $prepared->fetchAll();

$uid = $user ? $user->getId() : '';

?>
<div class="row">
    <div class="col">
        <h1>Resources for <?php echo $topic ?></h1>
    </div>
</div>
<div class="row">
    <div class="col">
        <table class="table topic-results">
            <thead>
            <th class="name">Name</th>
            <th class="description">Description</th>
            <th class="downloads">Downloads</th>
            <th class="filetype">File Type</th>
            <th class="actions"></th>
            </thead>
            <tbody>
            <?php foreach ($results as $result): ?>
                <tr>
                    <td class="name"><?php echo $result['r_name'] ?></td>
                    <td class="description"> <?php echo $result['r_description'] ?></td>
                    <td class="downloads"><?php echo $result['rd_downloads'] ?></td>
                    <td class="filetype"><?php echo strtoupper($result['rd_extension']) ?></td>
                    <td class="actions">
                        <div class="actions-wrapper">
                            <a href="downloaders/resource-data.php?r=<?php echo $result['rdid'] ?>">Download</a>
                            <?php if ($userIsAdmin || $uid == $result['uid']): ?>
                                <a href="resources/contribute/edit?r=<?php echo $result['rid'] ?>">
                                    <button class="btn" data-toggle="tooltip" data-placement="right" title="Edit">
                                        <i class="far fa-edit"></i>
                                    </button>
                                </a>
                            <?php endif; ?>
                        </div>

                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<div class="topic-results-mobile">
    <?php
    foreach ($results as $result) {
        echo '<div class="row justify-content-center">';
        echo '<div class="col">';
        echo '<h4>' . $result['r_name'] . '</h4>';
        echo '<p>' . $result['r_description'] . '</p>';
        echo '<a href="downloaders/resource-data.php?r=' . $result['rdid'] . '"><button class="btn btn-sm btn-secondary">Download</button></a>';
        echo '</div>';
        echo '</div>';
    }
    ?>
</div>


<?php include_once PUBLIC_FILES . '/components/footer.php'; ?>
