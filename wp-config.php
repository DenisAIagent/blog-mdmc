<?php
// === Configuration MySQL - Railway, version en dur pour test === //
/** Nom de la base de données de WordPress. */
define( 'DB_NAME', 'railway' );

/** Utilisateur de la base de données MySQL. */
define( 'DB_USER', 'root' );

/** Mot de passe de la base de données MySQL. */
// ATTENTION : Mot de passe en clair ci-dessous ! Risque de sécurité si le code est public !
define( 'DB_PASSWORD', 'ojuNJzLTITYUhVINEAYOLhqiTzWcoHMd' );

/** Adresse de l’hébergement MySQL. */
// Le port 3306 est standard, donc l'hôte seul suffit.
define( 'DB_HOST', 'mysql.railway.internal' );

/** Jeu de caractères à utiliser par la base de données lors de la création des tables. */
define( 'DB_CHARSET', 'utf8mb4' ); // utf8mb4 est recommandé

/** Type de collation de la base de données. */
define( 'DB_COLLATE', '' );

// === Clés de sécurité WordPress === //
// Assurez-vous que ce sont bien vos clés uniques générées
define('AUTH_KEY',         'c}8JDK)iS^hS+sw-#VZ;Y;4$+.BO,Xz#N-YIk:$kuNnWDRX-`KMyIdl|=Se,|Wvv');
define('SECURE_AUTH_KEY',  'UA_KcY7UyrvF{61F|5~rinmqW|sr *FD2.Ol~dV-YibLv(&:KLc*+uI6pUR+#+Lu');
define('LOGGED_IN_KEY',    ',f4yAIN;Gv^^0s-Hx1#(@>[(/IA#DZoTE[$uVLJ)`|+z& +1]kJe!3R|{{_sM{zj');
define('NONCE_KEY',        '!LwNl=Q*Y?-j7vl+ytQ~cVi:46J1/}Z-B9WsnAs]7{B4g9v0r]?1N,z:UUKP/?vP');
define('AUTH_SALT',        '_+rYOPiI)jjA]TphK%&.z(OaOA,F[:=8ldB.41mscw0}-}>i6`x_i}(WHqa3YSx%');
define('SECURE_AUTH_SALT', 'Mmz;,nnt-i3,K=x4MRSHi2+))Ds}/CF7(lXsjy|+5Z%aKw>A[MTl=c(W*[v^srh1');
define('LOGGED_IN_SALT',   'QhVcKW|Duyx n/>-U;=?U+s=eJ8rO%dT-}!a3>DN*$plPPzTfhg|GGt@*(!$u#J3');
define('NONCE_SALT',       '^:mZFv]so/m,Z{=7uK9L%x.~V_?=|L1]OY1&o[2X0_OoQD;o%Jj?hx-O@J)piM3}');

// === Préfixe des tables WordPress === //
$table_prefix = 'wp_';

// === Debug === //
// Mettre à true seulement pour le débogage, false en production.
define( 'WP_DEBUG', false );

// === Forcer l’URL (généralement pas nécessaire si bien configuré) === //
// define( 'WP_HOME', 'https://blog-mdmc.up.railway.app' );
// define( 'WP_SITEURL', 'https://blog-mdmc.up.railway.app' );

/* C’est tout, ne touchez pas à ce qui suit ! Bonne publication. */

/** Chemin absolu vers le dossier de WordPress. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Réglage des variables WordPress et de ses fichiers inclus. */
require_once ABSPATH . 'wp-settings.php';
