# Modèle de données — DripStudio

---

## Entités

### `categories`
Alimenté par un seeder et permet de créer des tenues de manière intelligente. 

| Attribut | Type | Rôle |
|---|---|---|
| `id` | INTEGER, PK | |
| `nom` | VARCHAR(50) | « T-shirt », « Jean », « Manteau »… |
| `emplacement` | ENUM | `haut`, `bas`, `pieds`, `exterieur`, `accessoire` |
| `couche` | TINYINT | 1 = au contact de la peau, 2 = intermédiaire, 3 = extérieur |
| `obligatoire` | BOOLEAN | Une tenue sans pièce de cet emplacement n'est pas valide |

### `vetements`
Entité centrale. Un vêtement physique de la garde-robe.

| Attribut | Type | Rôle |
|---|---|---|
| `id` | INTEGER, PK | |
| `user_id` | INTEGER, FK | Mono-utilisateur |
| `categorie_id` | INTEGER, FK | → `categories` |
| `nom` | VARCHAR(100) | |
| `couleur_hex` | CHAR(7) | `#3F334D` |
| `motif` | VARCHAR(50), NULL | `uni`, `rayé`, `imprimé`… |
| `temp_min` | SMALLINT | °C en dessous duquel la pièce n'est plus adaptée |
| `temp_max` | SMALLINT | °C au-dessus duquel elle ne l'est plus |
| `impermeable` | BOOLEAN | |
| `coupe_vent` | BOOLEAN | |
| `nb_ports` | INTEGER | Compteur pour savoir combien de fois on a porté une tenue |
| `derniere_fois_porte` | DATE, NULL | Clé de tri de l'algorithme éco-responsable |
| `deleted_at` | TIMESTAMP, NULL | Suppression logique |

### `images_vetements`
Un vêtement a au moins une photo, et également plusieurs versions de la même.

| Attribut | Type | Rôle |
|---|---|---|
| `id` | INTEGER, PK | |
| `vetement_id` | INTEGER, FK | → `vetements` |
| `chemin_fichier` | VARCHAR(500) | Chemin relatif dans le disque `Storage` |
| `type` | ENUM | `originale`, `detouree`, `vignette` |

### `tenues`
Une **composition** de vêtements.

| Attribut | Type | Rôle |
|---|---|---|
| `id` | INTEGER, PK | |
| `user_id` | INTEGER, FK | |
| `nom` | VARCHAR(100), NULL | |
| `temperature_generation` | DECIMAL(4,1) | Contexte météo au moment de la génération |
| `description_meteo` | VARCHAR(100) | |
| `est_favori` | BOOLEAN | |
| `deleted_at` | TIMESTAMP, NULL | |

### `vetements_tenues`
Table de liaison de l'association N–N, portant l'ordre d'empilement visuel.

| Attribut | Type |
|---|---|
| `tenue_id` | INTEGER, PK + FK |
| `vetement_id` | INTEGER, PK + FK |
| `ordre_affichage` | TINYINT |

### `ports`
Historique du nombre fois de la tenue portée.

| Attribut | Type | Rôle |
|---|---|---|
| `id` | INTEGER, PK | |
| `tenue_id` | INTEGER, FK | → `tenues` |
| `date_portee` | DATE | |
| `temperature_reelle` | DECIMAL(4,1), NULL |

### `profils`
Configuration locale. Une seule ligne en mono-utilisateur.

| Attribut | Type |
|---|---|
| `id` | INTEGER, PK |
| `user_id` | INTEGER, FK |
| `pseudo` | VARCHAR(50) |
| `taille_haut` | VARCHAR(10) |
| `taille_bas` | VARCHAR(10) |
| `pointure` | TINYINT |
| `ville_defaut` | VARCHAR(100) |
| `latitude` / `longitude` | DECIMAL | Dernière position connue, pour l'appel météo |

---

## Associations et cardinalités

| Association | Entité A | Entité B | Lecture | Traduction |
|---|---|---|---|---|
| `appartenir` | VETEMENT (1,1) | CATEGORIE (0,n) | Un vêtement appartient à exactement une catégorie | FK `categorie_id` dans `vetements` |
| `illustrer` | IMAGE (1,1) | VETEMENT (1,n) | Une image se rapporte à un seul vêtement, un vêtement en a au moins une | FK `vetement_id` dans `images_vetements` |
| `composer` | TENUE (1,n) | VETEMENT (0,n) | Une tenue contient plusieurs vêtements, un vêtement figure dans plusieurs tenues | Table de liaison `vetements_tenues` portant `ordre_affichage` |
| `porter` | PORT (1,1) | TENUE (0,n) | Un port concerne une seule tenue, une tenue peut avoir été portée plusieurs fois | FK `tenue_id` dans `ports` |
