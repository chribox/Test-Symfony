# Test-Symfony

## Installation
### Lancement Docker
À la racine du projet (**Test-Symfony**) taper la commande :
```
make up
```
cela va lancer les container Docker

### Créer la base de donnée
Toujours à la racine du projet (**Test-Symfony**) taper la commande :
```
make php
```
pour aller dans le container php, puis taper la commande :
```
php bin/console doctrine:migration:migrate
```
La base se nomme `mabase.db`, elle est dans le dossier `var`

### Importer les produits en base à l'aide des fixtures
Toujours dans le container php taper la commande :
```
php bin/console doctrine:fixtures:load
```

### compiler les assets
À la racine du projet Symfony (**ProjetSymfony**) taper la commande :
```
cd /ProjetSymfony
npm run dev
```

## Lancer les tests unitaires
Dans le container php taper la commande :
```
php bin/phpunit
```
## Traductions
Dans la navbar en haut à droite vous avez la possibilité de changer de langue en cliquant sur les drapeaux.

## Point API
Retourne l'intégralité les produits au format JSON
```
http://localhost:8080/api/products
```
## Espace d'administration sécurisé
Accès via l'url
```
http://localhost:8080/admin
```

user: `admin`   
password: `password`     

>[!WARNING]
>vous pouvez avoir cette erreur   
>`An exception has been thrown during the compilation of a template ("EmptyNode cannot have children.") in "@EasyAdmin/crud/index.html.twig".`   
> C'est lié à un problème de version TWIG trop récente (la version 3.15 fonctionne).

## Faire un export CSV des produits
Dans le container php (make php) taper
```
php bin/console app:export-products-csv
```
Cela génére un fichier `products_AAAA-MM-DD_H-M-i.csv` dans le dossier `/public/exports`.

## Lancer le projet
```
http://localhost:8080/fr/
```

## Stopper les containers Docker
À la racine du projet (**Test-Symfony**) taper la commande :
```
make down
```

