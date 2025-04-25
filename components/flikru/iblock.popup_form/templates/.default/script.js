document.addEventListener("DOMContentLoaded", function() {
    if(!document.querySelector(".flk_modal_container") ){
        return;
    }
    var modal = document.querySelector(".flk_modal_container");
    var form = modal.querySelector("form");
    let modal_id = modal.getAttribute("modal_id");
/*
    if(document.querySelector("#openFeedback") ){
        document.querySelector("#openFeedback").onclick = function() {
            modal.style.display = "block";
        };
    }*/

    form.addEventListener('submit', function (e){
        e.preventDefault();
        sendRequest(this);
        return false;
    });

    document.querySelector("#id_AGREE").addEventListener('change', function (e){
        this.value = this.checked?"Y":"";
    });

    // Закрыть модальную форму
    modal.querySelector(".close").onclick = function() {
        modal.style.display = "none";
        //yaMetrika("feedbackform_close");
        setCookie(modal_id,"1",24*1);
    };

    setTimeout(()=>{
        modal.style.display = "block";
    },5000);
});

async function sendRequest(form) {

    let formData = new FormData(form);
    const jsonData = getQueryJson(formData);
    const url = form.action;

    document.querySelectorAll(".error-field").forEach(leaf => leaf.remove());

    try {
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: jsonData
        }).then((res) => {
            return res.json();
        }).then((data)=>{
            if(!data) throw new Error('Ошибка сети');

            if(data.errors){
                for(let err in data.errors){
                    document.querySelector("[name="+err+"]").insertAdjacentHTML('afterend', "<div class='error-field'>"+data.errors[err]+"</div>")
                }
            }

            if(data.success==true){
                //yaMetrika("feedbackform_success");
                let modal_id = this.getAttribute("modal_id");
                listPage(99);
                setCookie(modal_id,"1",24*1);
            }
        })
    } catch (error) {
        console.error('Ошибка:', error);
    }
}


function listPage(page){
    document.querySelectorAll(".form_page").forEach(function (el){
        if(el.getAttribute('data-page') == page){
            el.classList.add('active');
        }else{
            el.classList.remove('active');
        }
    });
}

function getQueryJson(formElement){
    const formDataToJson = (formData) => JSON.stringify(Object.fromEntries(formData));
    return formDataToJson(formElement);
}
function setCookie(name, value, hours) {
    const date = new Date();
    date.setTime(date.getTime() + (hours * 60 * 60 * 1000));
    const expires = "expires=" + date.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}
function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) {
        return parts.pop().split(';')[0];
    }
    return null;
}
