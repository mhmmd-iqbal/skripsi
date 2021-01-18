<html>

<head>
    <link href="<?= base_url() ?>/assets/template/cms/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container">
    <form action="" style="padding-top: 50px;" method="POST">
        <div class="row form-group">
            <div class="col-md-12">
                <table class="table">
                    <tr>
                        <td style="width: 20%;">
                            Method
                        </td>
                        <td>
                            <input class="form-control" type="text" name="method" value="get">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            URL
                        </td>
                        <td>
                            <input class="form-control" type="text" name="url" value="/banking/v3/corporates/h2hauto008/accounts/0613005908">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            API Key
                        </td>
                        <td>
                            <input class="form-control" type="text" name="api_key" value="dcc99ba6-3b2f-479b-9f85-86a09ccaaacf">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            API Key Secret
                        </td>
                        <td>
                            <input class="form-control" type="text" name="api_secret" value="5e636b16-df7f-4a53-afbe-497e6fe07edc">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            APi Credential
                        </td>
                        <td>
                            <input class="form-control" type="text" name="client_id" value="b095ac9d-2d21-42a3-a70c-4781f4570704">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Api Credential Secret
                        </td>
                        <td>
                            <input class="form-control" type="text" name="client_secret" value="bedd1f8d-3bd6-4d4a-8cb4-e61db41691c9">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Access Token
                        </td>
                        <td>
                            <input class="form-control" type="text" name="token" value="ecU7TR0gm8pXas4je6PQNtLXwFj3Lz3Ec6sADCfar2CPEN4TdbFQni">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Timestamp
                        </td>
                        <td>
                            <input class="form-control" type="text" name="timestamp" value="2020-10-26T16:52:00.000+07:00">
                        </td>
                    </tr>
                    <tr>
                        <td>Request Body</td>
                        <td>
                            <textarea name="request_body" class="form-control" id="" cols="30" rows="10"></textarea>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <button type="submit" class="btn btn-info">Get Signature</button>
    </form>

    <script src="<?= base_url() ?>/assets/template/cms/js/jquery.js"></script>
    <script src="<?= base_url() ?>/assets/template/cms/js/jquery-1.8.3.min.js"></script>
    <script src="<?= base_url() ?>/assets/template/cms/js/bootstrap.min.js"></script>
    <script>

    </script>
</body>

</html>