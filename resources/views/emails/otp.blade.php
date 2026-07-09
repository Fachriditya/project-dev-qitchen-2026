<!DOCTYPE html>
<html>
<head>
    <title>Kode Verifikasi Qitchen</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f9fafb; padding: 40px 20px; color: #374151;">
    
    <div style="max-width: 500px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 12px; border: 1px solid #f3f4f6; text-align: center;">
        
        <h2 style="color: #f97316; margin-bottom: 10px;">Selamat Datang di Qitchen!</h2>
        <p style="margin-bottom: 25px; line-height: 1.6;">
            Terima kasih telah mendaftar. Untuk menyelesaikan proses pendaftaran dan mengamankan akun Anda, silakan gunakan kode verifikasi (OTP) di bawah ini:
        </p>
        
        <!-- Kotak OTP -->
        <div style="background-color: #fff7ed; border: 1px dashed #fdba74; padding: 15px; border-radius: 8px; margin-bottom: 25px;">
            <span style="font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #ea580c;">
                {{ $otpCode }}
            </span>
        </div>

        <p style="font-size: 13px; color: #6b7280; margin-bottom: 0;">
            Kode ini berlaku selama beberapa menit. Jangan bagikan kode ini kepada siapa pun.
        </p>
    </div>

</body>
</html>