<?php
// includes/nav.php — expects $currentPage ('home'|'store'|'history')
// and optionally $breadcrumb (defaults to the page name).
$breadcrumb = $breadcrumb ?? $currentPage;
?>
<div class="term-bar">
    <div class="term-dots">
        <span class="term-dot r"></span>
        <span class="term-dot a"></span>
        <span class="term-dot g"></span>
    </div>
    <div class="term-path">
        srtxcheats<span class="sep">~</span><span class="cur">/<?= htmlspecialchars($breadcrumb) ?></span>
    </div>
</div>
<?php if ($currentPage !== 'home'): ?>
<nav class="term-nav">
    <a href="/store.php" class="<?= $currentPage === 'store' ? 'active' : '' ?>">store</a>
    <a href="/history.php" class="<?= $currentPage === 'history' ? 'active' : '' ?>">history</a>
    <a href="#" onclick="doLogout(); return false;">logout</a>
</nav>
<?php endif; ?>
