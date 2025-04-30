	const signUpButton = document.getElementById('signUp');
	const signInButton = document.getElementById('signIn');
	const container = document.getElementById('container');

	signUpButton.addEventListener('click', () => {
	container.classList.add("right-panel-active");
	});

	signInButton.addEventListener('click', () => {
	container.classList.remove("right-panel-active");
	}); 
	document.addEventListener("DOMContentLoaded", function () {
		const signInButton = document.querySelector(".sign-in-container button");
		const emailInput = document.querySelector(".sign-in-container input[type='email']");
		const passwordInput = document.querySelector(".sign-in-container input[type='password']");
	  
		signInButton.addEventListener("click", function (e) {
		  e.preventDefault();
	  
		  const email = emailInput.value;
		  const password = passwordInput.value;
	  
		  // Vérifie les identifiants (exemple simple)
		  if (email === "admin@nextstep.com" && password === "admin") {
			localStorage.setItem("loggedIn", "true");
			window.location.href = "index.html";
		  } else {
			alert("Identifiants incorrects !");
		  }
		});
});
if (localStorage.getItem("loggedIn") !== "true") {
    window.location.href = "login.html";
}
	  
