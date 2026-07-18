<?php
// includes/config.php — the only place these values live. Every page
// includes this, so changing your backend URL means editing ONE file.

define('BACKEND_URL', 'https://fuckv1.onrender.com'); // ← your Node backend URL

define('FIREBASE_CONFIG_JSON', json_encode([
    'apiKey' => 'AIzaSyC75_Oqo4wc7Jx58wfkkoQML9YxgP24QR4',
    'authDomain' => 'bronzx.firebaseapp.com',
    'projectId' => 'bronzx',
    'storageBucket' => 'bronzx.firebasestorage.app',
    'messagingSenderId' => '155159545642',
    'appId' => '1:155159545642:web:1d615183d1cdee3bdac053',
]));
