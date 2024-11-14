document.addEventListener("DOMContentLoaded",main);
let enemys = [];
let objectsMap = [];
let score = 0;
let countEnemys = 5;
function main(event){
    let player = new Player();
    addEnemy();

    for (let i = 0; i<=1; i++){
        //objectsMap.push(new gameObject());
    }
    console.log(objectsMap);
    //let addBtn = document.querySelector("#btnBall").addEventListener('click', addScore);
}
function addScore(){
    let score = document.querySelector("#score").textContent;
    score++;
    document.querySelector("#score").textContent=score;
}
function addEnemy(){
    setInterval(()=>{
        if(enemys.length<countEnemys) enemys.push(new Enemy())
    },1000);
}

class Player{
    constructor() {
        this.shoot=[];
        this.player = document.createElement("div");
        this.player.id="player";
        document.querySelector("body").prepend(this.player);
        this.movePlayer();
        this.setShoot();
    }
    movePlayer(){
        window.onload = () => {
            let h = this.player.style.height;
            let w = this.player.style.width;
            this.player.style.height = h + "px";
            this.player.style.width = w + "px";
            window.addEventListener("mousemove", function(e) {
                //this.left = this.player.style.left;
                this.player.style.left = e.clientX - w / 2 + "px";
                //this.player.style.top = e.clientY - w / 2 + "px";
            })
        }
    }
    setShoot(){
        window.addEventListener("click", function(e) {
            this.shoot = new Shooting(this.player);
        })
    }
}
class Shooting{

    constructor(player) {
        this.damage = Math.random()*200;
        this.player = player
        this.createBullet();
        //this.rotate();
        this.moveShoot();
    }
    createBullet() {
        this.top = this.player.offsetTop;
        this.left = this.player.offsetLeft;
        this.posY = this.top;
        //this.speedX = 15;
        this.speedY = -11;
        this.el = document.createElement("div");
        this.el.classList.add("bullet");
        this.el.style.top =this.top+"px";
        this.el.style.left = this.left+34+"px";
        //let color = Enemy().getRandomColor()
        //this.el.style.backgroundColor  = color;
        //this.el.style.boxShadow  = "0px 0px 5px 2px #E81425FF";
        document.querySelector("body").prepend(this.el);
    }
    moveShoot(){
        this.posY += this.speedY;
        if (this.posY + this.el.offsetHeight > window.innerHeight || this.posY < 0) {
            this.speedY = -this.speedY;
            this.destroy();
        }
        this.el.style.top   = this.posY+"px";
        //this.el.style.left   = this.posX+"px";
        let coord = this.el.getBoundingClientRect();
        let bulL = coord.left;
        let bulT = coord.top;
        let bulR = coord.right;
        let bulB = coord.bottom;
        let enemysEl = enemys;
        let enemysLive = [];
        for (let i =0; i<enemysEl.length; i++){

            if(isIntersecting(enemysEl[i].el, this.el)){
                this.el.classList.add("bullet_bum");
                this.speedY = 0;
                setTimeout(()=>{
                    this.el.remove();
                },350);
                if(enemysEl[i].destroy(this.damage)){
                    score++;
                    document.querySelector("#score").textContent=score;
                    this.damage = 0;
                    continue;
                }
                this.damage = 0;
            }
            enemysLive.push(enemysEl[i])
        }
        enemys = enemysLive;

        requestAnimationFrame(this.moveShoot.bind(this));
    }
    destroy(){
        this.el.remove();
    }
    array_splice( array, key ){
        let resoltArray = new Array();
        for( let i = 0; i < array.length; i++ ){
            if( i != key ){
                resoltArray.push( array[ i ] );
            }
        }
        return resoltArray;
    }

}
class Enemy{
    constructor() {
        this.deg = 1;
        this.speed = 1;
        this.health = 200;
        this.createEnemy();
        this.rotate();
        this.moveEnemy();

    }
    rotate(){
        this.deg += 1;
        this.el.style.transform   = "rotate("+this.deg+"deg)";
        requestAnimationFrame(this.rotate.bind(this));
    }
    createEnemy() {
        this.speedX = this.speed;
        this.speedY = this.speed;

        this.el = document.createElement("div");
        this.el.classList.add("enemy");
        this.posX = Math.random() * (window.innerWidth-100);
        this.posY = Math.random() * (window.innerHeight-100);

        this.el.style.top   =this.posX +"px";
        this.el.style.left   = this.posX+"px";
        this.el.innerHTML = this.health;

        //let color = getRandomColor()
        //this.el.style.backgroundColor  = color;
        //this.el.style.boxShadow  = "0px 0px 10px 10px "+color;
        document.querySelector("body").prepend(this.el);
    }
    moveEnemy(){
        this.posX += this.speedX;
        this.posY += this.speedY;

        if (this.posX + this.el.offsetWidth > window.innerWidth || this.posX < 0) {
            this.speedX = -this.speedX;
        }
        if (this.posY + this.el.offsetHeight > window.innerHeight || this.posY < 0) {
            this.speedY = -this.speedY;
        }

        for (let i = 0; i<objectsMap.length;i++){
            if(isIntersecting(objectsMap[i].el, this.el)){
                //this.speedX = 0;
                //this.speedY = 0;
            }
        }
        this.el.style.top   = this.posY+"px";
        this.el.style.left   = this.posX+"px";
        //this.attackObject();
        requestAnimationFrame(this.moveEnemy.bind(this));
    }
    destroy(damage){
        this.health = this.health - damage
        this.el.innerHTML = Math.round(this.health);
        if(this.health<=0){
            this.el.remove();
            delete this;
            return true;
        }
        return false;
    }
    attackObject(){
        let fort = document.querySelector('.fort');
        let coord = fort.getBoundingClientRect();
        /*if(isIntersecting(fort,this.el)){

        }*/
    }
}

class gameObject{
    constructor() {
        this.create();
    }
    create(){
        this.posX = Math.random() * (window.innerWidth-100);
        this.posY = Math.random() * (window.innerHeight-100);
        let elHeight = Math.random() * 400;
        let elWidth = Math.random() * 400;
        if(elHeight>=elWidth){
            elWidth = 50;
        }else{
            elHeight = 50;
        }
        this.health = elWidth*elWidth/5;
        this.el = document.createElement("div");
        this.el.classList.add("gameObject");
        this.el.style.height = elHeight+"px";
        this.el.style.width  = elWidth+"px";
        this.el.style.top = this.posY+"px";
        this.el.style.left = this.posX+"px";
        let color = getRandomColor()
        this.el.style.backgroundColor  = color;

        document.querySelector("body").appendChild(this.el);
    }
}

function isIntersecting(elem1, elem2) {
    const rect1 = elem1.getBoundingClientRect();
    const rect2 = elem2.getBoundingClientRect();
    //enL<bulL && enT>bulT && enR>bulR && enB>bulB
    return (
        rect1.left < rect2.right &&
        rect1.right > rect2.left &&
        rect1.top < rect2.bottom &&
        rect1.bottom > rect2.top
    );
}
/*
function isIntersecting(elem1, elem2) {
    const rect1 = elem1.getBoundingClientRect();
    const rect2 = elem2.getBoundingClientRect();
    //enL<bulL && enT>bulT && enR>bulR && enB>bulB
    return (
        (rect1.left < rect2.left &&
            rect1.top > rect2.top &&
            rect1.right > rect2.right &&
            rect1.bottom > rect2.bottom) ||
        (rect2.left < rect1.left &&
            rect2.top > rect1.top &&
            rect2.right > rect1.right &&
            rect2.bottom > rect1.bottom)
    );
}*/
function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}