document.getElementById('loginForm').addEventListener('submit', function(event) {
    event.preventDefault();
    // Simulate login success
    document.getElementById('login').classList.add('hidden');
    document.getElementById('contacts').classList.remove('hidden');
    document.getElementById('notes').classList.remove('hidden');
});

document.getElementById('addContactBtn').addEventListener('click', function() {
    const contactName = prompt("Enter contact name:");
    if (contactName) {
        const contactList = document.getElementById('contactList');
        const contactItem = document.createElement('div');
        contactItem.textContent = contactName;
        contactList.appendChild(contactItem);
    }
});

document.getElementById('saveNoteBtn').addEventListener('click', function() {
    const noteInput = document.getElementById('noteInput');
    const noteText = noteInput.value;
    if (noteText) {
        const noteList = document.getElementById('noteList');
        const noteItem = document.createElement('div');
        noteItem.textContent = noteText;
        noteList.appendChild(noteItem);
        noteInput.value = ''; } 
});