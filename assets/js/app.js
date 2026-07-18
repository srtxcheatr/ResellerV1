// assets/js/app.js — shared across every page. Firebase Auth still
// runs client-side (that's normal and fine — it's how Firebase Auth
// works everywhere), but it NEVER touches Firestore directly anymore.
// Every read/write goes through your Node backend at window.BACKEND_URL.

import { initializeApp } from 'https://www.gstatic.com/firebasejs/10.12.2/firebase-app.js';
import {
    getAuth, onAuthStateChanged, signOut,
    signInWithEmailAndPassword, createUserWithEmailAndPassword,
    GoogleAuthProvider, signInWithPopup, sendPasswordResetEmail,
} from 'https://www.gstatic.com/firebasejs/10.12.2/firebase-auth.js';

export const app = initializeApp(window.FIREBASE_CONFIG);
export const auth = getAuth(app);
export const googleProvider = new GoogleAuthProvider();

export { onAuthStateChanged, signOut, signInWithEmailAndPassword, createUserWithEmailAndPassword, signInWithPopup, sendPasswordResetEmail };

export async function getAuthToken() {
    const user = auth.currentUser;
    if (!user) throw new Error('Not logged in. Please refresh.');
    return await user.getIdToken(true);
}

export async function backendFetch(path, options = {}) {
    const token = await getAuthToken();
    const r = await fetch(`${window.BACKEND_URL}${path}`, {
        ...options,
        headers: {
            Authorization: `Bearer ${token}`,
            ...(options.body ? { 'Content-Type': 'application/json' } : {}),
            ...(options.headers || {}),
        },
    });
    const d = await r.json();
    if (!d.success) throw new Error(d.error || 'Request failed');
    return d;
}

/**
 * Every page except home.php calls this on load. Redirects to
 * /home.php if not signed in, and calls back with the uid once
 * Firebase confirms the session — this is a full page each time
 * (traditional multi-page nav), so there's no shared in-memory state
 * between pages; each page re-checks auth on its own.
 */
export function requireAuth(onReady) {
    onAuthStateChanged(auth, (user) => {
        if (!user) {
            window.location.href = '/home.php';
            return;
        }
        onReady(user);
    });
}

export function toast(msg, kind) {
    let el = document.getElementById('__toast');
    if (!el) {
        el = document.createElement('div');
        el.id = '__toast';
        el.className = 'toast';
        document.body.appendChild(el);
    }
    el.textContent = msg;
    el.className = 'toast show' + (kind ? ' ' + kind : '');
    clearTimeout(el._timer);
    el._timer = setTimeout(() => el.classList.remove('show'), 3200);
}

export async function doLogout() {
    await signOut(auth);
    window.location.href = '/home.php';
}
window.doLogout = doLogout;

export function fmtDate(iso) {
    if (!iso) return '';
    try { return new Date(iso).toLocaleString(); } catch (e) { return iso; }
}

export function esc(s) {
    const d = document.createElement('div');
    d.textContent = s ?? '';
    return d.innerHTML;
}
