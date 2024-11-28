// Toggle between login and signup forms
document.getElementById('loginBtn').addEventListener('click', function () {
    document.getElementById('loginForm').style.display = 'block';
    document.getElementById('signupForm').style.display = 'none';
    this.style.backgroundColor = '#362b06';
    document.getElementById('signupBtn').style.backgroundColor = '#d8a006';
    document.getElementById('placeholderImage').style.display = 'none';
});

// Toggle between display picture and forms
document.getElementById('signupBtn').addEventListener('click', function () {
    document.getElementById('signupForm').style.display = 'block';
    document.getElementById('loginForm').style.display = 'none';
    this.style.backgroundColor = '##362b06';
    document.getElementById('loginBtn').style.backgroundColor = '#d8a006';
    document.getElementById('placeholderImage').style.display = 'none';
});

// Reset when clicking outside of the forms
document.getElementById('loginBtn').addEventListener('dblclick', function () {
    document.getElementById('loginForm').style.display = 'none';
    document.getElementById('placeholderImage').style.display = 'block'; // Show image again
});

document.getElementById('signupBtn').addEventListener('dblclick', function () {
    document.getElementById('signupForm').style.display = 'none';
    document.getElementById('placeholderImage').style.display = 'block'; // Show image again
});


// Show appropriate fields based on selected user type
document.getElementById('userType').addEventListener('change', function () {
    const studentFields = document.getElementById('studentFields');
    const companyFields = document.getElementById('companyFields');

    if (this.value === 'student') {
        studentFields.style.display = 'block';
        companyFields.style.display = 'none';
    } else if (this.value === 'company') {
        studentFields.style.display = 'none';
        companyFields.style.display = 'block';
    }
});

function applyFilters() {
    const category = document.getElementById('filterCategory').value;
    const listings = document.querySelectorAll('.job-listing');

    listings.forEach(listing => {
        if (category === 'all' || listing.innerText.toLowerCase().includes(category)) {
            listing.style.display = 'flex';
        } else {
            listing.style.display = 'none';
        }
    });
}

document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const email = e.target[0].value;
    const password = e.target[1].value;

    try {
        const response = await fetch('http://localhost:5000/api/auth/login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password }),
        });
        const data = await response.json();
        if (response.ok) {
            alert('Login successful!');
            localStorage.setItem('token', data.token);
            window.location.href = 'profile-student-disp.php';
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Login error:', error);
    }
});

document.getElementById('signupForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const userType = e.target[0].value;
    const name = e.target[1].value;
    const email = e.target[3].value;
    const password = e.target[4].value;

    const extraFields =
        userType === 'student'
            ? { university: e.target[2].value }
            : { companyName: e.target[2].value, industry: e.target[3].value };

    const body = { userType, name, email, password, ...extraFields };

    try {
        const response = await fetch('http://localhost:5000/api/auth/signup', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(body),
        });
        const data = await response.json();
        if (response.ok) {
            alert('Signup successful!');
            window.location.href = 'index.html';
        } else {
            alert(data.message);
        }
    } catch (error) {
        console.error('Signup error:', error);
    }
});

// scripts.js

document.getElementById('edit-profile').addEventListener('submit', function (e) {
    e.preventDefault();

    var formData = new FormData(this);

    fetch('profile-update.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            alert(data); // Or handle the response as needed
            window.location.href = 'profile-student-disp.php'; // Redirect to profile page after submission
        })
        .catch(error => console.error('Error:', error));
});

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('edit-profile').addEventListener('submit', function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        // Send the form data to the backend for processing
        fetch('update_profile-student.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.text())
            .then(data => {
                alert(data); // Alert the user with the success or error message
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    // Cancel button action
    document.getElementById('cancelEditBtn').addEventListener('click', function () {
        // Redirect to profile page
        window.location.href = 'profile-student-disp.php';
    });
});

document.getElementById('loginForm').addEventListener('submit', function (e) {
    e.preventDefault();

    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;

    fetch('login.php', { // Correct path to login.php
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`,
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Redirect based on user type
                if (data.user_type === 'student') {
                    window.location.href = 'profile-student.php';
                } else if (data.user_type === 'company') {
                    window.location.href = 'profile-company.php';
                } else {
                    alert('Unknown user type. Please contact support.');
                }
            } else {
                alert('Invalid credentials, please try again.');
            }
        })
        .catch(error => console.error('Error:', error));
});

