---
paths:
    - 'resources/js/**/*.vue'
    - 'resources/js/**/*.ts'
---

# Règles Vue 3

- Toujours <script setup lang="ts">
- Props typées avec defineProps<{...}>()
- Composables dans /composables/, préfixe "use" (useAuth, useCart...)
- Jamais de logique dans les templates, tout dans le script
- Pinia pour le state global, composables pour le state local
- fetch() via les composables dédiés, jamais directement dans un composant
- Utilise toujours la composition API mais pas l'option API
- Crée toujours des tests avec Vitest + Vue Test Utils pour chaque composant/composable
