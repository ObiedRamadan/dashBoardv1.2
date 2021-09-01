let updataField ;
$('.updata').click((e) => {
    updataField = $(e.target).parent().parent().next();
    updataField.fadeIn(500)
})

$('.cansal').click(() => {
    updataField.fadeOut(500)
})