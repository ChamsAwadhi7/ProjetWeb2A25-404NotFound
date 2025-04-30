        // Animation des labels au chargement si champs pré-remplis
        document.querySelectorAll('.input-field input').forEach(input => {
            if(input.value) {
                input.nextElementSibling.style.fontSize = '0.8rem';
                input.nextElementSibling.style.top = '10px';
                input.nextElementSibling.style.transform = 'translateY(-120%)';
            }
        });