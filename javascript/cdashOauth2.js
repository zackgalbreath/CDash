// loosely adapted & updated from the example here:
// http://www.gethugames.in/blog/2012/04/authentication-and-authorization-for-google-apis-in-javascript-popup-window-tutorial.html

var OAUTHURL    =   'https://accounts.google.com/o/oauth2/auth?';
var SCOPE       =   'https://www.googleapis.com/auth/userinfo.email';
var TYPE        =   'code';

function oauth2Login() {
  // construct redirect URI
  var REDIRECT = window.location.href;
  REDIRECT = REDIRECT.substring(0, REDIRECT.lastIndexOf("/"));
  REDIRECT += "/googleauth_callback.php";

  // get state (anti-forgery token) from session via CDash API
  $.get('api/getState.php', function(STATE) {

    // construct Google authentication URL with the query string all filled out
    var _url = OAUTHURL + 'scope=' + SCOPE + '&client_id=' + CLIENTID +
      '&redirect_uri=' + REDIRECT + '&response_type=' + TYPE + '&state=' +
      STATE;

    // open the Google authentication popup window
    var win = window.open(_url, "Login with Google", 'width=800, height=600');

    // poll for it to change its URL to our redirect page
    var pollTimer = window.setInterval(function() {
      try {
        if (win.document.URL.indexOf(REDIRECT) != -1) {
          // once it does, stop polling and close the window
          window.clearInterval(pollTimer);
          win.close();

          // redirect the user based on whether or not they logged in
          // successfully.
          $.get('api/checkSession.php', function(data) {
            if( data == "Expired" ) {
              window.location = "register.php";
            } else if (data == "Active" ) {
              window.location = "user.php";
            } else {
              var obj = JSON.parse(data);
              window.location = "register.php?firstname=" + obj.firstname + "&lastname=" + obj.lastname + "&email=" + obj.email;
            }
          });
        }
      } catch(e) {
        // pass
      }
    }, 100);
  });
}
