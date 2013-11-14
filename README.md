Onyx
===========

Billetterie pour les associations de l'utc
-----------

Projet utilisant le framework <a href='http://symfony.com/' title='Symfony' target='_blank'>Symfony (version 2.3)</a>


### Installation avec composer :

<!-- language: lang-none -->
    cd /var/www/
    git clone https://github.com/payutc/onyx
    cd onyx/
    # La commande suivante va installer les sources et également créer le fichier de configuration manquant : app/config/parameters.yml
    php composer.phar install
    # La commande suivante va mettre à jour votre base de données locale (Structure seulement...) avec les paramètres renseignés dans app/config/parameters.yml
    php app/console doctrine:schema:update --force
    # La commande suivante va installer les assets (images, css, js...)
    php app/console assets:install web/
    # NB : vous devez taper cette commande à chaque modification d'un fichier de ressource Front...
    # Ou bien, si vous n'êtes pas sous Windows (car problèmes...), et que vous voulez vous simplifier la vie, installez en tant qu'hyperliens :
    php app/console assets:install web/ --symlink
