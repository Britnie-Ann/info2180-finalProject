document.addEventListener('DOMContentLoaded', function (create) {
    var table = document.getElementById('table');
        fetch('../php/users.php')
            .then(response => response.text())
            .then(data => {
                table.innerHTML = data;
            })
})
