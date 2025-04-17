
    let courses = JSON.parse(localStorage.getItem("courses")) || [];
    let filteredCourses = [...courses];

    function formatDate(date) {
      const d = new Date(date);
      return `${d.getDate().toString().padStart(2, '0')}/${(d.getMonth() + 1).toString().padStart(2, '0')}/${d.getFullYear()}`;
    }

    function displayCourses() {
      const tbody = document.getElementById("courseTableBody");
      tbody.innerHTML = "";
      filteredCourses.forEach((course, index) => {
        const row = document.createElement("tr");
        row.innerHTML = `
          <td>${course.id}</td>
          <td>${course.nom}</td>
          <td>${course.description}</td>
          <td>${parseFloat(course.prix).toFixed(2)} </td>
          <td>${formatDate(course.date)}</td>
          <td>${course.cover ? `<img src="${course.cover}" class="preview-img">` : 'Aucune'}</td>
          <td>${course.export ? `<a href="${course.export}" target="_blank">📎</a>` : 'Aucun'}</td>
          <td class="actions">
            <button onclick="viewCourseDetails(${course.id})">👁️ Voir Détails</button>
            <button onclick="editCourse(${course.id})">✏️ Modifier</button>
            <button onclick="deleteCourse(${index})">🗑️ Supprimer</button>
            <button onclick="window.location.href='ressource.html?id=${course.id}';">📚 Voir les Ressources</button>
          </td>
        `;
        tbody.appendChild(row);
      });
    }

    function saveCourses() {
      localStorage.setItem("courses", JSON.stringify(courses));
    }

    document.getElementById("addCourseForm").addEventListener("submit", function(e) {
      e.preventDefault();
      const newCourse = {
        id: courses.length ? courses[courses.length - 1].id + 1 : 1,
        nom: document.getElementById("courseName").value,
        description: document.getElementById("courseDescription").value,
        prix: document.getElementById("coursePrix").value,
        cover: document.getElementById("courseImage").files[0] ? URL.createObjectURL(document.getElementById("courseImage").files[0]) : null,
        export: document.getElementById("courseExport").files[0] ? URL.createObjectURL(document.getElementById("courseExport").files[0]) : null,
        date: new Date(),
        resources: []  // Tableau vide pour les ressources
      };
      courses.push(newCourse);
      saveCourses();
      filteredCourses = [...courses];
      displayCourses();
      this.reset();
    });

    function deleteCourse(index) {
      courses.splice(index, 1);
      saveCourses();
      filteredCourses = [...courses];
      displayCourses();
    }

    function searchCourses() {
      const searchTerm = document.getElementById("searchInput").value.toLowerCase();
      filteredCourses = courses.filter(course =>
        course.nom.toLowerCase().includes(searchTerm) ||
        course.description.toLowerCase().includes(searchTerm)
      );
      displayCourses();
    }

    function sortCourses() {
      const sortCriteria = document.getElementById("sortCriteria").value;
      if (sortCriteria === "id") {
        filteredCourses.sort((a, b) => a.id - b.id);
      } else if (sortCriteria === "date") {
        filteredCourses.sort((a, b) => new Date(a.date) - new Date(b.date));
      }
      displayCourses();
    }

    function viewCourseDetails(courseId) {
      const course = courses.find(course => course.id === courseId);
      if (course) {
        document.getElementById("detailNom").textContent = course.nom;
        document.getElementById("detailDescription").textContent = course.description;
        document.getElementById("detailPrix").textContent = `${parseFloat(course.prix).toFixed(2)} €`;
        document.getElementById("detailDate").textContent = formatDate(course.date);
        document.getElementById("detailImage").src = course.cover || '';
        document.getElementById("detailExport").href = course.export || '#';
        document.getElementById("details").style.display = 'block';
      }
    }

    function editCourse(courseId) {
      const course = courses.find(course => course.id === courseId);
      if (course) {
        document.getElementById("courseName").value = course.nom;
        document.getElementById("courseDescription").value = course.description;
        document.getElementById("coursePrix").value = course.prix;
        // Ajout du bouton pour sauvegarder les modifications
      }
    }

    window.onload = displayCourses;
 