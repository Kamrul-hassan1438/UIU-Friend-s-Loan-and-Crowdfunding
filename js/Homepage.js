document.getElementById("updateProfileForm").onsubmit = function (event) {
    event.preventDefault();

    // Get updated values from the form
    var newName = document.getElementById("nameInput").value;
    var newPhone = document.getElementById("phoneInput").value;
    var profilePic = document.getElementById("profilePic").files[0];

    // Initialize a new FormData object
    var formData = new FormData();
    formData.append("username", newName);
    formData.append("phone", newPhone);

    // Add profile picture if a new one is selected
    if (profilePic) {
        formData.append("profile_image", profilePic);
    }

    // Show loading state (optional)
    var submitButton = document.querySelector("#updateProfileForm button[type='submit']");
    submitButton.disabled = true;
    submitButton.textContent = "Saving...";

    // Send the data to the PHP file for processing via AJAX
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "update_profile.php", true);
    xhr.onload = function () {
        submitButton.disabled = false;
        submitButton.textContent = "Save Changes"; // Revert button text

        if (xhr.status === 200) {
            var response = JSON.parse(xhr.responseText);

            if (response.success) {
                // Update the page if the response is successful
                document.getElementById("name").textContent = newName;
                document.getElementById("Phone-number").textContent = "Phone Number: " + newPhone;

                if (profilePic) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        document.querySelector(".profile-pic").src = e.target.result;
                    };
                    reader.readAsDataURL(profilePic);
                }

                // Close the modal after saving
                modal.style.display = "none";
            } else {
                console.error("Error: " + response.error);
            }
        } else {
            console.error("Error updating profile. Status: " + xhr.status);
        }
    };
    xhr.onerror = function () {
        submitButton.disabled = false;
        submitButton.textContent = "Save Changes";
        console.error("An error occurred during the request.");
    };
    
    // Send the FormData object via AJAX
    xhr.send(formData);
};

