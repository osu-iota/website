<?php
include_once BASE . '/lib/onidauth.php';
onidauth();
header('Location: ' . BASE_URL);
