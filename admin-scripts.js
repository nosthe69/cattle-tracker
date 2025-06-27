// Sample data for users
let users = [
    { id: 1, name: "John Doe", email: "john@example.com" },
    { id: 2, name: "Jane Smith", email: "jane@example.com" }
];

// Function to display users in the user list
function displayUsers() {
    const userList = document.getElementById('userList');
    userList.innerHTML = ''; // Clear previous content

    users.forEach(user => {
        const userItem = document.createElement('p');
        userItem.textContent = `ID: ${user.id}, Name: ${user.name}, Email: ${user.email}`;
        userList.appendChild(userItem);
    });
}

// Function to add a user
function addUser() {
    const userName = prompt("Enter the user's name:");
    const userEmail = prompt("Enter the user's email:");

    if (userName && userEmail) {
        const newUser = { id: users.length + 1, name: userName, email: userEmail };
        users.push(newUser);
        displayUsers();
        alert('User added successfully!');
    } else {
        alert('Name and email are required to add a user.');
    }
}

// Function to edit a user
function editUser() {
    const userId = prompt("Enter the user ID to edit:");

    const user = users.find(u => u.id == userId);
    if (user) {
        const newName = prompt("Enter the new name:", user.name);
        const newEmail = prompt("Enter the new email:", user.email);

        if (newName && newEmail) {
            user.name = newName;
            user.email = newEmail;
            displayUsers();
            alert('User updated successfully!');
        } else {
            alert('Both name and email are required to update a user.');
        }
    } else {
        alert('User not found.');
    }
}

// Function to delete a user
function deleteUser() {
    const userId = prompt("Enter the user ID to delete:");

    const userIndex = users.findIndex(u => u.id == userId);
    if (userIndex !== -1) {
        users.splice(userIndex, 1);
        displayUsers();
        alert('User deleted successfully!');
    } else {
        alert('User not found.');
    }
}


function generateReport() {
    const reportContent = document.getElementById('reportContent');
    reportContent.innerHTML = ''; // Clear previous content

    // Simulate report data
    const reportData = [
        { animal: 'Dog', status: 'Adopted', date: '2024-08-01' },
        { animal: 'Cat', status: 'Available', date: '2024-08-10' }
    ];

    // Create a simple report table
    const reportTable = document.createElement('table');
    const headerRow = reportTable.insertRow();
    headerRow.innerHTML = '<th>Animal</th><th>Status</th><th>Date</th>';

    reportData.forEach(item => {
        const row = reportTable.insertRow();
        row.innerHTML = `<td>${item.animal}</td><td>${item.status}</td><td>${item.date}</td>`;
    });

    reportContent.appendChild(reportTable);
    alert('Report generated successfully!');
}


// Sample data for animals
let animals = [
    { id: 1, name: "Buddy", breed: "Golden Retriever", status: "Available" },
    { id: 2, name: "Whiskers", breed: "Tabby Cat", status: "Adopted" }
];

// Function to display animals in the animal list
function displayAnimals() {
    const animalList = document.getElementById('animalList');
    animalList.innerHTML = ''; // Clear previous content

    animals.forEach(animal => {
        const animalItem = document.createElement('p');
        animalItem.textContent = `ID: ${animal.id}, Name: ${animal.name}, Breed: ${animal.breed}, Status: ${animal.status}`;
        animalList.appendChild(animalItem);
    });
}

// Function to add an animal
function addAnimal() {
    const animalName = prompt("Enter the animal's name:");
    const animalBreed = prompt("Enter the animal's breed:");
    const animalStatus = prompt("Enter the animal's status (Available/Adopted):");

    if (animalName && animalBreed && animalStatus) {
        const newAnimal = { id: animals.length + 1, name: animalName, breed: animalBreed, status: animalStatus };
        animals.push(newAnimal);
        displayAnimals();
        alert('Animal added successfully!');
    } else {
        alert('Name, breed, and status are required to add an animal.');
    }
}

// Function to edit an animal
function editAnimal() {
    const animalId = prompt("Enter the animal ID to edit:");

    const animal = animals.find(a => a.id == animalId);
    if (animal) {
        const newName = prompt("Enter the new name:", animal.name);
        const newBreed = prompt("Enter the new breed:", animal.breed);
        const newStatus = prompt("Enter the new status (Available/Adopted):", animal.status);

        if (newName && newBreed && newStatus) {
            animal.name = newName;
            animal.breed = newBreed;
            animal.status = newStatus;
            displayAnimals();
            alert('Animal updated successfully!');
        } else {
            alert('All fields are required to update an animal.');
        }
    } else {
        alert('Animal not found.');
    }
}

// Function to delete an animal
function deleteAnimal() {
    const animalId = prompt("Enter the animal ID to delete:");

    const animalIndex = animals.findIndex(a => a.id == animalId);
    if (animalIndex !== -1) {
        animals.splice(animalIndex, 1);
        displayAnimals();
        alert('Animal deleted successfully!');
    } else {
        alert('Animal not found.');
    }
}


function updateSettings() {
    const accountSettings = prompt("Update Account Settings:");
    const displaySettings = prompt("Update Display Settings:");
    const notificationSettings = prompt("Update Notification Settings:");

    if (accountSettings || displaySettings || notificationSettings) {
        alert('Settings updated successfully!');
        // Logic to handle the actual updating of settings would go here
    } else {
        alert('No changes were made to the settings.');
    }
}


displayUsers();
displayAnimals();
