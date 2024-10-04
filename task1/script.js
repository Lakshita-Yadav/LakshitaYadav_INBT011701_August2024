document.getElementById('newsletter-form').addEventListener('submit', function (e) {
    const name = document.getElementById('name').value;
    const email = document.getElementById('email').value;
  
    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
  
    if (!email.match(emailPattern)) {
      alert('Please enter a valid email.');
      e.preventDefault(); 
    }
  
    if (name.trim() === '') {
      alert('Please enter your name.');
      e.preventDefault();
    }
  });
  
