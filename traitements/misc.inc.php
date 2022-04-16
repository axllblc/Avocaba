<?php

/* Fonctions diverses */

/**
 * Transforme une chaîne de caractères en minuscules, remplace les caractères accentués et remplace les caractères non textuels par des espaces.
 */
function slugify ($str) {
  $str = strtolower($str);

  $search  = array('à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ð', 'ò', 'ó', 'ô', 'õ', 'ö', 'œ', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ');
  $replace = array('a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'oe', 'u', 'u', 'u', 'u', 'y', 'y');

  $str = str_replace($search, $replace, $str);

  $str = preg_replace('/[\W\s]/', ' ', $str);

  return $str;
}