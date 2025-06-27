document.addEventListener('DOMContentLoaded', () => {
    const qrReaderDiv = document.getElementById('qr-reader');
    const qrUploadInput = document.getElementById('qr-upload');
    const resultsDiv = document.getElementById('results');

    // Handle image upload and display
    qrUploadInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Display the uploaded image
                qrReaderDiv.innerHTML = `<img src="${e.target.result}" alt="QR Code Image" style="width: 100%; height: 100%;">`;
                decodeQRCodeFromImage(e.target.result);
            };
            reader.readAsDataURL(file);
        }
    });

    // Initialize the QR code scanner
    function decodeQRCodeFromImage(imageDataUrl) {
        const html5QrCode = new Html5Qrcode("qr-reader");
        Html5Qrcode.getCameras().then(devices => {
            if (devices && devices.length) {
                // Start scanning from the camera
                html5QrCode.start({ facingMode: "environment" }, {
                    fps: 10,
                    qrbox: 250
                }, decodedText => {
                    // On success, show the decoded text
                    resultsDiv.innerHTML = `<p>Scanned QR Code: ${decodedText}</p>`;
                }, errorMessage => {
                    // Handle scan errors or failures
                    console.error(`QR code scan error: ${errorMessage}`);
                });
            }
        }).catch(err => {
            console.error(`Error initializing QR code scanner: ${err}`);
        });
    }
});
// Handle form submission
traceCattleForm.addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission behavior

    // Get values from the form fields
    const location = document.getElementById('location').value;
    const description = document.getElementById('description').value;

    // Here, you can handle the form data as needed
    // Example: Display the captured information
    resultsDiv.innerHTML = `
        <p>Location Spotted: ${location}</p>
        <p>Additional Information: ${description}</p>
    `;
});

document.addEventListener('DOMContentLoaded', () => {
    const cattleTableBody = document.querySelector('#cattle-table tbody');
    const addCattleForm = document.getElementById('add-cattle-form');
    const addCattleBtn = document.getElementById('add-cattle-btn');
    const newCattleForm = document.getElementById('new-cattle-form');

    // Example cattle list (this could be fetched from the server)
    let cattleList = [
        { id: 1, name: 'Bella', breed: 'Angus' },
        { id: 2, name: 'Max', breed: 'Hereford' }
    ];

    // Function to display cattle list in the table
    function displayCattleList() {
        cattleTableBody.innerHTML = ''; // Clear existing rows
        cattleList.forEach((cattle) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${cattle.id}</td>
                <td>${cattle.name}</td>
                <td>${cattle.breed}</td>
                <td>
                    <button class="btn-remove" onclick="removeCattle(${cattle.id})">Remove</button>
                </td>
            `;
            cattleTableBody.appendChild(row);
        });
    }

    // Function to remove cattle from the list
    window.removeCattle = function(cattleId) {
        cattleList = cattleList.filter(cattle => cattle.id !== cattleId);
        displayCattleList();
    };

    // Show add cattle form when the "Add New Cattle" button is clicked
    addCattleBtn.addEventListener('click', () => {
        addCattleForm.style.display = 'block';
    });

    // Handle the submission of the new cattle form
    newCattleForm.addEventListener('submit', (event) => {
        event.preventDefault(); // Prevent form from submitting normally
        const cattleName = document.getElementById('cattle-name').value;
        const cattleBreed = document.getElementById('cattle-breed').value;

        // Add new cattle to the list (in a real app, you would save to the server here)
        const newCattle = {
            id: cattleList.length + 1, // Generate a new ID
            name: cattleName,
            breed: cattleBreed
        };
        cattleList.push(newCattle);
        displayCattleList();

        // Clear the form and hide it
        newCattleForm.reset();
        addCattleForm.style.display = 'none';
    });

    // Initial display of cattle list
    displayCattleList();
});
