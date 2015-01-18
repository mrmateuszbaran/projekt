document.onkeydown= function(key){ reactKey(key); }
document.onclick = function(e) { reactClick(e); }
document.addEventListener('backbutton', function(){ alert("siema"); }, false);
 
window.onload = function() { 

	checkIcons(); // Sprawdza czy jest jakis obraz i czy wyswietlic przyciski kasowania/edycji 

	var icon_loading;
	icon_loading = new Image();		// Wczytuje ikonkę wczytywania
	icon_loading.src = "loading.gif";
}

var image_shown = false;
var upload_box_shown = false;
var select_all = false;

function reactKey(evt) {
   if(image_shown && evt.keyCode== 27) {
		var div = document.getElementById("js-image-div");
		close(div);
		setTimeout(function() { image_shown = false; }, 500);
   }
}

function checkIcons()
{
	obrazy = document.getElementsByClassName('image-thumbnail');
	if (obrazy.length == 0)
	{
		deleteIcon = document.getElementById('icon-delete');
		selectAllIcon = document.getElementById('icon-select-all');
		deleteIcon.style.display = 'none';
		selectAllIcon.style.display = 'none';
	} 
}

function checkFilesCount()
{
	span = document.getElementById('uploadDontSearch');
	if (document.getElementById('uploadFiles').files.length > 1)
		span.style.display = "none"
	else
		span.style.display = "block";
}

function toggleSelectAll(button, form)
{
	select_all = !select_all;
	checkboxes = form.childNodes;
	for (var i = 0; i < checkboxes.length; i++)
	{
		checkboxes[i].checked = select_all;
	}
	var icon = "url('res/ikonki/select-all-" + select_all + ".png')";
	button.style.background = icon;
}

function close(object)
{
	object.style.width = "0px";
	object.style.height = "0px";
	object.style.margin = "0px";
	object.style.overflow = "hidden";
	setTimeout(function() { object.parentNode.removeChild(object); }, 500);
}

function closeNow(object)
{
	object.parentNode.removeChild(object);
}

function reactClick(e)
{
	if (image_shown)
	{
		var div = document.getElementById("js-image-div");
		if (e.target != div)
		{
			close(div);
			setTimeout(function() { image_shown = false; }, 500);
		}
	}
}

function maximize(div)
{
	div.style.width = "800px";
	div.style.height = "600px";
	div.style.left = "50%";
	div.style.top = "50%";
	div.style.margin = "-300px 0px 0px -400px";
}

function toggle_upload_box()
{
	var link = document.getElementById("upload-box-link");
	var box = document.getElementById("upload-box");
	
	if (upload_box_shown)
	{
		//link.style.display = "block";
		box.style.opacity = "0";
		box.style.visibility = "hidden";
		//setTimeout(function() { box.style.display = "none"; }, 500);
		link.innerHTML = "Wyślij nowe zdjęcie";
		upload_box_shown = false;
	} else
	{
		//link.style.display = "none";
		//box.style.display = "inline-block";
		box.style.opacity = "1";
		box.style.visibility = "visible";
		link.innerHTML = "Schowaj to";
		upload_box_shown = true;
	}
}

function toggle_tooltip(img)
{
	if (img.nextSibling.style.opacity == "1")
	{
		img.nextSibling.style.opacity = "0";
		img.nextSibling.style.width = "0px";
		img.nextSibling.style.height = "0px";
		img.nextSibling.style.marginTop = "0px";
	} else
	{
		img.nextSibling.style.opacity = "1";
		img.nextSibling.style.width = "200px";
		img.nextSibling.style.height = "80px";
		img.nextSibling.style.marginTop = "-95px";
	}
	
}

function show_tooltip(img)
{
	img.onmouseout = function() { 	img.nextSibling.style.width = "0px";
									img.nextSibling.style.height = "0";
									img.nextSibling.style.opacity = "0";
									//img.nextSibling.style.marginTop = "0";
									setTimeout(function() { img.nextSibling.parentNode.removeChild(img.nextSibling); }, 500); 
								}

	var tooltip = document.createElement("div");
	tooltip.setAttribute('class', 'tooltip');
	tooltip.style.position = "absolute";
	tooltip.style.width = "0";
	tooltip.style.height = "0";
	tooltip.style.opacity = "0";
	tooltip.style.marginLeft = "150px";
	tooltip.style.marginTop = "0";
	tooltip.style.overflow = "hidden";
	tooltip.style.border = "1px black solid";
	img.parentNode.insertBefore(tooltip, img.nextSibling);
		
	var h = 0;
	var xmlhttp;
	if (window.XMLHttpRequest)
		xmlhttp=new XMLHttpRequest();
	else
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			tooltip.innerHTML = xmlhttp.responseText;
			setTimeout(function() { h = tooltip.querySelector("#obraz-opis-autor").clientHeight + tooltip.querySelector("#obraz-opis-nazwa").clientHeight + tooltip.querySelector("#obraz-opis-tresc").clientHeight; tooltip.style.height = h + "px"; }, 400);
		}
	}
	xmlhttp.open("GET","get_image_desc.php?nazwa=" + img.id + "&q=" + Math.random(),true);
	xmlhttp.send();
	
	setTimeout(function() { tooltip.style.opacity = "1"; tooltip.style.width = "200px"; tooltip.style.height = "60px"; tooltip.style.marginTop = "-100px"; }, 50);
}

function pokaz_obraz(img)
{
	if (image_shown)
	{
		var div = document.getElementById("js-image-div");
		closeNow(div);
		image_shown = false;
	}
	
	if (document.getElementById("js-image-div"))
		return;
		
	var div = document.createElement("iframe");
	div.frameBorder = 0;
	div.setAttribute('id', 'js-image-div');
	div.setAttribute('border', '0');
	div.setAttribute('class', 'image-loading');
	div.style.border = "1px black solid";
	div.style.width = "64px";
	div.style.height = "64px";
	div.style.background = "white";
	div.style.color = "white";
	div.style.position = "fixed";
	div.style.left = "50%";
	div.style.top = "50%";
	div.style.margin = "-25px 0px 0px -25px";	
	document.body.appendChild(div);
	div.contentWindow.document.open();
	div.contentWindow.document.write("<html><body><img style = \"width: 100%; height: 100%;\" src = loading.gif></body></html>");
	div.contentWindow.document.close();
		
	// When loaded, display image, and adjust size of div
	// div.setAttribute('class', 'image-full');
	
	var xmlhttp;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			var image = new Image();
			image.src = "data:image/jpeg;base64," + xmlhttp.responseText;
			image.onerror = function() {alert("Image failed!");}
			image.onload = function() {
				maximize(div);
				div.contentWindow.document.open();
				div.contentWindow.document.write("<html style = \"cursor: pointer;\"><body><div style = \"width: 100%; height: 100%; background-color: black; margin: -1px; padding: 1px; \"> <img style = \"width: 100%; height: 100%; \" src = data:image/jpeg;base64," + image.src + "> </div></body></html>");
				div.contentWindow.document.close();
			}
		}
		
	}
	xmlhttp.open("GET","pobierz_obraz.php?baza=" + img.getAttribute('baza') + "&id=" + img.id + "&q=" + Math.random(),true);
	xmlhttp.send();
	setTimeout(function() { image_shown = true; }, 5);
}

function show_image(img)
{	
	if (image_shown)
	{
		var div = document.getElementById("js-image-div");
		closeNow(div);
		image_shown = false;
	}
	
	if (document.getElementById("js-image-div"))
		return;
		
	var div = document.createElement("iframe");
	div.frameBorder = 0;
	div.setAttribute('id', 'js-image-div');
	div.setAttribute('border', '0');
	div.setAttribute('class', 'image-loading');
	div.style.border = "1px black solid";
	div.style.width = "64px";
	div.style.height = "64px";
	div.style.background = "white";
	div.style.color = "white";
	div.style.position = "fixed";
	div.style.left = "50%";
	div.style.top = "50%";
	div.style.margin = "-25px 0px 0px -25px";	
	document.body.appendChild(div);
	div.contentWindow.document.open();
	div.contentWindow.document.write("<html><body><img style = \"width: 100%; height: 100%;\" src = loading.gif></body></html>");
	div.contentWindow.document.close();
	
	// When loaded, display image, and adjust size of div
	// div.setAttribute('class', 'image-full');
	
	var xmlhttp;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			// TODO:
			// Ajax musi zwrócić rozmiar obrazu przed samym obrazem
			// Dzięki temu będzie można ustawić rozmiar obrazu w iframie
			// (większy wymiar - 100%, mniejszy - odpowiednio mniej)
			
			var image = new Image();
			image.src = "data:image/jpeg;base64," + xmlhttp.responseText;
			image.onload = function() {
				maximize(div);
				div.contentWindow.document.open();
				div.contentWindow.document.write("<html style = \"cursor: pointer;\"><body><div style = \"width: 100%; height: 100%; background-color: black; margin: -1px; padding: 1px; \"> " +
												"<img style = \"width: 100%; height: 100%; \" src = data:image/jpeg;base64," + xmlhttp.responseText + "> </div></body></html>");
				div.contentWindow.document.close();
			}
			//return false;
		}
		
	}
	xmlhttp.open("GET","get_image.php?nazwa=" + img.id + "&q=" + Math.random(),true);
	xmlhttp.send();
	setTimeout(function() { image_shown = true; }, 5);
}