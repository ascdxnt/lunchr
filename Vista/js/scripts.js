//Función para sustituir innerHTML
function removeAllChildNodes(parent) {
  while (parent.firstElementChild) {
    parent.removeChild(parent.firstElementChild);
  }
}

function formatoFecha(fecha){
  arrayFecha = fecha.split('-');

  arrayFecha[2] = Number(arrayFecha[2]);

  if(arrayFecha[1] === "01") arrayFecha[1] = "Ene";
  if(arrayFecha[1] === "02") arrayFecha[1] = "Feb";
  if(arrayFecha[1] === "03") arrayFecha[1] = "Mar";
  if(arrayFecha[1] === "04") arrayFecha[1] = "Abr";
  if(arrayFecha[1] === "05") arrayFecha[1] = "May";
  if(arrayFecha[1] === "06") arrayFecha[1] = "Jun";
  if(arrayFecha[1] === "07") arrayFecha[1] = "Jul";
  if(arrayFecha[1] === "08") arrayFecha[1] = "Ago";
  if(arrayFecha[1] === "09") arrayFecha[1] = "Sep";
  if(arrayFecha[1] === "10") arrayFecha[1] = "Oct";
  if(arrayFecha[1] === "11") arrayFecha[1] = "Nov";
  if(arrayFecha[1] === "12") arrayFecha[1] = "Dic";

  return arrayFecha.reverse().join('/');
}
