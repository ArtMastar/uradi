document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('loginForm');

    loginForm.addEventListener('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission

        const email = document.getElementById('loginEmail').value;
        const password = document.getElementById('loginPassword').value;

        // Send the data to the server using fetch
        fetch('login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`,
        })
            .then((response) => response.json())
        // console.log(response)
            .then((data) => {
                if (data.success) {
                    console.log(`success: ${data.user_type}`);
                    // Redirect based on user type
                    if (data.user_type.toLowerCase() === 'student') {
                        window.location.href = 'profile-student.php';
                    } else if (data.user_type.toLowerCase() === 'company') {
                        window.location.href = 'profile-company.php';
                    } else {
                        alert('Unknown user type. Please contact support.');
                    }
                } else {
                    // Display error message
                    alert(data.message || 'Invalid credentials, please try again.');
                }
            })
            .catch((error) => {
                console.error('Error:', error);
                alert('An error occurred, please try again later.');
            });
    });
});
