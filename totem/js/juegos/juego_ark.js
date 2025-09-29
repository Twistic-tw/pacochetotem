// inner variables
var canvas, ctx;

var iStart = 0;
var bRightBut = false;
var bLeftBut = false;
var oBall, oPadd, oBricks;
var aSounds = [];
var iPoints = 0;
var iGameTimer;
var iElapsed = iMin = iSec = 0;
var sLastTime, sLastPoints;

//Inicializacion del numero de elementos a destruir
var filas = 1;
var columnas = 8;
//var total = 8 * 1;
var total = (filas)*(columnas);
var punto_local = total;

// objects :
function Ball(x, y, dx, dy, r) {
    this.x = x;
    this.y = y;
    this.dx = dx;
    this.dy = dy;
    this.r = r;
}
function Padd(x, w, h, img) {
    this.x = x;
    this.w = w;
    this.h = h;
    this.img = img;
}
function Bricks(w, h, r, c, p) {
    this.w = w;
    this.h = h;
    this.r = r; // rows
    this.c = c; // cols
    this.p = p; // padd
    this.objs;
    this.colors = ['#c0392b', '#f80207', '#feff01', '#0072ff', '#fc01fc', '#03fe03']; // colors for rows
}

// -------------------------------------------------------------
// draw functions :

function clear() { // clear canvas function
    ctx.clearRect(0, 0, ctx.canvas.width, ctx.canvas.height);

    //determino el fondo del canvas
    ctx.fillStyle = "rgba(0,0,0,0.6)";
    ctx.fillRect(0, 0, ctx.canvas.width, ctx.canvas.height);
}

function drawScene() { // main drawScene function
    clear(); // clear canvas

    // draw Ball (circle)
    //Color de la bola
    ctx.fillStyle = 'white';
    ctx.beginPath();
    ctx.arc(oBall.x, oBall.y, oBall.r, 0, Math.PI * 2, true);
    ctx.closePath();
    ctx.fill();

/*    if (bRightBut)
        oPadd.x += 5;
    else if (bLeftBut)
        oPadd.x -= 5;*/

    // draw Padd (rectangle)
    ctx.drawImage(oPadd.img, oPadd.x, ctx.canvas.height - oPadd.h);

    // draw bricks (from array of its objects)
    //Dibuja los cuadros de arriba
    for (i=0; i < oBricks.r; i++) {
        ctx.fillStyle = oBricks.colors[i];
        for (j=0; j < oBricks.c; j++) {
            if (oBricks.objs[i][j] == 1) {
                ctx.beginPath();
                ctx.rect((j * (oBricks.w + oBricks.p)) + oBricks.p, (i * (oBricks.h + oBricks.p)) + oBricks.p, oBricks.w, oBricks.h);
                ctx.closePath();
                ctx.fill();
            }
        }
    }

    // collision detection
    iRowH = oBricks.h + oBricks.p;
    iRow = Math.floor(oBall.y / iRowH);
    iCol = Math.floor(oBall.x / (oBricks.w + oBricks.p));

    // mark brick as broken (empty) and reverse brick
    if (oBall.y < oBricks.r * iRowH && iRow >= 0 && iCol >= 0 && oBricks.objs[iRow][iCol] == 1) {
        oBricks.objs[iRow][iCol] = 0;
        oBall.dy = -oBall.dy;

        iPoints++;
        punto_local++;

        //Se ha termiando el juego por ganador
        if (punto_local == total){
        
            alert('Has ganado!!!');
            punto_local = 0;
            
            //Un maximo de 6 niveles
            if(filas < 7 ){filas++}
            
            //Llamamos al metodo de inicialización
            empezar();  
        }

        //aSounds[0].play(); // play sound
    }
 
    // reverse X position of ball
    if (oBall.x + oBall.dx + oBall.r > ctx.canvas.width || oBall.x + oBall.dx - oBall.r < 0) {
        oBall.dx = -oBall.dx;
    }

    if (oBall.y + oBall.dy - oBall.r < 0) {
        oBall.dy = -oBall.dy;
    } else if (oBall.y + oBall.dy + oBall.r > ctx.canvas.height - oPadd.h) {
        if (oBall.x > oPadd.x && oBall.x < oPadd.x + oPadd.w) {
            oBall.dx = 10 * ((oBall.x-(oPadd.x+oPadd.w/2))/oPadd.w);
            oBall.dy = -oBall.dy;
        }
        else if (oBall.y + oBall.dy + oBall.r > ctx.canvas.height) {

            //Terminado el juego por fallo
            
            //Limpiamos los contadores 
            clearInterval(iStart);
            clearInterval(iGameTimer);
           
            //Guardamos las variables de puntos y tiempo
            localStorage.setItem('last-time', iMin + ':' + iSec);
            localStorage.setItem('last-points', iPoints);
          

            iElapsed = iMin = iSec =  iStart =  iPoints = punto_local = 0;
            punto_local = 0;

            //Mostramos una alerta con el tiempo y los puntos echo
            alert('GAME OVER tiempo:' + localStorage.getItem('last-time') + 'Puntos:' + localStorage.getItem('last-points'));

            //Llamamos al metodo de inicialización
           
            //empezar();
            location.reload();
            
        }
    }

    oBall.x += oBall.dx;
    oBall.y += oBall.dy;

    //Puntos, tiempo, etc...
    ctx.font = '16px Verdana';
    ctx.fillStyle = '#fff';
    iMin = Math.floor(iElapsed / 60);
    iSec = iElapsed % 60;
    if (iMin < 10) iMin = "0" + iMin;
    if (iSec < 10) iSec = "0" + iSec;
    ctx.fillText('Time: ' + iMin + ':' + iSec, 600, 520);
    ctx.fillText('Points: ' + iPoints, 600, 550);

    //Puntuacion anterior
    /*if (sLastTime != null && sLastPoints != null) {
        ctx.fillText('Last Time: ' + sLastTime, 600, 460);
        ctx.fillText('Last Points: ' + sLastPoints, 600, 490);
    }*/
}


$( document ).ready(function() {
    empezar();
});

// initialization
function empezar(){

    //Inicializar los puntos locales a la partida
    punto_local = 0;

    total = (filas)*(columnas);

    //El canvas del juego
    canvas = document.getElementById('arkanoid');
    ctx = canvas.getContext('2d');

    //Tamaño especificado por html
    var width = canvas.width;
    var height = canvas.height;


    var padImg = new Image();
    padImg.src = 'js/juegos/padd.png';
    padImg.onload = function() {};

    //Inicializa el objeto bola
    oBall = new Ball(width / 2, 550, 0.5, -5, 10); // new ball object
    

    //Inicializa el objeto pala
    oPadd = new Padd(width / 2, 120, 20, padImg); // new padd object

    //Inicializa los elementos a destruir
    oBricks = new Bricks((width / 8) - 1, 20, filas, columnas, 2); // new bricks object


    oBricks.objs = new Array(oBricks.r); // fill-in bricks
    for (i=0; i < oBricks.r; i++) {
        oBricks.objs[i] = new Array(oBricks.c);
        for (j=0; j < oBricks.c; j++) {
            oBricks.objs[i][j] = 1;
        }
    }
    

    iStart = setInterval(drawScene, 10); // loop drawScene
    iGameTimer = setInterval(countTimer, 1000); // inner game timer

    // HTML5 Local storage - get values
    sLastTime = localStorage.getItem('last-time');
    sLastPoints = localStorage.getItem('last-points');

    var iCanvX1 = $(canvas).offset().left;
    var iCanvX2 = iCanvX1 + width;
    $('#arkanoid').mousemove(function(e) { // binding mousemove event
        if (e.pageX > iCanvX1 && e.pageX < iCanvX2) {
            oPadd.x = Math.max(e.pageX - iCanvX1 - (oPadd.w/2), 0);
            oPadd.x = Math.min(ctx.canvas.width - oPadd.w, oPadd.x);
        }
    });
};

function countTimer() {
    iElapsed++;
}