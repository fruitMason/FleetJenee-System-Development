
function SubmitFromAlert(form, mes_title) {
    event.preventDefault();
    Swal.fire({
        icon: 'question',
        title: mes_title,
        showDenyButton: true,
        confirmButtonText: 'Yes',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
            //console.log('loggg',form);
        }
    })
};

function SumitEdit(mes_title, url) {
    event.preventDefault();
    Swal.fire({
        icon: 'question',
        title: mes_title,
        showDenyButton: true,
        confirmButtonText: 'Yes',
    }).then((result) => {
        if (result.isConfirmed) {
            //window.open(url,"_self");
            // window.location.href = url;
            console.log(url);
            // Simulate an HTTP redirect:
            window.location.replace(url)
        }
    })
};

function SubmitDelete(form, mes_title) {
    event.preventDefault();
    Swal.fire({
        icon: 'question',
        title: mes_title,
        showDenyButton: true,
        confirmButtonText: 'Yes',
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    })


    // swal({
    //     //title: "Are you sure?",
    //     text: mes_title,
    //     type: "question",
    //     showCancelButton: true,
    //     confirmButtonClass: "btn btn-danger",
    //     confirmButtonText: "Yes, delete it!",
    //     closeOnConfirm: false
    // },
    //     function (isConfirm) {
    //         if (isConfirm) {
    //             console.log('confirmed');
    //         } else {
    //             console.log('cancelled');
    //         }


    //     });

    // swal({
    //     title: "Are you sure?",
    //     text: "You will not be able to recover this imaginary file!",
    //     type: "warning",
    //     showCancelButton: true,
    //     confirmButtonClass: "btn-danger",
    //     confirmButtonText: "Yes, delete it!",
    //     cancelButtonText: "No, cancel plx!",
    //     closeOnConfirm: false,
    //     closeOnCancel: false
    //   },
    //   function(isConfirm) {
    //     if (isConfirm) {
    //      console.log('inside conifirmed!');
    //     } else {
    //         console.log('inside cancelled!');
    //     }
    //   });



};





