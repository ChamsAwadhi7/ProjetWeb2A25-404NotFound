document.getElementById("addUserForm").addEventListener("submit", function(e) {
    e.preventDefault();
  
    const id = document.getElementById("userId").value;
    const nom = document.getElementById("userName").value;
    const email = document.getElementById("userEmail").value;
    const role = document.getElementById("userRole").value;
  
    const table = document.getElementById("userTableBody");
    const row = table.insertRow();
  
    row.innerHTML = `
      <td>${id}</td>
      <td>${nom}</td>
      <td>${email}</td>
      <td>${role}</td>
      <td class="actions">
        <button onclick="deleteUser(this)">Supprimer</button>
      </td>
    `;
  
    document.getElementById("addUserForm").reset();
  });
  
  function deleteUser(btn) {
    const row = btn.parentNode.parentNode;
    row.remove();
  }
  
  function searchUsers() {
    const input = document.getElementById("searchInput").value.toLowerCase();
    const rows = document.getElementById("userTableBody").getElementsByTagName("tr");
  
    for (let i = 0; i < rows.length; i++) {
      let text = rows[i].innerText.toLowerCase();
      rows[i].style.display = text.includes(input) ? "" : "none";
    }
  }
  