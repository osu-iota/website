<?php
include_once 'authorize.php';
$menuOptions = array(
    'Dashboard' => array(
            'link' => '',
        'icon' => '<i class="fas fa-tachometer-alt"></i>'
    ),
    'Users' => array(
        'link' => 'users/',
        'icon' => '<i class="fas fa-users"></i>'
    )
)
?>

<div class="admin-menu">
    <?php foreach ($menuOptions as $name => $option): ?>
        <div class="item <?php if(urlContains($option['link'])) echo 'active'?>">
            <a href="admin/<?php echo $option['link'] ?>">
                <div class="content">
                    <?php echo $option['icon'] ?>
                    <span class="name"><?php echo $name ?></span>
                </div>
            </a>
        </div>
    <?php endforeach; ?>
</div>