<!-- resources/views/errors/403.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Unauthorized</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=poppins:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-color: #FFDF4F;
            font-family: 'Poppins', sans-serif;
        }

        .wizard-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 2rem;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            min-height: 100vh;
        }

        .wizard-illustration {
            flex: 1;
            position: relative;
        }

        .wizard-content {
            flex: 1;
            padding-left: 2rem;
        }

        .error-title {
            color: #4A1E68;
            font-weight: 800;
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .error-code {
            color: #FF9231;
            font-size: 8rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 1.5rem;
        }

        .error-message {
            color: #4A1E68;
            font-size: 1.5rem;
            margin-bottom: 2rem;
            max-width: 450px;
        }

        .home-button {
            background-color: #FF5C7C;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 100px;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .home-button:hover {
            background-color: #E84968;
            transform: translateY(-2px);
        }

        .back-button {
            background-color: #4A1E68;
            color: white;
            font-weight: 600;
            padding: 0.75rem 2rem;
            border-radius: 100px;
            text-decoration: none;
            display: inline-block;
            margin-right: 1rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .back-button:hover {
            background-color: #3A1850;
            transform: translateY(-2px);
        }

        .button-container {
            display: flex;
            gap: 1rem;
        }

        .magical-effects {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
        }

        @media (max-width: 768px) {
            .wizard-container {
                flex-direction: column;
                text-align: center;
                padding: 2rem 1rem;
            }

            .wizard-content {
                padding-left: 0;
                padding-top: 2rem;
            }

            .error-message {
                margin: 0 auto 2rem auto;
            }

            .button-container {
                justify-content: center;
            }

            .error-code {
                font-size: 6rem;
            }
        }
    </style>
</head>

<body>
    <div class="wizard-container">
        <div class="wizard-illustration">
            <img src="{{ asset('images/cantpass.jpg') }}" alt="">
        </div>

        <div class="wizard-content">
            <h1 class="error-title">YOU SHALL NOT PASS!</h1>
            <div class="error-code">403</div>
            <p class="error-message">
                {{ $exception->getMessage() ?: 'We are sorry, but you do not have access to this page or resource.' }}
            </p>
            <div class="button-container">
                <button onclick="window.history.back()" class="back-button">
                    GO BACK
                </button>
                <a href="{{ url('/') }}" class="home-button">
                    BACK TO HOME PAGE
                </a>
            </div>
        </div>
    </div>
</body>

</html>
