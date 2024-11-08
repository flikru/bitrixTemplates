document.addEventListener("DOMContentLoaded",main);


function main(event){
    /*
    * События
    * */
    let btnlog = document.querySelector("#loadfetch");
    btnlog.addEventListener('click', setText);

    let form1 = document.querySelector("#form1");
    form1.addEventListener('submit', sendQuery);

    let form2 = document.querySelector("#form2");
    form2.addEventListener('submit', sendQuery);
    /*
    * Работа с DOM
    * */
    let spans = document.querySelectorAll('.spans');
    for(let i = 0; i<spans.length; i++){
        el = spans[i];

        htmlp = el.querySelectorAll("p");
        for(let c = 0; c<htmlp.length; c++){
            let text = htmlp[c].textContent;
            if(text.indexOf('вот')){
                char = text.replace('вот', "<b>поменял текст</b>")
                htmlp[c].innerHTML = char;
            }
            console.log(char);
        }

        iText = el.querySelectorAll("input[type=text]");
        for(let cs = 0; cs<iText.length; cs++){
            iText[cs].value =  i;
            iText[cs].classList.toggle('redBorder');
            iText[cs].classList.add('redBorder');
            val = (iText[cs].value);
        }

        chlds = el.children;
        for(let c = 0; c<chlds.length; c++){
            text = chlds[c].textContent;
        }
    }
}

function setText(){
    document.querySelector("#i1").value = "НАЖАТА КНОПКА";
    document.querySelector("#i2").value = "НАЖАТА 232";
}

function sendQuery(event){
    event.preventDefault();
    let formObj = new FormData(this);
    let method = this.getAttribute('method')
    let url = this.getAttribute('action')

    let getString = getQueryString(formObj);
    let getJson = getQueryJson(formObj);

    if(method == 'post'){
        sendFetch(this);
    }else{
        sendAjax(this);
    }
    return false;
}


function getQueryString(formData){
    var pairs = [];
    for (var [key, value] of formData.entries()) {
        pairs.push(encodeURIComponent(key) + '=' + encodeURIComponent(value));
    }
    return '?' + pairs.join('&');
}

function getQueryJson(formElement){
    const formDataToJson = (formData) => JSON.stringify(Object.fromEntries(formData));
    const jsonData = formDataToJson(formElement);
    return jsonData;
}

function sendAjax(form){
    const formData = new FormData(form);
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'http://dev2.mgn-doctor.ru/api/testjson.php', true);
    xhr.setRequestHeader("Content-type", "application/json");
    //xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function(e) {//Вызывает функцию при смене состояния.
        console.log(e)
        if(xhr.readyState == XMLHttpRequest.DONE && xhr.status == 200) {
            console.log('приехали')
        }
    }

    let json = getQueryJson(formData)
    xhr.send(json);

    xhr.onload = function (res) {
        let data = this.response;
        let dataJson = JSON.parse(this.response);
        let type = this.responseType;
        //console.log(res)
        //console.log(JSON.parse(res.currentTarget.response))
        let text="";
        for (ob in dataJson){
            text += "key: "+ob+" val: "+dataJson[ob]+"<br>";
        }
        document.querySelector("#resAjax").innerHTML=text;
        console.log(dataJson)
    };
}

function sendFetch(form){
    formData =new FormData(form);
    /*formData =new FormData();*/
    formData.append('arrayField1', JSON.stringify([1,2,3,4]));

    const jsonData = getQueryJson(formData);
    fetch('http://dev2.mgn-doctor.ru/api/testjson.php', {
        method: 'POST',
        body: jsonData,
        headers: {
            'Content-Type': 'application/json'
        }
    }).then(response => response.json()).then(data=>{
        let text="";
                for (ob in data){
            text += "key: "+ob+" val: "+data[ob]+"<br>";
        }
        document.querySelector("#resFetch").innerHTML=text;
        console.log(data);
    });
}
function loadFile(){
    inputElement.addEventListener('change', (event) => {
        const files = event.target.files;
        const formData = new FormData();

        for (const file of files) {
            formData.append('files[]', file);
        }
    });
}