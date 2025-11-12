#!/bin/bash

# Script de déploiement professionnel pour CarAgency
# Résout automatiquement les problèmes de divergence de branches après force push

set -e  # Arrêter en cas d'erreur

echo "🚀 Démarrage du déploiement..."

# Aller dans le répertoire du projet
cd public_html || { echo "❌ Erreur: Répertoire public_html introuvable"; exit 1; }

# Afficher l'état actuel
echo "📊 État actuel du dépôt:"
git status

# Récupérer les dernières modifications depuis GitHub
echo "📥 Récupération des modifications depuis GitHub..."
git fetch origin

# Sauvegarder les modifications locales non commitées si elles existent
if ! git diff-index --quiet HEAD --; then
    echo "💾 Sauvegarde des modifications locales..."
    git stash save "Sauvegarde avant déploiement - $(date +%Y-%m-%d_%H-%M-%S)"
fi

# Réinitialiser la branche locale pour correspondre à origin/master
echo "🔄 Synchronisation avec origin/master..."
git reset --hard origin/master

# Nettoyer les fichiers non trackés si nécessaire
echo "🧹 Nettoyage des fichiers non trackés..."
git clean -fd

# Afficher le statut final
echo "✅ Déploiement terminé avec succès!"
echo "📊 État final:"
git status
echo "📝 Derniers commits:"
git log --oneline -5

# Réappliquer les modifications sauvegardées si elles existent
if git stash list | grep -q "Sauvegarde avant déploiement"; then
    echo "⚠️  Des modifications locales ont été sauvegardées dans le stash."
    echo "   Utilisez 'git stash pop' pour les réappliquer si nécessaire."
fi

echo "🎉 Déploiement réussi!"

