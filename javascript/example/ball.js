document.addEventListener("DOMContentLoaded",main);


function main(event){
    let addBtn = document.querySelector("#btnBall").addEventListener('click', addScore);

    setInterval(moveBall, 1000)
}
function addScore(){
    let score = document.querySelector("#score").textContent;
    score++;
    document.querySelector("#score").textContent=score;
}
function moveBall(){
    ball = document.querySelector("#btnBall");
    xMax=window.innerWidth;
    yMax=window.innerHeight;

    x = Math.round(Math.random()*xMax);
    y = Math.round(Math.random()*yMax);
    ball.style.top   = y+"px";
    ball.style.left   = x+"px";
    deg = Math.random()*360;
    ball.style.transform   = "rotate("+deg+"deg)";
    console.log(x);
}