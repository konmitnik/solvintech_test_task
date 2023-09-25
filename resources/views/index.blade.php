<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Страница пользователя</title>
    </head>
    <body>
        <p id="token">{{$token}}</p>
        <button id="change_token">Change user token</button>
        <button id="exit">Exit</button>

        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <script lang="javascript">
            $(document).ready(function () {
                $('#change_token').click(function () {
                    let token = $('#token').text();
                    $.ajax({
                        url: '/new-token',
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            token: token
                        },
                        success: function (data, status) {
                            console.log(data);
                            $('#token').text(data);
                        }
                    });
                });
                $('#exit').click(function () {
                    document.location.href = '/login';
                });
            });
        </script>
    </body>
</html>