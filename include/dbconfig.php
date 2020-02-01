<?php

const OPT = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

const HOST = 'localhost';
const DB = 'mymarket';
const USER = 'root';
const PASS = '';
const CHARSET = 'utf8';
const DSN = 'mysql:host=' . HOST. ';dbname=' . DB . ';charset=' . CHARSET;
