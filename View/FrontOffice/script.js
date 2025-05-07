document.addEventListener("DOMContentLoaded", function () {
    const dropdowns = document.querySelectorAll(".dropdown");
  
    dropdowns.forEach((dropdown) => {
      const button = dropdown.querySelector(".dropbtn");
      const menu = dropdown.querySelector(".dropdown-menu");
  
      button.addEventListener("click", function (event) {
        event.stopPropagation(); // Prevent closing when clicking inside
        closeAllDropdowns();
        menu.classList.toggle("active");
      });
    });
  
    document.addEventListener("click", closeAllDropdowns);
    function closeAllDropdowns() {
      document.querySelectorAll(".dropdown-menu").forEach((menu) => {
        menu.classList.remove("active");
      });
    }
  });
  document.addEventListener("DOMContentLoaded", function () {
    const elements = document.querySelectorAll(".about-item, h2, p");
  
    function revealOnScroll() {
      elements.forEach((el) => {
        const rect = el.getBoundingClientRect();
        if (rect.top < window.innerHeight - 100) {
          el.classList.add("show");
        } else {
          el.classList.remove("show");
        }
      });
    }
  
    window.addEventListener("scroll", revealOnScroll);
    revealOnScroll(); // Trigger on page load
  });
  // JavaScript pour déclencher l'animation lorsque les images entrent dans la vue
  document.addEventListener('DOMContentLoaded', function () {
      const images = document.querySelectorAll('.image');
  
      // Initialisation de l'IntersectionObserver
      const observer = new IntersectionObserver((entries, observer) => {
          entries.forEach(entry => {
              if (entry.isIntersecting) {
                  entry.target.classList.add('show'); // Ajoute la classe 'show' pour animer l'image
                  observer.unobserve(entry.target); // Arrête d'observer l'élément une fois qu'il est visible
              }
          });
      }, {
          threshold: 0.5, // L'animation est déclenchée lorsque 50% de l'image est visible
      });
  
      // Observation de chaque image
      images.forEach(image => {
          observer.observe(image);
      });
  });
  document.addEventListener("DOMContentLoaded", function () {
    const chatBox = document.getElementById("chat-box");
    const inputField = document.getElementById("ai-chat-input");
    const sendButton = document.getElementById("send-ai-btn");
  
    function addMessage(content, isUser = false) {
      const messageDiv = document.createElement("div");
      messageDiv.classList.add(isUser ? "user-message" : "ai-message");
      messageDiv.textContent = content;
      chatBox.appendChild(messageDiv);
      chatBox.scrollTop = chatBox.scrollHeight;
    }
  
    function simulateTyping(callback) {
      const typingDiv = document.createElement("div");
      typingDiv.classList.add("ai-message", "typing");
      typingDiv.textContent = "AI is typing...";
      chatBox.appendChild(typingDiv);
      chatBox.scrollTop = chatBox.scrollHeight;
  
      setTimeout(() => {
        chatBox.removeChild(typingDiv);
        callback();
      }, 2000);
    }
  
    sendButton.addEventListener("click", function () {
      const userText = inputField.value.trim();
      if (userText === "") return;
    
      addMessage(userText, true);
      inputField.value = "";
    
      simulateTyping(() => {
        getAIResponse(userText, (reply) => {
          addMessage(reply);
        });
      });
    });
    
  
    
    
  });
  const cards = document.querySelectorAll('.card');
  const totalCards = cards.length;
  cards.forEach((card, index) => {
    card.style.setProperty('--index', index);
    card.style.setProperty('--quantity', totalCards);
  });
  document.addEventListener("DOMContentLoaded", function () {
    const counters = document.querySelectorAll(".count");
  
    function animateCounter(counter) {
        const target = +counter.getAttribute("data-target");
        let count = 0;
        const speed = target / 100; // Contrôle la vitesse de l'animation
  
        function updateCount() {
            if (count < target) {
                count += speed;
                counter.innerText = Math.floor(count);
                requestAnimationFrame(updateCount);
            } else {
                counter.innerText = target;
            }
        }
        updateCount();
    }
  
    const counterWrapper = document.querySelector(".counter-wrapper");
    let statsAnimated = false;
  
    function checkStats() {
        const position = counterWrapper.getBoundingClientRect().top;
        const screenHeight = window.innerHeight;
        if (position < screenHeight * 0.9 && !statsAnimated) {
            counters.forEach(counter => animateCounter(counter));
            statsAnimated = true;
        }
    }
  
    window.addEventListener("scroll", checkStats);
  });
  
  
  
  let next = document.querySelector('.custom-next');
  let prev = document.querySelector('.custom-prev');
  
  next.addEventListener('click', function() {
      let items = document.querySelectorAll('.custom-slide-item');
      document.querySelector('.custom-slider').appendChild(items[0]);
  });
  
  prev.addEventListener('click', function() {
      let items = document.querySelectorAll('.custom-slide-item');
      document.querySelector('.custom-slider').prepend(items[items.length - 1]);
  });
  
  
  
  
  
  document.addEventListener('DOMContentLoaded', function () {
    const seeMoreButtons = document.querySelectorAll('.seeMoreBtn');
    const modal = document.getElementById('eventModal');
    const closeModalBtn = document.querySelector('.close-btn');
  
    const eventTitle = document.getElementById('eventTitle');
    const eventLocation = document.getElementById('eventLocation');
    const eventTime = document.getElementById('eventTime');
    const eventDescription = document.getElementById('eventDescription');
    const eventImage = document.getElementById('eventImage');
  
    seeMoreButtons.forEach(button => {
      button.addEventListener('click', () => {
        eventTitle.textContent = button.dataset.title;
        eventLocation.textContent = button.dataset.location;
        eventTime.textContent = button.dataset.time; // Customize as needed
        eventDescription.textContent = button.dataset.description;
  
        // Set image source directly from data-image attribute
        eventImage.src = button.dataset.image;
  
        modal.style.display = 'block';
      });
    });
  
    closeModalBtn.addEventListener('click', () => {
      modal.style.display = 'none';
    });
  
    window.addEventListener('click', event => {
      if (event.target === modal) {
        modal.style.display = 'none';
      }
    });
  });
  button.addEventListener('click', () => {
    console.log("Title from dataset: ", button.dataset.title); // Debug
    eventTitle.textContent = button.dataset.title;
    eventLocation.textContent = button.dataset.location;
    eventTime.textContent = button.dataset.time; // Customize as needed
    eventDescription.textContent = button.dataset.description;
    eventImage.src = button.dataset.image; // Set image source directly from data-image attribute
  });
  