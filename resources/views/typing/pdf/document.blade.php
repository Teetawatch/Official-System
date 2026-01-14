<!DOCTYPE html>
<html lang="th">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ $submission->assignment->title }} - {{ $submission->user->name }}</title>
    <style>
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: normal;
            src: url("{{ public_path('fonts/THSarabunNew.ttf') }}") format('truetype');
        }
        @font-face {
            font-family: 'THSarabunNew';
            font-style: normal;
            font-weight: bold;
            src: url("{{ public_path('fonts/THSarabunNew Bold.ttf') }}") format('truetype');
        }
        
        body {
            font-family: "THSarabunNew", sans-serif;
            font-size: 16pt;
            line-height: 1.2;
            color: #000;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 22pt;
            font-weight: bold;
            margin: 0;
        }
        .header p {
            margin: 0;
            font-size: 16pt;
        }
        
        .meta-info {
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        
        .content-box {
            padding: 20px;
            border: 1px solid #000;
            min-height: 500px;
            margin-bottom: 20px;
            white-space: pre-wrap; /* Preserve whitespace exactly as typed */
            font-family: "THSarabunNew", sans-serif; /* Ensure typing font match */
        }
        
        .stats-box {
            background-color: #f0f0f0;
            padding: 10px;
            border: 1px solid #ccc;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>รายงานผลการพิมพ์เอกสารราชการ</h1>
        <p>ระบบวิชาพิมพ์หนังสือราชการ 1</p>
    </div>

    <div class="meta-info">
        <table style="width: 100%">
            <tr>
                <td style="width: 60%">
                    <strong>ผู้พิมพ์:</strong> {{ $submission->user->name }} ({{ $submission->user->student_id ?? '-' }})<br>
                    <strong>บทเรียน:</strong> {{ $submission->assignment->title }}<br>
                    <strong>วันที่:</strong> {{ $submission->created_at->addYears(543)->format('d/m/Y H:i') }} น.
                </td>
                <td style="width: 40%; text-align: right;">
                    <h3>คะแนน: {{ $submission->score }} / {{ $submission->assignment->max_score }}</h3>
                </td>
            </tr>
        </table>
    </div>

    <div class="content-box">
        {!! nl2br(e($submission->assignment->content)) !!}
    </div>

    <div class="stats-box">
        <table style="width: 100%">
            <tr>
                <td style="text-align: center">
                    <strong>ความเร็ว (WPM)</strong><br>
                    {{ $submission->wpm }}
                </td>
                <td style="text-align: center">
                    <strong>ความแม่นยำ</strong><br>
                    {{ $submission->accuracy }}%
                </td>
                <td style="text-align: center">
                    <strong>เวลาที่ใช้</strong><br>
                    {{ gmdate("i:s", $submission->time_taken) }} นาที
                </td>
            </tr>
        </table>
    </div>
    
    <div style="margin-top: 30px; text-align: center; color: #777; font-size: 12pt;">
        เอกสารฉบับนี้ถูกสร้างขึ้นโดยอัตโนมัติจากระบบ
    </div>
</body>
</html>
