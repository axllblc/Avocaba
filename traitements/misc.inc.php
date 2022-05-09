<?php

/* Fonctions diverses */

error_reporting(0);


/**
 * Transforme une chaîne de caractères en minuscules, remplace les caractères accentués et remplace les caractères non
 * textuels par des espaces.
 * @param string $str Chaîne à transformer.
 * @return string Chaîne transformée.
 */
function slugify (string $str): string {
  $str = strtolower($str);

  $search  = array('à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'œ', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
  $replace = array('a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'oe', 'u', 'u', 'u', 'u', 'y', 'y');

  $str = str_replace($search, $replace, $str);

  $str = preg_replace('/[\W\s]/', ' ', $str);

  return $str;
}


/**
 * Remplacer les <code>\n</code> d'une chaîne de caractères par des retours à la ligne en HTML
 * @param string $str Chaîne à modifier avec des <code>\n</code>
 * @return string Chaîne avec des <code>&lt;br&gt;</code>
 */
function lineBreakChange(string $str) : string{
  return str_replace('\n', '<br>', $str);
}