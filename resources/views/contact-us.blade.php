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
            display: flex;
            flex-direction: column;
        }

        .background-image {
            background-image: url('{{ asset('images/welcomeblank.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            flex: 1; /* Ensures content fills vertical space */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .content-wrapper {
            width: 100%;
            max-width: 800px;
            background-color: rgba(255, 255, 255, 0.9); /* White overlay */
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Slight shadow for depth */
        }

        footer {
            background-color: #333; /* Dark background for footer */
            color: white; /* White text for contrast */
            text-align: center;
            padding: 10px 0; /* Consistent footer padding */
        }

        footer a {
            color: #f8f9fa; /* Light color for links */
            text-decoration: none; /* Remove underline */
        }

        footer a:hover {
            text-decoration: underline; /* Add underline on hover */
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    @include('frontend.partials.navbar')

    <div class="background-image">
        <div class="content-wrapper">
            <h1 class="text-center">Contact Us</h1>
            <p class="text-center">We'd love to hear from you! Reach out to us through any of the contact methods below:</p>

            <hr class="my-4">

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

    <!-- Footer -->
    @include('frontend.partials.footer')

    <!-- Bootstrap JS (with Popper.js for tooltips and popovers, CDN) -->
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
