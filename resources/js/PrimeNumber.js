$(document).ready(function () {

    $('#prime_number_form').on('submit', function () {
        $.ajax({
            type: 'get',
            url: 'assessment/prime-number/get',
            data: {
                'year': $('input[name="year"]')[0].value
            },
            dataType: 'json',
            beforeSubmit: event.preventDefault(),
            success: function (response) {
                if (typeof response.message === 'undefined') {
                    $('.table').css('display', 'block');
                    $('.table').empty();

                    let string = '';
                    string += '<tr>';
                    string += '<th>Priem getal</th>';
                    string += '<th>Kerstdag</th>';
                    string += '<th>Eeuw</th>';
                    string += '</tr>';
                    for (let i = 0; i < response.primeNumbers.length; i++) {
                        string += '<tr>';
                        string += '<td>' + response.primeNumbers[i] + '</td>'
                        string += '<td>' + response.christmas[i] + '</td>'
                        string += '<td>' + response.century[i] + '</td>'
                        string += '</tr>';
                    }

                    $('.table').append(string);
                } else {
                    alert(response.message);
                }
            },
            fail: function (data) {
                alert('failed');
            }
        })
    });
});