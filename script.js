document.addEventListener("DOMContentLoaded", function () {
  const addNoteForm = document.querySelector("#addNoteForm");
  const notesContainer = document.querySelector("#notesContainer");

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
});
