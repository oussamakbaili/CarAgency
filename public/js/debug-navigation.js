// SCRIPT DE DÉBOGAGE POUR IDENTIFIER LE PROBLÈME DE DOUBLE-CLIC
// Ce script va nous dire exactement ce qui se passe lors des clics

(function() {
    'use strict';
    
    console.log('🔍 Démarrage du script de débogage de navigation...');
    
    // Compteur de clics
    let clickCount = 0;
    let lastClickTime = 0;
    
    // Intercepter TOUS les événements de clic
    document.addEventListener('click', function(event) {
        clickCount++;
        const currentTime = Date.now();
        const timeSinceLastClick = currentTime - lastClickTime;
        
        console.log(`🎯 CLIC #${clickCount} détecté:`, {
            target: event.target,
            tagName: event.target.tagName,
            className: event.target.className,
            href: event.target.href || event.target.closest('a')?.href,
            onclick: event.target.onclick ? 'OUI' : 'NON',
            timeSinceLastClick: timeSinceLastClick + 'ms',
            timestamp: new Date().toLocaleTimeString()
        });
        
        // Vérifier si c'est un double-clic rapide
        if (timeSinceLastClick < 500) {
            console.warn('⚠️ DOUBLE-CLIC RAPIDE DÉTECTÉ !', {
                timeBetweenClicks: timeSinceLastClick + 'ms',
                target: event.target
            });
        }
        
        lastClickTime = currentTime;
        
        // Vérifier les gestionnaires d'événements attachés
        const listeners = getEventListeners ? getEventListeners(event.target) : 'Non disponible';
        console.log('👂 Gestionnaires d\'événements:', listeners);
        
    }, true);
    
    // Vérifier Alpine.js
    console.log('🔍 Vérification d\'Alpine.js:');
    console.log('- Alpine disponible:', !!window.Alpine);
    console.log('- Alpine version:', window.Alpine?.version || 'Inconnue');
    
    // Vérifier les scripts chargés
    console.log('🔍 Scripts chargés:');
    const scripts = document.querySelectorAll('script[src]');
    scripts.forEach((script, index) => {
        console.log(`- Script ${index + 1}:`, script.src);
    });
    
    // Vérifier les conflits potentiels
    setTimeout(() => {
        console.log('🔍 Vérification des conflits après 2 secondes...');
        
        // Vérifier les gestionnaires de clic sur les liens
        const links = document.querySelectorAll('a[href]');
        links.forEach((link, index) => {
            if (index < 5) { // Limiter l'affichage
                console.log(`- Lien ${index + 1}:`, {
                    href: link.href,
                    onclick: link.onclick ? 'OUI' : 'NON',
                    addEventListener: link.onclick ? 'POSSIBLE' : 'NON'
                });
            }
        });
        
        // Vérifier les éléments avec onclick
        const elementsWithOnclick = document.querySelectorAll('[onclick]');
        console.log(`- Éléments avec onclick: ${elementsWithOnclick.length}`);
        
    }, 2000);
    
    console.log('✅ Script de débogage chargé - Ouvrez la console pour voir les détails');
    
})();
