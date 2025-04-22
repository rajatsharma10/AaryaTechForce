(function () {
  "use strict";

  let forms = document.querySelectorAll('.php-email-form');

  forms.forEach(function (e) {
    e.addEventListener('submit', function (event) {
      event.preventDefault();

      let thisForm = this;

      let action = thisForm.getAttribute('action');
      let recaptcha = thisForm.getAttribute('data-recaptcha-site-key');

      if (!action) {
        displayError(thisForm, 'The form action property is not set!');
        return;
      }

      thisForm.querySelector('.loading').style.display = 'block';
      thisForm.querySelector('.error-message').style.display = 'none';
      thisForm.querySelector('.sent-message').style.display = 'none';

      let formData = new FormData(thisForm);

      if (recaptcha) {
        if (typeof grecaptcha !== "undefined") {
          grecaptcha.ready(function () {
            try {
              grecaptcha.execute(recaptcha, { action: 'php_email_form_submit' })
                .then(token => {
                  formData.set('recaptcha-response', token);
                  php_email_form_submit(thisForm, action, formData);
                });
            } catch (error) {
              displayError(thisForm, 'reCAPTCHA execution failed: ' + error.message);
            }
          });
        } else {
          displayError(thisForm, 'The reCAPTCHA JavaScript API URL is not loaded!');
        }
      } else {
        php_email_form_submit(thisForm, action, formData);
      }
    });
  });

  function php_email_form_submit(thisForm, action, formData) {
    fetch(action, {
      method: 'POST',
      body: formData,
      headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
      .then(response => {
        if (response.ok) {
          return response.text();
        } else {
          throw new Error(`${response.status} ${response.statusText} ${response.url}`);
        }
      })
      .then(data => {
        thisForm.querySelector('.loading').style.display = 'none';

        if (data.trim().toLowerCase().includes('success') || data.trim().toLowerCase() === 'ok') {
          thisForm.querySelector('.sent-message').style.display = 'block';
          thisForm.querySelector('.error-message').style.display = 'none';
          thisForm.reset();
        } else {
          throw new Error(data ? data : 'Form submission failed with no error message.');
        }
      })
      .catch((error) => {
        displayError(thisForm, 'Form submission error: ' + error.message);
      });
  }

  function displayError(thisForm, error) {
    thisForm.querySelector('.loading').style.display = 'none';
    thisForm.querySelector('.sent-message').style.display = 'none';
    thisForm.querySelector('.error-message').innerHTML = error;
    thisForm.querySelector('.error-message').style.display = 'block';
  }

})();
