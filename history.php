<?php
$pageTitle = 'History — SRT X CHEATS';
$currentPage = 'history';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/nav.php';
?>

<div class="term-window">
    <div class="term-content">

        <div class="prompt-header">tail -f /var/log/purchases.log</div>
        <div id="historyList"><div class="dim" style="text-align:center;padding:20px">loading...</div></div>

        <button class="btn btn-danger" id="clearBtn" style="margin-top:16px">clear history</button>

    </div>
</div>

<style>
.log-entry {
    background: var(--panel); border: 1px solid var(--border); border-radius: var(--radius-sm);
    padding: 12px; margin-bottom: 8px; font-size: 12px;
}
.log-entry .top { display: flex; justify-content: space-between; margin-bottom: 4px; }
.log-entry .name { font-weight: 700; }
.log-entry .price { color: var(--amber); font-weight: 700; }
.log-entry .meta { color: var(--text3); font-size: 11px; margin-bottom: 6px; }
.log-entry .key {
    background: #040a06; border: 1px solid var(--border); border-radius: 4px;
    padding: 6px 8px; word-break: break-all; color: var(--green); font-size: 11px;
}
</style>

<script type="module">
import { requireAuth, backendFetch, toast, fmtDate, esc } from '/assets/js/app.js';

requireAuth(async () => {
    await loadHistory();
});

async function loadHistory() {
    try {
        const d = await backendFetch('/api/user/history');
        renderHistory(d.history || []);
    } catch (e) {
        document.getElementById('historyList').innerHTML = `<div style="color:var(--red);font-size:12px">Couldn't load: ${esc(e.message)}</div>`;
    }
}

function renderHistory(items) {
    const el = document.getElementById('historyList');
    if (!items.length) {
        el.innerHTML = '<div class="dim" style="text-align:center;padding:20px">-- no purchases yet --</div>';
        return;
    }
    el.innerHTML = items.map(it => `
        <div class="log-entry">
            <div class="top"><span class="name">${esc(it.name || '—')}</span><span class="price">Rs ${it.price ?? '—'}</span></div>
            <div class="meta">${esc(it.duration || '')} · ${fmtDate(it.at)}</div>
            ${it.key ? `<div class="key">${esc(it.key)}</div>` : ''}
        </div>
    `).join('');
}

document.getElementById('clearBtn').onclick = async () => {
    if (!confirm('Clear your entire purchase history? This cannot be undone.')) return;
    try {
        await backendFetch('/api/user/history-clear', { method: 'POST' });
        toast('History cleared', 'success');
        await loadHistory();
    } catch (e) {
        toast(e.message, 'error');
    }
};
</script>

</body>
</html>
