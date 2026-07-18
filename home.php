<?php
$pageTitle = 'SRT X CHEATS';
$currentPage = 'home';
require __DIR__ . '/includes/head.php';
require __DIR__ . '/includes/nav.php';
?>

<div class="term-window">
    <div class="term-content">

        <div id="boot" style="padding-top:40px">
            <div style="color:var(--green);font-weight:800;font-size:20px;letter-spacing:1px;margin-bottom:2px">
                &gt; SRT<span style="color:var(--cyan)">X</span>CHEATS <span style="filter:saturate(1.4)">🇳🇵</span>
            </div>
            <div class="dim" style="font-size:12px;margin-bottom:18px">$ visitor@srtxcheats:~ connect --store</div>
            <div id="bootLines" style="display:flex;flex-direction:column;gap:2px;min-height:120px;font-size:13px"></div>
            <div style="margin-top:14px;height:3px;background:rgba(57,255,136,0.1);border-radius:99px;overflow:hidden">
                <div id="bootBar" style="height:100%;width:0;background:linear-gradient(90deg,var(--green-dim),var(--green));transition:width .3s ease"></div>
            </div>
        </div>

        <div id="authArea" class="hidden">
            <div class="prompt-header">auth --login</div>

            <div class="panel">
                <div id="loginForm">
                    <div class="field">
                        <label>email</label>
                        <input type="email" id="loginEmail" placeholder="you@example.com" autocomplete="username">
                    </div>
                    <div class="field">
                        <label>password</label>
                        <input type="password" id="loginPass" placeholder="••••••••" autocomplete="current-password">
                    </div>
                    <button class="btn btn-solid" id="loginBtn" style="margin-bottom:8px">login.sh</button>
                    <button class="btn btn-ghost" id="googleBtn" style="margin-bottom:8px">auth --provider=google</button>
                    <div style="display:flex;justify-content:space-between;font-size:11px;margin-top:6px">
                        <a href="#" id="forgotLink">forgot-password?</a>
                        <a href="#" id="showSignup">./signup.sh</a>
                    </div>
                </div>

                <div id="signupForm" class="hidden">
                    <div class="field">
                        <label>email</label>
                        <input type="email" id="regEmail" placeholder="you@example.com" autocomplete="username">
                    </div>
                    <div class="field">
                        <label>password (min 6 chars)</label>
                        <input type="password" id="regPass" placeholder="••••••••" autocomplete="new-password">
                    </div>
                    <button class="btn btn-solid" id="signupBtn" style="margin-bottom:8px">signup.sh</button>
                    <div style="text-align:center;font-size:11px">
                        <a href="#" id="showLogin">./login.sh</a>
                    </div>
                </div>
            </div>

            <div class="dim" style="font-size:11px;text-align:center;margin-top:10px">
                SRT X CHEATS © 2026 · solo-dev build · Nepal 🇳🇵
            </div>
        </div>

    </div>
</div>

<script type="module">
import {
    auth, onAuthStateChanged, signInWithEmailAndPassword, createUserWithEmailAndPassword,
    signInWithPopup, googleProvider, sendPasswordResetEmail, backendFetch, toast,
} from '/assets/js/app.js';

// ---- Boot sequence (plain, non-module timing is fine here since
// this page owns it) ----
const lines = [
    'booting srtxcheats::store v5.0 ...',
    '[ok] loading catalog',
    '[ok] connecting to backend',
    '[ok] securing session channel',
    '[ok] permissions: visitor (read-only)',
];
const bootLines = document.getElementById('bootLines');
lines.forEach((text, i) => {
    const el = document.createElement('div');
    el.style.opacity = '0';
    el.style.animation = `bootIn .25s ease forwards`;
    el.style.animationDelay = (i * 0.15) + 's';
    if (text.startsWith('[ok]')) {
        el.innerHTML = `<span style="color:var(--green);font-weight:700">[ok]</span>${text.slice(4)}`;
    } else {
        el.textContent = text;
        el.className = 'dim';
    }
    bootLines.appendChild(el);
});
const style = document.createElement('style');
style.textContent = '@keyframes bootIn { to { opacity: 1; } }';
document.head.appendChild(style);
requestAnimationFrame(() => { document.getElementById('bootBar').style.width = '100%'; });

// ---- Auth state: skip straight to /store.php if already logged in ----
onAuthStateChanged(auth, (user) => {
    if (user) {
        window.location.href = '/store.php';
        return;
    }
    setTimeout(() => {
        document.getElementById('boot').classList.add('hidden');
        document.getElementById('authArea').classList.remove('hidden');
    }, 1500);
});

// ---- Form toggling ----
document.getElementById('showSignup').onclick = (e) => {
    e.preventDefault();
    document.getElementById('loginForm').classList.add('hidden');
    document.getElementById('signupForm').classList.remove('hidden');
};
document.getElementById('showLogin').onclick = (e) => {
    e.preventDefault();
    document.getElementById('signupForm').classList.add('hidden');
    document.getElementById('loginForm').classList.remove('hidden');
};

// ---- Login ----
document.getElementById('loginBtn').onclick = async () => {
    const email = document.getElementById('loginEmail').value.trim();
    const pass = document.getElementById('loginPass').value;
    if (!email || !pass) return toast('Fill both fields', 'error');
    try {
        await signInWithEmailAndPassword(auth, email, pass);
        toast('Logged in', 'success');
        window.location.href = '/store.php';
    } catch (e) {
        toast(e.message, 'error');
    }
};

// ---- Signup ----
document.getElementById('signupBtn').onclick = async () => {
    const email = document.getElementById('regEmail').value.trim();
    const pass = document.getElementById('regPass').value;
    if (!email || !pass) return toast('Fill both fields', 'error');
    if (pass.length < 6) return toast('Password must be at least 6 characters', 'error');
    try {
        await createUserWithEmailAndPassword(auth, email, pass);
        await backendFetch('/api/user/init', { method: 'POST' });
        toast('Account created', 'success');
        window.location.href = '/store.php';
    } catch (e) {
        toast(e.message, 'error');
    }
};

// ---- Google ----
document.getElementById('googleBtn').onclick = async () => {
    try {
        await signInWithPopup(auth, googleProvider);
        await backendFetch('/api/user/init', { method: 'POST' });
        window.location.href = '/store.php';
    } catch (e) {
        if (e.code !== 'auth/popup-closed-by-user') toast(e.message, 'error');
    }
};

// ---- Forgot password ----
document.getElementById('forgotLink').onclick = async (e) => {
    e.preventDefault();
    const email = document.getElementById('loginEmail').value.trim();
    if (!email) return toast('Enter your email above first', 'error');
    try {
        await sendPasswordResetEmail(auth, email);
        toast('Password reset email sent', 'success');
    } catch (e) {
        toast(e.message, 'error');
    }
};
</script>

</body>
</html>
