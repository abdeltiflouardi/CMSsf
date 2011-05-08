<?php
function makeTags($entry) { //Fonction qui va dans une chaine récuperer des mots importants

	$new = stripslashes($entry); //On enlève les slashs

	$new = strtr($new, 'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy'); //On réduit l'accentuation

	$except = "(un|une|le|a|les|la|de|des|du|et|non|qui|que|quoi|est|c'est|ca|c'etait|encore|le|c|d|l|n|je|ne|pas|ou|aussi|avec|sans)";
	$new = preg_replace("#\b".$except."?\b#i",'__', $new); //On supprime tous les mots inutiles, ajoutez ceux que vous ne désirez pas voir ;)

	$new = preg_replace('/[^a-zA-Z0-9_]/', '_', $new); //Filtrage de tout ce qui est ' ", bref non caractère

	$new = preg_replace('/[_]{2,}/','_', $new); //On enlève les underscore si ils sont au moins répétés deux fois

	$new = preg_replace('/^[_]/','', $new); //On enlève les underscore en début de chaine

	$new = preg_replace('/[_]$/','', $new); //On enlève les underscore en fin de chaine

	$tags = explode('_', trim($new)); //On fait un tableau de chaque tag séparé par un underscore

	return $tags; //On retourne le tableau de tags

}
?>
