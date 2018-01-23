<?php
/* url=
http://fxgodin.com/TP/PHP-MYSQL/bbcode.php
*/
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr" >
     <head>
          <title>BB deCODE</title>
          <meta http-equiv="Content-Type" content="text/html; charset=utf8" />
          <link href="https://fonts.googleapis.com/css?family=Quicksand" rel="stylesheet"> 
			<style type="text/css">
				body{
					background-color: black;
					color: white;
					}
				html,input {
					font-family: 'Quicksand', sans-serif;
					}
				input{
					margin : 0 0 1em 0;
					}

				input[type=text]{
					width:100%;
					font-size: 1.5em;
				}
				input[type=submit]{
					font-weight:900;

				}

				.vrai, .faux {
					padding :0 2em;
					font-size: 150%;
				}
				.vrai{
					background-color: green;
				}
				.faux{
					background-color: red;
					color:black;
				}
				#txtparse{
					background-color:white;
					color:black;
				}
      		</style>
     </head>
     <body>

<h1>

    Bienvenue dans le parser de BBcode</a><br />

</h1>


<p>Amusez-vous à utiliser du bbCode. Tapez par exemple :</p>


<blockquote style="font-size:0.8em">

<p>

    J'ai [i]beaucoup appris[/i] sur https://openclassrooms.com<br />

    Je vous [b][color=green]recommande[/color][/b] d'aller sur ce site, vous pourrez apprendre  </br>
    [i][color=purple]vous aussi[/color][/i] à parser un texte BBcode en Html comme sur les forums ! Il n'y a pas toutes les balises bbcode mais au moins celle de ce texte. Regardez cette url avec ses paramètres :</br>
    http://www.siteduzero.com/index.php?page=3&skin=blue <br/>
     Vous pourrez aussi transformer une adresse email comme john.Doe@yaoo.fr , 
 du [u]texte souligné[/u], du {gras}texte en gras avec des balises différentes{/gras}, du [s]texte barré comme çi[/s] ou [strike]barré comme ça[/strike].
 [left]On peut aligner à gauche,[/left] [right]aligner à droite,[/right] [center]centrer du texte,[/center] [justify]ou justifier tout un paragraphe en Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam malesuada elementum blandit. Integer eget velit et arcu commodo hendrerit. Integer a sapien hendrerit sapien blandit tincidunt eu elementum nisl. [/justify] 
On peut afficher une citation comme celle de John Woods :</br>
[quote]Codez toujours comme si la personne qui allait maintenir votre code ...[/quote]</br>
[list]Une liste 
[*]élément 1 
[*]élément 2 
[*]élément 3
[/list]
On peut aussi afficher une image comme içi : </br></br>
    [img]https://goo.gl/B12AgP[/img]</br></br>
Sympa non? 
   
</p>

</blockquote>


<form method="post" action="bbcode.php/#txtparse"> 

<p>

    <label for="texte">Votre texte BBcode à parser :</label><br />

    <textarea id="texte" name="texte" cols="50" rows="8"><?php if(isset($_POST['texte'])){echo $_POST['texte'];} ?></textarea><br />

    <input type="submit" value="Montre-moi toute la puissance des regex" />

</p>

</form>

<?php
if (isset($_POST['texte']))

{

    $texte = stripslashes($_POST['texte']); // On enlève les slashs qui se seraient ajoutés automatiquement

    $texte = htmlspecialchars($texte); // On rend inoffensives les balises HTML que le visiteur a pu rentrer

    $texte = nl2br($texte); // On crée des <br /> pour conserver les retours à la ligne

    

    // On fait passer notre texte à la moulinette des regex
    //[b]texte en gras[/b]
    $texte = preg_replace('#\[b\](.+)\[/b\]#isU', '<strong>$1</strong>', $texte);
	//[i]
    $texte = preg_replace('#\[i\](.+)\[/i\]#isU', '<em>$1</em>', $texte);

	//[color]
    $texte = preg_replace('#\[color=(red|green|blue|yellow|purple|olive)\](.+)\[/color\]#isU', '<span style="color:$1">$2</span>', $texte);
	// [img]
	    $texte = preg_replace('#\[img\](.+)\[/img\]#isU', '<img src="$1">', $texte);

	//Lien cliquable...avec paramètres..sauf url d'img
    $texte = preg_replace('#[^">]https?://[a-z0-9._/-/?=&;]+#i', '<a href="$0">$0</a>', $texte);

    // adresse email cliquable
    $texte = preg_replace('#[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,24}#i', '<a href="mailto:$0">$0</a>', $texte);

    // [u]
    $texte = preg_replace('#\[u\](.+)\[/u\]#isU', '<u>$1</u>', $texte);
    
    // {gras}
    $texte = preg_replace('#\{gras\}(.+)\{/gras\}#isU', '<strong>$1</strong>', $texte);
    //Texte barré [s]Texte[/s] => <strike>Texte</strike>
    $texte = preg_replace('#\[s\](.+)\[/s\]#isU', '<strike>$1</strike>', $texte);
    //ou aussi [strike]Texte[/strike] => <strike>Texte</strike>
    $texte = preg_replace('#\[strike\](.+)\[/strike\]#isU', '<strike>$1</strike>', $texte);
    //[left]Text[/right] => <p style="text-align:right>Text</p>
    $texte = preg_replace('#\[right\](.+)\[/right\]#isU', '<p style="text-align:right">$1</p>', $texte);
    //[left]Text[/left]=> <p style="text-align:left>Text</p>
    $texte = preg_replace('#\[left\](.+)\[/left\]#isU', '<p style="text-align:left">$1</p>', $texte);

    //[center]Text[/center]=> <p style="text-align:center>Text</p>
    $texte = preg_replace('#\[center\](.+)\[/center\]#isU', '<p style="text-align:center">$1</p>', $texte);
    //[justify]Text[/justify]=> <p style="text-align:justify>Text</p>
    $texte = preg_replace('#\[justify\](.+)\[/justify\]#isU', '<p style="text-align:justify">$1</p>', $texte);
    //[quote]citation[/quote]=> <q>citation</q>
    $texte = preg_replace('#\[quote\](.+)\[/quote\]#isU', '<q>$1</q>', $texte);

    /*		[list]				=><ul>
			[*] Première phrase =><li>Première phrase</li>
			[*] Deuxième phrase =><li>Deuxième phrase</li>
			[/list]				=></ul>	
	*/
	        //[/list]liste[/list] => <ul>liste</ul>
	        $texte = preg_replace('#\[list\](.+)\[/list\]#isU', '<ul>$1</ul>', $texte);
	        //[*] => <li>
	        $texte = preg_replace('#\[\*\]#isU', '<li>', $texte);





    // Et on affiche le résultat. Admirez !

    echo '<hr />Votre texte parsé :<br /><br /><div id="txtparse">'.$texte.'</div>';

}

?>
     </body>
</html>
