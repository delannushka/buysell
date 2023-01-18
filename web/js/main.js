'use strict';

(function () {
  var chat = document.querySelector('.chat');
  var buttonToOpenChat = document.querySelector('.chat-button');
  var pageFooter = document.querySelector('.page-footer');

  function openChat() {
    chat.classList.remove('visually-hidden');
    chat.classList.add('chat--open');
    userChatField.focus();
  }

  function closeChat() {
    chat.classList.remove('chat--open');
    chat.classList.add('visually-hidden');
  }

  if (chat && buttonToOpenChat && pageFooter) {
    var userChatField = chat.querySelector('[name="chat-message"]');

    document.addEventListener('click', function (evt) {
      //evt.preventDefault();
      if (evt.target === buttonToOpenChat) {
        evt.preventDefault();
        openChat();
      }
      if (!evt.target.closest('.chat') && !evt.target.closest('.chat-button')) {
        closeChat();
      }
    });

    window.addEventListener('keydown', function (evt) {
      if (evt.keyCode === 27) {
        closeChat();
      }
    });

    var pageHeight = document.body.clientHeight;
    var currentWindowHeight = window.innerHeight;
    var footerHeight = pageFooter.clientHeight;
    var lastKnownScrollPosition = 0;
    var valueOfReachingFooter = pageHeight - currentWindowHeight - footerHeight;
    var maxScrollPosition = pageHeight - currentWindowHeight;
    var amendment = 0;
    var INDENT = 49;

    document.addEventListener('scroll', function () {
      lastKnownScrollPosition = window.scrollY;

      if (lastKnownScrollPosition > valueOfReachingFooter) {
        amendment = maxScrollPosition - lastKnownScrollPosition;
        buttonToOpenChat.style.bottom = footerHeight + INDENT - amendment + 'px';
        chat.style.bottom = footerHeight + INDENT - amendment + 'px';
      }
    });
  }
})();

'use strict';

(function() {
  var deletEls = document.querySelectorAll('.js-delete');
  for (var i = 0; i < deletEls.length; i++) {
    deletEls[i].addEventListener('click', function() {
      var card = this.closest('.js-card');
      card.parentNode.removeChild(card);

    })
  }
})();

'use strict';

(function () {
  var form = document.querySelector('.form');

  if (form) {
    var formFields = form.querySelectorAll('.js-field');
    var formButton = form.querySelector('.js-button');
    var userPassword = form.querySelector('[name="user-password"]');
    var userPasswordAgain = form.querySelector('[name="user-password-again"]');
    var userNameField = form.querySelector('[name="user-name"]');
    var userEmailField = form.querySelector('[name="user-email"]');
    var REGEX_NAME = /[^a-zA-Zа-яёА-ЯЁ\-\s]/g;

    var validateNameInput = function (formElement) {
      var RegEx = REGEX_NAME;
      if (RegEx) {
        formElement.addEventListener('input', () => {
          formElement.value = formElement.value.replace(RegEx, '');
        });
        return;
      }
    };

    //валидация поля email
    var validateEmailInput = function (input) {
      var flag = true;
      var emailString = /[a-zA-Zа-яёА-ЯЁ0-9]{1}([a-zA-Zа-яёА-ЯЁ0-9\-_\.]{1,})?@[a-zA-Zа-яёА-ЯЁ0-9\-]{1}([a-zA-Zа-яёА-ЯЁ0-9.\-]{1,})?[a-zA-Zа-яёА-ЯЁ0-9\-]{1}\.[a-zA-Zа-яёА-ЯЁ]{2,6}/;
      var regEmail = new RegExp(emailString, '');
      if (regEmail.test(input.value)) {
        input.parentNode.classList.remove('form__field--invalid');
      } else {
        input.parentNode.classList.add('form__field--invalid');
        flag = false;
      }
      return flag;
    };

    var doPasswordsMatch = function () {
      if (userPassword.value === userPasswordAgain.value) {
        userPasswordAgain.parentNode.classList.remove('form__field--invalid');
        return true;
      } else {
        userPasswordAgain.parentNode.classList.add('form__field--invalid');
        formButton.setAttribute('disabled', 'disabled');
        return false;
      }
    };

    if (userNameField && userEmailField && userPassword && userPasswordAgain) {
      // включаем ограничения для ввода поля имя пользователя
      validateNameInput(userNameField);

      userEmailField.addEventListener('input', function () {
        validateEmailInput(userEmailField);
      });

      Array.prototype.slice.call(formFields).forEach(function (field) {
        field.addEventListener('input', function () {
          setFillField(field);
          if (formButton) {
            if (getFillFields() && setTimeout(doPasswordsMatch, 1000)) {
              formButton.removeAttribute('disabled');
            } else {
              formButton.setAttribute('disabled', 'disabled');
            }
          }
        });
      });
    }

    var setFillField = function (field) {
      if (field.value) {
        field.parentNode.classList.add('form__field--fill');
      } else {
        field.parentNode.classList.remove('form__field--fill');
      }
      field.addEventListener('focus', function () {
        field.parentNode.classList.add('form__field--focus');
      });
      field.addEventListener('blur', function () {
        field.parentNode.classList.remove('form__field--focus');
      });
    };

    var getFillFields = function () {
      var fill = true;
      for (var i = 0; i < formFields.length; i++) {
        if (!formFields[i].value) {
          fill = false;
          break;
        }
      }
      return fill;
    };

    Array.prototype.slice.call(formFields).forEach(function (field) {
      setFillField(field);
    });

    Array.prototype.slice.call(formFields).forEach(function (field) {
      field.addEventListener('input', function () {
        setFillField(field);
        if (formButton) {
          if (getFillFields()) {
            formButton.removeAttribute('disabled');
          } else {
            formButton.setAttribute('disabled', 'disabled');
          }
        }
      });
    });

    var selects = document.querySelectorAll('.js-multiple-select');
    for (var i = 0; i < selects.length; i++) {
      var placeholder = selects[i].getAttribute('data-label');
      var SS = new Selectr(selects[i], {
        searchable: false,
        multiple: true,
        width: 222,
        placeholder: placeholder
      });
      var selection = Selectr.prototype.select,
        deselection = Selectr.prototype.deselect;
      var ours = document.createElement('div');
      ours.className = SS.selected.className;
      SS.selected.className += ' selectr-selected--hidden';
      SS.selected.parentNode.insertBefore(ours,SS.selected);
      var updateOurs = function(){
        ours.innerText = SS.selected.innerText.trim().replace(/\n/g, ', ') || placeholder;
      };
      Selectr.prototype.select = function(){
        selection.apply(this, arguments);
        updateOurs();
      };

      Selectr.prototype.deselect = function(){
        deselection.apply(this, arguments);
        updateOurs();
      };
      updateOurs();
    }

    var priceField = form.querySelector('.js-price');
    if (priceField) {
      priceField.addEventListener('keydown', function(e) {
        if (window.event.keyCode >= 65 && window.event.keyCode <= 90 || window.event.keyCode === 189 || window.event.keyCode === 188) {
          e.preventDefault();
        }
        if (window.event.keyCode === 190 && (!priceField.value || priceField.value.includes('.'))) {
          e.preventDefault();
        }
      })
    }
  }

})();

'use strict';

(function () {
  var signUpAvatarContainer = document.querySelector('.js-preview-container');

  if (signUpAvatarContainer) {
    var signUpFieldAvatarInput = signUpAvatarContainer.querySelector('.js-file-field');
    var signUpAvatar = signUpAvatarContainer.querySelector('.js-preview');

    var readFilePhoto = function (file) {
      var reader = new FileReader();
      reader.addEventListener('load', function () {
        var image = document.createElement('img');
        image.src = reader.result;
        signUpAvatar.innerHTML = '';
        signUpAvatar.appendChild(image);
      });
      reader.readAsDataURL(file);
    };

    signUpFieldAvatarInput.addEventListener('change', function () {
      var file = signUpFieldAvatarInput.files[0];
      readFilePhoto(file);
      signUpAvatarContainer.classList.add('uploaded');
    });
  }
})();

'use strict';

(function () {
  svg4everybody();
})();

'use strict';

(function () {
  var search = document.querySelector('.search');

  if (search) {
    var searchInput = search.querySelector('.search input');
    var searchCloseButton = search.querySelector('.search__close-btn');

    if (searchInput.value) {
      search.classList.add('search--active');
      searchCloseButton.classList.add('search__close-btn--active');
    } else {
      search.classList.remove('search--active');
      searchCloseButton.classList.remove('search__close-btn--active');
    }

    searchInput.addEventListener('change', function () {
      if (searchInput.value) {
        search.classList.add('search--active');
      } else {
        search.classList.remove('search--active');
      }
    });

    searchInput.addEventListener('input', function () {
      if (searchInput.value) {
        searchCloseButton.classList.add('search__close-btn--active');
      } else {
        searchCloseButton.classList.remove('search__close-btn--active');
      }
    });

    searchCloseButton.addEventListener('click', function () {
      searchCloseButton.classList.remove('search__close-btn--active');
      searchInput.value = '';
      search.classList.remove('search--active');
    });
  }
})();

//# sourceMappingURL=main.js.map
