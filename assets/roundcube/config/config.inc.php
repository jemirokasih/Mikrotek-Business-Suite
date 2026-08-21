<?php
/*
 * Mikrotek Business Suite - Official Roundcube Webmail 1.6.10 Configuration
 */

$config = [];

// Local SQLite database connection
$config['db_dsnw'] = 'sqlite:///' . __DIR__ . '/../temp/roundcube.db';

// Mail Server configuration
$config['default_host'] = 'ssl://mail.mzi.co.id';
$config['default_port'] = 993;
$config['smtp_server']  = 'ssl://mail.mzi.co.id';
$config['smtp_port']    = 465;
$config['smtp_user']    = '%u';
$config['smtp_pass']    = '%p';

// Branding & Interface
$config['product_name'] = 'Mikrotek Webmail Suite';
$config['des_key']      = 'MikrotekWebmailSuiteKey2026!#$';
$config['skin']         = 'elastic';
$config['plugins']      = ['archive', 'zipdownload'];

// Security & Session
$config['session_lifetime'] = 30;
$config['enable_installer'] = false;
$config['dont_override']    = ['skin'];
