// SOLUTION RADICALE : DÉSACTIVATION D'ALPINE.JS SI NÉCESSAIRE
// Ce script désactive Alpine.js temporairement pour éliminer les conflits

(function() {
    'use strict';
    
    console.log('🚨 DÉMARRAGE DU MODE DÉSACTIVATION ALPINE.JS');
    
    // Sauvegarder Alpine.js original
    const originalAlpine = window.Alpine;
    
    // Désactiver Alpine.js complètement
    if (window.Alpine) {
        console.log('⚠️ Alpine.js détecté - DÉSACTIVATION TEMPORAIRE');
        
        // Remplacer Alpine par un objet vide
        window.Alpine = {
            start: function() {
                console.log('🚫 Alpine.start() bloqué - mode désactivé');
            },
            stop: function() {
                console.log('🚫 Alpine.stop() bloqué - mode désactivé');
            },
            data: function() {
                console.log('🚫 Alpine.data() bloqué - mode désactivé');
            },
            directive: function() {
                console.log('🚫 Alpine.directive() bloqué - mode désactivé');
            },
            version: 'DISABLED'
        };
        
        // Désactiver toutes les fonctionnalités Alpine
        document.addEventListener('DOMContentLoaded', function() {
            // Supprimer tous les attributs Alpine des éléments
            const alpineElements = document.querySelectorAll('[x-data], [x-show], [x-if], [x-for], [x-text], [x-html], [x-model], [x-on], [x-bind]');
            
            alpineElements.forEach(element => {
                // Marquer comme désactivé
                element.dataset.alpineDisabled = 'true';
                
                // Supprimer les gestionnaires Alpine
                const attributes = element.attributes;
                for (let i = attributes.length - 1; i >= 0; i--) {
                    const attr = attributes[i];
                    if (attr.name.startsWith('x-') || attr.name.startsWith('@') || attr.name.startsWith(':')) {
                        element.removeAttribute(attr.name);
                    }
                }
            });
            
            console.log(`🚫 ${alpineElements.length} éléments Alpine désactivés`);
        });
        
        console.log('✅ Alpine.js désactivé temporairement');
    } else {
        console.log('✅ Alpine.js non détecté - aucun conflit possible');
    }
    
    // Fonction pour réactiver Alpine.js si nécessaire
    window.reactivateAlpine = function() {
        if (originalAlpine) {
            window.Alpine = originalAlpine;
            console.log('✅ Alpine.js réactivé');
            
            // Réactiver les éléments
            const disabledElements = document.querySelectorAll('[data-alpine-disabled="true"]');
            disabledElements.forEach(element => {
                element.removeAttribute('data-alpine-disabled');
            });
            
            // Redémarrer Alpine
            if (window.Alpine.start) {
                window.Alpine.start();
            }
        }
    };
    
    // Fonction pour vérifier l'état
    window.checkAlpineStatus = function() {
        console.log('🔍 État d\'Alpine.js:');
        console.log('- Alpine disponible:', !!window.Alpine);
        console.log('- Alpine version:', window.Alpine?.version || 'Inconnue');
        console.log('- Éléments Alpine désactivés:', document.querySelectorAll('[data-alpine-disabled="true"]').length);
    };
    
    console.log('✅ Script de désactivation Alpine.js chargé');
    console.log('💡 Utilisez reactivateAlpine() pour réactiver si nécessaire');
    
})();
