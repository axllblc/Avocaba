<?php

/* ⚠️ Erreur 404 (Not found) */

require_once $_SERVER['DOCUMENT_ROOT'] . '/avocaba/composants/error.php';

error(404, 'la page demandée est introuvable. Elle a peut-être été déplacée ou supprimée.');