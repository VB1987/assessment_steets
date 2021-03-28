$(document).ready(function () {

    /**
     * Send ajax
     * @param args
     * @returns {Promise<*>}
     */
    async function submit(args) {
        let result;
        
        try {
            result = await $.ajax({
                type: 'get',
                url: 'assessment/prime-number/get',
                data: {
                    'year': args
                },
                dataType: 'json',
            });

            return result;
        } catch (e) {
            console.log(e.responseText)
            alert(e.responseText);
        }
    }

    /**
     * Show results in table
     * @param response
     */
    let showResults = function (response) {
        if (typeof response.message !== 'undefined') {
            alert(response.message);
        }

        if (typeof response.primeNumbers !== 'undefined') {
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
        }
    }

    /**
     * On submit
     */
    $('#prime_number_form').on('submit', function () {
        event.preventDefault();
        let year = $('input[name="year"]')[0].value;
        
        submit(year).then( (response) => showResults(response));
    });
});