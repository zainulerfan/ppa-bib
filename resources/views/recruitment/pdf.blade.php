<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $document['title'] }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 18mm 20mm;
        }
        body {
            font-family: Helvetica, Arial, sans-serif;
            color: #1a2538;
            font-size: 11px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .header {
            width: 100%;
            margin-bottom: 8px;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-logo {
            width: 52px;
            vertical-align: middle;
        }
        .header-logo img {
            width: 48px;
            height: 48px;
            display: block;
        }
        .header-company {
            vertical-align: middle;
            padding-left: 10px;
        }
        .company-name {
            font-size: 14px;
            font-weight: bold;
            color: #101a2d;
            letter-spacing: 0.3px;
        }
        .company-sub {
            font-size: 9px;
            color: #55657e;
            font-weight: 600;
            letter-spacing: 0.8px;
            margin-top: 2px;
        }
        .header-code {
            text-align: right;
            vertical-align: middle;
            font-size: 8px;
            font-weight: bold;
            color: #5f6f87;
            letter-spacing: 0.5px;
        }
        .divider {
            height: 2px;
            background: #172844;
            margin-top: 6px;
            margin-bottom: 16px;
        }
        .title-block {
            text-align: center;
            margin-bottom: 16px;
        }
        .title-sub {
            font-size: 9px;
            font-weight: bold;
            color: #54657d;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        }
        .title-main {
            font-size: 16px;
            font-weight: bold;
            color: #152238;
            margin-top: 3px;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            font-size: 10px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .data-table th, .data-table td {
            border: 1px solid #c5cdd9;
            padding: 6px 10px;
            font-size: 10.5px;
            text-align: left;
        }
        .data-table th {
            width: 32%;
            background-color: #f2f5f9;
            color: #27374f;
            font-weight: bold;
        }
        .data-table td {
            color: #202b3d;
        }
        .notes-box {
            margin-top: 10px;
            padding: 8px;
            border: 1px solid #d0d7e2;
            background-color: #fafbfc;
            border-radius: 4px;
        }
        .notes-title {
            font-weight: bold;
            font-size: 9.5px;
            color: #33435c;
            margin-bottom: 4px;
        }
        .notes-line {
            height: 18px;
            border-bottom: 1px dotted #c5cdd9;
        }
        .signatures {
            width: 100%;
            margin-top: 26px;
            border-collapse: collapse;
        }
        .signatures td {
            width: 33.33%;
            text-align: center;
            vertical-align: top;
            font-size: 9.5px;
            color: #3b4b63;
        }
        .sign-space {
            height: 58px;
        }
        .sign-name {
            border-top: 1px solid #8e9bb0;
            display: inline-block;
            width: 80%;
            padding-top: 4px;
            font-size: 9px;
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 8px;
            color: #7b889b;
            border-top: 1px solid #e1e6ed;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <table class="header-table">
            <tr>
                <td class="header-logo">
                    @if($logoBase64)
                        <img src="{{ $logoBase64 }}" alt="PPA Logo">
                    @endif
                </td>
                <td class="header-company">
                    <div class="company-name">PT PUTRA PERKASA ABADI</div>
                    <div class="company-sub">SITE BIB &middot; HUMAN CAPITAL</div>
                </td>
                <td class="header-code">
                    HC / REC / {{ strtoupper($type) }}
                </td>
            </tr>
        </table>
        <div class="divider"></div>
    </div>

    <div class="title-block">
        <div class="title-sub">{{ $document['subtitle'] }}</div>
        <div class="title-main">{{ $document['title'] }}</div>
    </div>

    <table class="meta-table">
        <tr>
            <td style="text-align: left;">
                No. Dokumen: <strong>{{ $record?->letter_number ?: '........................................' }}</strong>
            </td>
            <td style="text-align: right;">
                Tanggal: <strong>{{ now()->format('d/m/Y') }}</strong>
            </td>
        </tr>
    </table>

    <table class="data-table">
        <tbody>
            @foreach($rows as [$label, $value])
                <tr>
                    <th>{{ $label }}</th>
                    <td>{{ filled($value) ? $value : '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="notes-box">
        <div class="notes-title">Catatan / Tujuan Pengajuan:</div>
        <div class="notes-line"></div>
        <div class="notes-line"></div>
    </div>

    <table class="signatures">
        <tr>
            <td>
                <div>Diajukan oleh,</div>
                <div class="sign-space"></div>
                <div class="sign-name">Nama &amp; Tanggal</div>
            </td>
            <td>
                <div>Diperiksa oleh,</div>
                <div class="sign-space"></div>
                <div class="sign-name">Atasan / HC</div>
            </td>
            <td>
                <div>Disetujui oleh,</div>
                <div class="sign-space"></div>
                <div class="sign-name">Pejabat Berwenang</div>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dokumen resmi internal PT Putra Perkasa Abadi &middot; Site BIB (Human Capital)
    </div>
</body>
</html>

