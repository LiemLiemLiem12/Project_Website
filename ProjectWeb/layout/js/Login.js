/**
 * Enhanced Login.js - User authentication functionality with strict validation
 */

document.addEventListener("DOMContentLoaded", function () {
  // Tab switching functionality
  const loginTab = document.getElementById("login-tab");
  const registerTab = document.getElementById("register-tab");
  const switchToRegister = document.getElementById("switchToRegister");
  const switchToLogin = document.getElementById("switchToLogin");
  const forgotPasswordLink = document.getElementById("forgotPasswordLink");

  // Forms
  const loginForm = document.getElementById("loginForm");
  const registerForm = document.getElementById("registerForm");
  const forgotPasswordForm = document.getElementById("forgotPasswordForm");

  // Modals
  const forgotPasswordModal = document.getElementById("forgotPasswordModal");
  const verificationModal = document.getElementById("verificationModal");

  // Modal instances
  let forgotPasswordModalInstance = null;
  let verificationModalInstance = null;

  if (forgotPasswordModal) {
    forgotPasswordModalInstance = new bootstrap.Modal(forgotPasswordModal);
  }

  if (verificationModal) {
    verificationModalInstance = new bootstrap.Modal(verificationModal);
  }

  // Initialize event listeners
  initTabSwitching();
  initFormValidation();
  initVerificationCode();
  initRealTimeValidation();

  /**
   * Initialize tab switching functionality
   */
  function initTabSwitching() {
    // Switch to register tab when clicking "Register Now" link
    if (switchToRegister) {
      switchToRegister.addEventListener("click", function (e) {
        e.preventDefault();
        const registerTabEl = new bootstrap.Tab(registerTab);
        registerTabEl.show();
      });
    }

    // Switch to login tab when clicking "Login" link
    if (switchToLogin) {
      switchToLogin.addEventListener("click", function (e) {
        e.preventDefault();
        const loginTabEl = new bootstrap.Tab(loginTab);
        loginTabEl.show();
      });
    }

    // Show forgot password modal when clicking "Forgot Password" link
    if (forgotPasswordLink && forgotPasswordModalInstance) {
      forgotPasswordLink.addEventListener("click", function (e) {
        e.preventDefault();
        forgotPasswordModalInstance.show();
      });
    }
  }

  /**
   * Initialize real-time validation
   */
  function initRealTimeValidation() {
    // Full name validation
    const fullNameInput = document.getElementById("fullName");
    if (fullNameInput) {
      fullNameInput.addEventListener("blur", function () {
        validateFullName(this);
      });

      fullNameInput.addEventListener("input", function () {
        clearFieldError(this);
      });
    }

    // Email validation
    const emailInput = document.getElementById("email");
    if (emailInput) {
      emailInput.addEventListener("blur", function () {
        validateEmail(this);
      });

      emailInput.addEventListener("input", function () {
        clearFieldError(this);
      });
    }

    // Phone validation
    const phoneInput = document.getElementById("phone");
    if (phoneInput) {
      phoneInput.addEventListener("blur", function () {
        validatePhone(this);
      });

      phoneInput.addEventListener("input", function () {
        clearFieldError(this);
        // Only allow numbers
        this.value = this.value.replace(/[^0-9]/g, "");
      });
    }

    // Password validation
    const passwordInput = document.getElementById("password");
    if (passwordInput) {
      passwordInput.addEventListener("input", function () {
        validatePassword(this);
        // Also validate confirm password if it has a value
        const confirmPasswordInput = document.getElementById("confirmPassword");
        if (confirmPasswordInput && confirmPasswordInput.value) {
          validatePasswordMatch(confirmPasswordInput);
        }
      });
    }

    // Confirm password validation
    const confirmPasswordInput = document.getElementById("confirmPassword");
    if (confirmPasswordInput) {
      confirmPasswordInput.addEventListener("input", function () {
        validatePasswordMatch(this);
      });
    }

    // Login email/phone validation
    const loginEmailInput = document.getElementById("loginEmail");
    if (loginEmailInput) {
      loginEmailInput.addEventListener("blur", function () {
        validateLoginEmailPhone(this);
      });

      loginEmailInput.addEventListener("input", function () {
        clearFieldError(this);
      });
    }
  }

  /**
   * Initialize form validation
   */
  function initFormValidation() {
    // Login form validation
    if (loginForm) {
      loginForm.addEventListener("submit", function (e) {
        if (!validateLoginForm()) {
          e.preventDefault();
        }
      });
    }

    // Register form validation
    if (registerForm) {
      registerForm.addEventListener("submit", function (e) {
        if (!validateRegisterForm()) {
          e.preventDefault();
        }
      });
    }
  }

  /**
   * Validate login form
   */
  function validateLoginForm() {
    const email = document.getElementById("loginEmail");
    const password = document.getElementById("loginPassword");

    let isValid = true;

    // Validate email/phone
    if (!validateLoginEmailPhone(email)) {
      isValid = false;
    }

    // Validate password
    if (!password.value.trim()) {
      showFieldError(password, "Vui lòng nhập mật khẩu");
      isValid = false;
    } else {
      clearFieldError(password);
    }

    return isValid;
  }

  /**
   * Validate register form
   */
  function validateRegisterForm() {
    const fullName = document.getElementById("fullName");
    const email = document.getElementById("email");
    const phone = document.getElementById("phone");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirmPassword");
    const agreeTerms = document.getElementById("agreeTerms");

    let isValid = true;

    // Validate all fields
    if (!validateFullName(fullName)) isValid = false;
    if (!validateEmail(email)) isValid = false;
    if (!validatePhone(phone)) isValid = false;
    if (!validatePassword(password)) isValid = false;
    if (!validatePasswordMatch(confirmPassword)) isValid = false;
    if (!validateTermsAgreement(agreeTerms)) isValid = false;

    return isValid;
  }

  /**
   * Validate full name
   */
  function validateFullName(input) {
    const value = input.value.trim();

    if (!value) {
      showFieldError(input, "Vui lòng nhập họ và tên");
      return false;
    }

    if (value.length < 2) {
      showFieldError(input, "Họ tên phải có ít nhất 2 ký tự");
      return false;
    }

    if (value.length > 50) {
      showFieldError(input, "Họ tên không được vượt quá 50 ký tự");
      return false;
    }

    // Check if name is all numbers
    if (/^\d+$/.test(value.replace(/\s/g, ""))) {
      showFieldError(input, "Họ tên không được toàn là số");
      return false;
    }

    // Check for valid characters (letters, spaces, Vietnamese characters)
    if (
      !/^[a-zA-ZÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴẶẸẺẼỀỂưăạảấầẩẫậắằẳẵặẹẻẽềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵýỷỹ\s]+$/u.test(
        value
      )
    ) {
      showFieldError(input, "Họ tên chỉ được chứa chữ cái và khoảng trắng");
      return false;
    }

    clearFieldError(input);
    return true;
  }

  /**
   * Validate email
   */
  function validateEmail(input) {
    const value = input.value.trim();

    if (!value) {
      showFieldError(input, "Vui lòng nhập email");
      return false;
    }

    // Enhanced email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(value)) {
      showFieldError(input, "Email không đúng định dạng");
      return false;
    }

    // Check for common email providers (optional - can be removed if not needed)
    const commonProviders = [
      "gmail.com",
      "yahoo.com",
      "hotmail.com",
      "outlook.com",
    ];
    const domain = value.split("@")[1];
    if (domain && !commonProviders.includes(domain.toLowerCase())) {
      // This is just a warning, not an error
      // showFieldWarning(input, 'Bạn có chắc chắn email này đúng không?');
    }

    clearFieldError(input);
    return true;
  }

  /**
   * Validate Vietnamese phone number
   */
  function validatePhone(input) {
    const value = input.value.trim();

    if (!value) {
      showFieldError(input, "Vui lòng nhập số điện thoại");
      return false;
    }

    // Remove spaces and special characters
    const cleanPhone = value.replace(/[\s\-\(\)]/g, "");

    // Vietnamese phone number validation
    // Mobile: 03x, 05x, 07x, 08x, 09x (10 digits)
    // Landline: 02x, 04x, 06x (10-11 digits)
    const mobileRegex = /^(03|05|07|08|09)[0-9]{8}$/;
    const landlineRegex = /^(02|04|06)[0-9]{8,9}$/;

    if (!mobileRegex.test(cleanPhone) && !landlineRegex.test(cleanPhone)) {
      showFieldError(input, "Số điện thoại không hợp lệ (phải là số Việt Nam)");
      return false;
    }

    clearFieldError(input);
    return true;
  }

  /**
   * Enhanced password validation
   */
  function validatePassword(input) {
    const value = input.value;

    if (!value) {
      showFieldError(input, "Vui lòng nhập mật khẩu");
      return false;
    }

    let errors = [];

    // Check minimum length
    if (value.length < 8) {
      errors.push("Ít nhất 8 ký tự");
    }

    // Check maximum length
    if (value.length > 128) {
      errors.push("Không quá 128 ký tự");
    }

    // Check for lowercase letter
    if (!/[a-z]/.test(value)) {
      errors.push("Ít nhất 1 chữ thường");
    }

    // Check for uppercase letter
    if (!/[A-Z]/.test(value)) {
      errors.push("Ít nhất 1 chữ hoa");
    }

    // Check for number
    if (!/[0-9]/.test(value)) {
      errors.push("Ít nhất 1 chữ số");
    }

    // Check for special character
    if (!/[!@#$%^&*(),.?":{}|<>]/.test(value)) {
      errors.push('Ít nhất 1 ký tự đặc biệt (!@#$%^&*(),.?":{}|<>)');
    }

    // Check for common weak passwords
    const weakPasswords = [
      "password",
      "12345678",
      "qwerty123",
      "admin123",
      "password123!",
      "123456789",
      "abcd1234",
    ];

    if (weakPasswords.includes(value.toLowerCase())) {
      errors.push("Mật khẩu này quá phổ biến");
    }

    if (errors.length > 0) {
      showFieldError(input, "Mật khẩu phải có: " + errors.join(", "));
      updatePasswordStrength(input, "weak");
      return false;
    }

    clearFieldError(input);
    updatePasswordStrength(input, getPasswordStrength(value));
    return true;
  }

  /**
   * Calculate password strength
   */
  function getPasswordStrength(password) {
    let score = 0;

    // Length bonus
    if (password.length >= 8) score += 1;
    if (password.length >= 12) score += 1;

    // Character variety bonus
    if (/[a-z]/.test(password)) score += 1;
    if (/[A-Z]/.test(password)) score += 1;
    if (/[0-9]/.test(password)) score += 1;
    if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) score += 1;

    // Pattern bonus
    if (!/(.)\1{2,}/.test(password)) score += 1; // No repeated characters
    if (!/123|abc|qwe/i.test(password)) score += 1; // No common sequences

    if (score < 4) return "weak";
    if (score < 6) return "medium";
    return "strong";
  }

  /**
   * Update password strength indicator
   */
  function updatePasswordStrength(input, strength) {
    let strengthIndicator =
      input.parentNode.querySelector(".password-strength");

    if (!strengthIndicator) {
      strengthIndicator = document.createElement("div");
      strengthIndicator.className = "password-strength mt-1";
      input.parentNode.appendChild(strengthIndicator);
    }

    const strengthTexts = {
      weak: "Yếu",
      medium: "Trung bình",
      strong: "Mạnh",
    };

    const strengthColors = {
      weak: "text-danger",
      medium: "text-warning",
      strong: "text-success",
    };

    strengthIndicator.className = `password-strength mt-1 small ${strengthColors[strength]}`;
    strengthIndicator.textContent = `Độ mạnh mật khẩu: ${strengthTexts[strength]}`;
  }

  /**
   * Validate password match
   */
  function validatePasswordMatch(input) {
    const password = document.getElementById("password").value;
    const confirmPassword = input.value;

    if (!confirmPassword) {
      showFieldError(input, "Vui lòng xác nhận mật khẩu");
      return false;
    }

    if (password !== confirmPassword) {
      showFieldError(input, "Mật khẩu xác nhận không khớp");
      return false;
    }

    clearFieldError(input);
    return true;
  }

  /**
   * Validate terms agreement
   */
  function validateTermsAgreement(input) {
    if (!input.checked) {
      showFieldError(input, "Vui lòng đồng ý với điều khoản sử dụng");
      return false;
    }

    clearFieldError(input);
    return true;
  }

  /**
   * Validate login email/phone
   */
  function validateLoginEmailPhone(input) {
    const value = input.value.trim();

    if (!value) {
      showFieldError(input, "Vui lòng nhập email hoặc số điện thoại");
      return false;
    }

    // Check if it's email format
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const isEmail = emailRegex.test(value);

    // Check if it's phone format
    const phoneRegex = /^(03|05|07|08|09)[0-9]{8}$/;
    const isPhone = phoneRegex.test(value.replace(/[\s\-\(\)]/g, ""));

    if (!isEmail && !isPhone) {
      showFieldError(input, "Email hoặc số điện thoại không đúng định dạng");
      return false;
    }

    clearFieldError(input);
    return true;
  }

  /**
   * Show field error
   */
  function showFieldError(input, message) {
    // Remove existing error
    clearFieldError(input);

    // Add error class
    input.classList.add("is-invalid");

    // Create error message
    const errorDiv = document.createElement("div");
    errorDiv.className = "invalid-feedback";
    errorDiv.textContent = message;

    // Insert error message
    input.parentNode.appendChild(errorDiv);
  }

  /**
   * Clear field error
   */
  function clearFieldError(input) {
    input.classList.remove("is-invalid");
    const errorDiv = input.parentNode.querySelector(".invalid-feedback");
    if (errorDiv) {
      errorDiv.remove();
    }
  }

  /**
   * Initialize verification code input functionality
   */
  function initVerificationCode() {
    const codeInputs = document.querySelectorAll(".verificationCode");

    codeInputs.forEach((input, index) => {
      // Focus on first input when modal is shown
      if (index === 0 && verificationModal) {
        verificationModal.addEventListener("shown.bs.modal", function () {
          input.focus();
        });
      }

      // Add input event listeners
      input.addEventListener("keyup", function (e) {
        // If key is backspace, move to previous field
        if (e.key === "Backspace" && !input.value && index > 0) {
          codeInputs[index - 1].focus();
          return;
        }

        // If input has a value and there is a next field, move to it
        if (input.value && index < codeInputs.length - 1) {
          codeInputs[index + 1].focus();
        }
      });

      // Only allow numbers
      input.addEventListener("input", function (e) {
        this.value = this.value.replace(/[^0-9]/g, "");
      });
    });

    // Handle "Resend Code" functionality
    const resendCodeLink = document.getElementById("resendCodeLink");
    const countdownTimer = document.getElementById("countdownTimer");
    const resendCodeText = document.querySelector(".resendCode");
    const countdownText = document.querySelector(".countdown");

    if (resendCodeLink) {
      resendCodeLink.addEventListener("click", function (e) {
        e.preventDefault();

        // Hide resend link and show countdown
        if (resendCodeText) resendCodeText.classList.add("d-none");
        if (countdownText) countdownText.classList.remove("d-none");

        // Start countdown
        let seconds = 60;
        if (countdownTimer) countdownTimer.textContent = seconds;

        const countdownInterval = setInterval(function () {
          seconds--;
          if (countdownTimer) countdownTimer.textContent = seconds;

          if (seconds <= 0) {
            clearInterval(countdownInterval);
            if (resendCodeText) resendCodeText.classList.remove("d-none");
            if (countdownText) countdownText.classList.add("d-none");
          }
        }, 1000);

        // Send AJAX request to resend code
        fetch("index.php?controller=login&action=resendCode", {
          method: "POST",
        })
          .then((response) => response.json())
          .then((data) => {
            showNotification(data.message, data.success ? "success" : "error");
          })
          .catch((error) => {
            console.error("Error:", error);
            showNotification(
              "Đã xảy ra lỗi khi gửi lại mã. Vui lòng thử lại sau.",
              "error"
            );
          });
      });
    }
  }

  /**
   * Helper function to move to next input field in verification code
   */
  window.moveToNext = function (input) {
    if (input.value.length === input.maxLength) {
      const fieldIndex = Array.from(
        document.querySelectorAll(".verificationCode")
      ).indexOf(input);
      const nextField =
        document.querySelectorAll(".verificationCode")[fieldIndex + 1];

      if (nextField) {
        nextField.focus();
      }
    }
  };

  /**
   * Show notification message
   */
  window.showNotification = function (message, type) {
    // Look for existing toast container or create one
    let toastContainer = document.querySelector(".toast-container");

    if (!toastContainer) {
      toastContainer = document.createElement("div");
      toastContainer.className =
        "toast-container position-fixed top-0 end-0 p-3";
      document.body.appendChild(toastContainer);
    }

    // Create toast element
    const toastEl = document.createElement("div");
    toastEl.className = `toast align-items-center text-white bg-${
      type === "success" ? "success" : "danger"
    }`;
    toastEl.setAttribute("role", "alert");
    toastEl.setAttribute("aria-live", "assertive");
    toastEl.setAttribute("aria-atomic", "true");

    // Create toast content
    toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

    // Add toast to container
    toastContainer.appendChild(toastEl);

    // Initialize and show toast
    const toast = new bootstrap.Toast(toastEl, {
      autohide: true,
      delay: 5000,
    });

    toast.show();

    // Remove toast after it's hidden
    toastEl.addEventListener("hidden.bs.toast", function () {
      toastEl.remove();
    });
  };
});
