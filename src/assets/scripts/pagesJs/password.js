function togglePasswordVisibility(inputId, iconId) {
    let passwordInput = document.getElementById(inputId);
    let eyeIcon = document.getElementById(iconId);

    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        eyeIcon.classList.remove("fa-eye-slash");
        eyeIcon.classList.add("fa-eye");
    } else {
        passwordInput.type = "password";
        eyeIcon.classList.remove("fa-eye");
        eyeIcon.classList.add("fa-eye-slash");
    }
}

function validatePassword() {
    let password = document.getElementById("password-animated-input").value;
    let warning = document.getElementById("password-warning");

    let strongPasswordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[!@#$%^&*()_+[\]{};:'",.<>?]).{8,}$/;

    if (!strongPasswordRegex.test(password)){
        warning.style.display = "block";
        return false;
    } else {
        warning.style.display = "none";
        return true;
    }
}

function checkPasswordMatch() {
    let password = document.getElementById("password-animated-input").value;
    let confirmPassword = document.getElementById("verify-password-animated-input").value;
    let matchWarning = document.getElementById("match-warning");

    if (password !== confirmPassword && confirmPassword.length > 0) {
        matchWarning.style.display = "block";
        return false;
    } else {
        matchWarning.style.display = "none";
        return true;
    }
}

function validateForm() {
    let isPasswordValid = validatePassword();
    let isMatchValid = checkPasswordMatch();

    if (!isPasswordValid || !isMatchValid) {
        alert("Please fix password errors before submitting.");
        return false;
    }
    return true;
}