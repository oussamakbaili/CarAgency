/**
 * Agency Form Validation
 * Professional client-side validation for agency forms
 */

// Car Form Validation
document.addEventListener('DOMContentLoaded', function() {
    // Car Create/Edit Form
    const carForm = document.querySelector('form[action*="cars"]');
    if (carForm) {
        carForm.addEventListener('submit', function(e) {
            if (!validateCarForm()) {
                e.preventDefault();
                return false;
            }
        });
        
        // Real-time validation
        const priceInput = document.querySelector('input[name="price_per_day"]');
        if (priceInput) {
            priceInput.addEventListener('input', function() {
                validatePrice(this);
            });
        }
        
        const yearInput = document.querySelector('input[name="year"]');
        if (yearInput) {
            yearInput.addEventListener('input', function() {
                validateYear(this);
            });
        }
        
        const stockInput = document.querySelector('input[name="stock_quantity"]');
        if (stockInput) {
            stockInput.addEventListener('input', function() {
                validateStock(this);
            });
        }
        
        const picturesInput = document.querySelector('input[name="pictures[]"]');
        if (picturesInput) {
            picturesInput.addEventListener('change', function() {
                validatePictures(this);
            });
        }
    }
    
    // Pricing Form Validation
    const pricingForm = document.querySelector('form[action*="pricing"]');
    if (pricingForm) {
        pricingForm.addEventListener('submit', function(e) {
            if (!validatePricingForm()) {
                e.preventDefault();
                return false;
            }
        });
    }
    
    // Maintenance Form Validation
    const maintenanceForm = document.querySelector('form[action*="maintenance"]');
    if (maintenanceForm) {
        maintenanceForm.addEventListener('submit', function(e) {
            if (!validateMaintenanceForm()) {
                e.preventDefault();
                return false;
            }
        });
    }
});

// Car Form Validation Function
function validateCarForm() {
    let isValid = true;
    clearErrors();
    
    // Brand validation
    const brand = document.querySelector('input[name="brand"]');
    if (brand && brand.value.trim().length < 2) {
        showError(brand, 'La marque doit contenir au moins 2 caractères');
        isValid = false;
    }
    
    // Model validation
    const model = document.querySelector('input[name="model"]');
    if (model && model.value.trim().length < 2) {
        showError(model, 'Le modèle doit contenir au moins 2 caractères');
        isValid = false;
    }
    
    // Registration number validation
    const registration = document.querySelector('input[name="registration_number"]');
    if (registration && registration.value.trim().length < 5) {
        showError(registration, 'Numéro d\'immatriculation invalide');
        isValid = false;
    }
    
    // Year validation
    const year = document.querySelector('input[name="year"]');
    if (!validateYear(year)) {
        isValid = false;
    }
    
    // Price validation
    const price = document.querySelector('input[name="price_per_day"]');
    if (!validatePrice(price)) {
        isValid = false;
    }
    
    // Stock validation
    const stock = document.querySelector('input[name="stock_quantity"]');
    if (!validateStock(stock)) {
        isValid = false;
    }
    
    // Pictures validation (only for create)
    const pictures = document.querySelector('input[name="pictures[]"]');
    if (pictures && pictures.hasAttribute('required')) {
        if (!validatePictures(pictures)) {
            isValid = false;
        }
    }
    
    return isValid;
}

// Year validation
function validateYear(input) {
    if (!input) return true;
    
    const year = parseInt(input.value);
    const currentYear = new Date().getFullYear();
    const minYear = 1900;
    
    if (year < minYear || year > currentYear + 1) {
        showError(input, `L'année doit être entre ${minYear} et ${currentYear + 1}`);
        return false;
    }
    
    hideError(input);
    return true;
}

// Price validation
function validatePrice(input) {
    if (!input) return true;
    
    const price = parseFloat(input.value);
    
    if (isNaN(price) || price <= 0) {
        showError(input, 'Le prix doit être supérieur à 0');
        return false;
    }
    
    if (price > 10000) {
        showError(input, 'Le prix semble trop élevé (max 10,000€)');
        return false;
    }
    
    hideError(input);
    return true;
}

// Stock validation
function validateStock(input) {
    if (!input) return true;
    
    const stock = parseInt(input.value);
    
    if (isNaN(stock) || stock < 1) {
        showError(input, 'Le stock doit être d\'au moins 1');
        return false;
    }
    
    if (stock > 1000) {
        showError(input, 'Le stock semble trop élevé (max 1,000)');
        return false;
    }
    
    hideError(input);
    return true;
}

// Pictures validation
function validatePictures(input) {
    if (!input) return true;
    
    const files = input.files;
    
    if (files.length === 0 && input.hasAttribute('required')) {
        showError(input, 'Veuillez ajouter au moins une photo');
        return false;
    }
    
    if (files.length > 4) {
        showError(input, 'Maximum 4 photos autorisées');
        return false;
    }
    
    // Validate file types and sizes
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    const maxSize = 2 * 1024 * 1024; // 2MB
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        
        if (!allowedTypes.includes(file.type)) {
            showError(input, 'Format de fichier non autorisé (JPG, PNG, GIF uniquement)');
            return false;
        }
        
        if (file.size > maxSize) {
            showError(input, `${file.name} est trop volumineux (max 2MB)`);
            return false;
        }
    }
    
    hideError(input);
    return true;
}

// Pricing Form Validation
function validatePricingForm() {
    let isValid = true;
    clearErrors();
    
    const price = document.querySelector('input[name="price_per_day"]');
    if (price && !validatePrice(price)) {
        isValid = false;
    }
    
    const multiplier = document.querySelector('input[name="seasonal_multiplier"]');
    if (multiplier) {
        const value = parseFloat(multiplier.value);
        if (isNaN(value) || value < 0.1 || value > 3) {
            showError(multiplier, 'Le multiplicateur doit être entre 0.1 et 3');
            isValid = false;
        }
    }
    
    const reason = document.querySelector('textarea[name="reason"], input[name="reason"]');
    if (reason && reason.value.trim().length < 10) {
        showError(reason, 'La raison doit contenir au moins 10 caractères');
        isValid = false;
    }
    
    return isValid;
}

// Maintenance Form Validation
function validateMaintenanceForm() {
    let isValid = true;
    clearErrors();
    
    const scheduledDate = document.querySelector('input[name="scheduled_date"]');
    if (scheduledDate) {
        const date = new Date(scheduledDate.value);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (date < today) {
            showError(scheduledDate, 'La date de maintenance ne peut pas être dans le passé');
            isValid = false;
        }
    }
    
    const cost = document.querySelector('input[name="cost"]');
    if (cost) {
        const value = parseFloat(cost.value);
        if (value && (isNaN(value) || value < 0)) {
            showError(cost, 'Le coût doit être positif');
            isValid = false;
        }
    }
    
    const description = document.querySelector('textarea[name="description"]');
    if (description && description.value.trim().length < 10) {
        showError(description, 'La description doit contenir au moins 10 caractères');
        isValid = false;
    }
    
    return isValid;
}

// Show error message
function showError(input, message) {
    // Remove existing error
    hideError(input);
    
    // Add error class to input
    input.classList.add('border-red-500', 'border-2');
    input.classList.remove('border-gray-300');
    
    // Create error message element
    const errorDiv = document.createElement('div');
    errorDiv.className = 'text-red-600 text-sm mt-1 validation-error';
    errorDiv.textContent = message;
    
    // Insert error message after input
    input.parentNode.insertBefore(errorDiv, input.nextSibling);
    
    // Scroll to first error
    if (document.querySelectorAll('.validation-error').length === 1) {
        input.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
}

// Hide error message
function hideError(input) {
    if (!input) return;
    
    // Remove error class from input
    input.classList.remove('border-red-500', 'border-2');
    input.classList.add('border-gray-300');
    
    // Remove error message
    const errorDiv = input.parentNode.querySelector('.validation-error');
    if (errorDiv) {
        errorDiv.remove();
    }
}

// Clear all errors
function clearErrors() {
    const errors = document.querySelectorAll('.validation-error');
    errors.forEach(error => error.remove());
    
    const errorInputs = document.querySelectorAll('.border-red-500');
    errorInputs.forEach(input => {
        input.classList.remove('border-red-500', 'border-2');
        input.classList.add('border-gray-300');
    });
}

// Show success message
function showSuccessMessage(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-lg z-50';
    successDiv.innerHTML = `
        <div class="flex items-center">
            <svg class="w-6 h-6 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="font-medium">${message}</p>
        </div>
    `;
    
    document.body.appendChild(successDiv);
    
    // Remove after 5 seconds
    setTimeout(() => {
        successDiv.remove();
    }, 5000);
}

