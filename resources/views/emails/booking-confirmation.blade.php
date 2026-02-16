<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            color: white;
            padding: 2rem;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 1.8rem;
        }
        .content {
            padding: 2rem;
        }
        .booking-details {
            background-color: #f9f9f9;
            border-left: 4px solid #ff9800;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-radius: 4px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #666;
        }
        .detail-value {
            color: #333;
        }
        .price-section {
            background-color: #fff3e0;
            padding: 1.5rem;
            border-radius: 4px;
            margin: 1.5rem 0;
        }
        .total-price {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ff9800;
            text-align: center;
        }
        .status-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 600;
            margin: 1rem 0;
        }
        .status-confirmed {
            background-color: #4caf50;
            color: white;
        }
        .status-pending {
            background-color: #ff9800;
            color: white;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 4px;
            text-decoration: none;
            font-weight: 600;
            margin: 1rem 0;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 1.5rem;
            text-align: center;
            font-size: 0.9rem;
            color: #666;
            border-top: 1px solid #eee;
        }
        .whatsapp-link {
            color: #25d366;
            text-decoration: none;
            font-weight: 600;
        }
        .icon {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="icon">
                @if($status === 'confirmed')
                    ✅
                @else
                    📋
                @endif
            </div>
            <h1>
                @if($status === 'confirmed')
                    Booking Confirmed!
                @else
                    Booking Status Update
                @endif
            </h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Hello <strong>{{ $booking->customerName }}</strong>,</p>

            <p>{{ $message }}</p>

            @if($status === 'confirmed')
                <div class="status-badge status-confirmed">✓ CONFIRMED</div>
            @else
                <div class="status-badge status-pending">⏳ PENDING</div>
            @endif

            <!-- Booking Details -->
            <div class="booking-details">
                <h3 style="margin-top: 0; color: #ff9800;">Booking Details</h3>
                
                <div class="detail-row">
                    <span class="detail-label">Vehicle:</span>
                    <span class="detail-value">{{ $booking->car->brand }} {{ $booking->car->model }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Booking ID:</span>
                    <span class="detail-value">#{{ $booking->id }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Check-in Date:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($booking->startDate)->format('M d, Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Check-out Date:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($booking->endDate)->format('M d, Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Duration:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($booking->startDate)->diffInDays($booking->endDate) }} days</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Daily Rate:</span>
                    <span class="detail-value">UGX {{ number_format($booking->car->dailyRate) }}</span>
                </div>
            </div>

            <!-- Price Section -->
            <div class="price-section">
                <div class="total-price">
                    Total: UGX {{ number_format($booking->totalPrice) }}
                </div>
            </div>

            @if($status === 'confirmed')
                <p><strong>Next Steps:</strong></p>
                <ul>
                    <li>Our team will contact you to arrange pickup details</li>
                    <li>Please have your ID/Passport ready for verification</li>
                    <li>Payment can be made via Mobile Money, Bank Transfer, or Cash on Pickup</li>
                </ul>

                <p style="text-align: center;">
                    <a href="{{ url('/dashboard') }}" class="cta-button">View Your Booking</a>
                </p>

                <p style="text-align: center; margin-top: 1.5rem;">
                    Need help? Contact us on 
                    <a href="https://wa.me/256755943973" class="whatsapp-link">WhatsApp</a>
                </p>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>
                <strong>MAM Tours & Travel Agency</strong><br>
                Premium Car Rental Services in Uganda<br>
                📞 +256 755 943 973 | 📧 info@mamtours.com
            </p>
            <p style="margin-top: 1rem; font-size: 0.85rem;">
                This is an automated email. Please do not reply directly to this message.
            </p>
        </div>
    </div>
</body>
</html>
