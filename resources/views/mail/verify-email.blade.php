@extends('mail.layout')
@section('content')
<tr>
    <td>
        <table bgcolor="#3490dc" width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width:332px;max-width:600px;border:1px solid #e0e0e0;border-bottom:0;border-top-left-radius:3px;border-top-right-radius:3px">
            <tbody>
                <tr>
                    <td height="72px" colspan="3"></td>
                </tr>
                <tr>
                    <td width="32px"></td>
                    <td style="font-family:Roboto-Regular,Helvetica,Arial,sans-serif;font-size:24px;color:#ffffff;line-height:1.25">
                        Mã xác minh tài khoản
                    </td><td width="32px"></td>
                </tr>
                <tr>
                    <td height="18px" colspan="3"></td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>
<tr>
    <td>
        <table bgcolor="#FAFAFA" width="100%" border="0" cellspacing="0" cellpadding="0" style="min-width:332px;max-width:600px;border:1px solid #f0f0f0;border-bottom:1px solid #c0c0c0;border-top:0;border-bottom-left-radius:3px;border-bottom-right-radius:3px">
            <tbody>
                <tr height="16px">
                    <td width="32px" rowspan="3"></td>
                    <td></td>
                    <td width="32px" rowspan="3"></td>
                </tr>
                <tr>
                    <td>
                        <p>Thân gửi: {{ $receiver->full_name }}</p>
                        <p>Chúng tôi đã nhận được yêu cầu truy cập tài khoản {{ env('APP_NAME') }} <span style="color:#659cef">
                                <a href="mailto:{{ $receiver->email }}" target="_blank">{{ $receiver->email }}</a>
                            </span> của bạn. Mã xác thực tài khoản {{ env('APP_NAME') }} của bạn là:
                        </p>
                        <div style="text-align:center">
                            <p><strong style="text-align:center;font-size:24px;font-weight:bold">{{ $code }}</strong></p>
                        </div>
                        <div style="margin-bottom: 10px">
                            <strong>Lưu ý:</strong> <p>Mã xác thực này chỉ có thời hạn trong vòng {{ config('const.VERIFICATION.EXPIRE') }} phút</p>
                        </div>
                        <p>Nếu bạn đã không yêu cầu mã xác thực này thì rất có thể có ai đó đang cố truy cập tài khoản {{ env('APP_NAME') }}  <a href="mailto:{{ $receiver->email }}" target="_blank">{{ $receiver->email }}</a>. <strong>Đừng tiết lộ mã xác thực này với bất cứ ai.</strong></p>
                        <p>Trân trọng!</p>
                        <p>{{ env('APP_NAME') }} team</p>
                    </td>
                </tr>
                <tr height="32px"></tr>
            </tbody>
        </table>
    </td>
</tr>
@endsection