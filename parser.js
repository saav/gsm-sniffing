var xhttp = new XMLHttpRequest();
alert("start");
xhttp.onreadystatechange = function() {
    if (xhttp.readyState == 4 && xhttp.status == 200) {
    alert("fc");
    myFunction(xhttp);
    }
}
xhttp.open("GET", "a.xml", true);
xhttp.send();

function myFunction(xml) {
    var xmlDoc = xml.responseXML;
    console.log(xmlDoc.getElementsByTagName("field")[0].nodeValue);
  	//$xml = $( xmlDoc );
  	//$title = $xml.find("field");
  	//alert("print");
    //$('#test').append($title[0].text());
}