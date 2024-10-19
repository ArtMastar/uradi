// Toggle between login and signup forms
document.getElementById('loginBtn').addEventListener('click', function() {
    document.getElementById('loginForm').style.display = 'block';
    document.getElementById('signupForm').style.display = 'none';
    this.style.backgroundColor = '#362b06';
    document.getElementById('signupBtn').style.backgroundColor = '#d8a006';
    document.getElementById('placeholderImage').style.display = 'none';
});

// Toggle between display picture and forms
document.getElementById('signupBtn').addEventListener('click', function() {
    document.getElementById('signupForm').style.display = 'block';
    document.getElementById('loginForm').style.display = 'none';
    this.style.backgroundColor = '##362b06';
    document.getElementById('loginBtn').style.backgroundColor = '#d8a006';
    document.getElementById('placeholderImage').style.display = 'none';
});

// Reset when clicking outside of the forms
document.getElementById('loginBtn').addEventListener('dblclick', function() {
    document.getElementById('loginForm').style.display = 'none';
    document.getElementById('placeholderImage').style.display = 'block'; // Show image again
});

document.getElementById('signupBtn').addEventListener('dblclick', function() {
    document.getElementById('signupForm').style.display = 'none';
    document.getElementById('placeholderImage').style.display = 'block'; // Show image again
});


// Show appropriate fields based on selected user type
document.getElementById('userType').addEventListener('change', function() {
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

