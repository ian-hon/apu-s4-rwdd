(() => {
    const requiredActionsInput = document.querySelector("#required-actions");
    const maximumActionsInput = document.querySelector("#maximum-actions");
    const pointsPerActionInput = document.querySelector("#points-per-action");
    const goalContributionInput = document.querySelector("#goal-contribution");
    const goalTypeInput = document.querySelector("#dropdown");
    const form = document.querySelector("#form");

    const errorsDiv = document.querySelector("#errors");
    const errorsContainer = document.querySelector("#errors #container");

    var goalTypes = [];
    const goalUnitComponent = document.querySelector("#goal-unit");

    fetch('../../api/goal_type/fetch_all.php')
        .then((e) => e.json())
        .then((e) => {
            goalTypes = e;
            updateGoalUnit();
        })

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

        // add check if max is more than required

        errors = errors.filter((i) => i != null);

        if (errors.length > 0) {
            event.preventDefault();
            showError(errors);
        }
    });

    // #region goal unit
    function updateGoalUnit() {
        let goalType = goalTypeInput.value;

        goalUnitComponent.innerHTML = `${goalTypes[goalType]['unit']} per action`;
    }

    goalTypeInput.addEventListener('change', () => {
        // console.log(goalTypeInput.value);
        updateGoalUnit();
    });
    // #endregion

    // #region rewards
    (() => {
        var requiredActions;
        var maximumActions;
        var optionalActions;
        var pointsPerAction;

        const container = form.querySelector("#final");
        const requiredResult = container.querySelector("#required #result h3");
        const optionalResult = container.querySelector("#optional #result h3");
        const requiredCalculation = container.querySelector("#required #calculation h6");
        const optionalCalculation = container.querySelector("#optional #calculation h6");
        const maximumResult = container.querySelector("#maximum #result h3");

        requiredActionsInput.addEventListener('keyup', () => {
            requiredActions = parseInt(requiredActionsInput.value);
            updateRewards();
        })

        maximumActionsInput.addEventListener('keyup', () => {
            maximumActions = parseInt(maximumActionsInput.value);
            updateRewards();
        })

        pointsPerActionInput.addEventListener('keyup', () => {
            pointsPerAction = parseInt(pointsPerActionInput.value);
            updateRewards();
        })

        function updateRewards() {
            optionalActions = maximumActions - requiredActions;

            if (isNaN(requiredActions) || isNaN(maximumActions) || isNaN(pointsPerAction)) {
                requiredCalculation.innerHTML = '-- x --';
                optionalCalculation.innerHTML = '-- x --';

                requiredResult.innerHTML = '--';
                optionalResult.innerHTML = '--';
                maximumResult.innerHTML = '--';
                return;
            }

            requiredCalculation.innerHTML = `${requiredActions} x ${pointsPerAction}`;
            optionalCalculation.innerHTML = `${optionalActions} x ${pointsPerAction}`;

            requiredResult.innerHTML = requiredActions * pointsPerAction;
            optionalResult.innerHTML = optionalActions * pointsPerAction;
            maximumResult.innerHTML = maximumActions * pointsPerAction;
        }

        requiredActions = parseInt(requiredActionsInput.value) || 0;
        maximumActions = parseInt(maximumActionsInput.value) || 0;
        pointsPerAction = parseInt(pointsPerActionInput.value) || 0;

        updateRewards();
    })();
    // #endregion
})();