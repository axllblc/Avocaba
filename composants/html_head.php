<?php

/**
 * En-tête du document HTML.
 * @param string $title Titre de la page
 * @return void
 */
function htmlHead (string $title = 'Avocaba'): void { ?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/avocaba/stylesheets/layout.css">
  <link rel="stylesheet" href="/avocaba/stylesheets/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
  <link rel="icon" href="/avocaba/img/favicon.png">
  <title><?= $title ?></title>
</head>
<?php }