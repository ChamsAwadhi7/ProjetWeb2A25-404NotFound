const  sideMenu = document.querySelector('aside');
const menuBtn = document.querySelector('#menu_bar');
const closeBtn = document.querySelector('#close_btn');


const themeToggler = document.querySelector('.theme-toggler');



menuBtn.addEventListener('click',()=>{
       sideMenu.style.display = "block"
})
closeBtn.addEventListener('click',()=>{
    sideMenu.style.display = "none"
})

themeToggler.addEventListener('click',()=>{
     document.body.classList.toggle('dark-theme-variables')
     themeToggler.querySelector('span:nth-child(1').classList.toggle('active')
     themeToggler.querySelector('span:nth-child(2').classList.toggle('active')
})
document.getElementById('sort-select').addEventListener('change', function() {
    const sortOrder = this.value;
    sortEvents(sortOrder);
  });
  
  function sortEvents(order) {
    // Assuming you have an array of events to sort
    const events = getEvents(); // This is a placeholder, replace with your event fetching method
    if (order === 'asc') {
      events.sort((a, b) => new Date(a.date) - new Date(b.date)); // Sort in ascending order
    } else {
      events.sort((a, b) => new Date(b.date) - new Date(a.date)); // Sort in descending order
    }
    renderEvents(events); // This is a placeholder function to render the events
  }

  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('event-form');
    const cancelBtn = document.getElementById('cancel-edit');
    
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            fetch(`index.php?action=get&id=${id}`)
                .then(response => response.json())
                .then(event => {
                    document.getElementById('event-id').value = event.id_event;
                    document.getElementById('event-name').value = event.nom_event;
                    document.getElementById('event-date').value = event.date_event;
                    document.getElementById('event-description').value = event.desc_event;
                    document.getElementById('event-location').value = event.lieu_event;
                    document.getElementById('form-title').textContent = 'Edit Event';
                    cancelBtn.style.display = 'inline-block';
                });
        });
    });
    
    // Cancel edit
    cancelBtn.addEventListener('click', function() {
        form.reset();
        document.getElementById('event-id').value = '';
        document.getElementById('form-title').textContent = 'Create Event';
        this.style.display = 'none';
    });

    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this event?')) {
                const id = this.getAttribute('data-id');
                fetch('index.php?action=delete', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `id=${id}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.closest('tr').remove();
                    }
                });
            }
        });
    });
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        
        fetch('index.php?action=save', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        });
    });
    
    const searchBox = document.getElementById('search-box');
    const sortSelect = document.getElementById('sort-select');
    
    function updateEvents() {
        const params = new URLSearchParams({
            search: searchBox.value,
            sort: sortSelect.value
        });
        window.location.href = `index.php?${params.toString()}`;
    }
    
    searchBox.addEventListener('keyup', debounce(updateEvents, 300));
    sortSelect.addEventListener('change', updateEvents);
    
    function debounce(func, wait) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }
});

document.getElementById('event-form').addEventListener('submit', function (e) {
    const nom = document.getElementById('nom').value.trim();
    const date = document.getElementById('date').value;
    const description = document.getElementById('desc').value.trim();
    const lieu = document.getElementById('lieu').value.trim();
    const image = document.getElementById('img').value;

    document.querySelectorAll('.error').forEach(el => el.textContent = '');

    let isValid = true;

    if (!nom) {
        document.getElementById('error-nom').textContent = "Le nom ne doit pas être vide.";
        isValid = false;
    } else {
        if (!/^[A-Z]/.test(nom)) {
            document.getElementById('error-nom').textContent = "Le nom doit commencer par une majuscule.";
            isValid = false;
        } else if (nom.length > 13) {
            document.getElementById('error-nom').textContent = "Le nom ne doit pas dépasser 12 caractères.";
            isValid = false;
        }
    }


    if (!date) {
        document.getElementById('error-date').textContent = "La date ne doit pas être vide.";
        isValid = false;
    } else {
        const today = new Date();
        const selectedDate = new Date(date);
        today.setHours(0, 0, 0, 0);
        if (selectedDate < today) {
            document.getElementById('error-date').textContent = "La date doit être aujourd'hui ou plus tard.";
            isValid = false;
        }
    }

    if (!description) {
        document.getElementById('error-desc').textContent = "La description ne doit pas être vide.";
        isValid = false;
    } else {
        if (!/^[A-Z]/.test(description[0]) && !/\.\s*[A-Z]/.test(description)) {
            document.getElementById('error-desc').textContent = "La description doit commencer par une majuscule ou en contenir après un point.";
            isValid = false;
        } else if (description.length > 900) {
            document.getElementById('error-desc').textContent = "La description ne doit pas dépasser 900 caractères.";
            isValid = false;
        }
    }

    if (!lieu) {
        document.getElementById('error-lieu').textContent = "Le lieu ne doit pas être vide.";
        isValid = false;
    } else {
        if (!/^[A-Z]/.test(lieu)) {
            document.getElementById('error-lieu').textContent = "Le lieu doit commencer par une majuscule.";
            isValid = false;
        } else if (!/,/.test(lieu)) {
            document.getElementById('error-lieu').textContent = "Le lieu doit contenir une virgule.";
            isValid = false;
        } else if (!/\d/.test(lieu)) {
            document.getElementById('error-lieu').textContent = "Le lieu doit contenir des chiffres.";
            isValid = false;
        }
    }

    if (!image) {
        document.getElementById('error-img').textContent = "Veuillez sélectionner une image.";
        isValid = false;
    }

    if (!isValid) {
        e.preventDefault();
    }
});




  