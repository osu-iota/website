<?php
include PUBLIC_FILES . '/components/header.php';
$members = array(
    array(
        'name' => 'Jacob Dawes',
        'room' => 'Dearborn 211',
        'hours' => array(
            'Monday 10am - 2pm',
            'Wednesday 12pm - 4pm',
            'Friday 12pm - 2pm'
        )
    ),
    array(
        'name' => 'Niraj Basnet',
        'room' => 'Johnson 123',
        'hours' => array(
            'Friday 3:00pm - 3:30pm'
        )
    )
);

?>

    <div class="row">
        <div class="col">
            <h1>Office Hours</h1>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <p>The IoT Alliance is pleased to offer the assistance of various members who have experties that all
                members
                can take advantage of. Office hours for these members will be posted here as they become available.</p>
        </div>
    </div>

<?php
foreach ($members as $member) {
    echo '<div class="row"><div class="col">';
    echo '<h4>' . $member['name'] . '</h4>';
    echo '<i>' . $member['room'] . '</i>';
    echo '<ul>';
    foreach ($member['hours'] as $hour) {
        echo '<li>' . $hour . '</li>';
    }
    echo '</ul>';
    echo '</div></div>';
}
?>

<?php include PUBLIC_FILES . '/components/footer.php'; ?>