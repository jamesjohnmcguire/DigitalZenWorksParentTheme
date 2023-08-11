/**********************************************************************
// Custom logo movement
**********************************************************************/
var ctx,
	//save the parameters to variables for easier clearing
	x = 220,
	y = 400,
	xIncrease = -1,
	yIncrease = -2,
	xStop = 34;
	yStop = 60;
	width = 0,
	height = 0,
	timerId = null,
	img = new Image();

//set an image url
img.src = "/images/dzw.png"
//img.src = document.getElementById("logo");

function draw()
{
	ctx.drawImage(img,x,y,width,height);
}

function move()
{
	ctx.clearRect(x,y,width,height);
	if (x == 0)
	{
		xIncrease = 1;
	}
	
	if (y == 0)
	{
		yIncrease = 1;
	}

	x = x + xIncrease;
	y = y + yIncrease;
	if (width <= 240)
	{
		width++;
	}
	if (height <= 240)
	{
		height++;
	}

	draw();

	if ((xIncrease == 1) && (x > xStop) && (yIncrease == 1) && (y > yStop))
	{
		xIncrease = 0,
		yIncrease = 0,
		clearInterval(timerId);
	}
}

function init()
{
	ctx =document.getElementById("canvas").getContext('2d');
	//initial drawing
	draw();
	timerId = setInterval(move, 5);

	document.getElementById('canvas-wrap').className="faded";
    setTimeout(function()
    {
        document.getElementById('canvas-wrap').className="normal";
    }, 10);
}

