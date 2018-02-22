<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- Datatables Styles -->
    <link href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>


    <div class="container">
        <h2>Dream Secure API Documentation</h2>
        <p>
            This is the documentation for the API for dreamsecure.
            Each Accordion below represents endpoints and clicking to expand shows more details about the endpoint.
            Details like the required fields, the JSON format for both request and the JSON format for response.
        </p>

        <p class="bg-success" style="padding: 4px;">
            <strong><span class="label label-success">Base URL:</span></strong> http://www.olync.net/dreamsecure/api
        </p>

        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="label label-primary">POST</span>
                /users/register
            </div>
            <div class="panel-body">
                This endpoint makes it possible for you to create a new dream secure app user.
                <br>
                <strong>Request: </strong>
                <br>
                <code>
                {
                    "last_name": "Olakunle",
                    "other_names": "Odegbaro",
                    "email": "oodegbaro@gmail.com",
                    "phone": "09097694139",
                    "gender": "m",
                    "password": "killacam",
                    "ice_1": "090989784456",
                    "ice_2": "090989784466",
                    "ice_3": "090989784488",
                    "rec_email_1": "o.odegbaro@dreammesh.ng",
                    "rec_email_2": "o.odegbaro@gmail.com",
                    "rec_email_3": "sanya@gmail.com"

                }
                </code>
                <br>
                <strong>Response: </strong>
                <br>
                <code>
                    {
                        "header": {
                            "status": "DONE",
                            "code": "200",
                            "completedTime": "Monday 13th of November 2017 12:40:01 PM"
                        },
                        "body": {
                            "id": 5
                        }
                    }
                </code>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="label label-primary">POST</span>
                /users/activate
            </div>
            <div class="panel-body">
                This endpoint makes it possible to activate a dream secure app user.
                <br>
                <strong>Request: </strong>
                <br>
                <code>
                {
                    "code": "AsyYn",
                    "email": "foo@foo.com"
                }
                </code>
                <br>
                <strong>Response: </strong>
                <br>
                <code>
                    {
                        "header": {
                            "status": "DONE",
                            "code": "200",
                            "completedTime": "Monday 13th of November 2017 01:06:07 PM"
                        },
                        "body": {
                            "userData": {
                                "id": 4,
                                "last_name": "Olakunle",
                                "other_names": "Odegbaro",
                                "gender": "m",
                                "email": "oodegbaro@gmail.com",
                                "phone": "09097694139",
                                "ice_1": "090989784456",
                                "ice_2": "090989784466",
                                "ice_3": "090989784488",
                                "rec_email_1": "m.anifowose@dreammesh.ng",
                                "rec_email_2": "a.eko@dreammesh.ng",
                                "rec_email_3": "o.odegbaro@dreammesh.ng",
                                "code": "AsyYn",
                                "activated": 1,
                                "created_at": "2017-11-08 04:03:24",
                                "updated_at": "2017-11-09 16:43:04"
                            }
                        }
                    }
                </code>
            </div>
        </div>


        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="label label-primary">POST</span>
                /users/login
            </div>
            <div class="panel-body">
                This endpoint makes it possible for you dream secure app user to login.
                <br>
                <strong>Request: </strong>
                <br>
                <code>
                {
                    "email": "o.odegbaro@gmail.com",
                    "password": "killacam"
                }
                </code>
                <br>
                <strong>Response: </strong>
                <br>
                <code>
                    {
                        "header": {
                            "status": "DONE",
                            "code": "200",
                            "completedTime": "Monday 13th of November 2017 12:55:55 PM"
                        },
                        "body": {
                            "userData": {
                                "id": 6,
                                "last_name": "Adetolani",
                                "other_names": "Eko",
                                "gender": "m",
                                "email": "a.eko@dreammesh.ng",
                                "phone": "09097694139",
                                "ice_1": "090989784456",
                                "ice_2": "090989784466",
                                "ice_3": "090989784488",
                                "rec_email_1": "o.odegbaro@dreammesh.ng",
                                "rec_email_2": "o.odegbaro@gmail.com",
                                "rec_email_3": "m.anifowose@dreammesh.ng",
                                "code": "JC17c",
                                "activated": 1,
                                "created_at": "2017-11-13 12:52:27",
                                "updated_at": "2017-11-13 12:52:27"
                            }
                        }
                    }
                </code>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="label label-primary">POST</span>
                /users/getdetails
            </div>
            <div class="panel-body">
                This endpoint makes it possible for to return the details of a dream secure app user.
                <br>
                <strong>Request: </strong>
                <br>
                <code>
                {
                    "id":4

                }
                </code>
                <br>
                <strong>Response: </strong>
                <br>
                <code>
                    {
                        "header": {
                            "status": "DONE",
                            "code": "200",
                            "completedTime": "Monday 13th of November 2017 12:55:55 PM"
                        },
                        "body": {
                            "userData": {
                                "id": 6,
                                "last_name": "Adetolani",
                                "other_names": "Eko",
                                "gender": "m",
                                "email": "a.eko@dreammesh.ng",
                                "phone": "09097694139",
                                "ice_1": "090989784456",
                                "ice_2": "090989784466",
                                "ice_3": "090989784488",
                                "rec_email_1": "o.odegbaro@dreammesh.ng",
                                "rec_email_2": "o.odegbaro@gmail.com",
                                "rec_email_3": "m.anifowose@dreammesh.ng",
                                "code": "JC17c",
                                "activated": 1,
                                "created_at": "2017-11-13 12:52:27",
                                "updated_at": "2017-11-13 12:52:27"
                            }
                        }
                    }
                </code>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="label label-primary">POST</span>
                /users/updatedetails
            </div>
            <div class="panel-body">
                This endpoint makes it possible for you to update a dream secure user app user data.
                <br>
                <strong>Request: </strong>
                <br>
                <code>
                {
                    "id": 4,
                    "last_name": "Olakunle",
                    "other_names": "Odegbaro",
                    "email": "oodegbaro@gmail.com",
                    "phone": "09097694139",
                    "gender": "m",
                    "ice_1": "090989784456",
                    "ice_2": "090989784466",
                    "ice_3": "090989784488",
                    "rec_email_1": "m.anifowose@dreammesh.ng",
                    "rec_email_2": "a.eko@dreammesh.ng",
                    "rec_email_3": "o.odegbaro@dreammesh.ng"
                }
                </code>
                <br>
                <strong>Response: </strong>
                <br>
                <code>
                    {
                        "header": {
                            "status": "DONE",
                            "code": "200",
                            "completedTime": "Monday 13th of November 2017 12:55:55 PM"
                        },
                        "body": {
                            "userData": {
                                "id": 6,
                                "last_name": "Adetolani",
                                "other_names": "Eko",
                                "gender": "m",
                                "email": "a.eko@dreammesh.ng",
                                "phone": "09097694139",
                                "ice_1": "090989784456",
                                "ice_2": "090989784466",
                                "ice_3": "090989784488",
                                "rec_email_1": "o.odegbaro@dreammesh.ng",
                                "rec_email_2": "o.odegbaro@gmail.com",
                                "rec_email_3": "m.anifowose@dreammesh.ng",
                                "code": "JC17c",
                                "activated": 1,
                                "created_at": "2017-11-13 12:52:27",
                                "updated_at": "2017-11-13 12:52:27"
                            }
                        }
                    }
                </code>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="label label-primary">POST</span>
                /users/resend-activation
            </div>
            <div class="panel-body">
                This endpoint makes it possible for the developers to resend the activation code to the givem email address.
                <br>
                <strong>Request: </strong>
                <br>
                <code>
                {
                    "email": "oodegbaro@gmail.com"
                }
                </code>
                <br>
                <strong>Response: </strong>
                <br>
                <code>
                  {
                    "header": {
                        "status": "DONE",
                        "code": "200",
                        "completedTime": "Wednesday 6th of December 2017 01:07:39 PM"
                    },
                    "body": {
                        "id": 2001
                    }
                  }
                </code>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="label label-primary">POST</span>
                /users/sendclientresetlink
            </div>
            <div class="panel-body">
                This sends a password reset link to the eamil address passed to it.
                <br>
                <strong>Request: </strong>
                <br>
                <code>
                {
                    "email": "o.odegbaro@dreammesh.ng"
                }
                </code>
                <br>
                <strong>Response: </strong>
                <br>
                <code>
                  {
                    "header": {
                        "status": "DONE",
                        "code": "200",
                        "completedTime": "Wednesday 6th of December 2017 01:07:39 PM"
                    },
                    "body": {
                        "id": 2001
                    }
                  }
                </code>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="label label-primary">POST</span>
                /data/post
            </div>
            <div class="panel-body">
                This endpoint makes it possible for you make an alert / notification on behalf of the dream secure app user.
                <br>
                <strong>Request: </strong>
                <br>
                <code>
                    {
                        "id": "4",
                        "message": "I am in a dangerous situation and needs your assistance. I am around this address (17b, Adeniji Street, Adeniji Estate, Ogba.) Please get help!",
                        "lon": "7.09234",
                        "lat": "1.03563"
                    }
                </code>
                <br>
                <strong>Response: </strong>
                <br>
                <code>
                    {
                        "header": {
                            "status": "DONE",
                            "code": "200",
                            "completedTime": "Monday 13th of November 2017 01:26:18 PM"
                        }
                    }
                </code>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <span class="label label-primary">POST</span>
                /report/getAll
            </div>
            <div class="panel-body">
                This endpoint makes it possible for you make to get all the ICE alerts made by a specific dream secure app.
                <br>
                <strong>Request: </strong>
                <br>
                <code>
                    {
                        "id": "500"
                    }
                </code>
                <br>
                <strong>Response: </strong>
                <br>
                <code>
                    {
                        "header": {
                            "status": "DONE",
                            "code": "200",
                            "completedTime": "Wednesday 15th of November 2017 02:04:24 PM"
                        },
                        "body": [
                            {
                                "id": 2,
                                "client_id": 4,
                                "message": "I am in a dangerous situation and needs your assistance. I am around this address (17b, Adeniji Street, Adeniji Estate, Ogba.) Please get help!",
                                "lon": "7.09234",
                                "lat": "1.03563",
                                "created_at": "2017-11-10 13:51:44",
                                "updated_at": "2017-11-10 13:51:44"
                            },
                            {
                                "id": 3,
                                "client_id": 4,
                                "message": "I am in a dangerous situation and needs your assistance. I am around this address (17b, Adeniji Street, Adeniji Estate, Ogba.) Please get help!",
                                "lon": "7.09234",
                                "lat": "1.03563",
                                "created_at": "2017-11-10 13:53:14",
                                "updated_at": "2017-11-10 13:53:14"
                            }
                        ]
                    }
                </code>
            </div>
        </div>

        <div class="well">
            <p class="bg-danger ">
                <h3><a name="error" title="Conclusion" id="end2">Status Codes</a></h3>
            </p>

            <table class="table">
                <thead>
                <td>&nbsp;</td>
                <td>Code</td>
                <td>Description</td>
              </thead>
              <tr class="bg-success">
                <td>1</td>
                <td>200</td>
                <td>Done/Complete</td>
              </tr>
              <tr >
                <td>2</td>
                <td>101</td>
                <td>Invalid authentication</td>
              </tr>
              <tr>
                <td>3</td>
                <td>106</td>
                <td>Get operation failed</td>
              </tr>
              <tr>
                <td>4</td>
                <td>110</td>
                <td>Post Operation failed</td>
              </tr>
              <tr>
                <td>7</td>
                <td>107</td>
                <td>wrong username and password</td>
              </tr>
              <tr>
                <td>5</td>
                <td>108</td>
                <td>inactive account</td>
              </tr>
              <tr>
                <td>6</td>
                <td>109</td>
                <td>deactivated account</td>
              </tr>
              <tr>
                <td>8</td>
                <td>500</td>
                <td>Server error</td>
              </tr>
              <tr>
                <td>9</td>
                <td>111</td>
                <td>Not confirmed Account</td>
              </tr>
            </table>
        </div>

    </div>



<!-- Datatables Scripts -->
    <script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
    <!-- Latest compiled and minified JavaScript -->
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>

    <!-- Scripts -->
    <!--<script src="{{ asset('js/app.js') }}"></script> -->
</body>
</html>
