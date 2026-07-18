<?php
// includes/head.php — expects $pageTitle to be set before including.
require_once __DIR__ . '/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
<title><?= htmlspecialchars($pageTitle ?? 'SRT X CHEATS') ?></title>
<link rel="stylesheet" href="/assets/css/terminal.css">
<script>
    window.BACKEND_URL = "<?= BACKEND_URL ?>";
    window.FIREBASE_CONFIG = <?= FIREBASE_CONFIG_JSON ?>;
</script>
</head>
<body>
