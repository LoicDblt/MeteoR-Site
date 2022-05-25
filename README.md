# MeteoR - Site

## **Présentation**
    Ce site fonctionne de pair avec le programme Python [MeteoR](https://github.com/LoicDblt/MeteoR-Programme). En effet, il permet d'afficher les
données renvoyées par ce dernier, de manière dynamique.

    Pour cela, il fait appel à PHP pour l'accès aux bases de données, mais aussi à JavaScript, et notamment
Plotly, pour l'affichage des moyennes sous forme de graphique.

## **Installation**
### Mise en place
    Il suffit de copier les différents fichiers sur un serveur tournant sous PHP 7/8.
Il faudra également penser à noter le chemin du dossier racine afin de le fournir au programme MeteoR lors de son lancement.

---

### SFTP
    Il est nécessaire de créer un identifiant/mot de passe SFTP vers ce même chemin racine, afin que le Raspberry Pi puisse y envoyer les bases de données.

## **Remerciement**
    Je remercie particulièrement Quentin F. pour la création des différents visuels, permettant au site d'arborer une identité qui lui est propre.
