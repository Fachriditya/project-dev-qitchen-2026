<!DOCTYPE html>
<html>
<head>
    <title>Pesan Baru - Qitchen</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Halo Admin Qitchen!</h2>
    <p>Anda mendapatkan pesan baru dari form Contact Us website:</p>
    
    <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold; width: 100px;">Nama</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $data['first_name'] }} {{ $data['last_name'] }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Email</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $data['email'] }}</td>
        </tr>
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Telepon</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $data['phone'] ?? '-' }}</td>
        </tr>
        <!-- Baris Subjek Baru -->
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Topik</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $data['subject'] }}</td>
        </tr>
        <!-- Akhir Baris Subjek Baru -->
        <tr>
            <td style="padding: 10px; border: 1px solid #ddd; font-weight: bold;">Pesan</td>
            <td style="padding: 10px; border: 1px solid #ddd;">{{ $data['message'] }}</td>
        </tr>
    </table>

    <p style="margin-top: 20px; font-size: 12px; color: #888;">
        Email ini dikirim otomatis dari sistem Qitchen.
    </p>
</body>
</html>