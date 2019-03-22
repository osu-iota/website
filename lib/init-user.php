<?php
// Load the user if it isn't already loaded
$userIsLoggedIn = !empty($_SESSION['onid']);
$userIsAdmin = false;
$userIsManager = false;
$userIsContributor = false;
/** @var \Model\User|null $user */
$user = null;
if ($userIsLoggedIn) {
    $user = $daoUsers->getUserWithOnid($_SESSION['onid']);
    // Initialize some variables to easily track permissions
    $userIsAdmin = $user->getPrivilegeLevel() > 2;
    $userIsManager = $user->getPrivilegeLevel() > 1;
    $userIsContributor = $user->getPrivilegeLevel() > 0;
}