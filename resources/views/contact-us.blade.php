<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Contact Us - Story Builder</title>

    <!-- Bootstrap CSS (CDN) -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styles -->
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .background-image {
            /* Use the background image */
            background-image: url('{{ asset('images/welcomeblank.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
        }

        .container {
            padding-top: 20px;
            background-color: rgba(255, 255, 255, 0.8); /* Light background overlay */
            border-radius: 10px;
            padding: 20px;
        }
    </style>
</head>
<body class="background-image">
    <!-- Navigation Bar -->
    @include('frontend.partials.navbar')

    <div class="container text-center py-4">
        <h1>Contact Us</h1>

        <div class="row mt-4">
            <!-- Contact Information Section -->
            <div class="col-md-12 text-left">
                <h2>Get in Touch</h2>
                <p>We'd love to hear from you! Reach out to us through any of the contact methods below:</p>

                <div class="mt-4">
                    <h4>Email:</h4>
                    <p><a href="mailto:sarushan1994@gmail.com">sarushan1994@gmail.com</a></p>

                    <h4>WhatsApp:</h4>
                    <p><a href="https://wa.me/94769980662">+94 769 980 662</a></p>

                    <h4>Call Us:</h4>
                    <p><a href="tel:+94758202743">+94 758 202 743</a></p>
                </div>
            </div>
        </div>
    </div>

    @include('frontend.partials.footer')

    <!-- Bootstrap JS (with Popper.js for tooltips and popovers, CDN) -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
