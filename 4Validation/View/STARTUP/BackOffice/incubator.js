document.addEventListener("DOMContentLoaded", function () {
  // Initialize Nitro form autofill
  const initNitroForm = () => {
    const nitroSelect = document.getElementById("nitro-name");
    const priceInput = document.getElementById("nitro-price");
    const periodInput = document.getElementById("nitro-period");

    if (nitroSelect && priceInput && periodInput) {
      const updateNitroDetails = () => {
        const selectedOption = nitroSelect.options[nitroSelect.selectedIndex];
        priceInput.value = selectedOption.getAttribute("data-price") || "";
        periodInput.value = selectedOption.getAttribute("data-period") || "";
      };

      nitroSelect.addEventListener("change", updateNitroDetails);
      
      // Set default on page load
      updateNitroDetails();
    }
  };

  // Sidebar Menu Controls
  const initSidebar = () => {
    const sideMenu = document.querySelector('aside');
    const menuBtn = document.querySelector('#menu_bar');
    const closeBtn = document.querySelector('#close_btn');

    if (menuBtn && closeBtn && sideMenu) {
      menuBtn.addEventListener('click', () => {
        sideMenu.style.display = "block";
        sideMenu.classList.add("active");
      });

      closeBtn.addEventListener('click', () => {
        sideMenu.style.display = "none";
        sideMenu.classList.remove("active");
      });
    }
  };

  // Theme Toggle
  const initThemeToggle = () => {
    const themeToggler = document.querySelector('.theme-toggler');
    if (themeToggler) {
      themeToggler.addEventListener('click', () => {
        document.body.classList.toggle('dark-theme-variables');
        localStorage.setItem('darkTheme', document.body.classList.contains('dark-theme-variables'));
        
        themeToggler.querySelector('span:nth-child(1)').classList.toggle('active');
        themeToggler.querySelector('span:nth-child(2)').classList.toggle('active');
      });

      // Load saved theme preference
      if (localStorage.getItem('darkTheme') === 'true') {
        document.body.classList.add('dark-theme-variables');
        themeToggler.querySelector('span:nth-child(1)').classList.remove('active');
        themeToggler.querySelector('span:nth-child(2)').classList.add('active');
      }
    }
  };

  // Content Toggle (Startups vs Incubators)
  const initContentToggle = () => {
    const incubatorBtn = document.getElementById("incubators-btn");
    const incubatorContent = document.querySelector(".incubator-content");
    const startupsBtn = document.getElementById("startups-btn");
    const startupContent = document.querySelector(".startup-content");

    if (incubatorBtn && incubatorContent && startupsBtn && startupContent) {
      incubatorBtn.addEventListener("click", function () {
        startupContent.style.display = "none";
        incubatorContent.style.display = "block";
        // Reset forms when switching views
        document.querySelectorAll('.form').forEach(form => form.style.display = "none");
        document.getElementById("modify-section").style.display = "none";
        document.getElementById("delete-section").style.display = "none";
      });

      startupsBtn.addEventListener("click", function () {
        incubatorContent.style.display = "none";
        startupContent.style.display = "block";
      });
    }
  };

  // Modal Controls
  const initModals = () => {
    const addBtn = document.getElementById("add-btn");
    const addModal = document.getElementById("add-modal");
    const closeModal = document.getElementById("close-modal");

    if (addBtn && addModal && closeModal) {
      addBtn.addEventListener("click", function () {
        addModal.style.display = "block";
      });

      closeModal.addEventListener("click", function () {
        addModal.style.display = "none";
      });

      window.addEventListener("click", function (event) {
        if (event.target === addModal) {
          addModal.style.display = "none";
        }
      });
    }

    // Form selection buttons
    const workshopBtn = document.getElementById("workshop-btn");
    const nitroBtn = document.getElementById("nitro-btn");
    const workspaceBtn = document.getElementById("workspace-btn");
    const workshopForm = document.getElementById("workshop-form");
    const nitroForm = document.getElementById("nitro-form");
    const workspaceForm = document.getElementById("workspace-form");

    if (workshopBtn && nitroBtn && workspaceBtn) {
      workshopBtn.addEventListener("click", function () {
        addModal.style.display = "none";
        workshopForm.style.display = "block";
        nitroForm.style.display = "none";
        workspaceForm.style.display = "none";
      });

      nitroBtn.addEventListener("click", function () {
        addModal.style.display = "none";
        nitroForm.style.display = "block";
        workshopForm.style.display = "none";
        workspaceForm.style.display = "none";
      });

      workspaceBtn.addEventListener("click", function () {
        addModal.style.display = "none";
        workspaceForm.style.display = "block";
        workshopForm.style.display = "none";
        nitroForm.style.display = "none";
      });
    }
  };

  // Modify Section Controls
  const initModifySection = () => {
    const modifyBtn = document.getElementById("modify-btn");
    const modifySection = document.getElementById("modify-section");

    if (modifyBtn && modifySection) {
      modifyBtn.addEventListener("click", () => {
        const isHidden = modifySection.style.display === "none";
        modifySection.style.display = isHidden ? "block" : "none";
        
        // Hide other sections when showing modify section
        if (isHidden) {
          document.querySelectorAll('.form').forEach(form => form.style.display = "none");
          document.getElementById("delete-section").style.display = "none";
        }
      });

      // Save buttons in modify section
      const saveButtons = document.querySelectorAll(".save-btn");
      saveButtons.forEach((btn) => {
        btn.addEventListener("click", (e) => {
          e.preventDefault();
          const row = e.target.closest("tr");
          if (row) {
            // Here you would typically send the updated data to the server
            alert("Modifications saved!");
          }
        });
      });
    }
  };

  // Delete Section Controls
  const initDeleteSection = () => {
    const deleteBtn = document.getElementById("delete-btn");
    const deleteSection = document.getElementById("delete-section");

    if (deleteBtn && deleteSection) {
      deleteBtn.addEventListener("click", () => {
        const isHidden = deleteSection.style.display === "none";
        deleteSection.style.display = isHidden ? "block" : "none";
        
        // Hide other sections when showing delete section
        if (isHidden) {
          document.querySelectorAll('.form').forEach(form => form.style.display = "none");
          document.getElementById("modify-section").style.display = "none";
        }
      });

      // Delete buttons in delete section
      document.addEventListener("click", function (e) {
        if (e.target && e.target.classList.contains("delete-btn")) {
          e.preventDefault();
          if (confirm("Are you sure you want to delete this item?")) {
            const row = e.target.closest("tr");
            if (row) {
              // Here you would typically send a delete request to the server
              row.remove();
              alert("Item deleted successfully!");
            }
          }
        }
      });
    }
  };

  // Form Submission Handling
  const initFormSubmissions = () => {
    document.querySelectorAll('form').forEach(form => {
      form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Basic form validation
        const inputs = this.querySelectorAll('input[required], select[required]');
        let isValid = true;
        
        inputs.forEach(input => {
          if (!input.value.trim()) {
            input.style.borderColor = 'red';
            isValid = false;
          } else {
            input.style.borderColor = '';
          }
        });
        
        if (isValid) {
          // In a real application, you would send the form data to the server here
          // For now, we'll just show a success message
          alert('Form submitted successfully!');
          this.reset();
          
          // Hide the form after submission
          if (this.id === 'workshop-form' || this.id === 'nitro-form' || this.id === 'workspace-form') {
            this.style.display = 'none';
          }
        } else {
          alert('Please fill in all required fields!');
        }
      });
    });
  };

  // Initialize all components
  initNitroForm();
  initSidebar();
  initThemeToggle();
  initContentToggle();
  initModals();
  initModifySection();
  initDeleteSection();
  initFormSubmissions();

  // Display success/error messages if they exist
  const alertMessages = document.querySelectorAll('.alert');
  if (alertMessages.length > 0) {
    alertMessages.forEach(alert => {
      setTimeout(() => {
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
      }, 3000);
    });
  }
});