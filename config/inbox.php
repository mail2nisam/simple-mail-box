<?php
return [
    'imap_server' => env('IMAP_SMTP_SERVER', '{imap.gmail.com:993/imap/ssl}INBOX'),
    'imap_user_name' => env('IMAP_SMTP_USER_NAME', 'harveyspect60@gmail.com'),
    'imap_password' => env('IMAP_SMTP_USER_PASSWORD', 'zwukybktspqnzwqk'),
    'items_per_page' => env('INBOX_ITEMS_PER_PAGE', 10),
];
