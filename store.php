<?php
$pageTitle = 'Store — SRT X CHEATS';
$currentPage = 'store';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/nav.php';
?>

<div class="term-window">
    <div class="term-content">

        <div class="panel" style="display:flex;justify-content:space-between;align-items:center">
            <div>
                <div class="dim" style="font-size:11px">balance</div>
                <div style="color:var(--amber);font-weight:800;font-size:20px" class="mono-num">Rs <span id="balAmount">—</span></div>
            </div>
            <div style="text-align:right">
                <div class="dim" style="font-size:11px">status</div>
                <div id="statusVal" style="font-weight:700;font-size:13px">—</div>
            </div>
        </div>

        <div class="panel" id="noticePanel" style="border-color:var(--border-strong)">
            <div class="dim" style="font-size:11px;margin-bottom:4px">### admin-notice.txt</div>
            <div id="noticeText" style="font-size:12px;color:var(--text2)">loading...</div>
        </div>

        <div style="display:flex;gap:8px;margin-bottom:16px">
            <button class="btn btn-ghost" id="openTopup" style="font-size:12px">./topup.sh</button>
            <button class="btn btn-ghost" id="openProfile" style="font-size:12px">./profile.sh</button>
            <button class="btn btn-ghost" id="openKeys" style="font-size:12px">./keys.sh</button>
        </div>

        <div class="prompt-header">ls -la /catalog</div>
        <div class="dim" style="font-size:10px;margin-bottom:8px;padding:0 2px">
            <span style="display:inline-block;width:52%">NAME</span><span style="display:inline-block;width:20%">TAG</span><span>SIZE</span>
        </div>
        <div id="catalogList"><div class="dim" style="text-align:center;padding:20px">loading catalog...</div></div>

    </div>
</div>

<!-- ---- Checkout confirm modal ---- -->
<div id="checkoutModal" class="modal-overlay hidden">
    <div class="panel" style="max-width:400px;margin:auto">
        <div class="prompt-header">confirm --purchase</div>
        <div id="checkoutSummary" style="font-size:13px;margin-bottom:12px"></div>
        <div class="field"><label>your name</label><input type="text" id="payName" placeholder="For delivery contact"></div>
        <div class="field"><label>whatsapp number</label><input type="text" id="payWA" placeholder="98xxxxxxxx"></div>
        <button class="btn btn-solid" id="confirmBuyBtn" style="margin-bottom:8px">confirm.sh</button>
        <button class="btn btn-ghost" onclick="closeModal('checkoutModal')">cancel</button>
    </div>
</div>

<!-- ---- Key delivered modal ---- -->
<div id="keyModal" class="modal-overlay hidden">
    <div class="panel" style="max-width:400px;margin:auto">
        <div class="prompt-header">cat delivered_key.txt</div>
        <div id="keyProductName" style="font-size:13px;margin-bottom:6px"></div>
        <div style="background:#040a06;border:1px solid var(--border-strong);border-radius:var(--radius-sm);padding:12px;word-break:break-all;color:var(--green);font-weight:700;margin-bottom:12px" id="keyValue"></div>
        <button class="btn btn-solid" onclick="closeModal('keyModal')">done</button>
    </div>
</div>

<!-- ---- Top-up modal ---- -->
<div id="topupModal" class="modal-overlay hidden">
    <div class="panel" style="max-width:400px;margin:auto">
        <div class="prompt-header">topup --esewa</div>
        <div class="dim" style="font-size:12px;margin-bottom:12px">Pay via eSewa, then submit your transaction ID. Admin verifies and credits shortly.</div>
        <div class="field"><label>amount (Rs)</label><input type="number" id="topupAmount" value="100" min="50"></div>
        <div class="field"><label>your eSewa ID</label><input type="text" id="topupEsewa" placeholder="phone or email"></div>
        <div class="field"><label>transaction code</label><input type="text" id="topupTx" placeholder="e.g. JRJDHD"></div>
        <button class="btn btn-solid" id="submitTopup" style="margin-bottom:8px">submit.sh</button>
        <button class="btn btn-ghost" onclick="closeModal('topupModal')">cancel</button>
    </div>
</div>

<!-- ---- Profile modal ---- -->
<div id="profileModal" class="modal-overlay hidden">
    <div class="panel" style="max-width:400px;margin:auto">
        <div class="prompt-header">profile --edit</div>
        <div class="field"><label>display name</label><input type="text" id="profName"></div>
        <div class="field"><label>whatsapp number</label><input type="text" id="profPhone"></div>
        <button class="btn btn-solid" id="saveProfile" style="margin-bottom:8px">save.sh</button>
        <button class="btn btn-ghost" onclick="closeModal('profileModal')">close</button>
    </div>
</div>

<!-- ---- API Keys modal ---- -->
<div id="keysModal" class="modal-overlay hidden">
    <div class="panel" style="max-width:400px;margin:auto">
        <div class="prompt-header">./api-keys --list</div>
        <div id="keysList" style="margin-bottom:12px"></div>
        <button class="btn btn-solid" id="genKey" style="margin-bottom:8px">generate.sh</button>
        <button class="btn btn-ghost" onclick="closeModal('keysModal')">close</button>
    </div>
</div>

<style>
.modal-overlay {
    position: fixed; inset: 0; z-index: 100;
    background: rgba(2,6,4,0.85);
    display: flex; align-items: center; justify-content: center;
    padding: 20px;
}
.cat-row {
    background: var(--panel); border: 1px solid var(--border); border-radius: var(--radius-sm);
    margin-bottom: 6px; overflow: hidden;
}
.cat-head {
    display: flex; align-items: center; gap: 8px; padding: 11px 12px; cursor: pointer;
}
.cat-head .name { flex: 1; font-size: 12.5px; font-weight: 600; }
.cat-head .tag { font-size: 9px; padding: 2px 6px; border-radius: 4px; border: 1px solid var(--border-strong); color: var(--text2); }
.cat-head .arrow { font-size: 10px; color: var(--text3); transition: transform .2s; }
.cat-row.open .arrow { transform: rotate(90deg); }
.cat-body { display: none; border-top: 1px solid var(--border); }
.cat-row.open .cat-body { display: block; }
.dur-row {
    display: flex; justify-content: space-between; align-items: center;
    padding: 10px 12px; font-size: 12px; border-top: 1px solid var(--border);
    cursor: pointer;
}
.dur-row:active { background: var(--panel2); }
.dur-row .price { color: var(--amber); font-weight: 700; }
</style>

<script type="module">
import { requireAuth, backendFetch, toast, fmtDate, esc } from '/assets/js/app.js';

let userState = {};
let catalog = {};
let pendingCheckout = null;

window.closeModal = (id) => document.getElementById(id).classList.add('hidden');
window.openModal = (id) => document.getElementById(id).classList.remove('hidden');

requireAuth(async (user) => {
    await Promise.all([loadBalance(), loadCatalog()]);
});

async function loadBalance() {
    try {
        const d = await backendFetch('/api/user/balance');
        userState = d;
        document.getElementById('balAmount').textContent = d.balance;
        document.getElementById('statusVal').textContent = d.requestStatus;
        document.getElementById('statusVal').style.color =
            d.requestStatus === 'Active' ? 'var(--green)' :
            d.requestStatus === 'Pending' ? 'var(--amber)' : 'var(--red)';
        document.getElementById('noticeText').textContent = d.adminMessage || 'No messages.';
        document.getElementById('profName').value = d.profileName || '';
        document.getElementById('profPhone').value = d.profilePhone || '';
        document.getElementById('payName').value = d.profileName || '';
        document.getElementById('payWA').value = d.profilePhone || '';
    } catch (e) {
        toast(e.message, 'error');
    }
}

async function loadCatalog() {
    try {
        const r = await fetch(`${window.BACKEND_URL}/api/catalog`);
        const d = await r.json();
        catalog = d.catalog;
        renderCatalog();
    } catch (e) {
        document.getElementById('catalogList').innerHTML = '<div style="color:var(--red);font-size:12px">Failed to load catalog</div>';
    }
}

function renderCatalog() {
    // Group by `row`
    const groups = {};
    for (const [sku, p] of Object.entries(catalog)) {
        if (!groups[p.row]) groups[p.row] = [];
        groups[p.row].push({ sku, ...p });
    }
    const tagOf = (row) => /root/i.test(row) && !/non ?root/i.test(row) ? 'ROOT'
        : /ios/i.test(row) ? 'IOS'
        : /pc/i.test(row) ? 'PC'
        : 'NONROOT';

    const html = Object.entries(groups).map(([row, items], gi) => `
        <div class="cat-row" id="cat-${gi}">
            <div class="cat-head" onclick="document.getElementById('cat-${gi}').classList.toggle('open')">
                <span class="name">${esc(row)}</span>
                <span class="tag">${tagOf(row)}</span>
                <span class="arrow">▸</span>
            </div>
            <div class="cat-body">
                ${items.map(it => `
                    <div class="dur-row" onclick="window.__startCheckout('${it.sku}')">
                        <span>${esc(it.name)} <span class="dim">· ${esc(it.duration)}</span></span>
                        <span class="price">Rs ${it.price}</span>
                    </div>
                `).join('')}
            </div>
        </div>
    `).join('');
    document.getElementById('catalogList').innerHTML = html;
}

window.__startCheckout = (sku) => {
    const p = catalog[sku];
    if (!p) return;
    pendingCheckout = { sku, ...p };
    document.getElementById('checkoutSummary').innerHTML = `
        <div style="display:flex;justify-content:space-between;margin-bottom:4px"><span class="dim">product</span><span>${esc(p.name)}</span></div>
        <div style="display:flex;justify-content:space-between;margin-bottom:4px"><span class="dim">duration</span><span>${esc(p.duration)}</span></div>
        <div style="display:flex;justify-content:space-between"><span class="dim">price</span><span class="price" style="color:var(--amber);font-weight:700">Rs ${p.price}</span></div>
    `;
    openModal('checkoutModal');
};

document.getElementById('confirmBuyBtn').onclick = async () => {
    if (!pendingCheckout) return;
    const name = document.getElementById('payName').value.trim();
    const waNum = document.getElementById('payWA').value.trim();
    const btn = document.getElementById('confirmBuyBtn');
    btn.disabled = true;
    try {
        const d = await backendFetch('/api/purchase/checkout', {
            method: 'POST',
            body: JSON.stringify({ sku: pendingCheckout.sku, name, waNum }),
        });
        closeModal('checkoutModal');
        document.getElementById('keyProductName').textContent = pendingCheckout.name;
        document.getElementById('keyValue').textContent = d.key;
        openModal('keyModal');
        document.getElementById('balAmount').textContent = d.newBalance;
    } catch (e) {
        toast(e.message, 'error');
    }
    btn.disabled = false;
};

// ---- Top-up ----
document.getElementById('openTopup').onclick = () => openModal('topupModal');
document.getElementById('submitTopup').onclick = async () => {
    const amount = parseInt(document.getElementById('topupAmount').value, 10);
    const esewaId = document.getElementById('topupEsewa').value.trim();
    const txCode = document.getElementById('topupTx').value.trim();
    try {
        await backendFetch('/api/user/topup', { method: 'POST', body: JSON.stringify({ amount, esewaId, txCode }) });
        toast('Submitted — awaiting admin approval', 'success');
        closeModal('topupModal');
    } catch (e) {
        toast(e.message, 'error');
    }
};

// ---- Profile ----
document.getElementById('openProfile').onclick = () => openModal('profileModal');
document.getElementById('saveProfile').onclick = async () => {
    const name = document.getElementById('profName').value.trim();
    const phone = document.getElementById('profPhone').value.trim();
    try {
        await backendFetch('/api/user/profile', { method: 'POST', body: JSON.stringify({ name, phone }) });
        toast('Saved', 'success');
        closeModal('profileModal');
    } catch (e) {
        toast(e.message, 'error');
    }
};

// ---- API Keys ----
document.getElementById('openKeys').onclick = async () => {
    openModal('keysModal');
    await refreshKeys();
};
async function refreshKeys() {
    try {
        const d = await backendFetch('/api/user/keys');
        renderKeys(d.apiKeys || []);
    } catch (e) {
        toast(e.message, 'error');
    }
}
function renderKeys(keys) {
    document.getElementById('keysList').innerHTML = keys.length ? keys.map(k => `
        <div style="border:1px solid var(--border);border-radius:var(--radius-sm);padding:8px 10px;margin-bottom:6px;font-size:11px">
            <div style="word-break:break-all;color:${k.active ? 'var(--green)' : 'var(--text3)'}">${esc(k.key)}</div>
            <div class="dim" style="margin-top:2px">${k.active ? 'active' : 'revoked'} · ${fmtDate(k.createdAt)}</div>
            ${k.active ? `<button class="btn btn-danger" style="margin-top:6px;padding:6px" onclick="window.__revokeKey('${k.key}')">revoke</button>` : ''}
        </div>
    `).join('') : '<div class="dim" style="font-size:12px">No keys yet</div>';
}
window.__revokeKey = async (key) => {
    try {
        await backendFetch('/api/user/keys', { method: 'POST', body: JSON.stringify({ action: 'revoke', key }) });
        await refreshKeys();
    } catch (e) {
        toast(e.message, 'error');
    }
};
document.getElementById('genKey').onclick = async () => {
    try {
        await backendFetch('/api/user/keys', { method: 'POST', body: JSON.stringify({ action: 'generate' }) });
        await refreshKeys();
        toast('Key generated', 'success');
    } catch (e) {
        toast(e.message, 'error');
    }
};
</script>

</body>
</html>
