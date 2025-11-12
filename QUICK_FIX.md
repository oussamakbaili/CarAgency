# Solution Rapide - Problème de Divergence de Branches

## Le Problème
```
fatal: Need to specify how to reconcile divergent branches.
```

## Solution Immédiate (1 commande)

Sur votre serveur SSH, exécutez :

```bash
cd public_html && git fetch origin && git reset --hard origin/master
```

## Solution avec Script

1. Téléchargez le script de correction :
```bash
cd public_html
git fetch origin
git checkout origin/master -- fix-divergence.sh
chmod +x fix-divergence.sh
```

2. Exécutez le script :
```bash
./fix-divergence.sh
```

## Explication

Après un force push sur GitHub, votre serveur a un historique différent. La commande `git reset --hard origin/master` synchronise complètement votre serveur avec GitHub, en écrasant l'historique local.

⚠️ **Attention** : Cette commande supprime toutes les modifications locales non commitées. Si vous avez des modifications importantes, sauvegardez-les d'abord avec `git stash`.

