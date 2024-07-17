"use strict";

$("#swal-1").click(function() {
	swal('Hello');
});

$("#swal-2").click(function() {
	swal('Good Job', 'You clicked the button!', 'success');
});

$("#swal-3").click(function() {
	swal('Good Job', 'You clicked the button!', 'warning');
});

$("#swal-4").click(function() {
	swal('Good Job', 'You clicked the button!', 'info');
});

$("#swal-5").click(function() {
	swal('Good Job', 'You clicked the button!', 'error');
});

$("#swal-6").click(function(e) {
  let target = e.currentTarget.getAttribute('href');
  e.preventDefault();
  swal({
      title: 'Apakah Kamu Yakin?',
      text: 'Sekali Menghapus, Maka Kamu Tidak Dapat Mengembalikannya Lagi',
      icon: 'warning',
      buttons: true,
      dangerMode: true,
    })
    .then((willDelete) => {
      if (willDelete) {
        window.location.href=target
      swal('Success, Anggota Berhasil di Hapus!', {
        icon: 'success',
      });
      } else {
      swal('Penghapusan Anggota di Batalkan!');
      }
    });
});

$("#swal-7").click(function() {
  swal({
    title: 'What is your name?',
    content: {
    element: 'input',
    attributes: {
      placeholder: 'Type your name',
      type: 'text',
    },
    },
  }).then((data) => {
    swal('Hello, ' + data + '!');
  });
});

$("#swal-8").click(function() {
  swal('This modal will disappear soon!', {
    buttons: false,
    timer: 3000,
  });
});