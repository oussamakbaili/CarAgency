# Guide de Déploiement - CarAgency

## Résolution du problème de divergence de branches

Après un force push sur GitHub, votre serveur peut avoir des branches divergentes. Utilisez le script de déploiement automatique pour résoudre ce problème.

### Solution Rapide (Recommandée)

Sur votre serveur SSH, exécutez simplement :

```bash
cd public_html
chmod +x deploy.sh
./deploy.sh
```

### Solution Manuelle

Si vous préférez faire manuellement :

```bash
cd public_html
git fetch origin
git reset --hard origin/master
git clean -fd
```

### Détails du Script de Déploiement

Le script `deploy.sh` effectue automatiquement :
1. ✅ Vérification de l'état du dépôt
2. ✅ Récupération des dernières modifications depuis GitHub
3. ✅ Sauvegarde des modifications locales non commitées (dans stash)
4. ✅ Synchronisation complète avec origin/master
5. ✅ Nettoyage des fichiers non trackés
6. ✅ Affichage du statut final

### Notes Importantes

- Le script sauvegarde automatiquement vos modifications locales avant de réinitialiser
- Si vous avez des modifications importantes, elles seront dans le stash Git
- Utilisez `git stash list` pour voir les sauvegardes
- Utilisez `git stash pop` pour réappliquer une sauvegarde

### En cas de problème

Si le script échoue, vérifiez :
1. Les permissions d'exécution : `chmod +x deploy.sh`
2. Que vous êtes dans le bon répertoire : `cd public_html`
3. La connexion à GitHub : `git fetch origin`

