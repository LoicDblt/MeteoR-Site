# MeteoR - Site
Ce site fonctionne de pair avec le programme Python [MeteoR](https://github.com/LoicDblt/MeteoR-Programme). En effet, il permet d'afficher les
données renvoyées par ce dernier, de manière dynamique.

Pour cela, il fait appel à PHP pour l'accès aux bases de données, mais aussi à JavaScript, et notamment
Plotly, pour l'affichage des moyennes sous forme de graphiques.

## **Sommaire**
- [Installation](#installation)
  - [Mise en place](#mise-en-place)
  - [SFTP](#sftp)
- [Crédit](#crédit)

## **Installation**
### Mise en place
Il suffit de copier les différents fichiers sur un serveur faisant tourner PHP 7/8.
Il faudra également penser à noter le chemin du dossier côté serveur, afin de le fournir au programme client MeteoR lors de son lancement.

### SFTP
Il est nécessaire de créer un identifiant/mot de passe SFTP, permettant l'envoi des bases de données par le programme côté client.

## **Crédit**
Je remercie particulièrement Quentin F. pour le temps passé à la création des différents visuels.
