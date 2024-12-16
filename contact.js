document.addEventListener('DOMContentLoaded',function(create){
    var dropdown = document.getElementById('assign_user');

 
    fetch('../php/contacts.php')
    .then(response => response.text())
    .then(data => {
        dropdown.innerHTML = data;
    })
})
