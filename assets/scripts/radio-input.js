searchRadio();

function searchRadio() {
    let radios = document.querySelectorAll('input[type="radio"]');

    for(let i = 0; i < radios.length; i++){
        let radio = radios.item(i);
        if(radio.hasAttribute('x-radio')){
            let hiddenInput = radio.getAttribute('x-radio');
            let hiddenElement = document.getElementById(hiddenInput);

            if(hiddenElement == null) continue;

            if(radio.hasAttribute('x-radio-value')){
                let value = radio.getAttribute('x-radio-value');
                radio.addEventListener('change', (e) => {
                    if(radio.checked){
                        hiddenElement.value = value;
                    }
                });
            } else if (radio.hasAttribute('x-radio-input')){
                let input = radio.getAttribute('x-radio-input');
                let inputElement = document.getElementById(input);

                if(inputElement == null) continue;

                inputElement.addEventListener('change', (e) => {
                    if(radio.checked){
                        hiddenElement.value = inputElement.value;
                    }
                })
            }
        }
    }
}