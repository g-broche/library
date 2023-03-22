function debounce(waitTime, ...funcs) {
    let timeout;                                //timeout variable
    return () => {
        clearTimeout(timeout);                   //clear timeout everytime the function is triggered
        timeout = setTimeout(() => {      //assign the timeout when the event is fired and fire the array of functions once the timeout arrive to 0
            for (func of funcs) {
                func();
            }
        }, waitTime);
    }
}

function valid(passField, passFieldConfirm) {
    if (passField.value == passFieldConfirm.value && passField.value.length > 5) {
        return true;
    } else {
        return false;
    }
}

function enableSubmitButton(button, booleanArray) {
    let mustEnable = true;
    booleanArray.forEach(element => {
        if (element == false) { mustEnable = false }
    })
    if (mustEnable) {
        button.disabled = false;
    } else {
        button.disabled = true;
    }
}

function checkStringValidy(string, pattern = /^.+$/, minLength = 0, maxLength = Number.MAX_SAFE_INTEGER) {
    if (minLength <= string.length && string.length <= maxLength) {
        return pattern.test(string);
    } else {
        return false;
    }
}