document.addEventListener("DOMContentLoaded", () => {
  // Sidebar toggle
  const sideMenu = document.querySelector("aside");
  const menuBtn = document.querySelector("#menu_bar");
  const closeBtn = document.querySelector("#close_btn");

  menuBtn.addEventListener("click", () => {
    sideMenu.style.display = "block";
  });

  closeBtn.addEventListener("click", () => {
    sideMenu.style.display = "none";
  });

  // Theme toggler
  const themeToggler = document.querySelector(".theme-toggler");
  themeToggler.addEventListener("click", () => {
    document.body.classList.toggle("dark-theme-variables");
    themeToggler.querySelector("span:nth-child(1)").classList.toggle("active");
    themeToggler.querySelector("span:nth-child(2)").classList.toggle("active");
  });

  // Sections
  const incubatorBtn = document.getElementById("incubators-btn");
  const startupBtn = document.getElementById("startups-btn");
  const formationBtn = document.getElementById("formations-btn");

  const incubatorContent = document.querySelector(".incubator-content");
  const startupContent = document.querySelector(".startup-content");
  const formationContent = document.querySelector(".formation-content");

  const incubatorButtons = incubatorContent.querySelector(".actions");
  const otherIncubatorSections = incubatorContent.querySelectorAll(
    ".form, .table-container, #modify-section, #delete-section"
  );

  incubatorBtn.addEventListener("click", () => {
    incubatorButtons.style.display = "block";
    otherIncubatorSections.forEach(section => section.style.display = "none");
    startupContent.style.display = "none";
    formationContent.style.display = "none";
    incubatorContent.style.display = "block";
  });

  startupBtn.addEventListener("click", () => {
    incubatorContent.style.display = "none";
    formationContent.style.display = "none";
    startupContent.style.display = "block";
  });

  formationBtn.addEventListener("click", () => {
    incubatorContent.style.display = "none";
    startupContent.style.display = "none";
    formationContent.style.display = "block";
  });

  // Add Incubator Modal
  const addBtn = document.getElementById("add-btn");
  const addModal = document.getElementById("add-modal");
  const closeModal = document.getElementById("close-modal");

  addBtn.addEventListener("click", () => {
    addModal.style.display = "block";
  });

  closeModal.addEventListener("click", () => {
    addModal.style.display = "none";
  });

  window.addEventListener("click", (event) => {
    if (event.target === addModal) {
      addModal.style.display = "none";
    }
  });

  // Add Incubator Modal Forms Toggle
  const workshopBtn = document.getElementById("workshop-btn");
  const nitroBtn = document.getElementById("nitro-btn");
  const workspaceBtn = document.getElementById("workspace-btn");

  const workshopForm = document.getElementById("workshop-form");
  const nitroForm = document.getElementById("nitro-form");
  const workspaceForm = document.getElementById("workspace-form");

  workshopBtn.addEventListener("click", () => {
    workshopForm.style.display = "block";
    nitroForm.style.display = "none";
    workspaceForm.style.display = "none";
  });

  nitroBtn.addEventListener("click", () => {
    nitroForm.style.display = "block";
    workshopForm.style.display = "none";
    workspaceForm.style.display = "none";
  });

  workspaceBtn.addEventListener("click", () => {
    workspaceForm.style.display = "block";
    workshopForm.style.display = "none";
    nitroForm.style.display = "none";
  });

  // Nitro selection update
  const nitroName = document.getElementById("nitro-name");
  const nitroPrice = document.getElementById("nitro-price");
  const nitroPeriod = document.getElementById("nitro-period");

  function updateNitroDetails() {
    const selectedOption = nitroName.options[nitroName.selectedIndex];
    nitroPrice.value = selectedOption.getAttribute("data-price");
    nitroPeriod.value = selectedOption.getAttribute("data-period");
  }

  updateNitroDetails();
  nitroName.addEventListener("change", updateNitroDetails);

  // Modify section
  const modifyBtn = document.getElementById("modify-btn");
  const modifySection = document.getElementById("modify-section");

  modifyBtn.addEventListener("click", () => {
    modifySection.style.display = modifySection.style.display === "none" ? "block" : "none";
  });

  const saveButtons = document.querySelectorAll(".save-btn");
  saveButtons.forEach((btn) => {
    btn.addEventListener("click", () => {
      alert("Modifications saved!");
    });
  });

  // Delete section
  const deleteBtn = document.getElementById("delete-btn");
  const deleteSection = document.getElementById("delete-section");

  deleteBtn.addEventListener("click", () => {
    const otherSections = document.querySelectorAll(".form, .table-container, #modify-section");
    otherSections.forEach(section => section.style.display = "none");
    deleteSection.style.display = deleteSection.style.display === "none" ? "block" : "none";
  });

  document.addEventListener("click", (e) => {
    if (e.target && e.target.classList.contains("delete-btn")) {
      const row = e.target.closest("tr");
      if (row) row.remove();
    }
  });

  // Formation modal logic
  const addFormationBtn = document.getElementById("add-formation-btn");
  const closeFormationModal = document.getElementById("close-formation-modal");
  const modifyFormationBtn = document.getElementById("modify-formation-btn");
  const deleteFormationBtn = document.getElementById("delete-formation-btn");

  const addFormationModal = document.getElementById("add-formation-modal");
  const modifyFormationSection = document.getElementById("modify-formation-section");
  const deleteFormationSection = document.getElementById("delete-formation-section");

  addFormationBtn.addEventListener("click", () => {
    addFormationModal.style.display = "block";
  });

  closeFormationModal.addEventListener("click", () => {
    addFormationModal.style.display = "none";
  });

  modifyFormationBtn.addEventListener("click", () => {
    modifyFormationSection.style.display = "block";
    deleteFormationSection.style.display = "none";
  });

  deleteFormationBtn.addEventListener("click", () => {
    deleteFormationSection.style.display = "block";
    modifyFormationSection.style.display = "none";
  });
});
document.addEventListener("DOMContentLoaded", function () {
  const submitBtn = document.getElementById("submit-formation");

  if (submitBtn) {
    submitBtn.addEventListener("click", function (event) {
      event.preventDefault(); // prevents page refresh if inside a <form>

      const name = document.getElementById("formation-name").value.trim();
      const date = document.getElementById("formation-date").value.trim();

      if (!name || !date) {
        alert("Please fill out all fields.");
        return;
      }

      const table = document.getElementById("formation-table-body");

      if (!table) {
        alert("Table body not found!");
        return;
      }

      const newRow = document.createElement("tr");
      newRow.innerHTML = `
        <td>${name}</td>
        <td>${date}</td>
        <td><button class="delete-btn">Delete</button></td>
      `;
      table.appendChild(newRow);

      // Reset inputs
      document.getElementById("formation-name").value = "";
      document.getElementById("formation-date").value = "";
      document.getElementById("add-formation-modal").style.display = "none";

      alert("Formation added!");
    });
  }
  
});

