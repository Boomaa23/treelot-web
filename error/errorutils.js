function center() {
  document.getElementById("container").style.paddingTop = (document.documentElement.clientHeight * 0.8) + "px";
  setTimeout(center, 1000);
}

center();