// Hamburger
const menuToggle = document.querySelector(".menu-toggle input");
const nav = document.querySelector("nav ul");

menuToggle.addEventListener("click", function () {
  nav.classList.toggle("slide");
});
// copy
//  Ip-Server
function javaip() {
  var copyText = document.getElementById("Copy0");
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  navigator.clipboard.writeText(copyText.value);
  var x = document.getElementById("popup0");
  x.className = "show";
  setTimeout(function () {
    x.className = x.className.replace("show", "");
  }, 3000);
}
function bedip() {
  var copyText = document.getElementById("Copy1");
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  navigator.clipboard.writeText(copyText.value);
  var x = document.getElementById("popup1");
  x.className = "show";
  setTimeout(function () {
    x.className = x.className.replace("show", "");
  }, 3000);
}
// Port-Server
function bedport() {
  var copyText = document.getElementById("Copy2");
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  navigator.clipboard.writeText(copyText.value);
  var x = document.getElementById("popup2");
  x.className = "show";
  setTimeout(function () {
    x.className = x.className.replace("show", "");
  }, 3000);
}
// Multi Tabs
function openPage(pageName, elmnt, color) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].style.backgroundColor = "";
  }
  document.getElementById(pageName).style.display = "block";
  elmnt.style.backgroundColor = color;
}
document.getElementById("defaultOpen").click();

// Live Chat
window.$crisp = [];
window.CRISP_WEBSITE_ID = "f4dc4c22-da4a-4696-8e9f-b4166de85534";
(function () {
  d = document;
  s = d.createElement("script");
  s.src = "https://client.crisp.chat/l.js";
  s.async = 1;
  d.getElementsByTagName("head")[0].appendChild(s);
})();
