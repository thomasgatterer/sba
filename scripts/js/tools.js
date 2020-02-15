function createXMLHttpRequestObject() {
  try {
    resObject = new ActiveXObject("Microsoft.XMLHTTP");
  }
  catch(Error) {
    try {
      resObject = new ActiveXObject("MSXLM2.XMLHTTP");
    }
    catch(Error) {
      try {
        resObject = new XMLHttpRequest();
      }
      catch(Error) {
        console.log("Erzeugung des XMLHttpRequest-Objektes fehlgeschlagen");
        resObject = null;
      }
    }
  }
  return resObject;
}

function sendRequest(url, sendParam, which) {
  if (! resObject) return;
  if (sendParam == null) return;
  resObject.open('post', url, true);
  switch (which) {
    case NUMMER : resObject.onreadystatechange = fillFields; break;
    default: resObject.onreadystatechange = handleResponse;
  }
  resObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  resObject.send(sendParam);
}

function handleResponse() {
  if (resObject.readyState == 4) {
    if (resObject.status == 200) {
      console.log(resObject.responseText);
    }
  }
}

function fillFields() {
  if (resObject.readyState == 4) {
    if (resObject.status == 200) {
      console.log(resObject.responseText);
      splitted = resObject.responseText.split("|");
      document.getElementById("buchnummer").value = splitted[0];
      document.getElementById("buchtitel").value = splitted[1];
      document.getElementById("buchpreis").value = splitted[2];
    }
  }
}

function gotoPrint() {
  k = document.getElementById("hiddenData").value;
  h = "print.php"
  if (k != "") {
    h = "print.php?klasse=" + k;
  }
  window.location.href = h;
}

function setHiddenValue(val) {
  h = document.getElementById("hiddenData");
  console.log(val);
  if (val === "eigene") {
    h.value = val;
  }
  else {
    if (val === "klasse") {
      h.value = val;
    }
  }
  document.eingabeform.submit();
}

function showDeleteForm() {
  document.getElementById("deleteform").style.visibility = "visible";
}

function deleteData()   {
  const form = document.createElement('form');
  form.method = 'post';
  form.action = "eingabe.php";
  const hf1 = document.createElement('input');
  hf1.type = 'hidden';
  hf1.name = 'buttonDel';
  hf1.value = 'deleteDaten';
  form.appendChild(hf1);
  const hf2 = document.createElement('input');
  hf2.type = 'hidden';
  hf2.name = 'buchnummerDelete';
  hf2.value = document.getElementById('buchnummer').value;
  form.appendChild(hf2);
  const hf3 = document.createElement('input');
  hf3.type = 'hidden';
  hf3.name = 'klasseDelete';
  hf3.value = document.getElementById('klassen').value;
  form.appendChild(hf3);
  const hf4 = document.createElement('input');
  hf4.type = 'hidden';
  hf4.name = 'lehrerhandDelete';
  hf4.value = document.getElementById('lehrerhand').checked ? 1 : 0;
  form.appendChild(hf4);
  document.body.appendChild(form);
  form.submit();
}

// variables for AJAX
NUMMER = 1;
resObject = createXMLHttpRequestObject(); // automatic initialization
