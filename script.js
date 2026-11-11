'use strict';

(function () {
  var cookieName = 'yc_cookie_ok';
  var banner = document.getElementById('cookie-notice');

  if (!banner) {
    return;
  }

  var acknowledged = document.cookie.split('; ').indexOf(cookieName + '=1') !== -1;

  if (acknowledged) {
    return;
  }

  banner.hidden = false;

  var button = banner.querySelector('.cookie-notice__button');

  if (!button) {
    return;
  }

  button.addEventListener('click', function () {
    var secure = window.location.protocol === 'https:' ? '; Secure' : '';
    document.cookie = cookieName + '=1; Max-Age=31536000; Path=/; SameSite=Lax' + secure;
    banner.hidden = true;
  });
})();

flatpickr('#lot-date', {
  enableTime: false,
  dateFormat: "Y-m-d",
  locale: "ru"
});
