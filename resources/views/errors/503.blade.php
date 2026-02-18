<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Under Maintenance | MAM Tours & Travel</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1a237e 0%, #283593 50%, #3f51b5 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .maintenance-container {
            max-width: 600px;
            background: white;
            border-radius: 20px;
            padding: 3rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
            animation: fadeIn 0.6s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .maintenance-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .maintenance-icon svg {
            width: 60px;
            height: 60px;
            stroke: white;
            stroke-width: 2;
            fill: none;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1a237e;
            margin-bottom: 1rem;
            letter-spacing: 1px;
        }

        h1 {
            font-size: 2rem;
            color: #1a237e;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .subtitle {
            font-size: 1.1rem;
            color: #666;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .message {
            background: #f5f5f5;
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            border-left: 4px solid #ff9800;
        }

        .message p {
            color: #333;
            line-height: 1.8;
            margin-bottom: 0.5rem;
        }

        .message p:last-child {
            margin-bottom: 0;
        }

        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            margin-top: 2rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            color: #666;
            font-size: 0.95rem;
        }

        .contact-item svg {
            width: 20px;
            height: 20px;
            stroke: #ff9800;
            stroke-width: 2;
            fill: none;
        }

        .contact-item a {
            color: #ff9800;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }

        .contact-item a:hover {
            color: #f57c00;
        }

        .footer {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e0e0e0;
            color: #999;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .maintenance-container {
                padding: 2rem;
            }

            h1 {
                font-size: 1.5rem;
            }

            .subtitle {
                font-size: 1rem;
            }

            .maintenance-icon {
                width: 100px;
                height: 100px;
            }

            .maintenance-icon svg {
                width: 50px;
                height: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">
            <svg viewBox="0 0 24 24">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
            </svg>
        </div>

        <div class="logo">MAM TOURS & TRAVEL</div>
        
        <h1>We'll Be Back Soon!</h1>
        
        <p class="subtitle">
            Our website is currently undergoing scheduled maintenance to improve your experience.
        </p>

        <div class="message">
            <p><strong>🔧 What's happening?</strong></p>
            <p>We're making some exciting updates and improvements to serve you better.</p>
            <p><strong>⏰ Expected downtime:</strong> We'll be back online shortly.</p>
        </div>

        <div class="contact-info">
            <div class="contact-item">
                <svg viewBox="0 0 24 24">
                    <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                </svg>
                <span>Need urgent assistance? Call us at <a href="tel:+256700000000">+256 700 000 000</a></span>
            </div>
            
            <div class="contact-item">
                <svg viewBox="0 0 24 24">
                    <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                    <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
                <span>Email: <a href="mailto:info@mamtours.com">info@mamtours.com</a></span>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for your patience and understanding.</p>
            <p>&copy; {{ date('Y') }} MAM Tours & Travel. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
