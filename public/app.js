document.addEventListener('DOMContentLoaded', function () {
  var flash = document.querySelector('.flash');
  if (flash) setTimeout(function () { flash.style.display = 'none'; }, 5000);
});
