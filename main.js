/* */
function onButton(value){
	document.location.href="element.php?value="+value+"";
}
/*SIDENAV*/
function openNav() {
    document.getElementById("mySidenav").style.width = "250px";
}

function closeNav() {
    document.getElementById("mySidenav").style.width = "0";
}



/*SLIDESHOW*/
var slideIndex = 0;
showSlides(slideIndex);

function plusSlides(n) {
	showSlides(slideIndex += n);
}

function currentSlide(n) {
	showSlides(slideIndex = n);
}

function showSlides(n) {
	var i;
	var slides = document.getElementsByClassName("mySlides");
	var dots = document.getElementsByClassName("dot");
	if (n > slides.length) {slideIndex = 1}    
	if (n < 1) {slideIndex = slides.length}
	for (i = 0; i < slides.length; i++)
		slides[i].style.display = "none";  
	slides[slideIndex-1].style.display = "block";  
}
autoSlide();
function autoSlide(){
	var i;
	var slides = document.getElementsByClassName("mySlides");
	for (i = 0; i < slides.length; i++)
		slides[i].style.display = "none";  
	slideIndex++;
	if (slideIndex > slides.length)
		slideIndex = 1;
	slides[slideIndex-1].style.display = "block";
	setTimeout(autoSlide,3000);
}