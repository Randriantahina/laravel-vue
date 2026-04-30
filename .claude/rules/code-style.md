# Workflow de développement

## Règle de commit obligatoire

Après chaque feature complète, tu DOIS suivre ce workflow dans l'ordre :

1. Implémenter la feature
2. Créer/mettre à jour les tests liés à cette feature
3. Exécuter les tests pour vérifier qu'ils passent
4. **Seulement si les tests sont verts** → faire un commit

## Format du commit

- Message : "feat: description courte de la feature"
- Conventionnel commits : feat, fix, refactor, test, chore

## Commande de test à utiliser

- php artisan test --filter=NomDuTest ← Laravel
- npx vitest run NomDuTest ← Vue/Vitest
- Toujours cibler un test précis, jamais toute la suite

## Ne jamais

- Committer du code sans tests associés
- Committer si un test échoue
- Faire un commit partiel d'une feature incomplète
