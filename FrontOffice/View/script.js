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

// Check login status when the page loads
/*function checkLogin() {
  let isLoggedIn = localStorage.getItem("loggedIn");

  if (isLoggedIn === "true") {
    enableApplyButtons();
    document.getElementById("loginBtn").style.display = "none";
    document.getElementById("logoutBtn").style.display = "inline";
  } else {
    disableApplyButtons();
    document.getElementById("loginBtn").style.display = "inline";
    document.getElementById("logoutBtn").style.display = "none";
  }
}

// Disable the "Apply" buttons if not logged in
function disableApplyButtons() {
  const applyButtons = document.querySelectorAll(".applyBtn");
  applyButtons.forEach((button) => {
    button.disabled = true;
  });
}

// Enable the "Apply" buttons if logged in
function enableApplyButtons() {
  const applyButtons = document.querySelectorAll(".applyBtn");
  applyButtons.forEach((button) => {
    button.disabled = false;

    // Show a message when a logged-in user applies for a job
    button.addEventListener("click", function () {
      alert("You have applied for this job!");
    });
  });
}

// Handle login
document.getElementById("loginBtn").addEventListener("click", function () {
  localStorage.setItem("loggedIn", "true");
  alert("You are now logged in!");
  checkLogin();
});

// Handle logout
document.getElementById("logoutBtn").addEventListener("click", function () {
  localStorage.removeItem("loggedIn");
  alert("You have logged out!");
  checkLogin();
});

// Run check on page load
checkLogin();*/
