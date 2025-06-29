<?php
/** ✅ Forcer HTTPS derrière le proxy Railway */
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

/** 🔧 Configuration de la base de données */
define( 'DB_NAME', 'railway' );
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'ojuNJzLTITYUhVINEAYOLhqiTzWcoHMd' );
define( 'DB_HOST', 'mysql.railway.internal' );
define( 'DB_CHARSET', 'utf8mb4' );
define( 'DB_COLLATE', '' );

/** 🔐 Clés de sécurité WordPress */
define('AUTH_KEY',         'c}8JDK)iS^hS+sw-#VZ;Y;4$+.BO,Xz#N-YIk:$kuNnWDRX-`KMyIdl|=Se,|Wvv');
define('SECURE_AUTH_KEY',  'UA_KcY7UyrvF{61F|5~rinmqW|sr *FD2.Ol~dV-YibLv(&:KLc*+uI6pUR+#+Lu');
define('LOGGED_IN_KEY',    ',f4yAIN;Gv^^0s-Hx1#(@>[(/IA#DZoTE[$uVLJ)`|+z& +1]kJe!3R|{{_sM{zj');
define('NONCE_KEY',        '!LwNl=Q*Y?-j7vl+ytQ~cVi:46J1/}Z-B9WsnAs]7{B4g9v0r]?1N,z:UUKP/?vP');
define('AUTH_SALT',        '_+rYOPiI)jjA]TphK%&.z(OaOA,F[:=8ldB.41mscw0}-}>i6`x_i}(WHqa3YSx%');
define('SECURE_AUTH_SALT', 'Mmz;,nnt-i3,K=x4MRSHi2+))Ds}/CF7(lXsjy|+5Z%aKw>A[MTl=c(W*[v^srh1');
define('LOGGED_IN_SALT',   'QhVcKW|Duyx n/>-U;=?U+s=eJ8rO%dT-}!a3>DN*$plPPzTfhg|GGt@*(!$u#J3');
define('NONCE_SALT',       '^:mZFv]so/m,Z{=7uK9L%x.~V_?=|L1]OY1&o[2X0_OoQD;o%Jj?hx-O@J)piM3}');

/** 🗂 Préfixe des tables */
$table_prefix = 'wp_';

/** 🛡️ Mode production avec debug activé pour diagnostiquer */
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
define( 'WP_DEBUG_DISPLAY', true );
@ini_set( 'display_errors', 1 );

/** 🌐 Domaine personnalisé du site WordPress */
define( 'WP_HOME', 'https://blog.mdmcmusicads.com' );
define( 'WP_SITEURL', 'https://blog.mdmcmusicads.com' );

/** 💾 Augmenter la mémoire PHP */
define( 'WP_MEMORY_LIMIT', '256M' );

/** 🚀 Fin des personnalisations – ne rien toucher en dessous */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}
require_once ABSPATH . 'wp-settings.php';
