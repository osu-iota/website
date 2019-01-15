<?php include_once '.meta.php' ?>
<?php
$menu = array(
    'Home' => '',
    'Calendar' => 'calendar',
    'Skill Tree' => 'skill-trees',
    'Office Hours' => 'office-hours',
    'Charter' => 'charter',
    'Contact Us' => 'contact',
    'Participation Form' => 'participate');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>OSU IOTA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <base href="<?php echo BASE_URL; ?>"/>

    <!-- Include Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css"
          integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
            integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
            integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
            integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
            crossorigin="anonymous"></script>

    <link rel="stylesheet" href="include/css/header.css"/>
    <link rel="stylesheet" href="include/css/main.css"/>
</head>
<body>
<header>
    <div class="top">
        <div class="logo">
            <img src="include/img/osu-logo.svg"/>
        </div>
        <div class="divider"></div>
        <div class="name">
            <span class="title">Internet-of-Things Alliance</span>
            <i class="college">College of Engineering</i>

        </div>
        <div class="name-mobile">
            <span class="title">IOTA</span>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <?php
                foreach ($menu as $title => $link) {
                    $active = (strpos($_SERVER['REQUEST_URI'], $link) !== false) ? 'active' : '';
                    echo '<li class="nav-item ' . $active . '"><a class="nav-link" href="' . $link . '">' . $title . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </nav>
</header>
<main class="container">


