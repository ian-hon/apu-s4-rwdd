(() => {
    const requiredActionsInput = document.querySelector("#required-actions");
    const maximumActionsInput = document.querySelector("#maximum-actions");
    const pointsPerActionInput = document.querySelector("#points-per-action");
    const goalContributionInput = document.querySelector("#goal-contribution");
    const form = document.querySelector("#form");

    const errorsDiv = document.querySelector("#errors");
    const errorsContainer = document.querySelector("#errors #container");

    function validateInteger(value, fieldName) {
        const num = parseInt(value, 10);
        if (isNaN(num) || !Number.isInteger(num)) {
            return `${fieldName} must be a valid whole number.`;
        }
        return null;
    }

    function validateDecimal(value, fieldName) {
        if (isNaN(parseFloat(value))) {
            return `${fieldName} must be a valid decimal number.`;
        }
        return null;
    }

    // 
    function showError(errors) {
        errorsContainer.innerHTML = '';
        let result = '';
        errors.forEach(error => {
            result += `<p>${error}</p>`
        });
        errorsContainer.innerHTML += result;
        errorsDiv.setAttribute('data-state', 'active');
    }

    function hideErrors() {
        errorsDiv.setAttribute('data-state', '');
    }
    // 

    errorsDiv.addEventListener('click', (_) => {
        hideErrors();
    });

    form.addEventListener("submit", (event) => {
        let errors = [];

        errors = errors.concat([validateInteger(requiredActionsInput.value, "Required actions")]);
        errors = errors.concat([validateInteger(maximumActionsInput.value, "Maximum actions")]);
        errors = errors.concat([validateInteger(pointsPerActionInput.value, "Points per action")]);
        errors = errors.concat([validateDecimal(goalContributionInput.value, "Goal contribution")]);

        errors = errors.filter((i) => i != null);

        if (errors.length > 0) {
            event.preventDefault();
            showError(errors);
        }

        hideErrors();
        // console.log('success');
    });
})();