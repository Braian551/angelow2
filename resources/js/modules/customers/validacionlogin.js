const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
const phoneRegex = /^[0-9]{10,15}$/;

const emailPhoneValidationSteps = [
    {
        check: value => value.trim() !== '',
        message: 'El campo no puede estar vacío',
    },
    {
        check: value => {
            const input = value.trim();
            return emailRegex.test(input) || phoneRegex.test(input);
        },
        message: 'Debes ingresar un correo electrónico válido o un número de teléfono (10-15 dígitos)',
    },
    {
        check: value => {
            const input = value.trim();
            if (emailRegex.test(input)) {
                const domain = input.split('@')[1]?.toLowerCase();
                return !['10minutemail.com', 'tempmail.org', 'mailinator.com'].includes(domain);
            }
            return true;
        },
        message: 'No se permiten correos electrónicos temporales',
    },
];

const passwordValidationSteps = [
    {
        check: password => password !== '',
        message: 'La contraseña es obligatoria',
    },
    {
        check: password => password.length >= 6,
        message: 'La contraseña debe tener al menos 6 caracteres',
    },
];

const validateProgressively = (value, validationSteps) => {
    for (const step of validationSteps) {
        if (!step.check(value)) {
            return { isValid: false, message: step.message };
        }
    }
    return { isValid: true, message: '' };
};

const setFieldError = (field, errorElement, message) => {
    field.classList.add('error');
    errorElement.textContent = message;
    errorElement.style.display = 'block';
};

const setFieldValid = (field, errorElement) => {
    field.classList.remove('error');
    errorElement.style.display = 'none';
};

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const emailPhoneInput = document.getElementById('correo_login');
    const passwordInput = document.getElementById('loginPassword');
    const progressSteps = document.querySelectorAll('.progress-steps .step');
    const progressBar = document.querySelector('.progress');
    const steps = document.querySelectorAll('.form-step');

    if (!loginForm || !emailPhoneInput || !passwordInput || !progressBar || !progressSteps.length) {
        return;
    }

    let currentStep = 1;
    const totalSteps = steps.length;

    const emailPhoneError = document.createElement('div');
    emailPhoneError.className = 'error-message';
    emailPhoneInput.parentNode.appendChild(emailPhoneError);

    const passwordError = document.createElement('div');
    passwordError.className = 'error-message';
    passwordInput.parentNode.parentNode.appendChild(passwordError);

    const showStep = step => {
        steps.forEach(s => s.classList.remove('active'));
        document.querySelector(`.form-step[data-step="${step}"]`).classList.add('active');

        progressSteps.forEach((s, index) => {
            if (index < step - 1) s.classList.add('completed');
            else s.classList.remove('completed');
            s.classList.toggle('active', index + 1 === step);
        });

        if (step === 2) {
            progressBar.style.width = '70%';
        } else {
            progressBar.style.width = `${((step - 1) / (totalSteps - 1)) * 100}%`;
        }
    };

    const validateStep = step => {
        switch (step) {
            case 1: {
                const result = validateProgressively(emailPhoneInput.value.trim(), emailPhoneValidationSteps);
                if (!result.isValid) {
                    setFieldError(emailPhoneInput, emailPhoneError, result.message);
                    return false;
                }
                setFieldValid(emailPhoneInput, emailPhoneError);
                return true;
            }
            case 2: {
                const result = validateProgressively(passwordInput.value, passwordValidationSteps);
                if (!result.isValid) {
                    setFieldError(passwordInput, passwordError, result.message);
                    return false;
                }
                setFieldValid(passwordInput, passwordError);
                return true;
            }
            default:
                return true;
        }
    };

    showStep(currentStep);

    document.querySelectorAll('.next-step').forEach(btn => {
        btn.addEventListener('click', () => {
            if (validateStep(currentStep) && currentStep < totalSteps) {
                currentStep++;
                showStep(currentStep);
            } else {
                const errorField = document.querySelector('.error');
                if (errorField) {
                    errorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    errorField.focus();
                }
            }
        });
    });

    document.querySelectorAll('.prev-step').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep > 1) {
                currentStep--;
                showStep(currentStep);
            }
        });
    });

    emailPhoneInput.addEventListener('input', () => {
        const result = validateProgressively(emailPhoneInput.value.trim(), emailPhoneValidationSteps);
        if (!result.isValid) {
            setFieldError(emailPhoneInput, emailPhoneError, result.message);
        } else {
            setFieldValid(emailPhoneInput, emailPhoneError);
        }
    });

    passwordInput.addEventListener('input', () => {
        const result = validateProgressively(passwordInput.value, passwordValidationSteps);
        if (!result.isValid) {
            setFieldError(passwordInput, passwordError, result.message);
        } else {
            setFieldValid(passwordInput, passwordError);
        }
    });

    document.querySelectorAll('.toggle-password').forEach(btn => {
        btn.addEventListener('click', () => {
            const input = btn.previousElementSibling;
            const icon = btn.querySelector('i');
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        });
    });

    loginForm.addEventListener('submit', event => {
        let allValid = true;

        for (let i = 1; i <= totalSteps; i++) {
            if (!validateStep(i)) {
                allValid = false;
                if (currentStep !== i) {
                    currentStep = i;
                    showStep(currentStep);
                }
                break;
            }
        }

        if (!allValid) {
            event.preventDefault();
            const errorField = document.querySelector('.error');
            if (errorField) {
                errorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                errorField.focus();
            }
        }
    });
});