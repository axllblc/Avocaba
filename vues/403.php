<?php

/* ⚠️ Erreur 403 (forbidden) */

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/error.php';

error(403, 'accès refusé.');