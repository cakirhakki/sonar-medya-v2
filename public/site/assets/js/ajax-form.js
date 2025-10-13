$(function () {
  var form = $('#contact-form');
  var formMessages = $('.ajax-response');

  $(form).on('submit', function (e) {
    e.preventDefault();

    // Varsayılan durumları sıfırla
    formMessages.removeClass('success error').empty();

    $.ajax({
      type: 'POST',
      url: form.attr('action'),
      data: form.serialize(),                    // _token zaten formda
      dataType: 'json',                          // JSON bekliyoruz
      headers: { 'Accept': 'application/json' }, // Laravel'e "JSON istiyorum" de
    })
      .done(function (res) {
        // Başarılı
        formMessages.removeClass('error').addClass('success');
        formMessages.text(res.message || 'Mesajınız alındı.');
        // Formu temizle
        form.find('input[type=text], input[type=email], textarea').val('');
        form.find('input[type=checkbox]').prop('checked', false);
      })
      .fail(function (xhr) {
        formMessages.removeClass('success').addClass('error');

        // 422 validasyon hataları
        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
          var errs = xhr.responseJSON.errors;
          var list = $('<ul class="m-0 ps-4"></ul>');
          Object.keys(errs).forEach(function (k) {
            errs[k].forEach(function (msg) {
              list.append($('<li></li>').text(msg));
            });
          });
          formMessages.html(list);
          return;
        }

        // Diğer hatalar
        var msg =
          (xhr.responseJSON && (xhr.responseJSON.message || xhr.responseJSON.error)) ||
          xhr.responseText ||
          'Bir hata oluştu, lütfen tekrar deneyin.';
        formMessages.text(msg);
      });
  });
});
