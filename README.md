# Janembart
Janembart est un forks du script de génération d'annuaire Bartemis laissé à l'abandon par son créateur, ce qui permet à ce formidable outil de revivre et d'être compatible avec les nouvelles normes (PHP 8.0, HTTPS...)

Plus d'infos sur https://janembart.com/

Ce script tourne uniquement sur un nom de domaine ayant activé le protocole HTTPS !

// INSTALLATION
1. Uploadez tout ce dossier sur votre serveur à la racine ou dans le dossier de votre choix
2. Rendez-vous sur https://tonsite.fr/(dossier_d_installation)/install.php
3. Laissez vous guider

PS : Gardez bien ce dossier sous le coude car à l'issu de l'installation, les fichiers install.php et setup_process.php vont être supprimés, un fichier config.php va être créé, et le fichier htaccess va être customisé.

// RE-INSTALLATION (D'USINE)
Pour relancer une installation "d'usine" :

1. Faites un backup de votre FTP et de votre base de donnée
2. Supprimez le fichier config.php et .htaccess, et videz le dossier cache
3. Ré-uploadez les fichiers .htaccess, install.php et setup_process.php initiaux
4. Supprimez les tables SQL liés à Janembart
5. Relancez l'installation (comme expliqué ci-dessus)

--------------------

© Janembart
https://janembart.com
** fr ** <help@janembart.com>

--------------------
© Bartemis
Merci à Robin D. pour sa création initiale sans qui rien n'aurait été possible.
