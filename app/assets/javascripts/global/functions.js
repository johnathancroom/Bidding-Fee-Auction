function number_to_currency(number) {
  var temp = (number*.01).toString();
  var split = temp.split(".");
  
  if(split[1] == undefined)
  {
    split[1] = "00";
  }
  if(split[1].length == 1)
  {
    split[1] += "0";
  }
  
  return "$"+parseFloat(split.join(".")).toFixed(2);
}