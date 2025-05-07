// ------- Variables et sélections DOM
const stars = document.querySelectorAll('.star');
const noteInput = document.getElementById('note');
const courseId = parseInt(document.getElementById('courseId').value); // On utilise un input caché pour le passer proprement
const commentForm = document.getElementById('commentForm');

// ------- Gestion des étoiles de notation
stars.forEach(star => {
    star.addEventListener('click', function() {
        const value = this.getAttribute('data-value');
        noteInput.value = value;
        stars.forEach(s => s.classList.remove('selected'));
        for (let i = 0; i < value; i++) {
            stars[i].classList.add('selected');
        }
    });
});

// ------- Coche/décoche des chapitres
document.querySelectorAll('.check-chapitre').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const icon = this.nextElementSibling;
        if (this.checked) {
            icon.style.color = '#fff';
            icon.style.backgroundColor = '#2ecc71';
            icon.style.borderColor = '#2ecc71';
        } else {
            icon.style.color = 'transparent';
            icon.style.backgroundColor = 'transparent';
            icon.style.borderColor = 'gray';
        }
    });
});

// ------- Validation formulaire commentaire
if (commentForm) {
    commentForm.addEventListener('submit', function(e) {
        const pseudo = document.getElementById('pseudo').value.trim();
        const contenu = document.getElementById('contenu').value.trim();

        if (pseudo === '' || contenu === '') {
            e.preventDefault();
            alert('Veuillez remplir tous les champs avant d\'envoyer votre commentaire.');
        }
    });
}

// ------- React pour chaque commentaire
function CommentApp({ commentId }) {
    return (
        <div className="reactions">
            <button>😀</button>
            <button>😢</button>
            <button>😡</button>
            <button>😱</button>
            <button>😍</button>
        </div>
    );
}

// ------- Montage de React pour chaque commentaire
document.querySelectorAll('[id^="comment-react-app-"]').forEach(container => {
    const id = container.id.split('-').pop();
    const root = ReactDOM.createRoot(container);
    root.render(<CommentApp commentId={id} />);
});
