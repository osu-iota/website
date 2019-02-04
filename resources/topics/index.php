<?php include_once BASE . '/include/templates/header.php';

$rtid = $_GET['t'];

// Get the resource topic (just in case their aren't any resources associated with it
$sql = 'SELECT rt_name FROM iota_resource_topic WHERE rtid = :id';
$prepared = $db->prepare($sql);
$prepared->bindParam(':id', $rtid, PDO::PARAM_STR);
$prepared->execute();
$prepared->setFetchMode(PDO::FETCH_ASSOC);
$topic = $prepared->fetchAll()[0]['rt_name'];

// Get all of the resources associated with the topic
$sql = 'SELECT rt.*, r.*, rd.rdid, rd.rd_extension ';
$sql .= 'FROM iota_resource_topic rt, iota_resource_for rf, iota_resource r, iota_resource_data rd ';
$sql .= 'WHERE rt.rtid = rf.rtid AND rf.rid = r.rid AND rd.rid = r.rid ';
$sql .= 'ORDER BY rd.rd_date DESC';
$prepared = $db->prepare($sql);
$prepared->execute();
$prepared->setFetchMode(PDO::FETCH_ASSOC);
$results = $prepared->fetchAll();

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
            <th>Name</th>
            <th>Description</th>
            <th>File Type</th>
            <th></th>
            </thead>
            <tbody>
            <?php
            echo '<tr>';
            foreach ($results as $result) {
                echo '<td>' . $result['r_name'] . '</td>';
                echo '<td>' . $result['r_description'] . '</td>';
                echo '<td>' . strtoupper($result['rd_extension']) . '</td>';
                echo '<td><a href="resources/topics/download.php?r=' . $result['rdid'] . '">Download</a></td>';
            }
            echo '</tr>';
            ?>
            </tbody>
        </table>
    </div>
</div>
<div class="row topic-results-mobile">
    <?php
    foreach ($results as $result) {
        echo '<div class="col">';
        echo '<h4>' . $result['r_name'] . '</h4>';
        echo '<p>' . $result['r_description'] . '</p>';
        echo '<a href="resources/topics/download.php?r=' . $result['rdid'] . '"><button class="btn btn-sm btn-secondary">Download</button></a>';
        echo '</div>';
    }
    ?>
</div>


<?php include_once BASE . '/include/templates/footer.php'; ?>
