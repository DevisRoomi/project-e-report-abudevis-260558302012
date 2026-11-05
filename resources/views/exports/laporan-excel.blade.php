<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        .header { background-color: #4A151C; color: #ffffff; font-weight: bold; text-align: center; font-family: Arial, sans-serif; height: 30px; }
        .data { font-family: Arial, sans-serif; font-size: 11pt; }
        .center { text-align: center; }
        .num { mso-number-format: "\@"; }
    </style>
</head>
<body>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th colspan="7" style="font-size: 14pt; font-weight: bold; text-align: center; height: 40px; font-family: Arial, sans-serif;">
                    REKAPITULASI LAPORAN PENGADUAN LAYANAN SIPIL (E-REPORT)
                </th>
            </tr>
            <tr>
                <th colspan="7" style="font-size: 10pt; text-align: center; font-style: italic; font-family: Arial, sans-serif;">
                    Dicetak pada: {{ now()->translatedFormat('d F Y H:i:s') }}
                </th>
            </tr>
            <tr>
                <th class="header">No</th>
                <th class="header">Nomor Berkas</th>
                <th class="header">Tanggal Aduan</th>
                <th class="header">Nama Pelapor</th>
                <th class="header">No. WhatsApp</th>
                <th class="header">Pokok Pengaduan</th>
                <th class="header">Status Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporans as $index => $row)
                <tr>
                    <td class="data center">{{ $index + 1 }}</td>
                    <td class="data center num">#{{ str_pad($row->id, 5, '0', STR_PAD_LEFT) }}</td>
                    <td class="data center">{{ $row->created_at->format('Y-m-d H:i') }}</td>
                    <td class="data">{{ $row->pelapor?->name ?? 'Anonim' }}</td>
                    <td class="data num">{{ $row->pelapor?->no_whatsapp ?? '-' }}</td>
                    <td class="data">{{ $row->judul }}</td>
                    <td class="data center">{{ strtoupper($row->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="data center">Tidak ada data laporan yang cocok.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
