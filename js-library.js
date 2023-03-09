function debounce(waitTime, ...funcs) {
    console.log(funcs);
    let timeout;                                //timeout variable
    return () => {
        clearTimeout(timeout);                   //clear timeout everytime the function is triggered
        timeout = setTimeout(() => {      //assign the timeout when the event is fired and fire the array of functions once the timeout arrive to 0
            for (func of funcs) {
                console.log(func);
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

function enableSubmitButton(button, ...booleans) {
    let mustEnable = true;
    booleans.forEach(element => {
        if (element == false) { mustEnable = false }
    })
    if (mustEnable) {
        button.disabled = false;
    } else {
        button.disabled = true;
    }
}