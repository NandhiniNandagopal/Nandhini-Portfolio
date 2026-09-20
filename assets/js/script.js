// ============================================================
// Nandhini Nandagopal — Portfolio
// Client-side interactivity
// ============================================================

document.addEventListener('DOMContentLoaded', function () {

  /* ---- Mobile nav toggle ---- */
  var toggle = document.querySelector('.nav-toggle');
  var links = document.querySelector('.nav-links');
  if (toggle && links) {
    toggle.addEventListener('click', function () {
      var open = links.classList.toggle('open');
      toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
    links.querySelectorAll('a').forEach(function (a) {
      a.addEventListener('click', function () {
        links.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      });
    });
  }

  /* ---- Mark current page in nav ---- */
  var here = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.nav-links a').forEach(function (a) {
    var href = a.getAttribute('href');
    if (href === here) a.setAttribute('aria-current', 'page');
  });

  /* ---- Scroll reveal (single subtle pass, not per-card fuss) ----
     Safety net: if IntersectionObserver is unavailable, slow to fire,
     or JS runs in a headless/older engine, content must never stay
     stuck invisible — a 900ms timeout force-reveals everything. */
  var revealEls = document.querySelectorAll('.reveal');
  if (revealEls.length) {
    if ('IntersectionObserver' in window) {
      var io = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add('in');
            io.unobserve(entry.target);
          }
        });
      }, { threshold: 0.15 });
      revealEls.forEach(function (el) { io.observe(el); });
    } else {
      revealEls.forEach(function (el) { el.classList.add('in'); });
    }
    setTimeout(function () {
      revealEls.forEach(function (el) { el.classList.add('in'); });
    }, 900);
  }

  /* ---- Contact form: client-side validation + async submit to PHP/MySQL ---- */
  var form = document.getElementById('contact-form');
  if (form) {
    var status = document.getElementById('form-status');

    function setError(fieldId, message) {
      var field = document.getElementById(fieldId);
      var wrap = field.closest('.form-field');
      wrap.classList.add('has-error');
      wrap.querySelector('.error-msg').textContent = message;
    }
    function clearErrors() {
      form.querySelectorAll('.form-field').forEach(function (w) {
        w.classList.remove('has-error');
      });
    }
    function isValidEmail(v) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v);
    }

    form.addEventListener('submit', function (e) {
      e.preventDefault();
      clearErrors();
      status.classList.remove('show', 'success', 'error');

      var name = document.getElementById('cf-name').value.trim();
      var email = document.getElementById('cf-email').value.trim();
      var subject = document.getElementById('cf-subject').value.trim();
      var message = document.getElementById('cf-message').value.trim();
      var valid = true;

      if (name.length < 2) { setError('cf-name', 'Please enter your name.'); valid = false; }
      if (!isValidEmail(email)) { setError('cf-email', 'Please enter a valid email address.'); valid = false; }
      if (subject.length < 2) { setError('cf-subject', 'Please add a short subject.'); valid = false; }
      if (message.length < 10) { setError('cf-message', 'Message should be at least 10 characters.'); valid = false; }

      if (!valid) return;

      var submitBtn = form.querySelector('button[type="submit"]');
      var originalLabel = submitBtn.textContent;
      submitBtn.disabled = true;
      submitBtn.textContent = 'Sending…';

      var formData = new FormData();
      formData.append('name', name);
      formData.append('email', email);
      formData.append('subject', subject);
      formData.append('message', message);

      fetch('php/contact.php', { method: 'POST', body: formData })
        .then(function (res) { return res.json(); })
        .then(function (data) {
          if (data.success) {
            status.textContent = data.message || 'Thanks! Your message has been received.';
            status.classList.add('show', 'success');
            form.reset();
          } else {
            status.textContent = data.message || 'Something went wrong. Please try again.';
            status.classList.add('show', 'error');
          }
        })
        .catch(function () {
          status.textContent = 'Could not reach the server. Please check your PHP/MySQL setup.';
          status.classList.add('show', 'error');
        })
        .finally(function () {
          submitBtn.disabled = false;
          submitBtn.textContent = originalLabel;
        });
    });
  }

});
