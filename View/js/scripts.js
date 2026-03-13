// Function to replace innerHTML
function removeAllChildNodes(parent) {
  while (parent.firstElementChild) {
    parent.removeChild(parent.firstElementChild);
  }
}

function formatDate(date) {
  dateArray = date.split('-');

  dateArray[2] = Number(dateArray[2]);

  if(dateArray[1] === "01") dateArray[1] = "Ene";
  if(dateArray[1] === "02") dateArray[1] = "Feb";
  if(dateArray[1] === "03") dateArray[1] = "Mar";
  if(dateArray[1] === "04") dateArray[1] = "Abr";
  if(dateArray[1] === "05") dateArray[1] = "May";
  if(dateArray[1] === "06") dateArray[1] = "Jun";
  if(dateArray[1] === "07") dateArray[1] = "Jul";
  if(dateArray[1] === "08") dateArray[1] = "Ago";
  if(dateArray[1] === "09") dateArray[1] = "Sep";
  if(dateArray[1] === "10") dateArray[1] = "Oct";
  if(dateArray[1] === "11") dateArray[1] = "Nov";
  if(dateArray[1] === "12") dateArray[1] = "Dic";

  return dateArray.reverse().join('/');
}
