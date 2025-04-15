<!-- resources/views/errors/404.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Page Not Found</title>

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
            max-width: 350px;
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

            .wizard-illustration {
                max-width: 250px;
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
            <svg width="100%" height="100%" viewBox="0 0 300 400" xmlns="http://www.w3.org/2000/svg">
                <!-- Magical Effects -->
                <g class="magical-effects">
                    <path d="M50,50 L40,30" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" />
                    <path d="M70,80 L90,70" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" />
                    <path d="M230,70 L250,50" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" />
                    <path d="M260,120 L280,110" stroke="#FFFFFF" stroke-width="3" stroke-linecap="round" />

                    <path d="M30,100 L10,90" stroke="#FF9231" stroke-width="3" stroke-linecap="round" />
                    <path d="M200,40 L220,20" stroke="#FF9231" stroke-width="3" stroke-linecap="round" />

                    <!-- Magnifying Glass -->
                    <circle cx="240" cy="130" r="20" stroke="#FFFFFF" stroke-width="3" fill="none" />
                    <line x1="255" y1="145" x2="275" y2="165" stroke="#FFFFFF" stroke-width="4"
                        stroke-linecap="round" />

                    <!-- Spiral -->
                    <path d="M60,140 C70,135 75,145 70,150 C65,155 55,150 55,145 C55,140 65,135 70,135" stroke="#FFFFFF"
                        stroke-width="2" fill="none" />

                    <!-- Question Mark -->
                    <text x="190" y="80" fill="#FFFFFF" font-size="30" font-weight="bold">?</text>
                    <text x="90" y="170" fill="#FFFFFF" font-size="30" font-weight="bold">?</text>

                    <!-- X mark -->
                    <path d="M40,170 L50,180 M40,180 L50,170" stroke="#FFFFFF" stroke-width="2"
                        stroke-linecap="round" />
                    <path d="M250,170 L260,180 M250,180 L260,170" stroke="#FFFFFF" stroke-width="2"
                        stroke-linecap="round" />
                </g>

                <!-- Wizard -->
                <g transform="translate(100, 100)">
                    <!-- Hat -->
                    <path d="M50,0 L100,120 L0,120 Z" fill="#4A1E68" />
                    <circle cx="50" cy="60" r="10" fill="#FFDF4F" />

                    <!-- Head -->
                    <rect x="35" y="120" width="30" height="40" fill="#FFDF4F" />

                    <!-- Beard -->
                    <path d="M35,130 Q50,200 65,130" fill="#FFFFFF" />

                    <!-- Eyes -->
                    <circle cx="42" cy="135" r="4" fill="#4A1E68" />
                    <circle cx="58" cy="135" r="4" fill="#4A1E68" />

                    <!-- Nose -->
                    <path d="M50,138 Q55,143 50,148" stroke="#FF5C7C" stroke-width="3" fill="none" />

                    <!-- Mouth -->
                    <path d="M42,152 Q50,158 58,152" stroke="#4A1E68" stroke-width="2" fill="none" />

                    <!-- Body/Robe -->
                    <path d="M20,160 L80,160 L90,250 L10,250 Z" fill="#4A1E68" />

                    <!-- Belt -->
                    <rect x="20" y="180" width="60" height="10" fill="#FF5C7C" />
                    <circle cx="50" cy="185" r="6" fill="#FFDF4F" />

                    <!-- Magnifying Glass in Hand -->
                    <circle cx="90" cy="180" r="15" stroke="#FFFFFF" stroke-width="3" fill="none" />
                    <line x1="100" y1="190" x2="110" y2="200" stroke="#FFFFFF" stroke-width="4"
                        stroke-linecap="round" />
                    <line x1="85" y1="155" x2="88" y2="170" stroke="#FFFFFF"
                        stroke-width="3" stroke-linecap="round" />

                    <!-- Feet -->
                    <rect x="25" y="250" width="20" height="10" fill="#FF9231" />
                    <rect x="55" y="250" width="20" height="10" fill="#FF9231" />
                </g>
            </svg>
        </div>

        <div class="wizard-content">
            <h1 class="error-title">PAGE NOT FOUND!</h1>
            <div class="error-code">404</div>
            <p class="error-message">Oops! Even with my magical powers, I couldn't find the page you're looking for.</p>
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
