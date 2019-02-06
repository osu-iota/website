<?php
include_once BASE . '/lib/OSU/IOTA/Util/onidauth.php';
onidauth($db);
header('Location: ' . BASE_URL);
