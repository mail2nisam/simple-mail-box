require('./bootstrap');

$('.delete-single-email').click(function (e) {
    e.preventDefault();
    let confirmation = confirm('Are you sure to delete this email?');
    if (confirmation) {
        axios.delete($(this).attr('href'))
            .then(function (response) {
                console.log(response)
            })
    }
});
