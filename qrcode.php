<?php

include __DIR__ . '/lib/phpqrcode.php';
ob_clean();
QRcode::png($_GET['data'] ?? '');
