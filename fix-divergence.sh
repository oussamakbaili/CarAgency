#!/bin/bash

# Script simple pour résoudre le problème de divergence de branches
# Usage: ./fix-divergence.sh

set -e

echo "🔧 Résolution du problème de divergence de branches..."

# Récupérer les dernières modifications
echo "📥 Récupération depuis GitHub..."
git fetch origin

# Réinitialiser la branche locale pour correspondre exactement à origin/master
echo "🔄 Synchronisation avec origin/master..."
git reset --hard origin/master

# Nettoyer
echo "🧹 Nettoyage..."
git clean -fd

echo "✅ Problème résolu! Votre branche est maintenant synchronisée avec GitHub."
echo "📊 État actuel:"
git status

