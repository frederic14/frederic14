<?php
/**
*
* @package language
* @version $Id: presentation_form.php,v 1.19 2007/11/23 10:56:20 elglobo Exp $
* @copyright (c) 2008 ErnadoO
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'TITLE' 				=> 'Poster votre présentation',
	'FIELDS'				=> 'Les champs marqués d\'une étoile * sont obligatoires',
	'PRESENTATION'			=> 'Présentation',
	'LEISURE'				=> 'Principaux loisirs',
	'LOISIR_1'				=> 'Sport',
	'LOISIR_2'				=> 'Cinéma',
	'LOISIR_3'				=> 'Lecture',
	'LOISIR_4'				=> 'Musique',
	'LOISIR_5'				=> 'Balades',
	'LOISIR_6'				=> 'Informatique',
	'LOISIR_7'				=> 'Jardinage',
	'LOISIR_8'				=> 'Bricolage',
	'LOISIR_9'				=> 'Jeux vidéos',
	'LOISIR_10'				=> 'Jeux de société',
	'LOISIR_11'				=> 'Shopping',
	'LOISIR_12'				=> 'Cuisine',
	'LOISIR_13'				=> 'Voyage',
	'LOISIR_14'				=> 'Farniente',
	'LOISIR_15'				=> 'Autre',
	'QUALITIES'				=> 'Types de pêche pratiqués',
	'DEFECTS'				=> 'Localitée',
	'INFO'					=> 'Comment avez-vous connu ce forum? Quelques infos sur votre matériel. ',
	'ADRESSE'				=> 'Adresse de votre site',

	'PRESENTATION_EMPTY'	=> 'Vous devez entrer une présentation',

	'HOW'					=> 'Comment j\'ai connu le forum',
	'URL'					=>'Adresse de mon forum ou de mon site',

	'POST_SUCCESS'			=> 'Votre présentation a été postée.',


));

?>