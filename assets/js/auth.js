document.addEventListener('DOMContentLoaded', function() {
    // Gestion de la mise à jour des rôles
    document.querySelectorAll('.update-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const userId = this.dataset.userId;
            const roleSelect = document.querySelector(`.role-select[data-user-id="${userId}"]`);
            const phoneInput = document.querySelector(`.admin-phone[data-user-id="${userId}"]`);
            const newRole = roleSelect.value;
            const phone = phoneInput.value;

            if (newRole === 'admin' && !validatePhone(phone)) {
                alert('Veuillez entrer un numéro de téléphone valide (8 chiffres) pour les administrateurs');
                return;
            }

            fetch('updateRole', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    user_id: userId,
                    new_role: newRole,
                    phone: phone
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Erreur: ' + (data.error || 'Mise à jour échouée'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Une erreur est survenue');
            });
        });
    });

    // Validation du formulaire
    const form = document.querySelector('.admin-recruitment form');
    if (form) {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validation des champs
            const fields = ['lastname', 'firstname', 'email', 'phone', 'password'];
            fields.forEach(field => {
                const input = document.getElementById(field);
                if (!validateField(input)) {
                    isValid = false;
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Veuillez corriger les erreurs dans le formulaire');
            }
        });
    }

    // Validation en temps réel
    document.querySelectorAll('.admin-recruitment input').forEach(input => {
        input.addEventListener('input', function() {
            validateField(this);
        });
    });

    // Fonctions utilitaires
    function validateField(input) {
        const value = input.value.trim();
        let isValid = true;
        const errorSpan = document.getElementById(`error-${input.id}`);

        // Supprimer anciennes erreurs
        input.classList.remove('invalid');
        if (errorSpan) errorSpan.textContent = '';

        switch(input.id) {
            case 'lastname':
            case 'firstname':
                if (value.length < 2) {
                    showError(input, 'Minimum 2 caractères');
                    isValid = false;
                }
                break;
                
            case 'email':
                if (!/^\S+@\S+\.\S+$/.test(value)) {
                    showError(input, 'Email invalide');
                    isValid = false;
                }
                break;
                
            case 'phone':
                if (!/^\d{8}$/.test(value.replace(/\D/g, ''))) {
                    showError(input, '8 chiffres requis');
                    isValid = false;
                }
                break;
                
            case 'password':
                if (value.length < 8) {
                    showError(input, '8 caractères minimum');
                    isValid = false;
                } else if (!/[A-Z]/.test(value) || !/[0-9]/.test(value)) {
                    showError(input, 'Majuscule et chiffre requis');
                    isValid = false;
                }
                break;
        }

        return isValid;
    }

    function showError(input, message) {
        input.classList.add('invalid');
        const errorSpan = document.getElementById(`error-${input.id}`);
        if (errorSpan) errorSpan.textContent = message;
    }

    function validatePhone(phone) {
        return /^\d{8}$/.test(phone.replace(/\D/g, ''));
    }
});