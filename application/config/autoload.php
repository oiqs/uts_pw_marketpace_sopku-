<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$autoload['packages'] = array();

// Library yang dibutuhkan: database (koneksi DB) & session (login/cart)
$autoload['libraries'] = array('database', 'session');

$autoload['drivers'] = array();

// Helper url() & form() dipakai di semua view (base_url, dsb)
$autoload['helper'] = array('url', 'form');

$autoload['config'] = array();
$autoload['language'] = array();
$autoload['model'] = array();