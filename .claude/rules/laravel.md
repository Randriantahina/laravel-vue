---
paths:
    - 'app/**/*.php'
    - 'database/**/*.php'
    - 'routes/**/*.php'
---

# Règles Laravel

- Toujours utiliser Eloquent, jamais de raw SQL sauf performance critique
- Utilise toujours le DTO pour le transport des données
- Les logiques metiers vont dans les services sauf si c'est un simple CRUD, la on peut directement utiliser un repository
- Les requêtes SQL vont dans les repository
- Form Requests obligatoires pour toute validation
- Policies pour toute autorisation, jamais de if($user->role) dans les controllers
- Les events/listeners pour le découplage métier
- Les Jobs pour tout ce qui prend plus de 500ms
- Utilise toujours les principes SOLID aussi pour faire du clean code
- Crée toujours des tests pour chaque feature, jamais de code non testé
- On utilise Laravel 13, utilise toujours les nouvelles syntaxes disponibles (attributs, etc.), jamais les anciennes
