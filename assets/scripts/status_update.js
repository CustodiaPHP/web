updateState();

function updateState(){
    let elements = document.getElementsByClassName('status-badge');
    for(let i = 0; i < elements.length; i++){
        let element = elements.item(i);
        if(element.hasAttribute('x-service')){
            let service = element.getAttribute('x-service');

            let httpRequest = new XMLHttpRequest();
            httpRequest.open("GET", "/api/status/" + service);
            httpRequest.send();

            httpRequest.onreadystatechange = (e) => {
                if(httpRequest.readyState === 4 && httpRequest.status === 200){
                    if(element.innerHTML !== httpRequest.responseText)
                        element.innerHTML = httpRequest.responseText;
                }
            }
        }
    }
    updateOverview()
}

function updateOverview(){
    let element = document.getElementById('status-overview')

    let httpRequest = new XMLHttpRequest();
    httpRequest.open("GET", "/api/status");
    httpRequest.send();

    httpRequest.onreadystatechange = (e) => {
        if(httpRequest.readyState === 4 && httpRequest.status === 200){
            if(element.innerHTML !== httpRequest.responseText)
                element.innerHTML = httpRequest.responseText;
        }
    }
}

window.setInterval(updateState, 3000);