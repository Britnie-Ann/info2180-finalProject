document.addEventListener("DOMContentLoaded", function () {
  const addNoteForm = document.querySelector("#addNoteForm");
  const notesContainer = document.querySelector("#notesContainer");
  const addUserForm = document.querySelector("#addUserForm");
  const usersTextContainer = document.querySelector("#usersTextContainer");

  if (addNoteForm){
    addNoteForm.addEventListener("submit", function (event) {
      event.preventDefault();

      const formData = new FormData(addNoteForm);

      fetch("add_notes.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            notesContainer.innerHTML = data.html;
            addNoteForm.reset();
          } else if (data.error) {
            alert("Error: " + data.error);
          }
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("An unexpected error occurred.");
        });
    });
  }

  if (addUserForm){
    addUserForm.addEventListener("submit", function (event) {
      event.preventDefault();

      const formData = new FormData(addUserForm);

      fetch("../php/add_users.php", {
        method: "POST",
        body: formData,
      })
        .then((response) => response.json())
        .then((data) => {
          usersTextContainer.innerHTML = data.html;
          usersTextContainer.style.display = "block";
          loadUserPage(data);
        })  
        .catch((error) => {
          console.error("Error:", error);
          alert("An unexpected error occurred.");
        });
    });
  }
});

function loadUserPage(response) {
  setTimeout(function () {
    if (response === "User Added Successfully!") {
      window.location.assign("../php/dashboard.php");
    } else {
      window.location.assign("../pages/newUsers.html");
    }
  }, 2000);
}