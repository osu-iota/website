<?php
include_once PUBLIC_FILES . '/lib/onidauth.php';
onidauth();
header('Location: ' . BASE_URL);
