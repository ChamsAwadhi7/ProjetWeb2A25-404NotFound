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

  function getAIResponse(question) {
    let response = "I'm here to help! Can you be more specific?";
    if (question.includes("How can I turn my idea into a startup?")) {
      response = "Transforming your idea into a startup requires a clear process. Follow these steps:\n\n1️⃣ **Identify a Problem & Validate Your Idea** - Research your market, understand your audience, and confirm demand.\n2️⃣ **Create a Business Plan** - Define your vision, revenue model, and target audience.\n3️⃣ **Build a Strong Team** - Surround yourself with talented co-founders and experts.\n4️⃣ **Secure Funding** - Choose between bootstrapping, angel investors, venture capital, or crowdfunding.\n5️⃣ **Develop a Minimum Viable Product (MVP)** - Start small, test, and improve based on feedback.\n6️⃣ **Market & Launch** - Use branding, social media, and networking to attract users.\n7️⃣ **Scale & Grow** - Analyze performance, adapt, and expand wisely.\n\nWould you like guidance on a specific step?";
    } else if (question.includes("How can I attract investors for my business?")) {
      response = "💰 Attracting investors requires a solid strategy. Here are key steps:\n\n1️⃣ **Develop a Strong Business Plan** - Clearly outline your vision, revenue model, and growth potential.\n2️⃣ **Create a Pitch Deck** - Highlight your unique value proposition, market opportunity, and financial projections.\n3️⃣ **Build a Prototype or MVP** - Show traction by having a working model or early users.\n4️⃣ **Network & Attend Events** - Join startup incubators, pitch competitions, and investor meetups.\n5️⃣ **Leverage Social Proof** - Secure testimonials, media coverage, or early partnerships to gain credibility.\n6️⃣ **Target the Right Investors** - Research angel investors, venture capitalists, or crowdfunding platforms that align with your industry.\n7️⃣ **Demonstrate Scalability** - Show how your business can grow and provide a strong return on investment.\n\nWould you like help preparing a pitch or finding investors?";
    } else if (question.includes("can you tell me about your services")) {
      response = "💼 We offer consulting, funding, and networking. Need details?";
    } else if (question.includes("hey")) {
      response = "📌Hello Sir ! how can i help you ?";
    }//something about the company
    else if (question.includes("what is the company about")) {
      response = "📌We are a startup incubator that helps entrepreneurs turn their ideas into successful businesses. Our services include consulting, funding, and networking. How can I assist you today?"
    }//something about our courses
    else if (question.includes("what courses do you offer")) {
      response = "📚We offer a range of courses on entrepreneurship";
    }else if (question.includes("ou maram ")) {
      response = "bozma bottya";
    }else if (question.includes("chbih ibri")) {
      response = "bhim"
    };
    return response;
  }

  sendButton.addEventListener("click", function () {
    const userText = inputField.value.trim();
    if (userText === "") return;

    addMessage(userText, true);
    inputField.value = "";

    simulateTyping(() => {
      addMessage(getAIResponse(userText));
    });
  });

  inputField.addEventListener("keypress", function (event) {
    if (event.key === "Enter") {
      sendButton.click();
    }
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
document.addEventListener("DOMContentLoaded", function () {
  const seeMoreButtons = document.querySelectorAll(".see-more");
  const popup = document.getElementById("successPopup");
  const popupImage = popup.querySelector(".popup-image");
  const popupTitle = popup.querySelector(".popup-title");
  const popupText = popup.querySelector(".popup-text");
  const subscribeButton = popup.querySelector(".btn-contact");
  const closePopup = popup.querySelector(".close-popup");
  const subscriptionDateEl = document.getElementById("subscriptionDate");

  let selectedFormationId = null;
  let currentTitle = null;

  seeMoreButtons.forEach(button => {
    button.addEventListener("click", () => {
      const title = button.getAttribute("data-title");
      const desc = button.getAttribute("data-desc");
      const price = button.getAttribute("data-price");
      const image = button.getAttribute("data-image");
      selectedFormationId = button.getAttribute("data-formation-id") || null; // Adjust attribute name as needed
      currentTitle = title;

      // Populate popup content
      popupTitle.textContent = title;
      popupText.textContent = desc;
      popupImage.src = image;
      subscribeButton.textContent = `Subscribe for ${price} TND`;

      // Load stored subscription date from localStorage
      const storedDate = localStorage.getItem(`subscriptionDate_${title}`);
      if (subscriptionDateEl) {
        subscriptionDateEl.textContent = storedDate ? `Subscribed on: ${storedDate}` : "";
      }

      // Show/hide subscribe button based on subscription status
      subscribeButton.style.display = storedDate ? "none" : "inline-block";

      // Show the popup
      popup.style.display = "flex";
      document.body.style.overflow = "hidden";
    });
  });

  // Close popup when clicking X
  closePopup.addEventListener("click", () => {
    popup.style.display = "none";
    document.body.style.overflow = "auto";
  });

  // Close popup when clicking outside content
  popup.addEventListener("click", function (e) {
    if (e.target === popup) {
      popup.style.display = "none";
      document.body.style.overflow = "auto";
    }
  });

  // Close popup with Escape key
  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape" && popup.style.display === "flex") {
      popup.style.display = "none";
      document.body.style.overflow = "auto";
    }
  });


  // Subscribe logic
  subscribeButton.addEventListener("click", () => {
    popup.style.display = "none";
    document.getElementById("confirmSubscriptionPopup").style.display = "block";
  });

  document.getElementById("confirmYes").addEventListener("click", () => {
    document.getElementById("confirmSubscriptionPopup").style.display = "none";

    fetch("subscribe_handler.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ formation_id: selectedFormationId })
    })
      .then((response) => response.json())
      .then((data) => {
        alert(data.message);
      });
  });

  document.getElementById("confirmNo").addEventListener("click", () => {
    document.getElementById("confirmSubscriptionPopup").style.display = "none";
  });
});
document.querySelectorAll('.see-more').forEach(button => {
    button.addEventListener('click', function () {
        const title = this.getAttribute('data-title');
        const description = this.getAttribute('data-desc');
        const price = this.getAttribute('data-price');
        const imageSrc = this.getAttribute('data-image');

        console.log('See more clicked for widget: ' + title);

        // Populate the popup BEFORE referencing title
        document.querySelector('.popup-title').textContent = title;
        document.querySelector('.popup-text').textContent = description;
        document.querySelector('.popup-image').setAttribute('src', imageSrc);

        const subscriptionDateEl = document.getElementById('subscriptionDate');
        const subscribeBtn = document.querySelector('.btn-contact');

        console.log('Stored date from localStorage for ' + title + ': ' + localStorage.getItem(`subscriptionDate_${title}`));

        // Load stored date from localStorage
        const storedDate = localStorage.getItem(`subscriptionDate_${title}`);
        if (subscriptionDateEl) {
            if (storedDate) {
                console.log('Displaying stored date: ' + storedDate);
                subscriptionDateEl.textContent = `Subscribed on: ${storedDate}`;
            } else {
                console.log('No stored date available.');
                subscriptionDateEl.textContent = '';
            }
        }

        // Replace old click listeners
        const newBtn = subscribeBtn.cloneNode(true);
        subscribeBtn.parentNode.replaceChild(newBtn, subscribeBtn);

        // Show/hide button based on subscription
        newBtn.style.display = storedDate ? 'none' : 'inline-block';

        // Add working click listener for this widget
        newBtn.addEventListener('click', function () {
            const currentDate = new Date();
            const formattedDate = currentDate.toLocaleDateString('en-GB');

            console.log('Subscription clicked, saving date: ' + formattedDate);
            localStorage.setItem(`subscriptionDate_${title}`, formattedDate);

            if (subscriptionDateEl) {
                subscriptionDateEl.textContent = `Subscribed on: ${formattedDate}`;
                console.log('Updated subscription date in popup: ' + formattedDate);
            }

            newBtn.style.display = 'none';

            // AJAX Request to send subscription data to the server
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '/ProjetWeb/Controller/ParticipationController.php', true);  // Adjust this to your path
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    console.log('Subscription saved successfully.');
                    // Handle any server response if necessary
                } else {
                    console.error('Failed to save subscription.');
                }
            };
            xhr.send('title=' + encodeURIComponent(title) + '&date=' + encodeURIComponent(formattedDate));
        });

        // Show popup
        document.getElementById('successPopup').style.display = 'block';
    });
});

// Close popup logic
document.querySelector('.close-popup').addEventListener('click', function () {
    document.getElementById('successPopup').style.display = 'none';
});
seeMoreButtons.forEach(button => {
  button.addEventListener("click", () => {
      // Populate the popup content here
      document.getElementById('successPopup').style.display = 'flex'; // Show the popup
      document.body.style.overflow = 'hidden'; // Prevent background scroll
  });
});
/*document.getElementById('sortOptions').addEventListener('change', function() {
  if (this.value === 'az') {
      const widgetsContainer = document.querySelector('.widgets-container');
      const widgets = Array.from(widgetsContainer.children);
      
      // Sort widgets based on title
      widgets.sort((a, b) => {
          const titleA = a.querySelector('h3').textContent.toLowerCase();
          const titleB = b.querySelector('h3').textContent.toLowerCase();
          return titleA.localeCompare(titleB);
      });

      // Clear the container and append sorted widgets
      widgetsContainer.innerHTML = '';
      widgets.forEach(widget => widgetsContainer.appendChild(widget));
  }
});
document.addEventListener('DOMContentLoaded', function() {
  document.getElementById('searchButton').addEventListener('click', function() {
      const searchTerm = document.getElementById('searchBox').value.toLowerCase();
      const widgets = document.querySelectorAll('.widgets-container .widget');

      widgets.forEach(widget => {
          const title = widget.querySelector('h3').textContent.toLowerCase();
          if (title.includes(searchTerm)) {
              widget.style.display = 'block'; // Show matching widget
          } else {
              widget.style.display = 'none'; // Hide non-matching widget
          }
      });
  });

  document.getElementById('searchBox').addEventListener('input', function() {
      if (this.value === '') {
          const widgets = document.querySelectorAll('.widgets-container .widget');
          widgets.forEach(widget => {
              widget.style.display = 'block'; // Show all widgets
          });
      }
  });
});*/



