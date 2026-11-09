# DripStudio

Application mobile qui répertorie les vêtements de ma garde-robe et propose chaque
matin une tenue adaptée à la météo et à la température locales.

Projet réalisé dans le cadre du cours de **Technologies web avancées**, UFR STGI, Master PSM.
**Mélinda ALAND** · Septembre 2026 → Décembre 2026

---

## Principe

Contrairement aux cabines d'essayage virtuelles des enseignes, qui projettent des
articles à vendre, **DripStudio** ne compose des tenues qu'à partir des vêtements que
je possède déjà. À qualité météo égale, l'algorithme privilégie les pièces les moins
portées d'où la dimension éco-responsable du projet.

## Stack technique

| Couche | Technologie |
|---|---|
| API | Laravel (PHP 8.3+), REST, authentification Sanctum |
| Base de données | MySQL 8 (migrations + Eloquent) |
| Application mobile | React Native via Expo (workflow managé) |
| Navigation | expo-router |
| État & cache réseau | TanStack Query |
| Capteurs | expo-camera (photos), expo-location (géolocalisation) |
| Stockage photos | Laravel `Storage` (local en dev, S3-compatible en prod) |
| Météo | Open-Meteo (sans clé d'API) |
| Traitement asynchrone | Files d'attente Laravel (driver `database`) |

## Structure du dépôt

```
dripstudio/
├── api/              API Laravel : modèles, migrations, endpoints, algorithme de tenues
├── mobile/           Application Expo : écrans, navigation, appels API
└── README.md
```

## Prérequis

- PHP 8.3 ou supérieur (fourni par MAMP)
- Composer 2
- Node.js 20 ou supérieur
- MySQL 8 (fourni par MAMP)
- L'application Expo Go sur le téléphone, ou un émulateur

## Installation

### 1. L'API

```bash
cd api
composer install
cp .env.example .env
php artisan key:generate
```

Renseigner la connexion MySQL dans `api/.env` (valeurs MAMP par défaut) :

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=8889
DB_DATABASE=dripstudio
DB_USERNAME=root
DB_PASSWORD=root
```

Puis créer le schéma et les données de référence :

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve --host=0.0.0.0 --port=8000
```

> `--host=0.0.0.0` est indispensable : sans cela le téléphone ne peut pas joindre l'API, qui n'écouterait que sur `localhost`.

### 2. L'application mobile

```bash
cd mobile
npm install
```

Créer `mobile/.env` avec l'adresse IP locale de la machine sur le réseau Wi-Fi
(`ipconfig getifaddr en0` sur Mac) :

```
EXPO_PUBLIC_API_URL=http://192.168.X.X:8000/api
```

Puis lancer :

```bash
npx expo start
```

## Périmètre

**Prioritaire (P1)** : base de données, API authentifiée, capture et envoi de photo,
dressing avec filtres, connecteur météo, algorithme de tenues, écran d'accueil.

**Secondaire (P2)** : favoris, profil, détourage automatique des photos,
préremplissage des informations du vêtement par reconnaissance d'image.

**Hors périmètre de décembre 2026** : mannequin 3D, comptes multi-utilisateurs.
Conservés comme perspectives d'évolution.

## Lien/références

[Maquettes Figma](https://www.figma.com/design/ZCg0Rlz4MO3vRYjglN3TaV/DripStudio)
