   <script type="text/javascript">
window.getCookie = function(name) {
  match = document.cookie.match(new RegExp(name + '=([^;]+)'));
  if (match) return match[1];
}
	var tzo = getCookie('tzo998');

    if (navigator.cookieEnabled ) {
       document.cookie = "tzo998="+ (- new Date().getTimezoneOffset());
       document.location.reload();
    }
   </script>