const form = document.querySelector('#login-form');

form.addEventListener('submit', async (e) => {
  e.preventDefault();

  let formData = new URLSearchParams(new FormData(form));

  try {
    const response = await fetch('login.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
        'X-Requested-With': 'XMLHttpRequest'
      },
      body: formData
    });

    if (!response.ok) {
      console.error('HTTP error', response.status);
      return;
    }

    const jsonData = await response.json();

    if (jsonData.success === true) {
      window.location.href = jsonData.redirect;
    } else {
      alert(jsonData.message);
    }
  } catch (error) {
    console.error('Fetch Error:', error);
  }
});

