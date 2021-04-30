<?php
// Captcha minimaliste mais très efficace ! Pensez à variez vos questions / réponses !

			$mabellebase = $_SERVER['SERVER_NAME'];
			$mabellebase = strlen($mabellebase);
			
			$kap_tcha = Rand (1,3); 
			switch ($kap_tcha)
			{
				 case 1:
					$id_q = 1; $question_q = "Combien font $mabellebase + 12 ?"; $reponse_q = $mabellebase + 12;
				 break;
				 
				 case 2:
					$id_q = 2; $question_q = "De combien de lettres est composé le mot 'BATMAN' ?"; $reponse_q = "6";
				 break;
				 
				 case 3:
					$id_q = 3; $question_q = "Quelle est la saison après l'été ? (Répondez en minuscule)"; $reponse_q = "automne";
			}
			$_SESSION["kap_tcha_id"] = $reponse_q;
?>