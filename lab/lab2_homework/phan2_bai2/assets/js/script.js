/* -------------------------
    Calculator ...
---------------------------*/
var result = document.getElementById("result");
var smallResult = document.getElementById("smallResult");
var cal = false;
var val_1 = false;
var val_2 = false;
var oper = "+";
var operPressed = false;
var tot = 0;
var cal_done = false;
var currentStatus = 0;
var o;

function num(val) {
  val = val.toString();
  if (cal_done) cls();
  if (!operPressed) {
    if (!val_1) val_1 = 0;
    val_1 = val_1 + val;
    val_1 = lengthFix(val_1);
    result.innerHTML = val_1;
    smallResult.innerHTML = val_1;
  }
  if (operPressed) {
    if (!val_2) val_2 = 0;
    val_2 = val_2 + val;
    val_2 = lengthFix(val_2);
    result.innerHTML = val_2;
    smallResult.innerHTML = val_1 + oper + val_2;
  }
}

function calc(val) {
  // Chuyển đổi ký hiệu hiển thị thành toán tử thực tế
  if (val === "×") val = "*";
  if (val === "÷") val = "/";

  if (val_1 && val_2) {
    operPressed = true;
    total();
    oper = val;
  }
  if (cal_done) {
    var x = (val_1 = tot);
    cls();
    val_1 = x;
    val_1 = lengthFix(val_1);
    result.innerHTML = val;
    smallResult.innerHTML = +x + val;
    oper = val;
    operPressed = true;
  }
  if (!val_1 || operPressed) {
    return false;
  }
  if (val_1 && !val_2) {
    result.innerHTML = val;
    var a = smallResult.innerHTML.toString();
    smallResult.innerHTML = a + val;
    oper = val;
    operPressed = true;
  }
}

function total() {
  if (!val_1) return false;
  if (!val_2 && operPressed) {
    tot = magic(val_1, val_1, oper);
    tot = lengthFix(tot);
  }
  if (val_1 && val_2) {
    tot = magic(val_1, val_2, oper);
    tot = lengthFix(tot);
  }
  tot = tot.toString();
  var noDec = tot.indexOf(".") == -1;
  if (!noDec) tot = parseFloat(tot).toFixed(3);
  result.innerHTML = tot;
}

function magic(a, b, oper) {
  switch (oper) {
    case "+":
      tot = +a + +b;
      cal_done = true;
      break;
    case "-":
      tot = +a - +b;
      cal_done = true;
      break;
    case "/":
      tot = +a / +b;
      cal_done = true;
      break;
    case "*":
      tot = +a * +b;
      cal_done = true;
      break;
    case "^": // Thêm trường hợp lũy thừa
      tot = Math.pow(+a, +b);
      cal_done = true;
      break;
    default:
      return false;
  }
  return tot;
}

function cls() {
  smallResult.innerHTML = "";
  result.innerHTML = 0;
  val_1 = false;
  val_2 = false;
  oper = "+";
  tot = 0;
  cal_done = false;
  operPressed = false;
}

function lengthFix(o) {
  o = o.toString();
  if (o != 0 || o != "0") {
    if (o.charAt(0) == 0) o = o.slice(1);
  }
  if (o.toString().length > 12) o = o.substring(0, 12);
  return o;
}

document.onkeyup = function (e) {
  const key = e.key;
  if (!isNaN(key)) num(key);
  if (key === ".") num(".");
  if (key === "/") calc("÷");
  if (key === "*") calc("×");
  if (key === "+") calc("+");
  if (key === "-") calc("-");
  if (key === "^") calc("^"); // Thêm sự kiện bàn phím cho lũy thừa
  if (key === "Enter") total();
  if (key === "Backspace" || key === "Delete") cls();
  if (key === "Escape") cls();
};