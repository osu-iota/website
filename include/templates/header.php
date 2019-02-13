<?php
include_once BASE . '/lib/OSU/IOTA/Util/onidauth.php';
session_start();
if ($_GET['auth'] == true || $_REQUEST['ticket'] . '' != '') {
    onidauth($db);
}

$menu = array(
    'Home' => '',
    'Calendar' => 'calendar',
    'Charter' => 'charter',
    'Office Hours' => 'office-hours',
    'Skill Trees' => 'skill-trees',
    'Resources' => 'resources',
    'Participate' => 'participate',
    'Contact Us' => 'contact');

if ($userIsManager) {
    $menu['Reports'] = 'reports';
}
if ($userIsAdmin) {
    $menu['Admin'] = 'admin';
}
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
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
            integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"
            integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut"
            crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"
            integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k"
            crossorigin="anonymous"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css"
          integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

    <!-- jQuery UI (for autocomplete -- may remove later)
    <script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js"
            integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E="
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />-->

    <link rel="stylesheet" href="include/css/header.css"/>
    <link rel="stylesheet" href="include/css/main.css"/>
    <link rel="stylesheet" href="include/css/snackbar.css"/>
    <?php if($userIsAdmin): ?><link rel="stylesheet" href="include/css/admin.css"/><?php endif; ?>
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
            <ul class="navbar-nav">
                <?php
                foreach ($menu as $title => $link) {
                    $styleClasses = '';
                    if (!empty($link)) {
                        $styleClasses = urlContains($link) ? 'active' : '';
                        $styleClasses .= $link == 'reports' ? ' extra-links-start' : '';
                    }
                    echo '<li class="nav-item ' . $styleClasses . '"><a class="nav-link" href="' . $link . '">' . $title . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </nav>
</header>
<main class="container<?php if(urlContains('admin')) echo '-fluid'; ?>">


