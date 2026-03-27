# EduFlow : API de Gestion de Cours Moderne 🎓

EduFlow est une API robuste basée sur **Laravel 12**, conçue pour les établissements d'enseignement afin de gérer les cours, les inscriptions des étudiants et l'organisation des groupes académiques. Cette plateforme intègre des fonctionnalités modernes telles que le groupage automatisé des étudiants, des recommandations de cours basées sur les intérêts et des paiements en ligne sécurisés.

---

## 🚀 Fonctionnalités Clés

### Authentification & Sécurité
- **Authentification JWT** : Inscription et connexion sécurisées des utilisateurs via JSON Web Tokens.
- **Accès par Rôles** : Fonctionnalités spécialisées pour les **Étudiants** et les **Enseignants**.
- **Gestion des Mots de Passe** : Flux de réinitialisation sécurisé.

### Gestion des Cours
- **Tableau de Bord Enseignant** : Opérations CRUD complètes pour les cours, incluant la tarification et les métadonnées.
- **Découverte pour l'Étudiant** : Recherche avancée et filtrage pour explorer les cours disponibles.
- **Liste de Souhaits** : Sauvegarde des cours d'intérêt dans une liste de favoris personnelle.

### Recommandations Intelligentes
- **Suggestions basées sur les Intérêts** : Recommandations automatisées selon les domaines sélectionnés et les aspirations professionnelles de l'étudiant.

### Paiements & Inscriptions
- **Intégration Stripe** : Paiements par carte bancaire fluides et sécurisés.
- **Cycle de Vie des Cours** : Processus simplifiés d'inscription et de désistement.

### Groupage Automatisé
- **Équipes Dynamiques** : Assignation automatique des étudiants dans des groupes (max 25 participants par groupe).
- **Auto-scaling** : Création automatique de nouveaux groupes au fur et à mesure de la croissance des inscriptions.

---

## 🛠 Stack Technique

- **Framework** : [Laravel 12](https://laravel.com)
- **Langage** : PHP 8.2+
- **Base de données** : MySQL / PostgreSQL
- **Sécurité** : [PHP Open Source Saver JWT-Auth](https://github.com/PHP-Open-Source-Saver/jwt-auth)
- **Passerelle de Paiement** : [Stripe PHP](https://github.com/stripe/stripe-php)
- **Documentation API** : [L5-Swagger](https://github.com/DarkaOnline/L5-Swagger) (OpenAPI 3.0)
- **Tests** : PHPUnit

---

## 🏗 Modèles d'Architecture

Ce projet respecte les principes du **Clean Code** et une architecture modulaire :
- **Repository Pattern** : Couche d'abstraction entre le domaine et la persistance des données.
- **Service Layer** : Logique métier découplée des contrôleurs pour une meilleure maintenance et testabilité.
- **Form Requests** : Logique de validation dédiée pour toutes les données entrantes de l'API.

---

## 📥 Mise en Route

### Prérequis
- PHP 8.2 ou supérieur
- Composer
- MySQL / MariaDB
- Un compte Stripe (pour les paiements)

### Installation

1. **Cloner le dépôt** :
   ```bash
   git clone <url-du-depot>
   cd EduFlow