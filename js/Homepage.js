var modal = document.getElementById("updateProfileModal");
var btn = document.querySelector(".profile-btn");
var span = document.querySelector(".close");

// When the user clicks the button, open the modal
btn.onclick = function () {
    modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function () {
    modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
}

// Handle form submission
document.getElementById("updateProfileForm").onsubmit = function (event) {
    event.preventDefault();

    // Update profile information
    var newName = document.getElementById("nameInput").value;
    var newPhone = document.getElementById("phoneInput").value;

    document.getElementById("name").textContent = newName;
    document.getElementById("Phone-number").textContent = "Phone Number: " + newPhone;

    // Update profile picture if a new one is selected
    var profilePic = document.getElementById("profilePic").files[0];
    if (profilePic) {
        var reader = new FileReader();
        reader.onload = function (e) {
            document.querySelector(".profile-pic").src = e.target.result;
        }
        reader.readAsDataURL(profilePic);
    }

    // Close the modal after saving
    modal.style.display = "none";
};