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
var ilosc_komunikatow = 0;

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
		if (deleteIcon != null)
			deleteIcon.style.display = 'none';
		if (selectAllIcon != null)	
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
	if (object.parentNode != null)
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
		img.nextSibling.style.height = "90px";		// wykryj wysokość!
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

function usun_baze(button)
{
	button.parentNode.style.opacity = 0;
	setTimeout(function() { button.parentNode.parentNode.removeChild(button.parentNode); }, 500);	
	sprawdz_formularz(button.parentNode);
}
function usun_konto(button)
{
	button.parentNode.style.opacity = 0;
	setTimeout(function() { button.parentNode.parentNode.removeChild(button.parentNode); }, 500);	
	sprawdz_formularz(button.parentNode);
}

function zapisz_bazy(button)
{
	var wynik = "";
	var form;
	for (var i = 0; i < document.forms.length; i++)
	{
		if (document.forms[i].getAttribute("name") == "konfiguracja-baz-form")
		{
			form = document.forms[i];
			wynik += "DB " +form[0].value + "\n";
			wynik += "Typ: " + form[1].value + "\n";
			wynik += "System: " + form[2].value + "\n";
			wynik += "Baza: " + form[3].value + "\n";
			wynik += "Host: " + form[4].value + "\n";
			wynik += "User: " + form[5].value + "\n";
			wynik += "Pass: " + form[6].value + "\n\n";
		}
	}
	document.forms['zapis-baz']['konfiguracja-baz'].value = wynik;
}

function zapisz_konta(button)
{
	var wynik = "";
	var form;
	for (var i = 0; i < document.forms.length; i++)
	{
		if (document.forms[i].getAttribute("name") == 'konfiguracja-kont-form')
		{
			form = document.forms[i];
			wynik += "Imie: " +form[0].value + "\n";
			wynik += "Nazwisko: " + form[1].value + "\n";
			wynik += "PESEL: " + form[2].value + "\n";
			wynik += "Nr odznaki: " + form[3].value + "\n";
			wynik += "Poziom: " + form[4].value + "\n\n";
		}
	}
	wynik = wynik.substring(0, wynik.length-2);
	document.forms['zapis-kont']['konfiguracja-kont'].value = wynik;
}

function sprawdz_formularz(form)
{
	var co = form.getAttribute('name') == "konfiguracja-baz-form" ? "baz" : "kont";
	var ok = true;
	for (var x = 0; x < document.forms.length; x++)
	{
		if (document.forms[x].getAttribute('name') == form.getAttribute('name'))
		{
			for (var i = 0, iLen = form.length; i < iLen; i++) 
			{
				if (document.forms[x][i].tagName == "INPUT" && document.forms[x][i].getAttribute('type') != "button")
				{
					if (document.forms[x][i].value.length == 0)
						ok = false;
				} 
			}
		}
	}
	
	if (ok)
	{
		document.getElementById("konfiguracja-"+co+"-save").disabled = false;
		document.getElementById("konfiguracja-"+co+"-save").style.opacity = "1";
		document.getElementById("konfiguracja-"+co+"-save").style.cursor = "pointer";
	}
	else 
	{
		if (!document.getElementById("konfiguracja-"+co+"-save").disabled)
			pokaz_ostrzezenie("Aby zapisać konfigurację, uzupełnij wszystkie pola!");
		document.getElementById("konfiguracja-"+co+"-save").disabled = true;
		document.getElementById("konfiguracja-"+co+"-save").style.opacity = "0.2";
		document.getElementById("konfiguracja-"+co+"-save").style.cursor = "default";
	}
}

function dodaj_baze(button)
{
	document.getElementById("konfiguracja-baz-save").disabled = true;
	document.getElementById("konfiguracja-baz-save").style.opacity = "0.2";
	document.getElementById("konfiguracja-baz-save").style.cursor = "default";
		
	var form = document.createElement("form");
	form.onchange = function() { sprawdz_formularz(form); };
	form.setAttribute('class', 'konfiguracja-form');
	form.setAttribute('name', 'konfiguracja-baz-form');
	form.innerHTML = "<span>Nazwa: </span><input value = \"\"><br>" +
					"<span>Typ: </span><input value = \"\"><br>" +
					"<span>System: </span><input value = \"\"><br>" +
					"<span>Baza: </span><input value = \"\"><br>" +
					"<span>Host: </span><input value = \"\"><br>" +
					"<span>Użytkownik: </span><input value = \"\"><br>" +
					"<span>Hasło: </span><input type = \"password\" value = \"\"><br><br><br>" + 
					"<input type = \"button\" style = \"width:16px; height:16px; display:inline-block; cursor:pointer; border:0px; background:none; background-image:url(res/ikonki/delete.png)\" onClick = \"usun_baze(this);\"><br>";
	form.style.opacity = 0;
	setTimeout(function() { form.style.opacity = 1; }, 50);
	button.parentNode.insertBefore(form, button);
}

function dodaj_konto(button)
{
	document.getElementById("konfiguracja-kont-save").disabled = true;
	document.getElementById("konfiguracja-kont-save").style.opacity = "0.2";
	document.getElementById("konfiguracja-kont-save").style.cursor = "default";
	
	var form = document.createElement("form");
	form.onchange = function() { sprawdz_formularz(form); };
	form.setAttribute('class', 'konfiguracja-form');
	form.setAttribute('name', 'konfiguracja-kont-form');
	form.innerHTML = "<span>Imie: </span><input value = \"\"><br>" +
					"<span>Nazwisko: </span><input value = \"\"><br>" +
					"<span>PESEL: </span><input value = \"\"><br>" +
					"<span>Nr odznaki: </span><input value = \"\"><br>" +
					"<span>Poziom: </span><input value = \"\"><br><br>" +
					"<input type = \"button\" style = \"width:16px; height:16px; display:inline-block; cursor:pointer; border:0px; background:none; background-image:url(res/ikonki/delete.png)\" onClick = \"usun_konto(this);\"><br>";
	form.style.opacity = 0;
	setTimeout(function() { form.style.opacity = 1; }, 50);
	button.parentNode.insertBefore(form, button);
}

function pokaz_komunikat(typ, wiadomosc)
{
	var div = document.createElement("div");
	div.setAttribute('class', typ+'-div');
	
	div.onclick = function() { 	setTimeout(function() { div.style.opacity = "0"; }, 100);
								setTimeout(function() { closeNow(div); ilosc_komunikatow--; }, 1100); };
	if (typ == "warning")
	{
		div.style.backgroundColor = "rgba(200, 160, 50, 0.8)";
	} else if (typ == "error")
	{
		div.style.backgroundColor = "rgba(200, 50, 50, 0.8)";
	} else if (typ == "powodzenie")
	{
		div.style.backgroundColor = "rgba(50, 200, 50, 0.8)";
	}
	div.style.border = "2px black solid";
	div.style.position = "fixed";
	var top = 5 + ilosc_komunikatow * 70;
	div.style.cursor = "pointer";
	div.style.fontSize = "18px";
	div.style.color = "white";
	div.style.top = top+"px";
	div.style.left = "25%";
	div.style.margin = "auto";
	div.style.width = "50%";
	div.style.height = "48px";
	div.style.lineHeight = "48px";
	div.style.zIndex = "4";
	div.style.opacity = "0";
	div.innerHTML = "<img src = \"res/ikonki/"+typ+".png\" style = \"float:left;\">" +wiadomosc;
	ilosc_komunikatow++;
	setTimeout(function() { div.style.opacity = "1"; }, 100);
	document.body.appendChild(div);
	setTimeout(function() { div.style.opacity = "0"; }, 7000);
	setTimeout(function() { closeNow(div); ilosc_komunikatow--; }, 8500);
}

function pokaz_ostrzezenie(wiadomosc)
{
	pokaz_komunikat('warning', wiadomosc);
}

function pokaz_blad(wiadomosc)
{
	pokaz_komunikat('error', wiadomosc);
}

function pokaz_powodzenie(wiadomosc)
{
	pokaz_komunikat('powodzenie', wiadomosc);
}

function instalacja_nastepny(div)
{
	var id = parseInt(div.id.substr(div.id.length-1, 1));
	var nast = document.getElementById('instalacja-div-'+(id+1));
	div.style.opacity = "0";
	div.style.backgroundColor = "white";
	nast.style.opacity = "1";
	nast.style.backgroundColor = "transparent";
	
	setTimeout(function() { div.style.display = "none"; nast.style.display = "block"; }, 500);
	
}

function instalacja_poprzedni(div)
{
	var id = parseInt(div.id.substr(div.id.length-1, 1));
	var poprz = document.getElementById('instalacja-div-'+(id-1));
	
	div.style.opacity = "0";
	div.style.backgroundColor = "white";
	poprz.style.opacity = "1";
	poprz.style.backgroundColor = "transparent";
	
	setTimeout(function() { div.style.display = "none"; poprz.style.display = "block"; }, 500);
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