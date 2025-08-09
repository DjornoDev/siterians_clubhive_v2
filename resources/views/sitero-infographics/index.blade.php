<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sitero Francisco Memorial National High School - Educational Infographics</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700;800;900&family=Cinzel:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary-navy: #0a1628;
            --primary-gold: #d4af37;
            --secondary-gold: #f4e4bc;
            --accent-blue: #1e40af;
            --accent-green: #059669;
            --accent-purple: #7c3aed;
            --accent-red: #dc2626;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-light: #64748b;
            --bg-dark: #0f172a;
            --bg-light: #f8fafc;
            --bg-white: #ffffff;
            --bg-cream: #fefcf3;
            --border-light: #e2e8f0;
            --border-gold: rgba(212, 175, 55, 0.3);
            --shadow-luxury: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            --shadow-elegant: 0 10px 40px rgba(0, 0, 0, 0.15);
            --shadow-soft: 0 4px 20px rgba(0, 0, 0, 0.08);
            --gradient-primary: linear-gradient(135deg, #0a1628 0%, #1e293b 50%, #334155 100%);
            --gradient-gold: linear-gradient(135deg, #d4af37 0%, #f4e4bc 50%, #d4af37 100%);
            --gradient-elegant: linear-gradient(135deg, #fefcf3 0%, #ffffff 50%, #f8fafc 100%);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--gradient-elegant);
            color: var(--text-primary);
            line-height: 1.7;
            scroll-behavior: smooth;
            overflow-x: hidden;
        }

        .font-display {
            font-family: 'Playfair Display', serif;
        }

        .font-luxury {
            font-family: 'Cinzel', serif;
        }

        /* Luxury Navigation */
        .luxury-nav {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 2000;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-gold);
            transition: all 0.3s ease;
        }

        .luxury-nav.scrolled {
            background: rgba(255, 255, 255, 0.98);
            box-shadow: var(--shadow-elegant);
        }

        .nav-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .nav-logo img {
            width: 50px;
            height: 50px;
            object-fit: contain;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
        }

        .nav-brand {
            font-family: 'Cinzel', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-navy);
            text-decoration: none;
            letter-spacing: 0.5px;
        }

        .nav-menu {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-link {
            color: var(--text-primary);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: var(--gradient-gold);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .nav-link:hover::before {
            left: 0;
        }

        .nav-link:hover {
            color: var(--primary-navy);
            transform: translateY(-2px);
        }

        .nav-toggle {
            display: none;
            flex-direction: column;
            gap: 4px;
            cursor: pointer;
        }

        .nav-toggle span {
            width: 25px;
            height: 3px;
            background: var(--primary-navy);
            transition: all 0.3s ease;
        }

        /* Scroll Progress */
        .scroll-progress {
            position: fixed;
            top: 0;
            left: 0;
            width: 0%;
            height: 4px;
            background: var(--gradient-gold);
            z-index: 2001;
            transition: width 0.1s ease;
        }

        /* Hero Section */
        .hero-section {
            height: 100vh;
            background: linear-gradient(135deg,
                    rgba(10, 22, 40, 0.95) 0%,
                    rgba(30, 41, 59, 0.9) 50%,
                    rgba(51, 65, 85, 0.85) 100%),
                url('/images/sitero.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="luxury-pattern" width="20" height="20" patternUnits="userSpaceOnUse"><circle cx="10" cy="10" r="0.5" fill="rgba(212,175,55,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23luxury-pattern)"/></svg>');
            animation: float 25s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
            }

            33% {
                transform: translateY(-10px) rotate(0.5deg);
            }

            66% {
                transform: translateY(5px) rotate(-0.3deg);
            }
        }

        .hero-content {
            position: relative;
            z-index: 10;
            max-width: 1000px;
            padding: 0 2rem;
        }

        .hero-logo {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            border-radius: 50%;
            border: 4px solid var(--primary-gold);
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            animation: logoGlow 3s ease-in-out infinite alternate;
        }

        @keyframes logoGlow {
            from {
                box-shadow: 0 0 20px rgba(212, 175, 55, 0.5);
            }

            to {
                box-shadow: 0 0 40px rgba(212, 175, 55, 0.8);
            }
        }

        .hero-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .hero-title {
            font-family: 'Cinzel', serif;
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 800;
            margin-bottom: 1rem;
            letter-spacing: 2px;
            background: linear-gradient(45deg, #ffffff, var(--primary-gold), #ffffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: titleShine 4s ease-in-out infinite;
        }

        @keyframes titleShine {

            0%,
            100% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }
        }

        .hero-subtitle {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.2rem, 3vw, 1.8rem);
            font-weight: 400;
            margin-bottom: 2rem;
            opacity: 0.95;
            font-style: italic;
            letter-spacing: 1px;
        }

        .hero-description {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 3rem;
            opacity: 0.9;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-badges {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .luxury-badge {
            background: linear-gradient(135deg,
                    rgba(212, 175, 55, 0.9) 0%,
                    rgba(244, 228, 188, 0.9) 50%,
                    rgba(212, 175, 55, 0.9) 100%);
            color: var(--primary-navy);
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: 2px solid rgba(212, 175, 55, 0.3);
            backdrop-filter: blur(10px);
            transition: all 0.3s ease;
            cursor: default;
        }

        .luxury-badge:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.4);
        }

        .hero-cta {
            display: inline-flex;
            align-items: center;
            gap: 1rem;
            background: linear-gradient(135deg, var(--primary-gold), #f4e4bc);
            color: var(--primary-navy);
            padding: 1.2rem 3rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .hero-cta:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(212, 175, 55, 0.5);
            border-color: rgba(255, 255, 255, 0.3);
        }

        .hero-scroll {
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            color: rgba(255, 255, 255, 0.8);
            font-size: 0.9rem;
            text-align: center;
            cursor: pointer;
        }

        .hero-scroll i {
            display: block;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {

            0%,
            20%,
            50%,
            80%,
            100% {
                transform: translateY(0);
            }

            40% {
                transform: translateY(-10px);
            }

            60% {
                transform: translateY(-5px);
            }
        }

        /* Container and Layout */
        .container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        .container-sm {
            max-width: 1000px;
            margin: 0 auto;
            padding: 0 2rem;
        }

        /* Luxury Sections */
        .luxury-section {
            padding: 6rem 0;
            position: relative;
            overflow: hidden;
        }

        .luxury-section.dark {
            background: var(--gradient-primary);
            color: white;
        }

        .luxury-section.light {
            background: var(--gradient-elegant);
        }

        .luxury-section.gold {
            background: linear-gradient(135deg, var(--bg-cream) 0%, var(--secondary-gold) 50%, var(--bg-cream) 100%);
        }

        .section-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-badge {
            display: inline-block;
            background: var(--gradient-gold);
            color: var(--primary-navy);
            padding: 0.5rem 2rem;
            border-radius: 50px;
            font-size: 0.9rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }

        .section-title {
            font-family: 'Cinzel', serif;
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 800;
            margin-bottom: 1.5rem;
            letter-spacing: 1px;
            line-height: 1.2;
        }

        .section-title.white {
            color: white;
        }

        .section-subtitle {
            font-family: 'Playfair Display', serif;
            font-size: clamp(1.1rem, 2.5vw, 1.4rem);
            font-weight: 400;
            font-style: italic;
            opacity: 0.8;
            max-width: 600px;
            margin: 0 auto;
            line-height: 1.6;
        }

        /* Luxury Cards */
        .luxury-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-gold);
            border-radius: 24px;
            padding: 3rem;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-soft);
        }

        .luxury-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(212, 175, 55, 0.1), transparent);
            transition: left 0.6s ease;
        }

        .luxury-card:hover {
            transform: translateY(-12px) rotateX(5deg);
            box-shadow: var(--shadow-luxury);
            border-color: var(--primary-gold);
        }

        .luxury-card:hover::before {
            left: 100%;
        }

        .card-icon {
            width: 80px;
            height: 80px;
            background: var(--gradient-gold);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--primary-navy);
            margin-bottom: 2rem;
            box-shadow: var(--shadow-soft);
        }

        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-navy);
            margin-bottom: 1rem;
        }

        .card-description {
            color: var(--text-secondary);
            line-height: 1.8;
            font-size: 1.05rem;
        }

        /* Stats Display */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 4rem 0;
        }

        .stat-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(212, 175, 55, 0.3);
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(212, 175, 55, 0.1) 0%, transparent 70%);
            animation: rotate 10s linear infinite;
        }

        @keyframes rotate {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .stat-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-gold);
            box-shadow: 0 20px 40px rgba(212, 175, 55, 0.3);
        }

        .stat-number {
            position: relative;
            z-index: 10;
            font-family: 'Cinzel', serif;
            font-size: clamp(2.5rem, 8vw, 4rem);
            font-weight: 900;
            background: linear-gradient(135deg, var(--primary-gold), #ffffff);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 1rem;
        }

        .stat-label {
            position: relative;
            z-index: 10;
            font-size: 1.1rem;
            font-weight: 600;
            color: rgba(255, 255, 255, 0.9);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Grid Layouts */
        .luxury-grid-2 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 3rem;
            margin: 4rem 0;
        }

        .luxury-grid-3 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
            margin: 4rem 0;
        }

        .luxury-grid-4 {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin: 4rem 0;
        }

        /* Timeline Luxury Design */
        .luxury-timeline {
            position: relative;
            max-width: 800px;
            margin: 4rem auto;
        }

        .luxury-timeline::before {
            content: '';
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 4px;
            background: var(--gradient-gold);
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 4rem;
            opacity: 0;
            transform: translateY(30px);
        }

        .timeline-item.animate {
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .timeline-dot {
            position: absolute;
            left: 50%;
            top: 0;
            width: 20px;
            height: 20px;
            background: var(--primary-gold);
            border: 4px solid white;
            border-radius: 50%;
            transform: translateX(-50%);
            z-index: 10;
            box-shadow: 0 0 20px rgba(212, 175, 55, 0.5);
        }

        .timeline-content {
            width: 45%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border-gold);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-elegant);
            transition: all 0.3s ease;
        }

        .timeline-content:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-luxury);
        }

        .timeline-item:nth-child(odd) .timeline-content {
            margin-left: 55%;
        }

        .timeline-item:nth-child(even) .timeline-content {
            margin-right: 55%;
            text-align: right;
        }

        .timeline-date {
            font-family: 'Cinzel', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--primary-gold);
            margin-bottom: 0.5rem;
        }

        .timeline-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--primary-navy);
            margin-bottom: 1rem;
        }

        /* Progress Bars */
        .progress-container {
            margin: 2rem 0;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .progress-bar {
            height: 12px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 6px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: var(--gradient-gold);
            border-radius: 6px;
            transition: width 2s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .progress-fill::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }

            100% {
                left: 100%;
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: rgba(255, 255, 255, 0.98);
                backdrop-filter: blur(20px);
                flex-direction: column;
                padding: 2rem;
                border-radius: 0 0 20px 20px;
                box-shadow: var(--shadow-elegant);
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-toggle {
                display: flex;
            }

            .hero-section {
                height: 100vh;
                background-attachment: scroll;
            }

            .hero-content {
                padding: 0 1rem;
            }

            .hero-badges {
                flex-direction: column;
                align-items: center;
            }

            .luxury-grid-2,
            .luxury-grid-3,
            .luxury-grid-4 {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 1.5rem;
            }

            .luxury-timeline::before {
                left: 20px;
            }

            .timeline-dot {
                left: 20px;
            }

            .timeline-content {
                width: calc(100% - 60px);
                margin-left: 60px !important;
                text-align: left !important;
            }

            .timeline-item:nth-child(even) .timeline-content {
                margin-right: 0 !important;
            }

            .container,
            .container-sm {
                padding: 0 1rem;
            }

            .luxury-section {
                padding: 4rem 0;
            }

            .luxury-card {
                padding: 2rem;
            }
        }

        /* Special Effects */
        .particle-background {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }

        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: var(--primary-gold);
            border-radius: 50%;
            opacity: 0.6;
            animation: float-particle 6s ease-in-out infinite;
        }

        @keyframes float-particle {

            0%,
            100% {
                transform: translateY(0px) translateX(0px);
                opacity: 0.6;
            }

            50% {
                transform: translateY(-20px) translateX(10px);
                opacity: 1;
            }
        }

        /* Hover Effects */
        .hover-lift {
            transition: all 0.3s ease;
        }

        .hover-lift:hover {
            transform: translateY(-8px);
        }

        .hover-glow {
            transition: all 0.3s ease;
        }

        .hover-glow:hover {
            box-shadow: 0 0 30px rgba(212, 175, 55, 0.4);
        }

        /* Loading Animation */
        .fade-in-up {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.8s ease-out;
        }

        .fade-in-up.animate {
            opacity: 1;
            transform: translateY(0);
        }

        /* Luxury Footer */
        .luxury-footer {
            background: var(--gradient-primary);
            color: white;
            padding: 4rem 0 2rem;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .luxury-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: var(--gradient-gold);
        }

        .footer-content {
            position: relative;
            z-index: 10;
        }

        .footer-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 2rem;
            border-radius: 50%;
            border: 3px solid var(--primary-gold);
            padding: 1rem;
            background: rgba(255, 255, 255, 0.1);
        }

        .footer-title {
            font-family: 'Cinzel', serif;
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--primary-gold);
        }

        .footer-text {
            opacity: 0.8;
            margin-bottom: 2rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .footer-divider {
            width: 100px;
            height: 2px;
            background: var(--gradient-gold);
            margin: 2rem auto;
        }

        .footer-copyright {
            opacity: 0.6;
            font-size: 0.9rem;
        }

        /* Table Styles */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            font-size: 0.875rem;
        }

        .info-table th,
        .info-table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid var(--border-light);
        }

        .info-table th {
            background: var(--primary-navy);
            color: white;
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        /* Maps */
        .map-container {
            border: 3px solid var(--primary-navy);
            border-radius: 8px;
            overflow: hidden;
        }

        /* Enhanced Responsive Design */
        @media (max-width: 768px) {
            .main-title {
                font-size: 1.75rem;
            }

            .floating-nav {
                top: 10px;
                right: 10px;
                padding: 5px;
                gap: 4px;
            }

            .nav-btn {
                width: 35px;
                height: 35px;
                font-size: 0.875rem;
            }

            .timeline::before {
                left: 20px;
            }

            .timeline-dot {
                left: 20px;
            }

            .timeline-content {
                width: calc(100% - 50px);
                margin-left: 50px !important;
                text-align: left !important;
            }

            .grid-2,
            .grid-3,
            .grid-4 {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .section {
                padding: 2rem 0;
            }

            .info-card {
                padding: 1.5rem;
            }

            .badge {
                padding: 0.5rem 1rem;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 0 0.75rem;
            }

            .main-title {
                font-size: 1.5rem;
            }

            .subtitle {
                font-size: 1rem;
            }

            .section-title {
                font-size: 1.5rem;
            }

            .data-point {
                font-size: 2rem;
            }

            .info-badges {
                gap: 0.5rem;
            }

            .badge {
                padding: 0.4rem 0.8rem;
                font-size: 0.7rem;
            }
        }

        /* Dark mode support */
        @media (prefers-color-scheme: dark) {
            :root {
                --bg-white: #1f2937;
                --bg-light: #111827;
                --text-primary: #f9fafb;
                --text-secondary: #d1d5db;
                --border-light: #374151;
            }
        }

        /* Print styles */
        @media print {

            .floating-nav,
            .scroll-progress {
                display: none !important;
            }

            .section {
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .info-card {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }

        /* Enhanced Utility Classes */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .mb-1 {
            margin-bottom: 0.25rem;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .mb-3 {
            margin-bottom: 0.75rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .mt-4 {
            margin-top: 1rem;
        }

        .font-bold {
            font-weight: 700;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-medium {
            font-weight: 500;
        }

        .text-sm {
            font-size: 0.875rem;
            color: var(--text-secondary);
            opacity: 1;
        }

        .text-lg {
            font-size: 1.125rem;
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .justify-center {
            justify-content: center;
        }

        .gap-2 {
            gap: 0.5rem;
        }

        .gap-4 {
            gap: 1rem;
        }

        .rounded-full {
            border-radius: 50%;
        }

        .shadow-lg {
            box-shadow: var(--shadow-lg);
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        /* Force text visibility - Override any potential conflicts */
        p,
        span,
        div,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        li,
        td,
        th {
            opacity: 1 !important;
        }

        /* Specific text color fixes */
        .data-label {
            color: var(--text-secondary) !important;
            opacity: 1 !important;
        }

        .text-secondary {
            color: var(--text-secondary) !important;
            opacity: 1 !important;
        }

        /* Fun Animations */
        @keyframes bounce {

            0%,
            20%,
            53%,
            80%,
            100% {
                transform: translateY(0);
            }

            40%,
            43% {
                transform: translateY(-10px);
            }

            70% {
                transform: translateY(-5px);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        @keyframes wiggle {

            0%,
            7% {
                transform: rotateZ(0);
            }

            15% {
                transform: rotateZ(-15deg);
            }

            20% {
                transform: rotateZ(10deg);
            }

            25% {
                transform: rotateZ(-10deg);
            }

            30% {
                transform: rotateZ(6deg);
            }

            35% {
                transform: rotateZ(-4deg);
            }

            40%,
            100% {
                transform: rotateZ(0);
            }
        }

        .bounce:hover {
            animation: bounce 1s ease infinite;
        }

        .pulse:hover {
            animation: pulse 2s ease-in-out infinite;
        }

        .wiggle:hover {
            animation: wiggle 0.8s ease-in-out;
        }
    </style>
</head>

<body>
    <!-- Luxury Navigation -->
    <nav class="luxury-nav" id="navbar">
        <div class="nav-container">
            <div class="nav-logo">
                <img src="{{ asset('images/school_logo.png') }}" alt="SFMNHS Logo">
                <a href="#" class="nav-brand">SFMNHS</a>
            </div>
            <ul class="nav-menu" id="nav-menu">
                <li><a href="#hero" class="nav-link">Home</a></li>
                <li><a href="#about" class="nav-link">About</a></li>
                <li><a href="#statistics" class="nav-link">Statistics</a></li>
                <li><a href="#programs" class="nav-link">Programs</a></li>
                <li><a href="#leadership" class="nav-link">Leadership</a></li>
                <li><a href="#achievements" class="nav-link">Achievements</a></li>
            </ul>
            <div class="nav-toggle" id="nav-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </div>
    </nav>

    <!-- Scroll Progress -->
    <div class="scroll-progress" id="scroll-progress"></div>

    <!-- Hero Section -->
    <section class="hero-section" id="hero">
        <div class="particle-background">
            <!-- Particles will be generated by JavaScript -->
        </div>

        <div class="hero-content" data-aos="fade-up" data-aos-duration="1000">
            <div class="hero-logo">
                <img src="{{ asset('images/school_logo.png') }}" alt="SFMNHS Logo">
            </div>

            <h1 class="hero-title">Sitero Francisco Memorial</h1>
            <h2 class="hero-subtitle">National High School</h2>

            <p class="hero-description">
                Excellence in Education, Character Formation, and Academic Achievement.
                Nurturing future leaders through innovative learning and holistic development
                in a distinguished academic environment.
            </p>

            <div class="hero-badges">
                <div class="luxury-badge">Est. 2002</div>
                <div class="luxury-badge">Public High School</div>
                <div class="luxury-badge">Academic Excellence</div>
                <div class="luxury-badge">Character Formation</div>
            </div>

            <a href="#about" class="hero-cta">
                <span>Explore Our School</span>
                <i class="fas fa-arrow-down"></i>
            </a>
        </div>

        <div class="hero-scroll" onclick="document.getElementById('about').scrollIntoView({behavior: 'smooth'})">
            <i class="fas fa-chevron-down"></i>
            <span>Scroll to Discover</span>
        </div>
    </section>

    <!-- About Section -->
    <section class="luxury-section light" id="about">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <div class="section-badge">About Our Institution</div>
                <h2 class="section-title">A Legacy of Excellence</h2>
                <p class="section-subtitle">
                    Founded in 2002, Sitero Francisco Memorial National High School stands as a beacon of educational
                    excellence,
                    nurturing minds and shaping futures in the heart of Valenzuela City.
                </p>
            </div>

            <div class="luxury-grid-2">
                <div class="luxury-card hover-lift" data-aos="fade-right">
                    <div class="card-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <h3 class="card-title">Academic Excellence</h3>
                    <p class="card-description">
                        We provide comprehensive K-12 education with a focus on academic rigor,
                        critical thinking, and innovative learning methodologies that prepare students
                        for higher education and future careers.
                    </p>
                </div>

                <div class="luxury-card hover-lift" data-aos="fade-left">
                    <div class="card-icon">
                        <i class="fas fa-heart"></i>
                    </div>
                    <h3 class="card-title">Character Development</h3>
                    <p class="card-description">
                        Beyond academic achievement, we emphasize moral values, ethical leadership,
                        and character formation to develop well-rounded individuals who contribute
                        positively to society.
                    </p>
                </div>

                <div class="luxury-card hover-lift" data-aos="fade-right">
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="card-title">Community Engagement</h3>
                    <p class="card-description">
                        Our school actively participates in community development, fostering
                        partnerships with local organizations and encouraging students to become
                        responsible citizens and future leaders.
                    </p>
                </div>

                <div class="luxury-card hover-lift" data-aos="fade-left">
                    <div class="card-icon">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <h3 class="card-title">Innovation & Technology</h3>
                    <p class="card-description">
                        We embrace modern educational technologies and innovative teaching methods
                        to create engaging learning experiences that prepare students for the
                        digital age.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="luxury-section dark" id="statistics">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <div class="section-badge" style="background: rgba(212, 175, 55, 0.2); color: var(--primary-gold);">
                    School Statistics</div>
                <h2 class="section-title white">Numbers That Define Excellence</h2>
                <p class="section-subtitle" style="color: rgba(255, 255, 255, 0.8);">
                    Our achievements speak volumes about our commitment to educational excellence and community
                    development.
                </p>
            </div>

            <div class="stats-grid">
                <div class="stat-card hover-glow" data-aos="fade-up" data-aos-delay="100">
                    <div class="stat-number">2,847</div>
                    <div class="stat-label">Junior High Students</div>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 87.3%" data-width="87.3"></div>
                        </div>
                        <div class="progress-label">
                            <span style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">87.3% of total
                                enrollment</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card hover-glow" data-aos="fade-up" data-aos-delay="200">
                    <div class="stat-number">413</div>
                    <div class="stat-label">Senior High Students</div>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 12.7%" data-width="12.7"></div>
                        </div>
                        <div class="progress-label">
                            <span style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">12.7% of total
                                enrollment</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card hover-glow" data-aos="fade-up" data-aos-delay="300">
                    <div class="stat-number">91</div>
                    <div class="stat-label">Teaching Staff</div>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 100%" data-width="100"></div>
                        </div>
                        <div class="progress-label">
                            <span style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">Dedicated
                                Educators</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card hover-glow" data-aos="fade-up" data-aos-delay="400">
                    <div class="stat-number">23</div>
                    <div class="stat-label">Non-Teaching Staff</div>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 80%" data-width="80"></div>
                        </div>
                        <div class="progress-label">
                            <span style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">Support Personnel</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card hover-glow" data-aos="fade-up" data-aos-delay="500">
                    <div class="stat-number">9,136</div>
                    <div class="stat-label">Campus Area (SQM)</div>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 95%" data-width="95"></div>
                        </div>
                        <div class="progress-label">
                            <span style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">Spacious Learning
                                Environment</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card hover-glow" data-aos="fade-up" data-aos-delay="600">
                    <div class="stat-number">21</div>
                    <div class="stat-label">Years of Excellence</div>
                    <div class="progress-container">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 85%" data-width="85"></div>
                        </div>
                        <div class="progress-label">
                            <span style="color: rgba(255, 255, 255, 0.7); font-size: 0.9rem;">Since 2002</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Leadership Section -->
    <section class="luxury-section gold" id="leadership">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <div class="section-badge">School Leadership</div>
                <h2 class="section-title">Visionary Leadership</h2>
                <p class="section-subtitle">
                    Our dedicated administrators and educators work tirelessly to provide exceptional educational
                    experiences.
                </p>
            </div>

            <div class="luxury-card" style="max-width: 800px; margin: 0 auto;" data-aos="fade-up">
                <div
                    style="display: flex; flex-direction: column; align-items: center; text-align: center; gap: 2rem;">
                    <div
                        style="width: 150px; height: 150px; border-radius: 50%; border: 4px solid var(--primary-gold); padding: 1rem; background: rgba(255, 255, 255, 0.9); display: flex; align-items: center; justify-content: center;">
                        <img src="{{ asset('images/principal.jpg') }}" alt="School Principal"
                            style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                        <div
                            style="display: none; align-items: center; justify-content: center; width: 100%; height: 100%; background: var(--bg-light); border-radius: 50%;">
                            <i class="fas fa-user" style="font-size: 3rem; color: var(--primary-gold);"></i>
                        </div>
                    </div>

                    <div>
                        <h3 class="font-display"
                            style="font-size: 2rem; font-weight: 700; color: var(--primary-navy); margin-bottom: 0.5rem;">
                            Dr. Benilda B. Santos
                        </h3>
                        <p
                            style="color: var(--primary-gold); font-weight: 600; font-size: 1.2rem; margin-bottom: 2rem;">
                            School Principal IV
                        </p>

                        <div
                            style="max-width: 600px; margin: 0 auto; font-size: 1.1rem; line-height: 1.8; color: var(--text-primary);">
                            <p style="margin-bottom: 1.5rem; font-style: italic;">
                                "Welcome to Sitero Francisco Memorial National High School, where we are committed to
                                providing quality education that develops not just academic excellence, but also strong
                                character and values."
                            </p>
                            <p style="margin-bottom: 1.5rem;">
                                "Our school continues to strive for educational excellence while maintaining our
                                commitment to developing well-rounded individuals who will contribute meaningfully to
                                society."
                            </p>
                            <p style="font-weight: 600; color: var(--primary-navy);">
                                "Together, we build futures through education, integrity, and service to our community."
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Programs Section -->
    <section class="luxury-section light" id="programs">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <div class="section-badge">Academic Programs</div>
                <h2 class="section-title">Educational Excellence Pathways</h2>
                <p class="section-subtitle">
                    Comprehensive K-12 education with specialized tracks to prepare students for their chosen career
                    paths and higher education.
                </p>
            </div>

            <div class="luxury-grid-3">
                <!-- Academic Track -->
                <div class="luxury-card hover-lift" data-aos="zoom-in" data-aos-delay="100">
                    <div class="card-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <h3 class="card-title">Academic Track</h3>
                    <p class="card-description">
                        Designed for students planning to pursue higher education. Provides strong foundation in core
                        academic subjects
                        with emphasis on critical thinking, research skills, and college preparation.
                    </p>
                    <div style="margin-top: 2rem;">
                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Mathematics</span>
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Sciences</span>
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Literature</span>
                        </div>
                    </div>
                </div>

                <!-- TVL Track -->
                <div class="luxury-card hover-lift" data-aos="zoom-in" data-aos-delay="200">
                    <div class="card-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3 class="card-title">Technical-Vocational-Livelihood</h3>
                    <p class="card-description">
                        Hands-on learning approach that develops technical skills and entrepreneurial mindset.
                        Students gain practical experience in various trades and vocational areas.
                    </p>
                    <div style="margin-top: 2rem;">
                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Food
                                Service</span>
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">ICT</span>
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Entrepreneurship</span>
                        </div>
                    </div>
                </div>

                <!-- Arts & Design Track -->
                <div class="luxury-card hover-lift" data-aos="zoom-in" data-aos-delay="300">
                    <div class="card-icon">
                        <i class="fas fa-palette"></i>
                    </div>
                    <h3 class="card-title">Arts & Design</h3>
                    <p class="card-description">
                        Nurtures creative expression and artistic talents. Students explore various art forms,
                        design principles, and cultural appreciation while developing their creative potential.
                    </p>
                    <div style="margin-top: 2rem;">
                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Visual
                                Arts</span>
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Media
                                Arts</span>
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Creative
                                Writing</span>
                        </div>
                    </div>
                </div>

                <!-- Sports Track -->
                <div class="luxury-card hover-lift" data-aos="zoom-in" data-aos-delay="400">
                    <div class="card-icon">
                        <i class="fas fa-running"></i>
                    </div>
                    <h3 class="card-title">Sports Track</h3>
                    <p class="card-description">
                        Develops athletic excellence while maintaining academic standards. Perfect for student-athletes
                        who want to balance sports training with quality education.
                    </p>
                    <div style="margin-top: 2rem;">
                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Basketball</span>
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Volleyball</span>
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Track
                                & Field</span>
                        </div>
                    </div>
                </div>

                <!-- HUMSS Track -->
                <div class="luxury-card hover-lift" data-aos="zoom-in" data-aos-delay="500">
                    <div class="card-icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="card-title">Humanities & Social Sciences</h3>
                    <p class="card-description">
                        Focuses on understanding human behavior, society, and culture. Ideal for students interested
                        in social work, education, politics, and human services.
                    </p>
                    <div style="margin-top: 2rem;">
                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Psychology</span>
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Sociology</span>
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Philosophy</span>
                        </div>
                    </div>
                </div>

                <!-- STEM Track -->
                <div class="luxury-card hover-lift" data-aos="zoom-in" data-aos-delay="600">
                    <div class="card-icon">
                        <i class="fas fa-atom"></i>
                    </div>
                    <h3 class="card-title">Science, Technology, Engineering & Mathematics</h3>
                    <p class="card-description">
                        Advanced study in STEM fields with hands-on laboratory work and research projects.
                        Prepares students for careers in science, technology, and engineering.
                    </p>
                    <div style="margin-top: 2rem;">
                        <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Physics</span>
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Chemistry</span>
                            <span
                                style="background: var(--secondary-gold); color: var(--primary-navy); padding: 0.3rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">Engineering</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Achievements Section -->
    <section class="luxury-section dark" id="achievements">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <div class="section-badge" style="background: rgba(212, 175, 55, 0.2); color: var(--primary-gold);">
                    Our Achievements</div>
                <h2 class="section-title white">Awards & Recognition</h2>
                <p class="section-subtitle" style="color: rgba(255, 255, 255, 0.8);">
                    Our commitment to excellence has been recognized through various awards and achievements over the
                    years.
                </p>
            </div>

            <div class="luxury-timeline">
                <div class="timeline-item" data-aos="fade-right">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">2023</div>
                        <div class="timeline-title">Outstanding Public School Recognition</div>
                        <p>Recognized for excellence in academic performance and community engagement by the Department
                            of Education.</p>
                    </div>
                </div>

                <div class="timeline-item" data-aos="fade-left">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">2022</div>
                        <div class="timeline-title">Best Performing School in Division</div>
                        <p>Achieved highest overall rating in academic excellence, school management, and student
                            development programs.</p>
                    </div>
                </div>

                <div class="timeline-item" data-aos="fade-right">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">2021</div>
                        <div class="timeline-title">Digital Innovation Award</div>
                        <p>Recognized for successful implementation of digital learning platforms during the pandemic.
                        </p>
                    </div>
                </div>

                <div class="timeline-item" data-aos="fade-left">
                    <div class="timeline-dot"></div>
                    <div class="timeline-content">
                        <div class="timeline-date">2020</div>
                        <div class="timeline-title">Environmental Sustainability Champion</div>
                        <p>Awarded for outstanding environmental programs and sustainable campus initiatives.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="luxury-section dark" id="contact">
        <div class="container">
            <div class="section-header" data-aos="fade-up">
                <div class="section-badge" style="background: rgba(255, 255, 255, 0.2); color: var(--primary-navy);">
                    Get in Touch</div>
                <h2 class="section-title white">Contact Us</h2>
                <p class="section-subtitle" style="color: rgba(255, 255, 255, 0.8);">
                    We welcome your inquiries and feedback. Reach out to us through the following channels:
                </p>
            </div>

            <div class="grid-3">
                <div class="info-card" data-aos="fade-up">
                    <div class="flex items-center mb-3">
                        <div
                            style="width: 60px; height: 60px; background: #3b82f6; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                            <i class="fas fa-map-marker-alt" style="color: white; font-size: 1.5rem;"></i>
                        </div>
                        <h3 class="font-bold" style="color: #3b82f6;">Our Address</h3>
                    </div>
                    <p class="text-sm" style="color: var(--text-secondary);">
                        Sta. Monica Subdivision, Ugong<br>
                        Valenzuela City, Metro Manila<br>
                        Philippines
                    </p>
                </div>

                <div class="info-card" data-aos="fade-up" data-aos-delay="100">
                    <div class="flex items-center mb-3">
                        <div
                            style="width: 60px; height: 60px; background: #059669; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                            <i class="fas fa-phone" style="color: white; font-size: 1.5rem;"></i>
                        </div>
                        <h3 class="font-bold" style="color: #059669;">Phone Numbers</h3>
                    </div>
                    <p class="text-sm" style="color: var(--text-secondary);">
                        📞 0922 625 1196<br>
                        📞 (02) 288-1695
                    </p>
                </div>

                <div class="info-card" data-aos="fade-up" data-aos-delay="200">
                    <div class="flex items-center mb-3">
                        <div
                            style="width: 60px; height: 60px; background: #dc2626; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                            <i class="fas fa-envelope" style="color: white; font-size: 1.5rem;"></i>
                        </div>
                        <h3 class="font-bold" style="color: #dc2626;">Email Us</h3>
                    </div>
                    <p class="text-sm" style="color: var(--text-secondary);">
                        sfmnhs@gmail.com<br>
                        siterohigh@yahoo.com
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Senior High School Programs -->
    <section class="section" id="programs">
        <div class="container">
            <h2 class="section-title">🎓 Senior High School Programs</h2>
            <p class="text-center mb-4"
                style="font-size: 1.1rem; color: var(--text-secondary); max-width: 700px; margin: 0 auto 3rem;">
                Preparing students for higher education, employment, and entrepreneurship through specialized learning
                tracks.
            </p>
            <div class="grid-2">
                <div class="info-card"
                    style="background: linear-gradient(145deg, #eff6ff, #dbeafe); border: 2px solid var(--accent-blue);">
                    <div class="flex items-center mb-4">
                        <div
                            style="width: 60px; height: 60px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                            <i class="fas fa-graduation-cap" style="color: white; font-size: 1.5rem;"></i>
                        </div>
                        <h3 class="font-bold" style="color: #1d4ed8; font-size: 1.5rem;">📚 Academic Tracks</h3>
                    </div>

                    <div class="mb-4"
                        style="border-left: 4px solid #3b82f6; padding-left: 1.5rem; background: rgba(59, 130, 246, 0.05); padding: 1rem; border-radius: 0 10px 10px 0;">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-chart-line"
                                style="color: #3b82f6; margin-right: 1rem; font-size: 1.5rem;"></i>
                            <div>
                                <h4 class="font-bold" style="font-size: 1.1rem;">📊 ABM Track</h4>
                                <p class="text-sm font-semibold" style="color: var(--accent-blue);">Accountancy,
                                    Business & Management</p>
                            </div>
                        </div>
                        <p class="text-sm" style="color: var(--text-secondary); line-height: 1.6;">
                            Perfect for students interested in business, entrepreneurship, and financial management
                            careers.
                        </p>
                    </div>

                    <div
                        style="border-left: 4px solid #3b82f6; padding-left: 1.5rem; background: rgba(59, 130, 246, 0.05); padding: 1rem; border-radius: 0 10px 10px 0;">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-globe-americas"
                                style="color: #3b82f6; margin-right: 1rem; font-size: 1.5rem;"></i>
                            <div>
                                <h4 class="font-bold" style="font-size: 1.1rem;">🌐 GAS Track</h4>
                                <p class="text-sm font-semibold" style="color: var(--accent-blue);">General Academic
                                    Strand</p>
                            </div>
                        </div>
                        <p class="text-sm" style="color: var(--text-secondary); line-height: 1.6;">
                            Ideal for students who want flexibility in choosing their college course and career path.
                        </p>
                    </div>
                </div>

                <div class="info-card"
                    style="background: linear-gradient(145deg, #ecfdf5, #dcfce7); border: 2px solid var(--accent-green);">
                    <div class="flex items-center mb-4">
                        <div
                            style="width: 60px; height: 60px; background: linear-gradient(135deg, #16a34a, #15803d); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-right: 1rem;">
                            <i class="fas fa-tools" style="color: white; font-size: 1.5rem;"></i>
                        </div>
                        <h3 class="font-bold" style="color: #15803d; font-size: 1.5rem;">🔧
                            Technical-Vocational-Livelihood</h3>
                    </div>

                    <div class="mb-4"
                        style="border-left: 4px solid #16a34a; padding-left: 1.5rem; background: rgba(16, 185, 129, 0.05); padding: 1rem; border-radius: 0 10px 10px 0;">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-laptop-code"
                                style="color: #16a34a; margin-right: 1rem; font-size: 1.5rem;"></i>
                            <div>
                                <h4 class="font-bold" style="font-size: 1.1rem;">💻 ICT Track</h4>
                                <p class="text-sm font-semibold" style="color: var(--accent-green);">Computer System
                                    Servicing</p>
                            </div>
                        </div>
                        <p class="text-sm" style="color: var(--text-secondary); line-height: 1.6;">
                            Hands-on training in computer hardware, software installation, and network troubleshooting.
                        </p>
                    </div>

                    <div
                        style="border-left: 4px solid #16a34a; padding-left: 1.5rem; background: rgba(16, 185, 129, 0.05); padding: 1rem; border-radius: 0 10px 10px 0;">
                        <div class="flex items-center mb-3">
                            <i class="fas fa-bolt" style="color: #16a34a; margin-right: 1rem; font-size: 1.5rem;"></i>
                            <div>
                                <h4 class="font-bold" style="font-size: 1.1rem;">⚡ Industrial Arts</h4>
                                <p class="text-sm font-semibold" style="color: var(--accent-green);">Electrical
                                    Installation & Maintenance</p>
                            </div>
                        </div>
                        <p class="text-sm" style="color: var(--text-secondary); line-height: 1.6;">
                            Practical skills in electrical systems, wiring, and maintenance for immediate employment.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Program Benefits -->
            <div class="info-card mt-4"
                style="background: linear-gradient(135deg, #fef3c7, #fffbeb); text-align: center;">
                <h3 class="font-bold mb-4" style="font-size: 1.3rem; color: var(--primary-navy);">🌟 Why Choose SFMNHS
                    Programs?</h3>
                <div class="grid-3">
                    <div style="padding: 1rem;">
                        <i class="fas fa-certificate"
                            style="font-size: 2rem; color: var(--primary-gold); margin-bottom: 0.5rem;"></i>
                        <h4 class="font-semibold mb-2">Industry-Ready Skills</h4>
                        <p class="text-sm" style="color: var(--text-secondary);">Practical training aligned with
                            industry standards</p>
                    </div>
                    <div style="padding: 1rem;">
                        <i class="fas fa-users"
                            style="font-size: 2rem; color: var(--primary-gold); margin-bottom: 0.5rem;"></i>
                        <h4 class="font-semibold mb-2">Expert Faculty</h4>
                        <p class="text-sm" style="color: var(--text-secondary);">Qualified teachers with industry
                            experience</p>
                    </div>
                    <div style="padding: 1rem;">
                        <i class="fas fa-trophy"
                            style="font-size: 2rem; color: var(--primary-gold); margin-bottom: 0.5rem;"></i>
                        <h4 class="font-semibold mb-2">Proven Excellence</h4>
                        <p class="text-sm" style="color: var(--text-secondary);">High graduate employment and college
                            acceptance rates</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- School Anthem -->
    <section class="section section-alt">
        <div class="container">
            <h2 class="section-title">School Anthem</h2>
            <div class="info-card text-center" style="max-width: 600px; margin: 0 auto;">
                <h3 class="font-bold mb-2" style="font-size: 1.5rem;">"Himig ng SFMNHS"</h3>
                <p class="mb-4" style="color: var(--text-secondary);">Lyrics and Music by <strong>Virgilio T. Santos
                        Jr.</strong></p>
                <div style="line-height: 1.8; font-size: 1rem;">
                    <p class="mb-3">Dangal ka ng puso<br>
                        Tanglaw sa aming buhay<br>
                        Hinubog sa 'yong adhikain<br>
                        Talinong aming angkin</p>

                    <p class="mb-3">Luwalhati sa 'yong ngalan<br>
                        Mahal naming paaralan<br>
                        Pasasalamat at lubusan<br>
                        Saksi ang buong tanan</p>

                    <p>O aming Alma Mater<br>
                        Ningas mo'y itataas<br>
                        Hindi ka mawawaglit<br>
                        Kailanman sa aming puso</p>
                </div>
            </div>
        </div>
    </section> <!-- Enhanced Footer -->
    <footer id="contact"
        style="background: linear-gradient(135deg, #111827, #1f2937); color: white; padding: 4rem 0 2rem; position: relative; overflow: hidden;">
        <!-- Background Pattern -->
        <div
            style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; opacity: 0.1; background-image: url('data:image/svg+xml,<svg xmlns=&quot;http://www.w3.org/2000/svg&quot; viewBox=&quot;0 0 100 100&quot;><defs><pattern id=&quot;grid&quot; width=&quot;10&quot; height=&quot;10&quot; patternUnits=&quot;userSpaceOnUse&quot;><path d=&quot;M 10 0 L 0 0 0 10&quot; fill=&quot;none&quot; stroke=&quot;white&quot; stroke-width=&quot;0.5&quot;/></pattern></defs><rect width=&quot;100&quot; height=&quot;100&quot; fill=&quot;url(%23grid)&quot;/></svg>');">
        </div>

        <div class="container" style="position: relative; z-index: 10;">
            <div class="text-center mb-4">
                <div
                    style="display: inline-block; padding: 1rem 2rem; background: linear-gradient(45deg, var(--primary-navy), var(--primary-gold)); border-radius: 50px; margin-bottom: 2rem;">
                    <h3 class="font-bold mb-2" style="font-size: 1.8rem; margin-bottom: 0;">📞 Contact Information
                    </h3>
                </div>
                <p style="font-size: 1.1rem; color: #d1d5db; max-width: 600px; margin: 0 auto;">
                    🤝 Ready to be part of our educational community? Get in touch with us!
                </p>
            </div>

            <div class="grid-3 mb-4">
                <div class="info-card"
                    style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); text-align: center;">
                    <div
                        style="width: 80px; height: 80px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; box-shadow: var(--shadow-lg);">
                        <i class="fas fa-map-marker-alt bounce" style="color: white; font-size: 2rem;"></i>
                    </div>
                    <h4 class="font-semibold mb-2" style="color: #60a5fa;">📍 Address</h4>
                    <p style="color: #d1d5db; font-size: 0.9rem; line-height: 1.6;">
                        Sta. Monica Subdivision, Ugong<br>
                        Valenzuela City, Metro Manila<br>
                        <span style="color: #fbbf24;">Philippines 🇵🇭</span>
                    </p>
                </div>

                <div class="info-card"
                    style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); text-align: center;">
                    <div
                        style="width: 80px; height: 80px; background: linear-gradient(135deg, #16a34a, #15803d); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; box-shadow: var(--shadow-lg);">
                        <i class="fas fa-phone pulse" style="color: white; font-size: 2rem;"></i>
                    </div>
                    <h4 class="font-semibold mb-2" style="color: #4ade80;">📞 Phone Numbers</h4>
                    <p style="color: #d1d5db; font-size: 0.9rem; line-height: 1.6;">
                        📱 <strong>0922 625 1196</strong><br>
                        ☎️ <strong>(02) 288-1695</strong><br>
                        <span style="color: #10b981;">Available Mon-Fri 7AM-5PM</span>
                    </p>
                </div>

                <div class="info-card"
                    style="background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); text-align: center;">
                    <div
                        style="width: 80px; height: 80px; background: linear-gradient(135deg, #dc2626, #b91c1c); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem; box-shadow: var(--shadow-lg);">
                        <i class="fas fa-envelope wiggle" style="color: white; font-size: 2rem;"></i>
                    </div>
                    <h4 class="font-semibold mb-2" style="color: #f87171;">📧 Email Addresses</h4>
                    <p style="color: #d1d5db; font-size: 0.9rem; line-height: 1.6;">
                        📩 <strong>sfmnhs@gmail.com</strong><br>
                        📩 <strong>siterohigh@yahoo.com</strong><br>
                        <span style="color: #f87171;">Response within 24 hours</span>
                    </p>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="info-card mb-4"
                style="background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); text-align: center;">
                <h4 class="font-semibold mb-3" style="color: #fbbf24; font-size: 1.2rem;">🔗 Quick Navigation</h4>
                <div class="flex justify-center gap-4" style="flex-wrap: wrap;">
                    <button onclick="scrollToSection('header')" class="badge transition-all"
                        style="cursor: pointer; border: none;">🏠 Home</button>
                    <button onclick="scrollToSection('stats')" class="badge transition-all"
                        style="cursor: pointer; border: none;">📊 Statistics</button>
                    <button onclick="scrollToSection('values')" class="badge transition-all"
                        style="cursor: pointer; border: none;">🌟 Values</button>
                    <button onclick="scrollToSection('programs')" class="badge transition-all"
                        style="cursor: pointer; border: none;">🎓 Programs</button>
                    <button onclick="scrollToSection('contact')" class="badge transition-all"
                        style="cursor: pointer; border: none;">📞 Contact</button>
                </div>
            </div>

            <!-- Copyright -->
            <div style="border-top: 1px solid rgba(255, 255, 255, 0.2); padding-top: 2rem; text-align: center;">
                <div
                    style="background: rgba(255, 255, 255, 0.1); padding: 1.5rem; border-radius: 15px; backdrop-filter: blur(10px);">
                    <p style="color: #9ca3af; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        Made with 💖 for education • Designed for students, teachers & staff
                    </p>
                    <p style="color: #6b7280; font-size: 0.875rem;">
                        &copy; 2025 <strong style="color: #fbbf24;">Sitero Francisco Memorial National High
                            School</strong>. All rights reserved.
                    </p>
                    <p style="color: #4b5563; font-size: 0.8rem; margin-top: 0.5rem;">
                        🚀 Empowering minds • Building futures • Creating leaders
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Luxury Footer -->
    <footer class="luxury-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-logo">
                    <img src="{{ asset('images/school_logo.png') }}" alt="SFMNHS Logo"
                        style="width: 100%; height: 100%; object-fit: contain;">
                </div>

                <h3 class="footer-title">Sitero Francisco Memorial National High School</h3>

                <p class="footer-text">
                    Excellence in Education, Character Formation, and Academic Achievement.
                    Building futures through quality education and holistic development since 2002.
                </p>

                <div style="display: flex; justify-content: center; gap: 2rem; margin: 2rem 0; flex-wrap: wrap;">
                    <div style="text-align: center;">
                        <i class="fas fa-map-marker-alt"
                            style="color: var(--primary-gold); font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                        <p style="font-size: 0.9rem; opacity: 0.8;">Sta. Monica Subdivision<br>Ugong, Valenzuela City
                        </p>
                    </div>

                    <div style="text-align: center;">
                        <i class="fas fa-phone"
                            style="color: var(--primary-gold); font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                        <p style="font-size: 0.9rem; opacity: 0.8;">School Office<br>Contact Information</p>
                    </div>

                    <div style="text-align: center;">
                        <i class="fas fa-envelope"
                            style="color: var(--primary-gold); font-size: 1.5rem; margin-bottom: 0.5rem;"></i>
                        <p style="font-size: 0.9rem; opacity: 0.8;">Official Email<br>Communication</p>
                    </div>
                </div>

                <div class="footer-divider"></div>

                <p class="footer-copyright">
                    © 2024 Sitero Francisco Memorial National High School. All rights reserved.
                    <br>
                    <span style="font-size: 0.8rem; opacity: 0.6;">Designed with excellence in mind</span>
                </p>
            </div>
        </div>
    </footer>

    <!-- AOS Animation Library -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>

    <!-- Enhanced JavaScript -->
    <script>
        // Suppress Google Maps console errors
        window.addEventListener('error', function(e) {
            if (e.filename && (e.filename.includes('maps.googleapis.com') ||
                    e.filename.includes('google') ||
                    e.message.includes('google'))) {
                e.preventDefault();
                return true;
            }
        });

        // Scroll Progress Bar
        function updateScrollProgress() {
            const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrollProgress = (scrollTop / scrollHeight) * 100;
            document.getElementById('scrollProgress').style.width = scrollProgress + '%';
        }

        // Smooth scroll to section
        function scrollToSection(sectionId) {
            const element = document.getElementById(sectionId);
            if (element) {
                element.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });

                // Update active nav button
                document.querySelectorAll('.nav-btn').forEach(btn => btn.classList.remove('active'));
                event.target.classList.add('active');
            }
        }

        // Animate statistics bars on scroll
        const observerOptions = {
            threshold: 0.3,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Animate stat bars
                    const bars = entry.target.querySelectorAll('.stat-fill');
                    bars.forEach(bar => {
                        const width = bar.getAttribute('data-width') || bar.style.width;
                        bar.style.width = '0%';
                        setTimeout(() => {
                            bar.style.transition = 'width 2s cubic-bezier(0.4, 0, 0.2, 1)';
                            bar.style.width = width + (width.includes('%') ? '' : '%');
                        }, 300);
                    }); // Animate section titles
                    const titles = entry.target.querySelectorAll('.section-title');
                    titles.forEach((title, index) => {
                        setTimeout(() => {
                            title.style.animation = 'fadeInUp 0.8s ease-out forwards';
                        }, index * 200);
                    });

                    // Animate cards with stagger effect
                    const cards = entry.target.querySelectorAll('.info-card');
                    cards.forEach((card, index) => {
                        setTimeout(() => {
                            card.style.opacity = '0';
                            card.style.transform = 'translateY(30px)';
                            card.style.transition =
                                'all 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
                            setTimeout(() => {
                                card.style.opacity = '1';
                                card.style.transform = 'translateY(0)';
                            }, 50);
                        }, index * 150);
                    });
                }
            });
        }, observerOptions);

        // Add interactive hover effects
        function addCardInteractions() {
            document.querySelectorAll('.card-interactive').forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-10px) scale(1.02)';
                    this.style.boxShadow = 'var(--shadow-xl)';
                });

                card.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0) scale(1)';
                    this.style.boxShadow = 'var(--shadow-md)';
                });
            });
        }

        // Update active navigation based on scroll position
        function updateActiveNav() {
            const sections = ['header', 'stats', 'values', 'programs', 'contact'];
            const navButtons = document.querySelectorAll('.nav-btn');

            let currentSection = '';
            sections.forEach(sectionId => {
                const element = document.getElementById(sectionId);
                if (element) {
                    const rect = element.getBoundingClientRect();
                    if (rect.top <= 100 && rect.bottom >= 100) {
                        currentSection = sectionId;
                    }
                }
            });

            navButtons.forEach((btn, index) => {
                btn.classList.remove('active');
                if (sections[index] === currentSection) {
                    btn.classList.add('active');
                }
            });
        }

        // Initialize everything when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            // Observe all sections for animations
            const sections = document.querySelectorAll('.section');
            sections.forEach(section => observer.observe(section));

            // Add card interactions
            addCardInteractions();

            // Add scroll listeners
            window.addEventListener('scroll', function() {
                updateScrollProgress();
                updateActiveNav();
            });

            // Initial calls
            updateScrollProgress();
            updateActiveNav();

            // Add easter egg - click on school name for confetti effect
            document.querySelector('.main-title').addEventListener('click', function() {
                // Simple confetti effect using existing elements
                for (let i = 0; i < 50; i++) {
                    const confetti = document.createElement('div');
                    confetti.style.cssText = `
                        position: fixed;
                        width: 10px;
                        height: 10px;
                        background: ${['#3b82f6', '#f59e0b', '#16a34a', '#ef4444'][Math.floor(Math.random() * 4)]};
                        top: 20%;
                        left: ${Math.random() * 100}%;
                        animation: confetti-fall 3s linear forwards;
                        z-index: 10000;
                        pointer-events: none;
                    `;
                    document.body.appendChild(confetti);

                    setTimeout(() => confetti.remove(), 3000);
                }
            });

            // Add CSS animation for confetti
            const style = document.createElement('style');
            style.textContent = `
                @keyframes confetti-fall {
                    to {
                        transform: translateY(100vh) rotate(360deg);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        });

        // Initialize AOS (Animate On Scroll)
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100,
            easing: 'ease-out-cubic'
        });

        // Luxury JavaScript functionality
        class LuxuryWebsite {
            constructor() {
                this.navbar = document.getElementById('navbar');
                this.scrollProgress = document.getElementById('scroll-progress');
                this.navToggle = document.getElementById('nav-toggle');
                this.navMenu = document.getElementById('nav-menu');

                this.init();
            }

            init() {
                this.setupScrollEffects();
                this.setupNavigation();
                this.setupAnimations();
                this.setupParticles();
                this.setupProgressBars();
                this.setupInteractiveElements();
                this.setupHeroTyping();
            }

            setupScrollEffects() {
                let ticking = false;

                const updateScrollEffects = () => {
                    const scrolled = window.pageYOffset;
                    const documentHeight = document.documentElement.scrollHeight - window.innerHeight;
                    const scrollPercent = (scrolled / documentHeight) * 100;

                    // Update scroll progress
                    this.scrollProgress.style.width = scrollPercent + '%';

                    // Update navbar
                    if (scrolled > 100) {
                        this.navbar.classList.add('scrolled');
                    } else {
                        this.navbar.classList.remove('scrolled');
                    }

                    ticking = false;
                };

                const requestScrollUpdate = () => {
                    if (!ticking) {
                        requestAnimationFrame(updateScrollEffects);
                        ticking = true;
                    }
                };

                window.addEventListener('scroll', requestScrollUpdate);
                updateScrollEffects();
            }

            setupNavigation() {
                // Mobile menu toggle
                if (this.navToggle) {
                    this.navToggle.addEventListener('click', () => {
                        this.navMenu.classList.toggle('active');
                        this.navToggle.classList.toggle('active');
                    });
                }

                // Smooth scrolling for navigation links
                document.querySelectorAll('.nav-link').forEach(link => {
                    link.addEventListener('click', (e) => {
                        e.preventDefault();
                        const targetId = link.getAttribute('href');
                        const targetElement = document.querySelector(targetId);

                        if (targetElement) {
                            const headerOffset = 100;
                            const elementPosition = targetElement.getBoundingClientRect().top;
                            const offsetPosition = elementPosition + window.pageYOffset - headerOffset;

                            window.scrollTo({
                                top: offsetPosition,
                                behavior: 'smooth'
                            });
                        }

                        // Close mobile menu if open
                        if (this.navMenu) {
                            this.navMenu.classList.remove('active');
                            this.navToggle.classList.remove('active');
                        }
                    });
                });
            }

            setupAnimations() {
                // Intersection Observer for animations
                const observerOptions = {
                    threshold: 0.1,
                    rootMargin: '0px 0px -50px 0px'
                };

                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animate');
                        }
                    });
                }, observerOptions);

                // Observe elements for animation
                document.querySelectorAll('.fade-in-up, .timeline-item').forEach(el => {
                    observer.observe(el);
                });
            }

            setupParticles() {
                const particleContainer = document.querySelector('.particle-background');
                if (!particleContainer) return;

                // Create floating particles
                for (let i = 0; i < 30; i++) {
                    const particle = document.createElement('div');
                    particle.className = 'particle';
                    particle.style.left = Math.random() * 100 + '%';
                    particle.style.top = Math.random() * 100 + '%';
                    particle.style.animationDelay = Math.random() * 6 + 's';
                    particle.style.animationDuration = (Math.random() * 4 + 4) + 's';
                    particleContainer.appendChild(particle);
                }
            }

            setupProgressBars() {
                const progressBars = document.querySelectorAll('.progress-fill');

                const animateProgressBars = () => {
                    progressBars.forEach(bar => {
                        const targetWidth = bar.getAttribute('data-width');
                        if (targetWidth) {
                            setTimeout(() => {
                                bar.style.width = targetWidth + '%';
                            }, 500);
                        }
                    });
                };

                // Trigger animation when stats section is visible
                const statsSection = document.getElementById('statistics');
                if (statsSection) {
                    const observer = new IntersectionObserver((entries) => {
                        entries.forEach(entry => {
                            if (entry.isIntersecting) {
                                animateProgressBars();
                                observer.unobserve(entry.target);
                            }
                        });
                    }, {
                        threshold: 0.3
                    });

                    observer.observe(statsSection);
                }
            }

            setupInteractiveElements() {
                // Make hero scroll button accessible
                const heroScroll = document.querySelector('.hero-scroll');
                if (heroScroll) {
                    heroScroll.setAttribute('role', 'button');
                    heroScroll.setAttribute('tabindex', '0');
                    heroScroll.setAttribute('onkeydown',
                        'if(event.key === "Enter") document.getElementById("about").scrollIntoView({behavior: "smooth"})'
                        );
                }

                // Card hover effects
                document.querySelectorAll('.luxury-card').forEach(card => {
                    card.addEventListener('mouseenter', () => {
                        card.style.transform = 'translateY(-12px) rotateX(5deg)';
                    });

                    card.addEventListener('mouseleave', () => {
                        card.style.transform = '';
                    });
                });

                // Hero title click effect (Easter egg)
                const heroTitle = document.querySelector('.hero-title');
                if (heroTitle) {
                    heroTitle.addEventListener('click', this.createConfettiEffect.bind(this));
                    heroTitle.style.cursor = 'pointer';
                }
            }

            setupHeroTyping() {
                // Add typing effect for hero subtitle
                const heroSubtitle = document.querySelector('.hero-subtitle');
                if (heroSubtitle) {
                    const originalText = heroSubtitle.textContent;
                    heroSubtitle.textContent = '';

                    let i = 0;

                    function typeWriter() {
                        if (i < originalText.length) {
                            heroSubtitle.textContent += originalText.charAt(i);
                            i++;
                            setTimeout(typeWriter, 30);
                        }
                    }

                    setTimeout(() => {
                        typeWriter();
                    }, 1000);
                }
            }

            createConfettiEffect() {
                const colors = ['#d4af37', '#f4e4bc', '#0a1628', '#1e40af'];

                for (let i = 0; i < 60; i++) {
                    const confetti = document.createElement('div');
                    confetti.style.cssText = `
                        position: fixed;
                        width: ${Math.random() * 10 + 5}px;
                        height: ${Math.random() * 10 + 5}px;
                        background: ${colors[Math.floor(Math.random() * colors.length)]};
                        top: 30%;
                        left: ${Math.random() * 100}%;
                        border-radius: 50%;
                        pointer-events: none;
                        z-index: 10000;
                        animation: confetti-fall ${Math.random() * 2 + 2}s ease-out forwards;
                    `;
                    document.body.appendChild(confetti);

                    setTimeout(() => confetti.remove(), 3000);
                }
            }
        }

        // CSS animations and style fixes
        const styleFixElement = document.createElement('style');
        styleFixElement.textContent = `
            @keyframes confetti-fall {
                to {
                    transform: translateY(100vh) rotate(720deg);
                    opacity: 0;
                }
            }
            
            .nav-link.active {
                color: var(--primary-navy) !important;
                background: var(--gradient-gold);
            }
            
            .nav-toggle.active span:nth-child(1) {
                transform: rotate(45deg) translate(5px, 5px);
            }
            
            .nav-toggle.active span:nth-child(2) {
                opacity: 0;
            }
            
            .nav-toggle.active span:nth-child(3) {
                transform: rotate(-45deg) translate(7px, -6px);
            }
            
            @media (max-width: 768px) {
                .nav-menu.active {
                    display: flex;
                }
            }
        `;
        document.head.appendChild(styleFixElement);

        // Initialize the luxury website when DOM is loaded
        document.addEventListener('DOMContentLoaded', () => {
            new LuxuryWebsite();

            // Add loading animation
            document.body.style.opacity = '0';
            setTimeout(() => {
                document.body.style.transition = 'opacity 1s ease-in';
                document.body.style.opacity = '1';
            }, 100);
        });
    </script>
</body>

</html>
