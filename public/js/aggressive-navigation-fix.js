// SOLUTION AGRESSIVE POUR CORRIGER LE PROBLÈME DE DOUBLE-CLIC
// Ce script force la navigation et élimine tous les conflits possibles

(function() {
    'use strict';
    
    console.log('🚀 Loading aggressive navigation fix...');
    
    // Désactiver temporairement Alpine.js si nécessaire
    if (window.Alpine) {
        console.log('⚠️ Alpine.js détecté, vérification des conflits...');
    }
    
    // Force la navigation immédiate
    function forceNavigation(url) {
        console.log('🔗 Force navigation vers:', url);
        
        // Empêcher toute autre action
        event.stopImmediatePropagation();
        event.preventDefault();
        
        // Navigation immédiate
        window.location.href = url;
    }
    
    // Intercepter TOUS les clics sur les liens
    document.addEventListener('click', function(event) {
        const target = event.target.closest('a[href]');
        
        if (target && target.href) {
            console.log('🎯 Clic détecté sur lien:', target.href);
            
            // Vérifier si c'est un lien interne
            if (target.href.includes(window.location.origin)) {
                console.log('✅ Lien interne détecté, navigation forcée');
                
                // Marquer comme en cours de navigation
                target.dataset.navigating = 'true';
                target.style.pointerEvents = 'none';
                
                // Navigation immédiate
                setTimeout(() => {
                    window.location.href = target.href;
                }, 50);
                
                event.preventDefault();
                event.stopPropagation();
                return false;
            }
        }
    }, true); // Capture phase pour intercepter avant tout le reste
    
    // Intercepter les clics sur les boutons avec onclick
    document.addEventListener('click', function(event) {
        const target = event.target.closest('button[onclick], *[onclick]');
        
        if (target && target.onclick) {
            console.log('🎯 Clic détecté sur élément avec onclick:', target);
            
            // Laisser le onclick se déclencher mais empêcher les conflits
            target.dataset.clicked = 'true';
            
            setTimeout(() => {
                target.dataset.clicked = 'false';
            }, 1000);
        }
    }, true);
    
    // Désactiver les gestionnaires de clic problématiques
    function disableProblematicHandlers() {
        // Désactiver les gestionnaires de clic sur les lignes de tableau
        const tableRows = document.querySelectorAll('tr[onclick], .clickable-row');
        tableRows.forEach(row => {
            if (row.dataset.originalOnclick) return; // Déjà traité
            
            row.dataset.originalOnclick = row.onclick ? row.onclick.toString() : '';
            row.onclick = function(event) {
                console.log('🔧 Gestionnaire de clic de ligne intercepté');
                
                // Empêcher la propagation
                event.stopImmediatePropagation();
                
                // Exécuter l'action originale si elle existe
                if (row.dataset.originalOnclick) {
                    try {
                        eval(row.dataset.originalOnclick);
                    } catch (e) {
                        console.error('Erreur lors de l\'exécution du onclick original:', e);
                    }
                }
            };
        });
        
        console.log('✅ Gestionnaires problématiques désactivés');
    }
    
    // Appliquer les corrections dès que possible
    function applyFixes() {
        console.log('🔧 Application des corrections...');
        
        // Désactiver les gestionnaires problématiques
        disableProblematicHandlers();
        
        // Forcer le style des liens
        const links = document.querySelectorAll('a[href]');
        links.forEach(link => {
            link.style.cursor = 'pointer';
            link.style.pointerEvents = 'auto';
        });
        
        console.log('✅ Corrections appliquées');
    }
    
    // Appliquer immédiatement si le DOM est prêt
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', applyFixes);
    } else {
        applyFixes();
    }
    
    // Réappliquer après un délai pour les éléments chargés dynamiquement
    setTimeout(applyFixes, 1000);
    setTimeout(applyFixes, 3000);
    
    console.log('✅ Script de correction agressive chargé');
    
})();
