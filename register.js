const form = document.querySelector('#register-form');

form.addEventListener('submit', (e) => {
  e.preventDefault();

  const formData = new FormData(form);

  fetch('register.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(response => {
    if (response.success) {
      alert(response.message);
      window.location.href = 'login.php';
    } else {
      alert(response.message);
    }
  })
  .catch(error => console.error('Error:', error));
});

