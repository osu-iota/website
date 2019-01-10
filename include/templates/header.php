<?php include_once '.meta.php' ?>
<?php 
    $menu = array(
        'Home' => '', 
        'Calendar' => 'calendar',
        'Skill Tree' => 'skill-tree',
        'Office Hours' => 'office-hours',
        'Charter' => 'charter',
        'Contact Us' => 'contact',
        'Participation Form' => 'participate');
    $baseHref = 'http://web.engr.oregonstate.edu/~hitchcob/iota/website/';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>OSU IOTA</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <base href="<?php echo $baseHref; ?>"/>

    <!-- Include Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" 
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" 
        crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" 
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" 
        crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" 
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" 
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" 
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" 
        crossorigin="anonymous"></script>

    <link rel="stylesheet" href="include/css/header.css"/>
    <link rel="stylesheet" href="include/css/main.css"/>
    
</head>
<body>
    <div class="header">
        <div class="top">
            <div class="logo">
                <img src="include/img/osu-logo.svg"/>
            </div>
            <div class="divider"></div>
            <div class="name">
                <span class="title">Internet of Things Alliance</span>
                <i class="college">College of Engineering</i>
                
            </div>
        </div>
        <div class="menu">
            <nav class="nav nav-pills">
                <?php
                    foreach($menu as $title => $link) {
                        $active = (strpos($_SERVER['REQUEST_URI'], $link) !== false) ? 'active' : '';
                        echo '<li class="nav-item"><a class="nav-link ' . $active . '" href="' . $link . '">' . $title . '</a></li>'; 
                    }
                ?>
            </nav>
        </div>
        
        
    </div>
    <div class="container">

