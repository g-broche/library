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