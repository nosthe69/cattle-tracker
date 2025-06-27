document.getElementById('trace-cattle-form').addEventListener('submit', function(event) {
    event.preventDefault();
    
    const qrInput = document.getElementById('qr-input').files[0];
    if (qrInput) {
        const reader = new FileReader();
        reader.onload = function(e) {
            // Process the QR code image here
            // For now, we'll just display the image in the results section
            const resultDiv = document.getElementById('results');
            resultDiv.innerHTML = `<img src="${e.target.result}" alt="QR Code" width="200">`;
            
            // Placeholder: Simulate a search and display result
            setTimeout(() => {
                resultDiv.innerHTML += `<p>Cattle ID: 12345</p><p>Owner: John Doe</p><p>Location: Village XYZ</p>`;
            }, 1000);
        };
        reader.readAsDataURL(qrInput);
    }
});