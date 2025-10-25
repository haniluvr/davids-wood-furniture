<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Email from David\'s Wood Furniture' }}</title>
    <style>
        /* Reset styles */
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
        }

        /* Main styles */
        body {
            margin: 0;
            padding: 0;
            width: 100% !important;
            min-width: 100%;
            height: 100%;
            background-color: #F8F8F8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333333;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #8B7355 0%, #b7a99a 100%);
            padding: 30px 20px;
            text-align: center;
        }

        .logo {
            max-width: 90px;
            height: auto;
            margin-bottom: 15px;
        }

        .company-name {
            color: #ffffff;
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .tagline {
            color: #F5F5F5;
            font-size: 14px;
            margin: 5px 0 0 0;
            font-weight: 300;
            font-style: italic;
        }

        .content {
            padding: 40px 30px;
        }

        .content h1 {
            color: #8B7355;
            font-size: 28px;
            margin: 0 0 20px 0;
            font-weight: 600;
        }

        .content h2 {
            color: #374151;
            font-size: 22px;
            margin: 30px 0 15px 0;
            font-weight: 600;
        }

        .content p {
            margin: 0 0 15px 0;
            font-size: 16px;
            line-height: 1.6;
        }

        .button {
            display: inline-block;
            padding: 12px 30px;
            background: linear-gradient(135deg, #8B7355 0%, #b7a99a 100%);
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            margin: 20px 0;
            transition: all 0.3s ease;
        }

        .button:hover {
            background: linear-gradient(135deg, #504538 0%, #b7a99a 100%);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(139, 115, 85, 0.3);
        }

        .info-box {
            background-color: #F8F8F8;
            border-left: 4px solid #8B7355;
            padding: 20px;
            margin: 20px 0;
            border-radius: 0 6px 6px 0;
        }

        .footer {
            background-color: #F8F8F8;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #E5E5E5;
        }

        .footer p {
            margin: 0 0 10px 0;
            font-size: 12px;
            color: #6b7280;
        }

        .social-links {
            margin: 9px 0 20px 0;
        }

        .social-links a {
            display: inline-block;
            margin: 0 10px;
            color: #6b7280;
            text-decoration: none;
            font-size: 12px;
        }

        .social-links a:hover {
            color: #3b82f6;
        }

        .unsubscribe {
            font-size: 9px;
            color: #9ca3af;
            margin-top: 20px;
        }

        .unsubscribe a {
            color: #9ca3af;
            text-decoration: none;
        }

        /* Responsive styles */
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            
            .header {
                padding: 20px 15px;
            }
            
            .content {
                padding: 30px 20px;
            }
            
            .content h1 {
                font-size: 24px;
            }
            
            .footer {
                padding: 20px 15px;
            }
        }

        /* Table styles for order details */
        .order-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .order-table th,
        .order-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .order-table th {
            background-color: #f8fafc;
            font-weight: 600;
            color: #374151;
        }

        .order-table tr:last-child td {
            border-bottom: none;
        }

        .total-row {
            background-color: #f8fafc;
            font-weight: 600;
        }

        /* Rating stars */
        .rating {
            color: #fbbf24;
            font-size: 18px;
        }

        .rating .star {
            display: inline-block;
            margin-right: 2px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="https://davidswood.shop/frontend/assets/favicon.png" alt="David's Wood Furniture" class="logo">
            <h1 class="company-name">DAVID'S WOOD FURNITURES</h1>
            <p class="tagline">Nature's grain shaped by artistry</p>
        </div>

        <!-- Main Content -->
        <div class="content">
            @yield('content')
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>DAVID'S WOOD FURNITURES</strong></p>
            <p>123 Santa Rosa - Tagaytay Rd, Silang, 4118 Cavite</p>
            <p>Phone: +63 (917) 123-4567 | Email: info@davidswood.shop</p>
            
            <div class="social-links">
                <a href="#">LinkedIn</a>
                <a href="#">Instagram</a>
                <a href="#">Facebook</a>
                <a href="#">Twitter</a>
            </div>
            
            <p>Thank you for choosing David's Wood Furnitures for your home furnishing needs.</p>
            
            <div class="unsubscribe">
                <p>You received this email because you have an account with us or subscribed to our newsletter.</p>
                <p><a href="#">Unsubscribe</a> | <a href="#">Update Preferences</a></p>
            </div>
        </div>
    </div>
</body>
</html>

